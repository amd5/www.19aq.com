<?php
namespace app\admin\model;

use think\Model;

class Manageuser extends Model
{
	protected $name = 'article';
	// status修改器
    public function getStatus1Attr($value,$data){
	$status = [-1 =>'删除',0 =>'禁用',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}
	// sort修改器
    public function getSortid1Attr($value,$data){
	$sortid = [12 =>'A分类',14 =>'B分类',99 => '正常',999 =>'待审核'];
	return $sortid[$data['sortid']];
	}

	
}