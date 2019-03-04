<?php
namespace app\admin\model;

use think\Model;

class Config extends Model
{
	public static function list(){
		return self::cache(true,8640000)->select();
	}

}