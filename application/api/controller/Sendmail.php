<?php
namespace app\api\controller;

use think\Controller;
/*载入第三方模块*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Sendmail extends Controller
{
	public function index()
	{
		// echo "string";
		// $mail=new PHPMailer();
        // dump($mail);

		$info_sum = "test";

	}

	public function send_headas(){
		
		$fahuo = "https://www.19aq.com/article-286.html";
		$kucun = "https://www.19aq.com/article-286.html";
		$time=date("Y-m-d H-i-s");
		$shijian=date("Y-m-d");
		$info = file_get_contents ( $fahuo );
		$info1 = file_get_contents ($kucun);
		$info_sum = $info. " " .$info1;

		try {
			$mail = new PHPMailer(true); 
			// $mail->SMTPDebug = 1;			// 开启Debug
			$mail->IsSMTP();          // 使用SMTP模式发送新建
			$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth   = true;                  //开启认证
			$mail->SMTPSecure = "ssl";          //打开SSL加密
			$mail->Port       = 465;                    
			$mail->Host       = "smtp.exmail.qq.com"; 
			$mail->Username   = "chenquan@headas.com";    
			$mail->Password   = "cC3253220";            
			//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
			$mail->AddReplyTo("chenquan@headas.com","mckee");//回复地址
			$mail->From       = "chenquan@headas.com";
			$mail->FromName   = "www.headas.com";
			$mail->AddAddress("chenquan@headas.com");
			// $mail->AddAddress("yangyan@headas.com");
			// $mail->AddAddress("duanlei@headas.com");
			$mail->Subject  = "test $shijian";
			$mail->Body = $info_sum;
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap   = 80; // 设置每行字符串的长度
			//$mail->AddAttachment("tmp/$time.html");  //可以添加附件
			$mail->IsHTML(true); 
			$mail->Send();
			echo 'email send Success';
		}catch (Exception $e){
			echo "Email send Error：".$mail->errorMessage();
		}
	}
	}
    

}