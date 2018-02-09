<?php
namespace app\index\controller;

use think\Request;    //请求IP地址等
use think\Controller;

class Search extends Controller
{
	//构造方法  初始化实例化模型
    public function __construct()
    {
        // $this->record   = new Record;
        // $this->wenz     = new Article;
        // $this->tag      = new ArticleTag;
        // $this->sort     = new ArticleSort;
        //调用父类构造方法
        parent::__construct();
    }

    public function index()
    {
    	$request = Request::instance();
    	// $a = $this->param['key'];
    	// dump($a);die;

    	dump($request->param());
        echo "</br>Search</br>";
        dump($request->param('key'));

    }


}