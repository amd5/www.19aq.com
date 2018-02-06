<?php
# #########################################
# #Function:    文章存档
# #Blog:        http://www.19aq.com/
# #Datetime:    2018-2-6 21:38:06
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\index\model;

use think\Model;
use app\index\model\Article;

class Record extends Model
{
	public function nian()
    {
    	$nian = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();

        return $nian;
    }

    public function yue()
    {
    	$yue = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y年-%m月") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();

        return $yue;
    }

}