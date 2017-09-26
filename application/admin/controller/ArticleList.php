<?php
namespace app\admin\controller;

use think\Controller;
//use think\Db;

class Articlelist extends Controller
{
    public function index()
    {
        return $this->fetch();
		
    }
	
}
