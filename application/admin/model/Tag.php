<?php
namespace app\admin\model;

use think\Model;

class Tag extends Model
{
	public static function list($page, $limit){
		$result = self::page($page)
		->limit($limit)
		->cache(true,8640000)
		->select();
		
		$count = self::cache(true,8640000)->count();
		foreach ($result as $key => $value) {
			$value['id_count'] = $count;
			$value['addtime'] = date('Y-m-d H:i:s',$value['addtime'] ?: time());
		}
		
		return $result;
	}

	public static function qiantai(){
		$data = [];
		$result = self::cache(true,8640000)->select();
		if (!$result) {
			return false;
		}
		foreach ($result as $key => $value) {
			$res['tid'] = $value['tid'];
			$res['tagname'] = $value['tagname'];
			$tag_count = Taga::where('tid',$value['tid'])->cache(true,8640000)->count();
			$res['count'] = $tag_count ? $tag_count : 0;
			array_push($data,$res);
		}
		return $data;
	}

}