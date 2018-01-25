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
        //输出查询IP的详细物理地址
        echo ($xxip.$xxdz);
        //本地IP的详细物理地址
        $local = $add->getlocation($dizhi);

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