<?php
namespace app\extra\cache;
// use think\Controller;
// use app\extra\cache\Redis;
use app\admin\model\SystemConfig;

class Redisconfig
{
	public function index()
    {

    	$pass = SystemConfig::where('name',"Redis_Password")->find();
    	$port = SystemConfig::where('name',"Redis_Port")->find();
    	$host = SystemConfig::where('name',"Redis_Host")->find();

    	$config = [
		'host' => $host->content,
		'port' => $port->content,
		'password' => $pass->content,
		'select' => 0,   //选择redis数据库
		'timeout' => 0,
		'expire' => 0,
		'persistent' => false,
		'prefix' => '',
		];


		$Redis=new Redis($config);
		// dump($Redis);
		// echo "22222222";
		// $Redis->set("blog","test");
		// echo $Redis->get("foo");
        return $Redis;
    }
}