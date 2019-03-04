<?php
namespace app\admin\model;

use think\Model;

class Log extends Model
{
	/**
	 * [list 友链列表数据]
	 * @param  [type] $page  [description]
	 * @param  [type] $limit [description]
	 * @return [type]        [description]
	 */
	public static function list($page, $limit){
		$result = self::page($page)
		->limit($limit)
		->order('id desc')
		->cache(true,8640000)
		->select();

		$count = self::cache(true,8640000)->count();
		foreach ($result as $key => $value) {
			$value['id_count'] = $count;
			$value['time'] = date('Y-m-d H:i:s',$value['time'] ?: time());
		}
		
		return $result;
	}
}