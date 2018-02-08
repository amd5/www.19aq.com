<?php
# #########################################
# #Function:    文章功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #感谢King east  QQ1207877378指导的模型关联
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
		return $this->hasMany('ArticleTag','gid');
		//hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	}

	//分类页面--文章列表  不是管理员显示没有密码的文章
	public function Articlelist($sid){
		$result = Article::order('id','desc')
        ->where('password','=','')
        ->where('sortid','=',$sid)
        ->limit(15)
        ->paginate();

		return $result;
	}

	//分类页面--文章列表  管理员显示全部文章
	public function Articlea($sid){
		$result = Article::where('sortid','=',$sid)
        ->order('id','desc')
        ->limit(15)
        ->paginate();
        
		return $result;
	}
	//显示全部文章（带密码的除外）
	public function Articles(){
        $result = Article::order('id','desc')
		->where('password','=','')
		->limit(15)
		->paginate();

		return $result;
	}
	//显示所有文章
	public function Articleall(){
        $result = Article::order('id','desc')
		->limit(15)
		->paginate();

		return $result;
	}

	//标签文章列表
	public function tagArticle($tags){
		$result = Article::all($tags,'',false);
		return $result;
	}


}