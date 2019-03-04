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
    //缓存
    public function nian(){
        $cache = Cache::get('nian');
        if($cache == false){
            $nian   = $this->niana();
            Cache::set('nian',$nian,14000);
            $cache = Cache::get('nian');
        }
        return $cache;
    }

	public function niana()
    {
    	$nian = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();
        return $nian;
    }
    public function yue(){
        $cache = Cache::get('yue');
        if($cache == false){
            $yue   = $this->yuea();
            Cache::set('yue',$yue,14000);
            $cache = Cache::get('yue');
        }
        return $cache;
    }
    public function yuea()
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

        $datas = ArticleTag::select();
        foreach ($result as $a => $value) {
            $ls = $this->wenz->qutag($datas,$value['id']);
            $result[$a]['tag_name'] = $ls;
        }
        return $result;
    }
    //归档页面-显示全部文章
    public function ArticleALL($stsj,$endsj){
        $result = Article::order('id','desc')
        ->where('date','>=',$stsj)
        ->where('date','<=',$endsj)
        ->limit(15)
        ->paginate();

        $datas = ArticleTag::select();
        foreach ($result as $a => $value) {
            $ls = $this->wenz->qutag($datas,$value['id']);
            $result[$a]['tag_name'] = $ls;
        }
        return $result;
    }




}