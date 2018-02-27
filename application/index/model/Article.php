<?php
# #########################################
# #Function:    文章功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #感谢King east  QQ1207877378指导的模型关联
# #感谢流年 QQ130770305指导解决后台查询300条文章产生600条语句的问题
# #########################################
namespace app\index\model;

use think\Model;
use app\index\model\ArticleTag;

class Article extends Model
{
	// 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 定义自动完成的属性
    protected $insert = ['status' => 1];
    
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

	// public function indextag(){
	// 	$where="find_in_set($id,gid)";
	// 	$result = ArticleTag::where($where)->select();
	// 	return $result;
	// }

	//分类页面--文章列表  不是管理员显示没有密码的文章
	public function Articlelist($sid){
		$result = self::with('sort,tag')
		->order('id','desc')
        ->where('password','=','')
        ->where('sortid','=',$sid)
        ->limit(15)
        ->paginate();

		return $result;
	}

	//分类页面--文章列表  管理员显示全部文章
	public function Articlea($sid){
		$result = self::with('sort,tag')
		->where('sortid','=',$sid)
        ->order('id','desc')
        ->limit(15)
        ->paginate();
        
		return $result;
	}

	public function indextag($tags){
		$result = ArticleTag::where('tid','in',$tags)
		->select();
		return $result;
		// dump($result);
	}
	//首页-显示全部文章（带密码的除外）
	public function Articles(){
        // $result = self::with('tag')->
        $data = self::order('id','desc')
		->where('password','=','')
		->paginate(15);

		// foreach ($result as $key => $value) {
  //               # code...
  //               $tagid = $value['tagid'];
                
  //               $str  = substr($tagid,0,strlen($tagid)-1); 
  //               $tags   = substr($str,1);
  //               dump($tags);
  //           }
  //           die;

  //       $result = ArticleTag::where('id','in',$tags)
		// ->paginate(15);
		// ->select();
		// dump()

            // $result = self::where('id','in',$tags)->select();

		return $data;
	}
	//首页-显示所有文章
	public function Articleall(){
        $result = self::with('sort,tag')
        ->order('id','desc')
		->paginate(15);

		return $result;
	}

	//标签文章列表
	public function tagArticle($tags){
		// $result = self::all($tags,'',false);
		$result = self::with('sort,tag')
		->where('id','in',$tags)
		->limit(15)
		->paginate();
		// dump($tags);
		return $result;
	}

	public function article($id){
		$result = self::where('id','=',$id)->find();
		//每次被访问增加阅读1
		$view = self::where('id',$id)->setInc('views');
		return $result;
	}
	//首页-前台搜索功能
	public function search($key)
	{
		$result = self::with('sort,tag')
		->where('title','like','%'.$key.'%')
		->limit(15)
		->paginate();
		return $result;
	}

	public function a()
	{
		//查询本模型全部数据
		$info = self::all();
        return $info;
	}


}