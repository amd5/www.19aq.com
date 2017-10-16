<?php
namespace app\admin\model;

use think\Model;

class books extends Model
{
	protected $name = 'article_sort';
	
	// 定义关联方法
    // public function user()
    // {
        // return $this->hasOne('AdminUser', "id", "uid")->setAlias(["id" => "uuid"]);
    // }


	
}