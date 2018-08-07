<?php
namespace app\admin\controller;

use think\Controller;
use think\Cache;
use think\Session;

class BaseController extends Controller
{
	public function _initialize()
    {
        Session::init();
        $id    = session_id();
        $sid   = cache::get($id);

        if(isset($sid)) {
            if((time() - $sid) > 7200){Cache::rm($id);}
        }

        if(!$_SERVER['PHP_SELF'] = "index.php"){
            $this->redirect('./admin/login');
        }
    }


}