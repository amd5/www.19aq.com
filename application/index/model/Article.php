<?php
namespace app\index\model;

use think\Model;
use think\Cache;
use app\index\model\Link;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
use app\index\model\ArticleRecord;

class Article extends Model
{
	// 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 定义自动完成的属性
    protected $insert = ['status' => 1];

	//分类页面--文章列表  不是管理员显示没有密码的文章
	public function SortArticlelist($sid){
		return $this->tag(self::order('id','desc')->where('password','=','')->where('sortid','=',$sid)->limit(15)->cache(true,8640000)->paginate());
	}

	//首页-显示全部文章（带密码的除外）
	public function Article_list(){
		return $this->tag(self::order('id','desc')->where('password','=','')->cache(true,8640000)->paginate(15));
	}

	//缓存-标签文章列表
	public function tagArticle($tags){
		return $this->tag(self::where('id','in',$tags)->limit(15)->cache(true,8640000)->paginate());
	}

	//文章
	public function article($id){
        //每次被访问增加阅读1
        $view = self::where('id',$id)->setInc('views');
        //开启缓存
			if(Cookie('username') !== "c32"){
				$data = self::where('id','=',$id)->where('password','')->cache(true,8640000)->find();
				if (empty($data)) {
					die('非管理员禁止访问！');
				}
				$result = $data;
			}else{
				$result = self::where('id','=',$id)->cache(true,8640000)->find();
			}
			//----------------------------------------------
			$tag_name = [];
			$tags = ArticleTag::where('tid',$id)->cache(true,8640000)->select();
			foreach ($tags as $key => $value) {
				array_push($tag_name, $vv['tagname']);
			}
			$result['tag_name'] = $tag_name;
			//----------------------------------------------
        return $result;
	}
	//首页-前台搜索功能
	public function search($key){
		return $this->tag(self::where('title','like','%'.$key.'%')->limit(15)->cache(true,8640000)->paginate());
	}

	//本类内部调用
	public Function tag($result){
		foreach ($result as $key => $value) {
			$tags = ArticleTag::where('tid',$value['id'])->cache(true,8640000)->select();
			if ($tags == true) {
				$tag_name = [];
				foreach ($tags as $kk => $vv) {
					array_push($tag_name, $vv['tagname']);
				}
				$value['tag_name'] = $tag_name;
			}else{
				$value['tag_name'] ='';
			}
		}
		return $result;
	}

	//友情链接================================================================
	public function links(){
        return Link::order('id','asc')->where('hide','n')->cache(true,8640000)->select();
	}

	//tag================================================================
	public function taglist(){
        	$result = ArticleTag::field('distinct tagname')->cache(true,8640000)->select();
        	foreach ($result as $key => $value) {$value['tag_num'] = $this->tagsearch($value['tagname']);}
        return $result;
    }

    public function tagsearch($name){
    	return ArticleTag::where('tagname',$name)->cache(true,8640000)->count();
    }

    public function articletag($id){
        return ArticleTag::where('tid',$id)->cache(true,8640000)->select();
    }

    //sort================================================================
    //缓存-分类列表
    public function Sortlist(){
        $result = ArticleSort::where('status','=','1')->cache(true,8640000)->select();
        foreach ($result as $key => $value) {$value['sort_count'] = self::where('sortid',$value['sid'])->cache(true,8640000)->count();}
        return $result;
    }

    //Record================================================================
    public function nian(){   
        return Article::order('days','desc')->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')->GROUP('days')->cache(true,8640000)->select();
    }

    public function yue(){   
        return Article::order('days','desc')->field('FROM_UNIXTIME(date,"%Y-%m") as days,COUNT(*) as COUNT')->GROUP('days')->cache(true,8640000)->select();
    }

    //归档页面--文章列表 (带密码除外)
    public function Articlelist($stsj,$endsj){
        return $this->wenz->tag(Article::order('id','desc')->where('password','=','')->where('date','>=',$stsj)->where('date','<=',$endsj)->limit(15)->cache(true,8640000)->paginate());
    }



}