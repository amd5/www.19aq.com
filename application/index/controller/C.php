<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\Fenxiao;

class C extends Controller
{
    public function index()
    {
    	//存档列表
		// SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
    	//SELECT distinct YEAR(FROM_UNIXTIME(date)) as nianfen FROM think_article ORDER BY nianfen DESC
        echo "test";
		$test = Fenxiao::select();
        dump($test);

    }



}