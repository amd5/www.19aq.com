<?php
namespace app\index\controller;
use think\Controller;
/*模块*/
use app\index\model\Article;
use app\index\model\ArticleSort;
use app\index\model\ArticleTag;
use app\index\model\Link;
/*后台继承*/
use app\admin\controller\BaseController;

class Index	extends BaseController
{
    public function index()
    {
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

	
	
}
