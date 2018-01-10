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

    	$archives = Article::with('cundang')
    	->order('days','desc')
		->field('FROM_UNIXTIME(date,"%Y年%m月") as days,COUNT(*) as COUNT')
		->GROUP(days)
		->select();


		$nian = Article::order('days','desc')
		->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
		->GROUP(days)
		->select();





    	// $archives = Article::withCount('cundang')
    	// ->field('date')
		// ->select();
		// foreach($archives as $user){
		//     // 获取用户关联的card关联统计
		//     echo $user->cards_count;
		// }



    	// echo ($archives);
    	// dump($archives);
    	$this->assign('nian', $nian);
    	$this->assign('archives', $archives);
    	return $this->fetch();


    }
}