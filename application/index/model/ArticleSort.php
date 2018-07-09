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
		return $this->hasMany('Article','sortid','sid');
	}
    //缓存-分类列表
    public function Sortlist(){
        $result = Cache::get('Sortlist');
        if($result == false){
            $result = self::withCount('sort')
            ->where('status','=','1')
            ->select();
            Cache::set('Sortlist',$result,14000);
            $result = Cache::get('Sortlist');
        }
        return $result;
    }
	
}