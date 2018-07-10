<?php
//感谢github作者zjw710
namespace app\admin\controller;

use think\Controller;
// use think\Request;
use think\Cache;
use think\Session;

class BaseController extends Controller
{
	public function _initialize()
    {
        session::init();
        $id    = session_id();
        $sid   = cache::get($id);
        //判断用户是否登陆
        if(!isset($sid)) {
            //如果Session为空进入后台登录界面
            if(!$_SERVER['PHP_SELF'] = "index.php"){
                $this->redirect('./admin/login');
            }
        }else{
            if((time() - $sid) > 7200)  //Session有效期 秒
            {
                Cache::rm($id);
            }

        }
    }


}