<?php
# #########################################
# #Function:    文章标签
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-11-24 22:30:31
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;

class ArticleTag extends Model
{	
	//首页标签列表
	public function taglist(){
		$result = self::select();
		return $result;
	}
	//文章关联模型绑定
	// public function article(){
	// 	return $this->belongsTo('article');
	// }

}