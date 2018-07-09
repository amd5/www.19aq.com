<?php
# #########################################
# #Function:    网站首页
# #Blog:        http://www.19aq.com/
# #Datetime:    2018-2-9 11:30:59
# #Author:      c32
# #Email:       amd5@qq.com
# #感谢King east(1207877378),一指流沙(287100654)
# #########################################
namespace app\index\controller;

use think\Cookie;
use think\Controller;
/*前台模块*/
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
/*后台模块*/
use app\admin\model\SystemConfig;
/*其他第三方模块*/
use app\api\controller\Sendsms;
use app\extra\rss\Rss;

/*后台继承*/
use app\admin\controller\BaseController;
// use app\index\controller\Check;
define('DB_HOST', 'localhost');

class Index	extends Controller
{
    //protected表示受保护的，只有本类或子类或父类中可以访问；
    // protected $user = '';
    //private表示私有的，只有本类内部可以使用；
    // private $wenz = '1';
    protected $wenz;
    protected $tag;
    protected $sort;
    protected $record;
    protected $link;
    //构造方法  初始化实例化模型
    public function __construct()
    {
        $this->link     = new Link;
        $this->record   = new ArticleRecord;
        $this->wenz     = new Article;
        $this->tag      = new ArticleTag;
        $this->sort     = new ArticleSort;
        //调用父类构造方法
        parent::__construct();
    }
    //没有加管理员权限检查
    public function index()
    {
        // phpinfo();
        // die;
        // $rss = new Rss('标题','内容','描述','./static/blog/rss.png');
        // $rss->AddItem("日志的标题","日志的地址","日志的摘要","2018-02-26"); 
        // $rss->Display();//输出RSS内容 
        // dump($wenz);die;
        // die;
        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
        
        $config = SystemConfig::where('name',"Sendsms")->find();
        // $config = SystemConfig::get(4);
        // echo ($config->status);
        //如果配置开启才发送短信，否则不发送短信
        if($config->status=='1'){
            $sms = new Sendsms();
            echo $sms->mainsend();
        }else{
            //不发送短信
        }

        // dump(session('username'));die;
    	if(!Cookie('username') || Cookie('username') !== "c32")
    	{
    		//文章列表  不是管理员显示没有密码的文章
            $result = $this->wenz->Articles();
			$page = $result->render();   //获取分页显示
    	}else
    	{
    		//文章列表  管理员显示全部文章
            $result = $this->wenz->Articleall();
			$page = $result->render();

    	}

    	//文章标签
        $tag =$this->tag->taglist();
        //文章标签所属文章求和
        // $tag =$this->tag->tagcount();

        //分类列表
        $articlesort = $this->sort->sortlist();
        
        //存档列表
        $nian = $this->record->nian();
        $yue = $this->record->yue();

        //友情链接
        $links = $this->link->links();

		//输出
        $this->assign('url', $url);
        $this->assign('tag', $tag);
		// $this->assign('data', $data);
		$this->assign('links', $links);
		$this->assign('nian', $nian);
        $this->assign('yue', $yue);
		$this->assign('articlesort', $articlesort);
		$this->assign('result', $result);
		$this->assign('page', $page);


		return $this->fetch();
    }
	
	public function article($id)
    {
        //文章详情标题和内容
        $result = $this->wenz->article($id);

        //文章详情分类显示
        $sort   = $this->sort->sortname($result['sortid']);

        //文章详情标签显示
        // dump($id);die;
        $tag =$this->tag->articletag($id);

        $this->assign('tag', $tag);
        $this->assign('sort', $sort);
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }


	
}
