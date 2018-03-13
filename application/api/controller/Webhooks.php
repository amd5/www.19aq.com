<?php
namespace app\api\controller;

use think\Controller;

class Webhooks extends Controller
{
    public function a1()
    {
        //echo shell_exec("ifconfig");
        echo "webhooksf</br>";
        $lj = shell_exec('pwd');
        $qu=substr($lj,-8);
        $path =explode($qu,$lj);
        //dump($path['0']);
        $dir = $path['0'];
        error_reporting ( E_ALL );
        //$dir = '/www/wwwroot/www.19aq.com/';//该目录为git检出目录
        $handle = popen('cd '.$dir.' && git pull 2>&1','r');
        $read = stream_get_contents($handle);
        printf($read."</br>");
        pclose($handle);
    }

    public function a2()
    {
        //echo shell_exec("ifconfig");
        echo "</br>";
        error_reporting ( E_ALL );
        $dir = '/www/wwwroot/www.19aq.com/';//该目录为git检出目录
        // $handle = popen('cd '.$dir.' && git checkout -f','r');
        $handle = popen('cd '.$dir.' && chown -R www:www *','r');
        $read = stream_get_contents($handle);
        printf($read."</br>");
        pclose($handle);
    }

    public function a3()
    {
        //echo shell_exec("ifconfig");
        echo "拉取错误重复执行a3即可</br>";
        error_reporting ( E_ALL );
        $dir = '/www/wwwroot/www.19aq.com/';//该目录为git检出目录
        $handle = popen('cd '.$dir.' && git checkout -f && git pull','r');
        $read = stream_get_contents($handle);
        printf($read."</br>");
        pclose($handle);
    }

    public function a4()
    {
        //echo shell_exec("ifconfig");
        echo "</br>";
        error_reporting ( E_ALL );
        $dir = '/www/wwwroot/www.19aq.com/';//该目录为git检出目录
        $handle = popen('cd '.$dir.' && git clean -d -fx && git pull','r');
        $read = stream_get_contents($handle);
        printf($read."</br>");
        pclose($handle);
    }

}