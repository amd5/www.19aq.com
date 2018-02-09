<?php
# #########################################
# #感谢1207877378大神
# #########################################
namespace app\index\controller;
use think\Controller;
/*前台模块*/
use app\index\model\Link;
use app\index\model\Record;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
/*后台模块*/
use app\admin\model\SystemConfig;
/*其他第三方模块*/
use app\api\controller\Sendsms;

/*后台继承*/
use app\admin\controller\BaseController;
// use app\index\controller\Check;

class Index	extends Controller
{
    protected $wenz;
    protected $tag;
    protected $sort;
    protected $record;
    protected $link;

    public function __construct()
    {
        $this->link     = new Link;
        $this->record   = new Record;
        $this->wenz     = new Article;
        $this->tag      = new ArticleTag;
        $this->sort     = new ArticleSort;
        parent::__construct();
    }
    //没有加管理员权限检查
    public function index()
    {
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
    	if(!session('username') || session('username') !== "c32")
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
        $result = $this->wenz->article($id);
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }

    public function test()
    {
        return $this->fetch();
    }

	
}
