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

class ArticleRecord extends Model
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
        ->field('FROM_UNIXTIME(date,"%Y-%m") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();

        return $yue;
    }

    //归档页面--文章列表 (带密码除外)
    public function Articlelist($stsj,$endsj){
        $result = Article::order('id','desc')
        ->where('password','=','')
        ->where('date','>=',$stsj)
        ->where('date','<=',$endsj)
        ->limit(15)
        ->paginate();

        return $result;
    }
    //归档页面-显示全部文章
    public function ArticleALL($stsj,$endsj){
        $result = Article::order('id','desc')
        ->where('date','>=',$stsj)
        ->where('date','<=',$endsj)
        ->limit(15)
        ->paginate();

        return $result;
    }




}