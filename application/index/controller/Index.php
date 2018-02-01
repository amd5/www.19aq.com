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
/*后台模块*/
use app\admin\model\SystemConfig;
/*其他第三方模块*/
use app\api\controller\Sendsms;

/*后台继承*/
use app\admin\controller\BaseController;
// use app\index\controller\Check;

// class Index extends BaseController
class Index	extends Controller
{
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
			$result = Article::with('sort')
            ->order('id','desc')
			->where('password','=','')
			->limit(15)
			->paginate();
			$page = $result->render();   //获取分页显示
    	}elseif(session('username') == "1")
    	{
    		//管理员ID不是1
			$result = Article::with('sort')
            ->order('id','desc')
			->where('password','=','')
			->limit(15)
			->paginate();
			$page = $result->render();
    	}else
    	{
    		//文章列表  管理员显示全部文章
			$result = Article::with('sort')
            ->order('id','desc')
			->limit(15)
			->paginate();
			$page = $result->render();
            // print_r($result);

            // $sort = $result->ArticleSort->sortname()
    	}

        // $sort = 

    	//文章标签
    	$tag = ArticleTag::select();
    	//dump($tag);

		//分类列表
		$articlesort = ArticleSort::withCount('sort')
        ->where('status','=','1')
		->select();
		
		//存档列表
        // SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
        //SELECT distinct YEAR(FROM_UNIXTIME(date)) as nianfen FROM think_article ORDER BY nianfen DESC
        $nian = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();

        $yue = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y年-%m月") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();


        

		//友情链接
		$links = Link::order('taxis','asc')
		->select();

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

    // public function sort()
    // {
    //     echo "string";
    //     $articlesort = ArticleSort::withCount('sort')
    //     // ->alias('tiaoshu')
    //     ->select();
    //     $this->assign('articlesort', $articlesort);

    //     return $this->fetch();
    // }


  //   public function tag()
  //   {
  //   	$result = Article::order('id','desc')
		// 	->limit(15)
		// 	->paginate();
		// 	$page = $result->render();
  //   	$tag = ArticleTag::select();
  //   	// dump($tag);
  //   	$this->assign('result', $result);
		// $this->assign('page', $page);
  //   	return $this->fetch();
  //   }


    // public function cheip($ip)
    // {
    //     $add=new IpLocation();
    //     $ipdizhi = $add->getlocation($ip);
    //     $dizhi = $this->request->ip();
    //     $xxip = $ipdizhi['ip'];
    //     $xxdz = $ipdizhi['country'].$ipdizhi['area'];
    //     echo ("查询IP：".$xxip);
    //     echo ($xxdz);

    //     // $ipdizhi1 = $add->getlocation($dizhi);
    //     // $xxip1 = $ipdizhi1['ip'];
    //     // $xxdz1 = $ipdizhi1['country'].$ipdizhi1['area'];
    //     // echo "</br>本机IP：".$xxip1;
    //     // echo ($xxdz1);
    // }

    public function test()
    {
        return $this->fetch();
    }

	
}
