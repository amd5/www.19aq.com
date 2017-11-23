<?php
//感谢github作者zjw710
namespace app\admin\controller;

// use app\index\model\User;
use app\admin\model\ManageUser;
use think\Controller;
use think\Request;
use think\Session;

class BaseController extends Controller
{
	public function _initialize()
    {
        $sid = session('username');
        $logintime = session('logintime');
        //判断用户是否登陆
        if(!isset($sid)) {
            //如果Session为空进入后台登录界面
        	$this->redirect('./admin/login');
        }else{
            if((time() - $logintime) > 7200)  //Session有效期 秒
            {
                Session::delete('username');
                Session::delete('logintime');
            }
        	echo "Session ok </br>";
            echo("Session开始时间".  date("Y-m-d h-i-s",$logintime));
            //当Session正常且未过期自动进入后台主页
        }
    }



}