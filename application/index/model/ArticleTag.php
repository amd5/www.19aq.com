<?php
# #########################################
# #Function:    文章标签
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-11-24 22:30:31
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;

class ArticleTag extends Model
{	
	//首页标签列表
	public function taglist(){
		$result = self::select();
		foreach ($result as $k => $v) {
			# code...
			$ls = $this -> qu_tag_num($v['tid']);
			$result[$k]['tag_num'] = $ls;
			// dump($result);
		}
		return $result;
	}
	
	//查询文章详情所包含的标签
	public function articlelist($id){
		$where="find_in_set($id,gid)";
		$result = self::where($where)->select();

		return $result;
	}

	//统计每个标签里面有多少文章
	public function qu_tag_num($id){
		$result = self::where('tid',$id)->find();
		$str = substr_count($result['gid'],',');
		$str = $str - 1;
		
		return $str;
	}

}