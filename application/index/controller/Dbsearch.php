<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Dbsearch
{

    public function index()
    {
		//查询数据
		$db = db('data');
		$result = $db->select();
		dump($result);
		// 更新记录
		$db->where('id', 1)->update(['body' => "framework"]);
		// 插入记录
		$db->insert(['id' => 100, 'title' => 'thinkphp','body'=>'neironga']);
		// 删除数据
		$db->where('id', 100)->delete();
		
    }
}