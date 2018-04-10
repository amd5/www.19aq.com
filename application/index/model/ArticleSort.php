<?php
# #########################################
# #Function:    文章分类
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-31 21:49:07
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;
use think\Cache;

class ArticleSort extends Model
{
	public function sort(){
		// return $this->hasOne('Article','sortid','sid');
		//hasOne('关联模型名','外键名','主键名',['模型别名定义'],'join类型');
		return $this->hasMany('Article','sortid','sid');
	}
    //缓存-分类列表
    public function Sortlist(){
        $cache = Cache::get('Sortlist');
        if($cache == false){
            $Sortlist   = $this->Sortlista();
            Cache::set('Sortlist',$Sortlist,14000);
            $cache = Cache::get('Sortlist');
        }
        return $cache;
    }
    //分了列表
	public function Sortlista()
    {
    	$articlesort = self::withCount('sort')
        ->where('status','=','1')
        ->select();

        return $articlesort;
    }
    //文章页 显示分类名称
    public function sortname($id){
    	$sort = self::where('sid',$id)
        ->find();

		return $sort;
	}

	
}