<?php
namespace app\admin\model;

use think\Model;

class ArticleSort extends Model
{
	protected $name = 'article_sort';


	public function items() { //建立一对多关联  
        return $this->hasMany('Articlelist', 'sortid', 'sid'); //关联的模型，外键，当前模型的主键   hasOne
    }  
	
	
	
}