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
use think\Cache;
use app\index\model\ArticleTag;

class Article extends Model
{
	// 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 定义自动完成的属性
    protected $insert = ['status' => 1];
    
	//使用文章表sortid关联sort分类表的sid
	// public function sort(){
	// 	return $this->hasOne('ArticleSort','sid','sortid');
	// 	//hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
	// }

	// public function tag(){
	// 	return $this->hasMany('ArticleTag','gid');
	// 	//hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	// }

	//分类页面--文章列表  不是管理员显示没有密码的文章
	public function SortArticlelist($sid){
		$result = self::order('id','desc')
        ->where('password','=','')
        ->where('sortid','=',$sid)
        ->limit(15)
        ->paginate();
        $result = $this->tag($result);
		return $result;
	}

	//分类页面--文章列表  管理员显示全部文章
	// public function SortArticleALL($sid){
	// 	$result = self::where('sortid','=',$sid)
 //        ->order('id','desc')
 //        ->limit(15)
 //        ->paginate();
	// 	return $result;
	// }

	//首页-显示全部文章（带密码的除外）
	public function Article_list(){
        $result = self::order('id','desc')
		->where('password','=','')
		->paginate(15);
		$result = $this->tag($result);
		return $result;
	}

	//缓存-标签文章列表
	public function tagArticle($tags){
		$cache = Cache::get('tagArticle'.$tags);
        if($cache == false){
            $result = self::where('id','in',$tags)
			->limit(15)
			->paginate();
			$result = $this->tag($result);
            Cache::set('tagArticle'.$tags,$result,14000);
            $cache = Cache::get('tagArticle'.$tags);
        }
		return $cache;
	}

	//文章
	public function article($id){
		$result = Cache::get('article'.$id);
        if($result == false){
			if(Cookie('username') !== "c32"){
				$data = self::where('id','=',$id)->where('password','')->find();
				if (empty($data)) {
					die('非管理员禁止访问！');
				}
				$result = $data;
			}else{
				$result = self::where('id','=',$id)->find();
			}
			//每次被访问增加阅读1
			$view = self::where('id',$id)->setInc('views');
			//----------------------------------------------
			$tag_name = [];
			$tags = ArticleTag::where('tid',$id)->select();
			foreach ($tags as $key => $value) {
				array_push($tag_name, $vv['tagname']);
			}
			$result['tag_name'] = $tag_name;
			//----------------------------------------------
		Cache::set('article'.$id,$result,14000);
        $result = Cache::get('article'.$id);
        }
        return $result;
	}
	//首页-前台搜索功能
	public function search($key)
	{
		$result = Cache::get('search'.$key);
		if($result == false){
			$result = self::where('title','like','%'.$key.'%')
			->limit(15)
			->paginate();
		$result = $this->tag($result);
	   	Cache::set('search'.$key,$result,14000);
        $result = Cache::get('search'.$key);
        }
		return $result;
	}

	//本类内部调用
	public Function tag($result){
		foreach ($result as $key => $value) {
			$tags = ArticleTag::where('tid',$value['id'])->select();
			if ($tags == true) {
				$tag_name = [];
				foreach ($tags as $kk => $vv) {
					array_push($tag_name, $vv['tagname']);
				}
				$value['tag_name'] = $tag_name;
			}else{
				$value['tag_name'] ='';
			}
			
		}
		return $result;
	}

}