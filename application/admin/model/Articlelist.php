<?php
namespace app\admin\model;

use think\Model;

class Articlelist extends Model
{
	protected $name = 'article';
	// status读取器
    public function getStatus11Attr($value,$data){
	$status = [-1 =>'删除',0 =>'禁用',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}

	
}