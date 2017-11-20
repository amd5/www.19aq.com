<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Article;
use app\index\model\ArticleSort;
use app\index\model\Link;

class Index	extends Controller
{
    public function index()
    {
    	//文章列表
		$result = Article::order('id','desc')
		->limit(15)
		->paginate();
		$page = $result->render();   //获取分页显示

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

	
	
}
