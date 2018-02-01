<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Api_webscan_target;
use app\api\model\Api_webscan_tmp;

class Webscan extends Controller
{
    public function index()
    {
        return 0;
    }

    public function sao()
    {
    	$s = 10;
    	//获取扫描目标
    	$target = Api_webscan_target::select();
    	//查询当前共多少条了
    	$tiaoshu = Api_webscan_tmp::order('id desc')->limit(1)->select();
    	foreach ($tiaoshu as $key => $value) {
    		echo $value['id'];
    	}

    	//给予扫描目标给curl
        foreach ($target as $key => $value) {
        	$ch 		= curl_init();  
        	$url 		= $value['url'];
			$timeout 	= 1;
			// for ($i=0; $i < $s; $i++) { 
				# code...
				curl_setopt($ch, CURLOPT_URL, $url);  
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
				curl_setopt($ch, CURLOPT_HEADER, 0);  
				$info = curl_exec($ch);  
				// dump($info);

				if($info!=false)
				{
					// preg_match_all('/(src|href)="(.*?)"/',$info,$m);
					preg_match('/(src|href)="(.*?)"/',$info,$m);
					// dump($m);
					if(strpos($m[2],'http')===false)
					{
						echo "链接内容没有网址前缀";
						$t1  = $value['url'].$m[2];
						$data = Api_webscan_tmp::where('url',$t1)->find();
						if(!$data){
							$tmp = Api_webscan_tmp::create(['url'=>$t1]);
							echo "增加成功".$t1;
						}
					}else{
						//内容包含域名则直接新增
						$tmp = Api_webscan_tmp::create(['url'=>$m[2]]);
					}
				}else{
					echo "没扫</br>";
				}
				// echo "cccc";
				dump($m[2]);
				// dump($m);
			// }
			


			
        }

        
		
    }

    public function somd5()
    {
        $filename = "http://www.tp5.com/static/blog/style.css";
		$md5file = md5_file($filename);
		echo $md5file;
    }

    public function cmsfingerprint()
    {
    	echo "指纹.</br>";
    	$result = Api_webscan_tmp::select();
    	foreach ($result as $key => $value) {
    		# code...
    		// echo $value['url']."</br>";


    		$filename = $value['url'];
			$md5file = md5_file($filename);
			echo $md5file."</br>";;
			//然后取文件名，大小，MD5入库
    	}
    }



}