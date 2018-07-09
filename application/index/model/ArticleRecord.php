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
use think\Cache;
use app\index\model\Article;

class ArticleRecord extends Model
{
    protected $wenz;
    public function __construct()
    {
        $this->wenz     = new Article;
        parent::__construct();
    }

	public function nian()
    {   $result = Cache::get('nian');
        if($result == false){
        	$result = Article::order('days','desc')
            ->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
            ->GROUP('days')
            ->select();
            Cache::set('nian',$result,14000);
            $result = Cache::get('nian');
        }
        return $result;
    }

    public function yue()
    {   $result = Cache::get('yue');
        if($result == false){
        	$result = Article::order('days','desc')
            ->field('FROM_UNIXTIME(date,"%Y-%m") as days,COUNT(*) as COUNT')
            ->GROUP('days')
            ->select();
            // return $yue;
            Cache::set('yue',$result,14000);
            $result = Cache::get('yue');
        }
        return $result;
    }

    //归档页面--文章列表 (带密码除外)
    public function Articlelist($stsj,$endsj){
        $result = Article::order('id','desc')
        ->where('password','=','')
        ->where('date','>=',$stsj)
        ->where('date','<=',$endsj)
        ->limit(15)
        ->paginate();
        $result = $this->wenz->tag($result);

        return $result;
    }




}