<?php
# #########################################
# #Function:    网站首页
# #Blog:        http://www.19aq.com/
# #Datetime:    2018-2-9 11:30:59
# #Author:      c32
# #Email:       amd5@qq.com
# #感谢King east(1207877378),一指流沙(287100654)
# #感谢King east指导的模型关联
# #感谢流年 QQ130770305指导解决后台查询300条文章产生600条语句的问题
# #//感谢github作者zjw710的继承案例
# #########################################
namespace app\index\controller;

// use think\Cookie;
use think\Request;    //请求IP地址等
use think\Controller;
/*前台模块*/
use app\index\model\Link;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
use app\index\model\ArticleRecord;
/*后台模块*/
use app\admin\model\SystemConfig;
/*其他第三方模块*/
use app\api\controller\Sendsms;
use app\extra\rss\Rss;

class Index	extends Controller
{
    protected $wenz;
    //构造方法  初始化实例化模型
    public function __construct()
    {
        $this->wenz     = new Article;
        $this->sendsms;
        $this->setting;
        //调用父类构造方法
        parent::__construct();
    }
    //没有加管理员权限检查
    public function index()
    {
        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
        $request = Request::instance();

        $title = "";
        //文章列表
        $result = $this->wenz->Article_list($title);
        
        if ($request->param('tag') == true) {
            $type = $request->param('tag');
            $data = ArticleTag::where('tagname',$type)->cache(true,8640000)->select();
            foreach ($data as $key => $value) {
                $tid = $tid.",";
                $tid = $tid.$value['tid'];
            }
            $tid = substr($tid,1);

            $result = $this->wenz->tagArticle($tid,$type);
            // return $this->fetch();
        }
        
        if ($request->param('sort') == true) {
            $type = $request->param('sort');
            $sortid = ArticleSort::where('alias','=',$type)->cache(true,8640000)->find();
            $sid = $sortid['sid'];
            // dump($this->wenz);die;
            $result = $this->wenz->SortArticlelist($sid);
        }

        if ($request->param('record') == true) {
            $type = $request->param('record');
            $nian = substr($type, 0, 4);  $yue  = substr($type, 4, 5);
            $sj   = $nian.'-'.$yue.'-'.'01'.' '.'00:00:00';
            //开始时间
            $stsj = strtotime($sj);
            //结束时间
            $mdays = date( 't', strtotime($sj) );
            $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', strtotime($sj));
            $endsj = strtotime($end_time);

            //文章列表  不是管理员显示没有密码的文章
            $result = $this->wenz->Record_article($stsj,$endsj);

        }

        // 
        if ($request->param('key') == true) {
            $type = $request->param('key');
            $result = $this->wenz->search($type);
        }

        //获取分页显示
        $page = $result->render();   
    	//文章标签
        $tag =$this->wenz->taglist();

        //分类列表
        $sort_list = $this->wenz->sortlist();
        
        //存档列表
        $nian = $this->wenz->nian();
        $yue = $this->wenz->yue();

        //友情链接
        $links = $this->wenz->links();

		//输出
        $this->assign('blog_title', $type);
        $this->assign('url', $url);
        $this->assign('tag', $tag);
		$this->assign('links', $links);
		$this->assign('nian', $nian);
        $this->assign('yue', $yue);
		$this->assign('sort_list', $sort_list);
		$this->assign('result', $result);
		$this->assign('page', $page);

        // dump($type);die;
		return $this->fetch();
    }
	
	public function article($id)
    {
        $result = $this->wenz->article($id);                                                //文章详情标题和内容
        $sort = ArticleSort::where('sid',$result['sortid'])->cache(true,8640000)->find();   //文章详情分类显示
        $tag = ArticleTag::where('tid',$id)->cache(true,8640000)->select();                 //文章详情标签显示

        $this->assign('tag', $tag);
        $this->assign('sort', $sort);
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }

    public function sendsms(){
        $config = SystemConfig::where('name',"Sendsms")->cache(true,8640000)->find();
        //如果配置开启才发送短信，否则不发送短信
        if($config->status=='1'){
            $sms = new Sendsms();
            echo $sms->mainsend();
        }
    }

    public function setting(){
        return dump('1');
    }


	
}
