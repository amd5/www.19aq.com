<?php
namespace app\index\model;

use think\Model;

class ArticleSort extends Model
{
	public function sort(){
		return $this->hasMany('Article','sortid','sid');
	}
    
}