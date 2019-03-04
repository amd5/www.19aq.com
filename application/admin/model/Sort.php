<?php
namespace app\admin\model;

use think\Model;

class Sort extends Model
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
			$res['sid'] = $value['sid'];
			$res['sortname'] = $value['sortname'];
			$sort_count = Article::where('sort',$value['sid'])->cache(true,8640000)->count();
			$res['count'] = $sort_count ? $sort_count : 0;
			array_push($data,$res);
		}
		return $data;
	}

}