<?php
namespace app\index\controller;

use app\index\model\User;
use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{
	public function index()
	{
		echo "12";
	}

    public function _initialize()
    {
    	$user = User::get(Session::get('user_id'));
		Request::instance()->bind('user',$user);
    }
}