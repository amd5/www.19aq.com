<?php
# #########################################
# #Function:    文档功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;


use think\Db;
use think\Model;
use think\Exception;
use think\Controller;

class ManageUser extends Model
{
	// protected $name = 'manage_user';
	// role获取器
    public function getRoleAttr($value,$data){
	$role = ['admin' =>'超级管理员','admin' =>'普通管理员','writer' => '作者'];
	return $role[$data['role']];
	}
	
	
	
	
	protected function initialize()
    {
        // 需要调用`Model`的`initialize`方法
        parent::initialize();
        // TODO:自定义的初始化
    }
	
	public function ManageUser()
    {
		$result = ManageUser::all();
		//// dump ($result);
		return $result;
		//// return $result->hasMany('ManageUserRole','role','id');
		
    }
	
}