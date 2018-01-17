<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\User;

class C extends Controller
{
    public function index()
    {
    	//存档列表
		// SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
    	//SELECT distinct YEAR(FROM_UNIXTIME(date)) as nianfen FROM think_article ORDER BY nianfen DESC

		$test = User::select();
        dump($test);

    }

    public function post()
    {
    	$data="memberid=2";
    	$postUrl= "http://www.thornbirdstar.com/tool/getMemberName.html";
    	$ch = curl_init();//初始化curl
    	curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $jieguo = curl_exec($ch);//运行curl
        curl_close($ch);

    	echo $jieguo;
    	if ($jieguo == "查无此人") {
    		echo "1111";
    	}else{
    		echo "22222";
    	}
    }

}