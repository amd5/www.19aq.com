<?php
# #########################################
# #Function:    友情链接
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-11-20 18:55:19
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;

class Link extends Model
{
	public function links()
    {
    	$links = Link::order('id','asc')
    	->where('hide','n')
        ->select();

        return $links;
    }

}