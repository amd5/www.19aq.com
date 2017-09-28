<?php
namespace app\admin\controller;

use think\Controller;
//use think\Db;

class ArticleList extends Controller
{
    public function index()
    {
        return $this->fetch();
		
    }
	
}
