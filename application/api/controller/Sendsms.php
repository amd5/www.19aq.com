<?php
namespace app\api\controller;

use think\Controller;
/*载入第三方模块*/
use app\extra\ip\IpLocation;
use app\extra\api_demo\SmsDemo;
use app\api\model\System_config;


class Sendsms extends Controller
{
    //监控http状态异常则发警告短信
    public function httpsend()
    {
        $id     = System_config::where('name','Access_Key_ID')->find();
        $Secret = System_config::where('name','Access_Key_Secret')->find();
        // echo $id['content'];
        $demo = new SmsDemo($id['content'],$Secret['content']);
        $response = $demo->sendSms("c32博客","SMS_115390722","15024267536",Array("address"=>'湖北省武汉市',"ip"=>'1_2_4_8'),"123");
        print_r($response);
    }
    //首页网友访问发送短信
    public function mainsend()
    {
        $id     = System_config::where('name','Access_Key_ID')->find();
        $Secret = System_config::where('name','Access_Key_Secret')->find();
        
        $ip=new IpLocation();
        $dizhi = $this->request->ip();
        $ipdizhi = $ip->getlocation($dizhi);
        $xxip = $ipdizhi['ip'];
        $xxip = str_replace('.','_',$xxip);
        //输出详细地址
        $xxdz = $ipdizhi['country'].$ipdizhi['area'];

        if(session('id') <> "1")
        {
            $demo = new SmsDemo($id['content'],$Secret['content']);
            $response = $demo->sendSms("c32博客","SMS_115390722","15024267536",Array("address"=>$xxdz,"ip"=>$xxip),"123");
            print_r($response);
        }
        echo (session('id'));

    }



}