<?php
//感谢github作者zjw710
namespace app\admin\controller;

use think\Controller;
// use think\Request;
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
            //
            if(!$_SERVER['PHP_SELF'] = "index.php"){
                $this->redirect('./admin/login');
            }
            //输出当前域名
            // echo $_SERVER['HTTP_HOST'];
        }else{
            if((time() - $logintime) > 7200)  //Session有效期 秒
            {
                Session::delete('username');
                Session::delete('logintime');
            }
            echo "Session id : " .session('id') ."</br>";
            echo "Session username : " .session('username') ."</br>";
            echo "Session last_login_ip : " .session('last_login_ip') ."</br>";
            echo("Session开始时间".  date("Y-m-d h-i-s",$logintime));
            //当Session正常且未过期自动进入后台主页
        }
    }


}