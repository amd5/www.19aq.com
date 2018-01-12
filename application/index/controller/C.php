<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\Article;

class C extends Controller
{
    public function index()
    {
    	//存档列表
		// SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
    	//SELECT distinct YEAR(FROM_UNIXTIME(date)) as nianfen FROM think_article ORDER BY nianfen DESC

		$nian = Article::order('days','desc')
		->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
		->GROUP('days')
		->select();


		$yue = Article::order('days','desc')
		->field('FROM_UNIXTIME(date,"%Y-%m") as days,COUNT(*) as COUNT')
		->GROUP('days')
		->select();

    	$this->assign('nian', $nian);
    	$this->assign('yue', $yue);
    	return $this->fetch();


    }

    public function php(){
    	echo "phpinfo";
    	// phpinfo();
    }
}