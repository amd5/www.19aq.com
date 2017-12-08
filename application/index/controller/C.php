<?php

namespace app\index\controller;

class C
{
    public function index()
    {
    	//设置执行超时时间
		set_time_limit(0);
		//共计循环次数
		// $a 			= '0' ;		
		// //初始化CURL
		// $ch 		= curl_init();  
		// $url 		= 'http://192.168.2.9/address/explorer.php?'.$a;
		// $timeout 	= 10;
		// // 2. 设置选项，包括URL  
		// curl_setopt($ch, CURLOPT_URL, $url);  
		// curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		// curl_setopt($ch, CURLOPT_HEADER, 0);  
		// // 3. 执行并获取HTML文档内容  
		// $info = curl_exec($ch);  
		// preg_match('/<tr class="illu"><td>(.*?)<\/td><td><a href="?(.*?)">(.*?)<\/a><\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)----(.*?)<\/td>/',$info,$m);
		// dump($info) ;
		// //区块高度
		// dump($m[1]); 
		// //区块hash
		// dump($m[2]);
		// //区块难度
		// dump($m[4]);
		// //区块时间
		// dump($m[5]);
		// //交易笔数
		// dump($m[6]);
		// //交易枚数
		// dump($m[7]);


		$a 			= '200900' ;	

		for ($i=1; $i<=$a ; $i++) { 
			# code...
			// print ($i."</br>");
			//初始化CURL
			$ch 		= curl_init();  
			$url 		= 'http://192.168.2.9/address/explorer.php?'.$i;
			$timeout 	= 1;
			// 2. 设置选项，包括URL  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			curl_setopt($ch, CURLOPT_HEADER, 0);  
			// 3. 执行并获取HTML文档内容  

			
			$info = curl_exec($ch);  

			if($info!=false)
			{
				preg_match('/<tr class="illu"><td>(.*?)<\/td><td><a href="?(.*?)">(.*?)<\/a><\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)----(.*?)<\/td>/',$info,$m);
			}else{
				return $info();
			}
			// dump ($info);
			// $code = curl_getinfo($ch);
			
			// print ($code['http_code']);
			// print ($i."</br>");
			// print ($m[1]."</br>");
			// $update['blockid']		= $m[1];
			// $update['hash']			= $m[2];
			// $update['difficulty']	= $m[4];
			// $update['time']			= $m[5];
			// $update['frequency']	= $m[6];
			// $update['number']		= $m[7];
			// dump ($update);
			// Db::name("block")->insert($update);
			// Db::name("block")->select();

			
			// print ("区块高度".$m[1]."</br>");
			print ("区块高度".$m[1]."区块哈希: ".$m[2]."</br>");
			//刷新输出缓冲区
			ob_flush();
			//将缓冲区输出发送到浏览器
			flush();
			curl_close($ch);  
			

		}


		//释放curl句柄  
		// curl_close($ch);  
    }
}