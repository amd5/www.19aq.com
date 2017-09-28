<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Dbsearch extends Controller
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
	public function AdminList()
	{
		$adminlist = db('data');
		$result = $adminlist->select();
		$this->assign([
		'adminlist'  => $adminlist
		]);
		
		return $this->fetch('admin_list');
	}
}