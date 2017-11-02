<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Article;

class Index	extends Controller
{
    public function index()
    {
		$result = Article::order('id','desc')
		->limit(15)
		->paginate();
		$page = $result->render();   //获取分页显示
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
