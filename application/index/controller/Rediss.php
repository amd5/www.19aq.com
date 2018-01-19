<?php

namespace app\index\controller;
use think\Controller;

use app\extra\cache\Redisconfig;    //载入ip类a

class Rediss extends Controller
{
    public function index()
    {
        $config = new Redisconfig();
        $Redis = $config->index();
        echo($Redis->get("blog"));
        
        // $Redis->set("blog","test");
        // echo $Redis->get("blog");

        // return $this->fetch();
    }

    public function a()
    {
        // phpinfo();
    }


}