<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\Api_cheip;
/*其他第三方模块*/
use app\extra\ip\IpLocation;

class Ip extends Controller
{
    public function cheip($ip)
    {
        $add=new IpLocation();
        
        //查询请求IP对应地址信息
        $ipdizhi = $add->getlocation($ip);

        //使用官方数据库需要进行编码转换GBK->UTF-8
        // $ipdizhi['country'] = iconv('GBK//IGNORE', 'UTF-8', $ipdizhi['country']);
        $ipdizhi['country'] = iconv('GBK', 'UTF-8', $ipdizhi['country']);
        $ipdizhi['area'] = iconv('GBK', 'UTF-8', $ipdizhi['area']);
        // dump($ipdizhi);

        //获取访问者外网IP
        $dizhi = $this->request->ip();

        //本地IP的详细物理地址
        $local = $add->getlocation($dizhi);
        $xxip = $ipdizhi['ip'];
        $xxdz = $ipdizhi['country'].$ipdizhi['area'];

        //输出查询IP的详细物理地址
        // echo ($xxip.$xxdz);

        //json格式输出内容
        echo json_encode(($ipdizhi));
        
        //获取本地IP、查询IP、查询时间
        $data['localip']    = $dizhi;
        $data['localaddr']  = $local['country'].$local['area'];
        $data['cheip']      = $ip;
        $data['cheaddr']    = $ipdizhi['country'].$ipdizhi['area'];
        $data['time']       = time();
        //插入数据库
        $result = Api_cheip::insert($data);
    }

}