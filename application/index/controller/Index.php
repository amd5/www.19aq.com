<?php
namespace app\index\controller;
use think\Controller;
/*模块*/
use app\index\model\Article;
use app\index\model\ArticleSort;
use app\index\model\ArticleTag;
use app\index\model\Link;
use app\extra\ip\IpLocation;
use app\extra\api_demo\SmsDemo;
use think\Request;

/*后台继承*/
use app\admin\controller\BaseController;
use app\index\controller\Check;

class Index	extends BaseController
{
    public function index()
    {
        //调用check方法
        $check  =new index();
        // dump($check);
        echo $check->check();

        

    	if(!session('username'))
    	{
    		//文章列表  不是管理员显示没有密码的文章
			$result = Article::order('id','desc')
			->where('password','=','')
			->limit(15)
			->paginate();
			$page = $result->render();   //获取分页显示
    	}elseif(session('username') == "1")
    	{
    		//管理员ID不是1
			$result = Article::order('id','desc')
			->where('password','=','')
			->limit(15)
			->paginate();
			$page = $result->render();
    	}else
    	{
    		//文章列表  管理员显示全部文章
			$result = Article::order('id','desc')
			->limit(15)
			->paginate();
			$page = $result->render();
    	}

    	//文章标签
    	$tag = ArticleTag::select();
    	

		//分类列表
		$articlesort = ArticleSort::where('status','=','1')
		->select();
		
		//存档列表
		//SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
		$archives = Article::order('days','desc')
		->field('FROM_UNIXTIME(date,"%Y年%m月") as days,COUNT(*) as COUNT')
		->GROUP(days)
		->select();

		//友情链接
		$links = Link::order('taxis','asc')
		->select();

		//输出
		$this->assign('links', $links);
		$this->assign('archives', $archives);
		$this->assign('articlesort', $articlesort);
		$this->assign('result', $result);
		$this->assign('page', $page);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }
	
	public function article($id)
    {
		$result = Article::where('id','=',$id)->select();
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }


    public function tag()
    {
    	$result = Article::order('id','desc')
			->limit(15)
			->paginate();
			$page = $result->render();
    	$tag = ArticleTag::select();
    	// dump($tag);
    	$this->assign('result', $result);
		$this->assign('page', $page);
    	return $this->fetch();
    }


    public function check()
    {
        $ip=new IpLocation();
        $dizhi = $this->request->ip();
        //只含    ip地址
        $ipdizhi = $ip->getlocation($dizhi);
        //显示ip地址信息数组
        // dump($ip->getlocation($dizhi));
        //输出ip地址
        // dump($ipdizhi['ip']);
        $xxip = $ipdizhi['ip'];
        $xxip = str_replace('.','_',$xxip);
        //输出详细地址
        $xxdz = $ipdizhi['country'].$ipdizhi['area'];
        // dump($xxdz);
        
        // 如果不是管理员才执行
        if(session('id') <> "1")
        {
            $demo = new SmsDemo(
            "LTAIJ7jxMyfxE9nw",
            "6QVo3Ibosh2OujQ9GUWOE4K70dOPX0"
            );
            $response = $demo->sendSms("c32博客","SMS_115390722","15024267536",Array("address"=>$xxdz,"ip"=>$xxip),
                "123"
            );
            print_r($response);
        }
        echo (session('id'));
        // echo "222";

    }

    public function cheip($ip)
    {
        $add=new IpLocation();
        $ipdizhi = $add->getlocation($ip);
        $dizhi = $this->request->ip();
        $xxip = $ipdizhi['ip'];
        $xxdz = $ipdizhi['country'].$ipdizhi['area'];
        echo ("查询IP：".$xxip);
        echo ($xxdz);

        // $ipdizhi1 = $add->getlocation($dizhi);
        // $xxip1 = $ipdizhi1['ip'];
        // $xxdz1 = $ipdizhi1['country'].$ipdizhi1['area'];
        // echo "</br>本机IP：".$xxip1;
        // echo ($xxdz1);
    }


	
	
}
