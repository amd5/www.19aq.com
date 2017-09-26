<?php
namespace app\admin\controller;

use think\Controller;
//use think\Db;

class Welcome extends Controller
{
    public function index()
    {
        return $this->fetch();
		
    }
	
}
