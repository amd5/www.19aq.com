<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Api_webscan_cmsfingerprint;
use app\api\model\Api_webscan_target;
use app\api\model\Api_webscan_tmp;
use app\api\controller\Httpcode;
use think\Request;   //CMS上传文件请求

class Webscan extends Controller
{
    public function index()
    {
        return 0;
        //设定扫描参数->给予目标->加入队列->
    }

    public function scan(){
    	//查询当前一共扫了多少条了
    	// $tiaoshu = Api_webscan_tmp::order('id desc')->limit(1)->select();
    	// foreach ($tiaoshu as $key => $value) {
    	// 	echo $value['id'];
    	// }

    	//获取扫描目标
    	$target = Api_webscan_target::select();

    	$sm = new Webscan();
    	

    	foreach ($target as $key => $link) {
    		# code...
    		$url = $link['url'];
    		// echo $url."</br>";
    		$start = $sm->sao($url);
    	}

    }

    public function sao($url)
    {
    	//设置执行超时时间
        set_time_limit(0);

		$ch 		= curl_init();  
		$timeout 	= 1;
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		$info = curl_exec($ch);  
		// dump($info);

		if($info!=false)
		{
			preg_match_all('/(src|href)="(.*?)"/',$info,$m);
			// $arr = array('base64','#','javascript:void()');
			// preg_match('/(src|href)="(.*?)"/',$info,$m);
			// echo($m[2]);
			foreach ($m[2] as $key => $body) {
				if(strpos($body,'http')===false){
					$t1  = $url.$body;
					if($body !== $url."/"){
						if(strpos($body,'base64')===false){
							if(strpos($body,'#')===false){
								if(strpos($body,'javascript:void(')===false){
									$data = Api_webscan_tmp::where('url',$t1)->find();
									if(!$data){
										$tmp = Api_webscan_tmp::create(['url'=>$t1]);
										echo "增加成功a".$t1."</br>";
									}
								}
							}
						}
					}		
				}else{
					if($body !== $url."/"){
						if(strpos($body,'base64')===false){
							if(strpos($body,'#')===false){
								if(strpos($body,'javascript:void(')===false){
									$data = Api_webscan_tmp::where('url',$body)->find();
									if(!$data){
										//内容包含域名则直接新增
										$tmp = Api_webscan_tmp::create(['url'=>$body]);
										echo "增加成功b".$body."</br>";
									}
								}
							}
						}
					}
				}
				
				//如果包含则不插入数据库
				
				// foreach ($arr as $key => $zifuchuan){
				// 	dump(strpos($body,$zifuchuan));
				// 	echo $body;
				// if(strpos($body,$zifuchuan)===false){

				// 	//如果插入数据等于扫描URL则不插入
				// 	// if($body !== $url){
				// 	// 	//如果插入数据没有网址 则自动补全
				// 	// 	if(strpos($body,'http')===false)
				// 	// 	{
				// 	// 		// echo "链接内容没有网址前缀";
				// 	// 		$t1  = $url.$body;
				// 	// 		$data = Api_webscan_tmp::where('url',$t1)->find();
				// 	// 		if(!$data){
				// 	// 			$tmp = Api_webscan_tmp::create(['url'=>$t1]);
				// 	// 			echo "增加成功a".$t1."</br>";
				// 	// 		}
				// 	// 	}else{
				// 	// 		$data = Api_webscan_tmp::where('url',$body)->find();
				// 	// 		if(!$data){
				// 	// 			//内容包含域名则直接新增
				// 	// 			$tmp = Api_webscan_tmp::create(['url'=>$body]);
				// 	// 			echo "增加成功b".$body."</br>";
				// 	// 		}
				// 	// 	}
				// 	// }
				// }   //for结束
				// }		//这里
				
			}
			
		}else{
			echo "没扫</br>";
		}
		// echo "cccc";
		// dump($m[2]);
		// dump($m);
			



        
		
    }

    public function somd5()
    {
        $filename = "http://www.tp5.com/static/blog/style.css";
		$md5file = md5_file($filename);
		echo $md5file;
    }

    public function cmsin()
    {
    	$request = Request::instance();
    	if(move_uploaded_file($_FILES["file"]["tmp_name"],"D:\/fingerprint.Log")){
        echo "上传成功";
	    }else{
	        echo "上传失败";
	    }
    	//接收上传文件匹配后去重入库
    	$file_path = 'D:\fingerprint.Log';
    	// $file_path = $_FILES["file"]["tmp_name"];
    	$data = iconv("gb2312", "utf-8//IGNORE",file_get_contents($file_path));   
    	// $cia = preg_match_all('/文件名：(.*?)\nMD5：(.*?)\nSHA1：(.*?)\n文件大小：(.*?)\n修改时间：(.*?)\n路径：(.*?)/',$data,$m);
    	$cishu = preg_match('/文件名：(.*?)\nMD5：(.*?)\nSHA1：(.*?)\n文件大小：(.*?)\n修改时间：(.*?)\n路径：(.*?)/',$data,$m);
    	
    	$cmsname	= $request->param('cmsname') ?"提交成功":"请提交CMS名称";
    	$cmsver 	= $request->param('cmsver') ?"提交成功":"请提交CMS版本";
    	dump($m);
    	echo time();
		$tmp = Api_webscan_cmsfingerprint::create(['filename'=>$filename ,'md5'=>$md5 ,'sha1'=>$sha1 ,'size'=>$size ,'cmsname'=>$cmsname ,'cmsversion'=>$cmsver ,'subtime'=>time()]);
  
    	// foreach ($m as $key => $body) {
    		// $filename 	= $c[1];
	    	// $md5			= $c[2];
	    	// $sha1 		= $c[3];
	    	// $size 		= $c[4];
    		// $tmp = Api_webscan_cmsfingerprint::create(['filename'=>$filename ,'md5'=>$md5 ,'sha1'=>$sha1 ,'size'=>$size ,'cmsname'=>$cmsname ,'cmsversion'=>$cmsver ,'subtime'=>time()]);


    	// }
	    		
	

    	// $this->assign('page', $page);

    	return $this->fetch();

    }

    public function cmsfingerprint()
    {
    	$http = new Httpcode();
    	//            "/.*?[\.php|\.htm|\.html|\.asp]/"
    	preg_match_all('/(src|href)="(.*?)"/',$info,$m);
    	echo "指纹.</br>";
    	$result = Api_webscan_tmp::select();
    	foreach ($result as $key => $link) {
    		$code = $http->scan($link['url'],1);
    		if($code == 200){
    			$filename = $link['url'];
    			//获取当前文件md5
				$md5file = md5_file($filename);
				//获取当前文件名
				$name = end(explode('/',$filename)); 
				// echo $filename; 
				// die();
				$tmp = Api_webscan_cmsfingerprint::create(['filename'=>$name ,'md5'=>$md5file]);
				dump( $md5file."</br>");

				//然后取文件名，大小，MD5入库
    		}
    		
    	}
    }



}