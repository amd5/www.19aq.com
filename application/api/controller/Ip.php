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
        $ipdizhi = $add->getlocation($ip);
        $dizhi = $this->request->ip();
        $xxip = $ipdizhi['ip'];
        $xxdz = $ipdizhi['country'].$ipdizhi['area'];
        echo ($xxip.$xxdz);
        //获取本地IP、查询IP、查询时间
        $data['localip'] = $dizhi;
        $data['cheip']   = $ip;
        $data['time']    = time();
        //插入数据库
        $result = Api_cheip::insert($data);
    }

}