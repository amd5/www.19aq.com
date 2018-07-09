<?php
# #########################################
# #Function:    友情链接
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-11-20 18:55:19
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;
use think\Cache;

class Link extends Model
{
	public function links(){
		$cache = Cache::get('links');
        if($cache == false){
            $links = Link::order('id','asc')
            ->where('hide','n')
            ->select();
            Cache::set('links',$links,140000);
            $cache = Cache::get('links');
        }
        return $cache;
	}
}