<?php
# #########################################
# #Function:    文章功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;

use think\Db;
use think\Model;
use think\Request;
use think\Controller;
use think\Exception;
use app\admin\model\ArticleSort;


class Article extends Model
{
	// status获取器
    public function getStatusAttr($value,$data){
	$status = [-1 =>'删除',0 =>'隐藏',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}
	
	
	// public function index()
    // {
        // return $this->fetch(logincheck);	//默认进入登陆界面
		// return $this->fetch();
    // }
	
	public function ArticleList()
    {
		$result = Article::all();
		return $result;
    }
	
	public function Article($id)
    {
		$result = Article::where('id','=',$id)->select();
		return $result;
    }
	
	public function Pcs()
    {
    	return $this->hasMany('ArticleSort','taxis','sortid');
    }
	
	public function ArticleEdit($id)
    {
		$result = Article::where('id', $id)
			->update([
			'title' 	=> $_POST["articletitle"],
			'content' 	=> $_POST["content"],
			'sortid'	=> $_POST["brandclass"],
			'date' 		=> strtotime($_POST["datetime"]),
			
			]);
			
		return $result;
    }
	
	public function ArticleDel($id,$action)  //未解决
    {
		echo "123";
    }
	
	
	
	
	
}