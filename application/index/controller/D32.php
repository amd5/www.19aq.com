<?php

namespace app\index\controller;
use think\Controller;
use think\Db;

class D32
{
    public function index()
    {
        
		// 查询数据
		$list = Db::name('data')
			->where('')   //where('id', 1)
			->select();
			dump($list);
		// 更新记录
		$update = Db::name('data')
			->where('id', 1)
			->update(['body' => "framework"]);
			dump($update);
		// 插入记录
		Db::name('data')
			->insert([ 'title' => 'thinkphp','body'=> '123123']);
		// 删除数据
		Db::name('data')
			->where('id',2) 
			->delete();

    }
}