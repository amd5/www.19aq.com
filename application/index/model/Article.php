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
		// $result = ArticleTag::select();
		return $this->hasMany('ArticleTag','gid');
		// return $result;
		//hasMany('关联模型名','外键名','主键名',['模型别名定义']);
	}

	// public function indextag(){
	// 	$gid = '34';
	// 	$data = ArticleTag::select();
	// 	$tags = new Article;
	// 	$tagss = $tags->qutag($data,$gid);
	// 	$ls = $this -> qutag($data,$gid);

	// 	return $ls;
	// }

	//根据文章取文章标签
	public function qutag($data,$gid)
	{
		if(is_array($data) && !empty($data)){
			$_data = [];
			foreach($data as $a){
				if(!empty($a['gid'])){
					$b = substr($a['gid'],1);
					$b = substr($b,0,-1);
					$c = explode(',',$b);
					foreach($c as $d){
						if($d == $gid){
							array_push($_data,$a['tagname']);
						}
					}
				}
			}
			return $_data;
			// dump($_data);
		}

	}

	//分类页面--文章列表  不是管理员显示没有密码的文章
	public function SortArticlelist($sid){
		// $result = self::with('sort,tag')
		$result = self::order('id','desc')
        ->where('password','=','')
        ->where('sortid','=',$sid)
        ->limit(15)
        ->paginate();

        //遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
		}

		return $result;
	}

	//分类页面--文章列表  管理员显示全部文章
	public function SortArticleALL($sid){
		$result = self::where('sortid','=',$sid)
        ->order('id','desc')
        ->limit(15)
        ->paginate();

        //遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
		}
        
		return $result;
	}
	//缓存-首页-显示全部文章（带密码的除外）
	// public function Articles(){
	// 	$cache = Cache::get('Articles');
 //        if($cache == false){
 //            $Articles   = $this->Articlesa();
 //            Cache::set('Articles',$Articles,14000);
 //            $cache = Cache::get('Articles');
 //        }
	// 	return $cache;
	// }
	//首页-显示全部文章（带密码的除外）
	public function Articles(){
        // $result = self::with('tag')->
        $result = self::order('id','desc')
		->where('password','=','')
		->paginate(15);

		//遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
		}

		return $result;
	}
	//缓存-首页-显示所有文章
	// public function Articleall(){
	// 	$cache = Cache::get('Articleall');
 //        if($cache == false){
 //            $Articleall   = $this->Articlealls();
 //            Cache::set('Articleall',$Articleall,14000);
 //            $cache = Cache::get('Articleall');
 //        }
	// 	return $cache;
	// }
	//首页-显示所有文章
	public function Articleall(){
        $result = self::order('id','desc')
		->paginate(15);

		//遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
		}

		return $result;
	}
	//缓存-标签文章列表
	public function tagArticle($tags){
		$cache = Cache::get('tagArticle');
        if($cache == false){
            $tagArticle   = $this->tagArticlea($tags);
            Cache::set('tagArticle',$tagArticle,14000);
            $cache = Cache::get('tagArticle');
        }
		return $cache;
	}
	//标签文章列表
	public function tagArticlea($tags){
		// $result = self::all($tags,'',false);
		$result = self::where('id','in',$tags)
		->limit(15)
		->paginate();
		
		//遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
		}

		return $result;
	}
	//文章缓存
	public function article($id){
		$cache = Cache::get('article');
        if($cache == false){
            $article   = $this->articlea($id);
            Cache::set('article',$article,14000);
            $cache = Cache::get('article');
        }
		return $cache;
	}
	//文章
	public function articlea($id){
		$result = self::where('id','=',$id)->find();
		//每次被访问增加阅读1
		$view = self::where('id',$id)->setInc('views');
		return $result;
	}
	//首页-前台搜索功能
	public function search($key)
	{
		$result = self::where('title','like','%'.$key.'%')
		->limit(15)
		->paginate();


		//遍历文章ID 根据文章ID取文章标签 后 赋值给文章数据
		$datas = ArticleTag::select();
		foreach ($result as $a => $value) {
			$ls = $this -> qutag($datas,$value['id']);
			$result[$a]['tag_name'] = $ls;
			$result[$a]['searchkey'] = $key;

		}
		// dump($result);


		// die;
		return $result;
	}

	public function a()
	{
		//查询本模型全部数据
		$info = self::all();
        return $info;
	}


}