<?php
# #########################################
# #Function:    系统日志
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-12-4 18:57:14
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;

use think\Model;

class SystemLog extends Model
{
	public function loglist($page,$limit){
		$result = self::order('id desc')
		->page($page)
        ->limit($limit)
		->select();
		// 增加统计条数
		$count = self::count();
		foreach ($result as $key => $value) {
			$value['id_count'] = $count;
		}
		// dump($result);
		return $result;
	}
	
}