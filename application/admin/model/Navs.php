<?php
# #########################################
# #Function:    文章功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;

use think\Cache;
use think\Model;

class Navs extends Model
{
    public function navs(){
        $cache = Cache::get('navs');
        if($cache == false){
            $nav   = $this->navlist();
            Cache::set('navs',$nav,2592000);
            $cache = Cache::get('navs');
        }
        return $cache;
    }
    public function navlist(){
		$data = self::field('id,href') -> where(['state' => 'y','spread' => '']) -> select();
        $nav = null;
        // if($data){
            foreach($data as $a){
                $nava = [];
                $navb = [];
                $nava = self::field('id,title,icon,href,spread,target') -> where(['sid' => $a['id'],'state' => 'y']) -> select();
                // if($nava){
                    foreach($nava as $s => $aa){
                        $ls = self::field('title,icon,href,spread,target') -> where(['sid' => $aa['id'],'state' => 'y']) -> select();
                        if($ls){
                            $nava[$s]['children'] = $ls;
                        }
                        unset($nava[$s]['id']);
                        if(empty($nava[$s]['target'])){
                            unset($nava[$s]['target']);
                        }
                        if($nava[$s]['spread'] == 'false'){
                            $nava[$s]['spread'] = false;
                        }else{
                            $nava[$s]['spread'] = true;
                        }
                    }
                $nav[$a['href']] = array_merge($nava,$navb);
            }
        return $nav;
	}

	
}