<?php
# #########################################
# #Function:    文章功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;

class Article extends Model
{
	// status获取器
    public function getStatusAttr($value,$data){
	$status = [-1 =>'删除',0 =>'隐藏',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}
	
	//使用文章表sortid关联sort分类表的sid
	public function sort(){
		return $this->hasOne('ArticleSort','sid','sortid');
		//hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
	}

	public function tag(){
		return $this->hasMany('ArticleTag','gid','id');
		//hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	}


}