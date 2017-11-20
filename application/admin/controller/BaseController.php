<?php
//感谢github作者zjw710
namespace app\admin\controller;

use app\index\model\User;
use think\Controller;
use think\Request;
use think\Session;

class BaseController extends Controller
{
	public function _initialize(){
        $sid = session('username');
        //取值并删除Session
        Session::pull('username');
        // Session::set('username',$result["username"]);
        // dump(session('username'));
        //判断用户是否登陆
        if(!isset($sid)) {
        	echo "nooooooooo";
        	// $this->redirect('index/login');
        	$this->redirect('./admin/index/login');die();   //所有admin/index方法重复执行了这个地方，导致重定向过多，无法访问
        	// 
            // redirect('Index/login');
        }else{
        	echo "you";
        }
    }

}