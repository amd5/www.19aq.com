<?php
namespace app\admin\model;

use think\Model;

class Manageuser extends Model
{
	protected $name = 'manage_user';
	// status修改器
    public function getRole1Attr($value,$data){
	$role = ['admin' =>'超级管理员','admin' =>'普通管理员','writer' => '作者'];
	return $role[$data['role']];
	}

	
}