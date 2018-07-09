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
use think\Cache;
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
	
	//后台文章列表关联文章所属分类
	public function sort(){
		return $this->hasOne('ArticleSort','sid','sortid')->field('sid,sortname');
		//hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
	}
	//后台文章列表关联作者
	public function admin(){
		return $this->hasOne('ManageUser','id','author')->field('id,username');
	}

	//后台文章列表
	public function ArticleList($page,$limit,$key)
    {
    	$cache = Cache::get('navs'.$page.$limit.$key);
        if($cache == false){
	    	//使用关联预载入，解决语句N+1的查询问题
			$result = self::with('sort,admin')
			->whereOr('title&content','like','%'.$key.'%')
			->page($page)
	        ->limit($limit)
	        ->order('id desc')
	        ->select();

			//增加统计条数
			$count = self::count();
			foreach ($result as $key => $value) {
				$value['id_count'] = $count;
				$value['date'] = date('Y-m-s h:i:s',$value['date']);
			}
		    Cache::set('navs'.$page.$limit.$key,$result,60);
            $cache = Cache::get('navs'.$page.$limit.$key);
        }
        return $cache;
    }
	
	public function Article($id)
    {
		$result = self::where('id',$id)->select();
		return $result;
    }

	
}