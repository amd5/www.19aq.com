<?php
namespace app\admin\model;

use think\Model;

class Articlelistsortid extends Model
{
	protected $name = 'article_sort';
	
	// 定义一对一关联方法
	public function profile()
    {
        return $this->hasOne('sortid,sid');
    }
	
	
	// 定义一对多关联方法
    public function books()
    {
        return $this->hasMany('sortid,sid');
    }


	
}