<?php
# #########################################
# #Function:    文档功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;

use think\Model;

class Article extends Model
{
	// status读取器
    public function getStatus11Attr($value,$data){
	$status = [-1 =>'删除',0 =>'隐藏',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}
	
	public function index()
    {
        // return $this->fetch(logincheck);	//默认进入登陆界面
		return $this->fetch();
    }
	
	
}