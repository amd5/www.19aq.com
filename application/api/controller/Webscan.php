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
    	// $target = Api_webscan_tmp::select();

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
        //检查URL是否可访问
        $http = new Httpcode;
        $code = $http->scan($url,1);
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

		if($info!=false && $code==200)
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
                                    $acode = $http->scan($t1,1);
									if(!$data && $acode==200){
										$tmp = Api_webscan_tmp::create(['url'=>$t1]);
										echo "1号增加成功".$t1."</br>";
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
                                    $bcode = $http->scan($body,1);
									if(!$data && $bcode==200){
										//内容包含域名则直接新增
										$tmp = Api_webscan_tmp::create(['url'=>$body]);
										echo "2号增加成功b".$body."</br>";
									}
								}
							}
						}
					}
				}
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
		echo strtoupper($md5file);
    }

    public function tu()
    {
    	$request = Request::instance();
    	$cmsver  = $request->param('a');
    	// dump();
    	// $cmsver  = $request->param('cmsver');
    	$a = new Api_webscan_tmp;
    	$b = $a->Abc($cmsver);
    	dump($b);
    	// dump(input(''));
    	// echo "<img src='http://cimage.tianjimedia.com/uploadImages/thirdImages/2015/333/N42LRRE404U9.jpg' />";

    }

    public function cmsin()
    {
    	$request = Request::instance();
    	//获取毫秒级时间戳
    	list($t1, $t2) = explode(' ', microtime());
    	$a = (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    	$path = "./lib/ueditor/php/upload/log/".date('Ymd',time())."/";
    	//如果上传目录不存在，则建立上传目录
    	if(is_dir($path)==false){
    		mkdir("./lib/ueditor/php/upload/log/".date('Ymd',time()),0777,true);
    	}
    	//移动上传的临时文件到指定位置
    	$file 	 = move_uploaded_file($_FILES["file"]["tmp_name"],$path.$a.".Log");
    	//接收上传文件匹配后去重入库
    	$file_path = $path.$a.".Log";
    	$dd = is_file($file_path);
		// echo($path.$a.".Log");
    	//如果文件存在才执行，否则进行提示
    	if($dd==true){
    		//转换文件编码为中文
    		$data = iconv("gb2312", "utf-8//IGNORE",file_get_contents($file_path));  
	    	$cishu = preg_match_all('/文件名：(.*?)\nMD5：(.*?)\nSHA1：(.*?)\n文件大小：(.*?)\n修改时间：(.*?)\n路径：(.*?)/',$data,$m);
			foreach ($m[0] as $key => $value) {
				preg_match('/文件名：(.*?)\nMD5：(.*?)\nSHA1：(.*?)\n文件大小：(.*?)\n修改时间：(.*?)\n路径：(.*?)/',$value,$c);
				$filename 	= $c[1];
		    	$md5		= $c[2];
		    	$sha1 		= $c[3];
		    	$size 		= $c[4];
		    	$cmsname	= $request->param('cmsname');
	    		$cmsver 	= $request->param('cmsver');
	    		//过滤重复数据
	    		$repeat = Api_webscan_cmsfingerprint::where('filename',$filename)->where('md5',$md5)->where('sha1',$sha1)->where('size',$size)->find();
	    		if(!$repeat){
	    			$tmp = Api_webscan_cmsfingerprint::create(['filename'=>$filename ,'md5'=>$md5 ,'sha1'=>$sha1 ,'size'=>$size ,'cmsname'=>$cmsname ,'cmsversion'=>$cmsver ,'subtime'=>time()]);
	    		}
			}
			unlink($file_path);
			echo "数据已录入成功！";
    	}else{
    		echo "<font color='red'><h2>请上传指纹文件！</h2></font><br/>";
    	}
    	// $this->assign('page', $page);
    	return $this->fetch();
    }

    public function cmsfingerprint()
    {
    	// 1、打开页面
    	// 2、输入网址
    	// 3、开始爬行页面
    	// 4、读取各页面MD5
    	// 5、MD5与数据库进行匹配
    	// 6、返回匹配失败数量
    	// 7、返回匹配成功数量
    	// 8、返回匹配成功的各CMS版本条数
    }



}