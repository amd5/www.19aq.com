<?php
namespace app\admin\model;

use think\Model;

class Articlelist extends Model
{
	protected $name = 'article';
	// status修改器
    public function getStatus1Attr($value,$data){
	$status = [-1 =>'删除',0 =>'禁用',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}
	// sort修改器
    public function getSortid1Attr($value,$data){
	$sortid = [-1 =>'A分类',2 =>'B分类',12 => 'C分类',14 =>'D分类',15 =>'F分类',17 =>'G分类'];
	return $sortid[$data['sortid']];
	}

	
}