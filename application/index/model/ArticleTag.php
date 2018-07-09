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
use think\Cache;

class ArticleTag extends Model
{
	public function taglist(){
    	$result = self::field('distinct tagname')->select();
    	foreach ($result as $key => $value) {
    		$value['tag_num'] = $this->tagsearch($value['tagname']);
    	}
    	return $result;
    }

    public function tagsearch($name){
        $result = Cache::get($name);
        if ($result == false) {
            $result = self::where('tagname',$name)->count();
            Cache::set($name,$result,160);
            $result = Cache::get($name);
        }
    	
    	return $result;
    }

    public function articletag($id){
        $result = self::where('tid',$id)->select();
        return $result;
    }

}