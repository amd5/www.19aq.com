<?php
namespace app\extra\Redis;

use think\cache\driver\Redis;

class Index
{
public function index(){
$config = [
'host' => '127.0.0.1',
'port' => 6379,
'password' => '',
'select' => 0,
'timeout' => 0,
'expire' => 0,
'persistent' => false,
'prefix' => '',
];

$Redis=new Redis($config);
$Redis->set("testccccc","testccccc");
echo $Redis->get("testccccc");
}

>