<?php
# #########################################
# #感谢1207877378大神
# #########################################
namespace app\index\controller;
use think\Controller;
/*前台模块*/
use app\index\model\Article;
use app\index\model\ArticleSort;
use app\index\model\ArticleTag;
use app\index\model\Link;
use app\index\model\Record;
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

    public function __construct()
    {
        $this->wenz = new Article;
        parent::__construct();
    }

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

        
    	if(!session('username'))
    	{
    		//文章列表  不是管理员显示没有密码的文章
            $result = $this->wenz->Articles();
			$page = $result->render();   //获取分页显示
    	}elseif(session('username') == "1")
    	{
    		//管理员ID不是1
            $result = $this->wenz->Articles();
			$page = $result->render();
    	}else
    	{
    		//文章列表  管理员显示全部文章
            $result = $this->wenz->Articleall();
			$page = $result->render();

    	}


    	//文章标签
        $taglist = new ArticleTag;
        $tag     = $taglist->taglist();

        //分类列表
        $sort = new ArticleSort;
        $articlesort = $sort->sortlist();
        
        //存档列表
        $record = new Record;
        $nian = $record->nian();
        $yue  = $record->yue();

        //友情链接
        $link = new link;
        $links = $link->links();

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
        
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }
	
	public function article($id)
    {
		$result = Article::where('id','=',$id)->find();
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }

    public function test()
    {
        return $this->fetch();
    }

	
}
