<?php
namespace app\index\controller;

use think\Request;    //请求IP地址等
use think\Controller;

class Search extends Controller
{
    public function index()
    {
    	$request = Request::instance();

    	dump($request->param());
        echo "</br>Search</br>";
        dump($request->param('key'));

    }


}