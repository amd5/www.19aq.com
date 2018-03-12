<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Api_httpcode;
use app\api\controller\Sendsms;

class Httpcode extends Controller
{
    public function index()
    {
        return 1;
    }

    public function scan($url,$time)
    {
        //设置执行超时时间
        set_time_limit(0);
        // $url = 'http://www.19aq.com/';

        //初始化CURL
        $ch = curl_init ();  
        //设置访问url
        curl_setopt($ch, CURLOPT_URL, $url);  
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $time);  
        //设置头
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;http://www.19aq.com)'); 
        //设置CURLOPT_HEADER为false 不返回头部信息
        curl_setopt($ch, CURLOPT_HEADER, ture);  
        //CURLOPT_NOBODY 不获取内容
        curl_setopt($ch, CURLOPT_NOBODY, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        //访问时不进行重定向访问
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);  
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');  
        $http = curl_exec($ch);  
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);  
        // dump($httpCode);
        $info = curl_getinfo($ch);
        // echo($info['download_content_length']);
        //测试访问每个页面需要多长时间
        // echo ($info['total_time']."</br>");
        curl_close($ch);

        return $httpCode;
    }

    public function httplist()
    {
        $time = 200;
        $result = Api_httpcode::where('status','1')->select();

        //调用短信访问异常则报警
        $dx    = new Sendsms();
        $http  = new Httpcode();
        
        foreach ($result as $key => $value) {
            $code = $http->scan($value->url,$time);
            if($code=='200'){
                echo ("HTTP状态码".$code."&nbsp;".$value['url']."</br>");
            }elseif($code >= '300' and $code < '399'){
                echo "30x"."</br>";
            }elseif($code >= '400' and $code < '499'){
                echo "40x"."</br>";
            }elseif($code >= '500' and $code < '600'){
                echo "50x"."</br>";
            }elseif($code == '0'){
                echo "无法访问"."</br>";
                echo $dx->httpsend();
            }else{
                echo "未知错误"."</br>";
                echo $dx->httpsend();
            }
        }
    }

}