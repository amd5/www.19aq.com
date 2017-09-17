<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class NewsContrallor
{

    public function index()
    {
		// // 插入记录
		// $result = Db::query('show tables from demo');
		// dump($result);
		// Db::table('data')
			// ->insert(['title' => 'biaoti', 'body' => 'neirong']);
		// // 更新记录
		// Db::table('think_data')
			// ->where('id', 18)
			// ->update(['name' => "hello"]);
		// // 查询数据
		// $list = Db::table('think_data')
			// ->where('id', 18)
			// ->select();
		// // 删除数据
		// Db::table('think_data')
			// ->where('id', 18)
			// ->delete();
		$db = db('data');
		$list = $db->select();
		dump($list);

    }
    // public function add(){
        // $this->display();
    // }
    // public function insert(){
        // $this->display();
    // }
    // public function delete(){
        // $this->display();
    // }
    // public function edit(){
        // $this->display();
    // } public function update(){
        // $this->display();
    // } 
    //}

}