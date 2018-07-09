<?php
# #########################################
# #Function:    文章分类
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-31 21:49:07
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;
use app\admin\model\Article;

use think\Model;

class ArticleSort extends Model
{
	// status读取器
    public function getStatus11Attr($value,$data){
	$status = [-1 =>'删除',0 =>'隐藏',1 => '正常',2 =>'待审核'];
	return $status[$data['status']];
	}

	public function Article()
    {
		return $this->belongsTo('Article','sortid','sid');
    }
    //获取所有分类名称并展示到发布文章页面
	public function ArticleSort()
	{
		$result = ArticleSort::order('sid', 'asc')->select();
		foreach ($result as $key => $value) {
			switch ($value['status']) {
				case '1':$value['status'] = '正常';break;
				case '2':$value['status'] = '已删除';break;
				default:
					# code...
					break;
			}
		}
		return $result;
	}
	
	public function ArticleSortAdd()
    {
		$data['sortname']    = $_POST["SortName"];			//分类名称
		$data['alias']       = $_POST["SortAlias"];		//分类别名
		$data['description'] = $_POST["SortDesc"];		//分类描述
		$result = ArticleSort::insert($data);
			
		return $result;
    }
	
	
	
}