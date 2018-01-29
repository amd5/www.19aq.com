<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Sort extends Controller
{
    public function index()
    {
        $request = Request::instance();

        // dump($request->param('name'));
        // dump($_GET["name"]);
        $sortid = ArticleSort::where('alias','=',$request->param('name'))->find();
        // dump($sortid['sid']);
        // die;
        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];

        if(!session('username'))
        {
            //文章列表  不是管理员显示没有密码的文章
            $result = Article::with('sort')
            ->order('id','desc')
            ->where('password','=','')
            ->where('sortid','=',$sortid['sid'])
            ->limit(15)
            ->paginate();
            $page = $result->render();   //获取分页显示
        }elseif(session('username') == "1")
        {
            //管理员ID不是1
            $result = Article::with('sort')
            ->order('id','desc')
            ->where('password','=','')
            ->where('sortid','=',$sortid['sid'])
            ->limit(15)
            ->paginate();
            $page = $result->render();
        }else
        {
            //文章列表  管理员显示全部文章
            $result = Article::with('sort')
            ->where('sortid','=',$sortid['sid'])
            ->order('id','desc')
            ->limit(15)
            ->paginate();
            $page = $result->render();
            // print_r($result);

            // $sort = $result->ArticleSort->sortname()
        }

        // $sort = 

        //文章标签
        $tag = ArticleTag::select();
        //dump($tag);

        //分类列表
        $articlesort = ArticleSort::withCount('sort')
        ->where('status','=','1')
        ->select();
        
        //存档列表
        // SELECT FROM_UNIXTIME(date,'%Y-%m') days,COUNT(*) COUNT FROM think_article GROUP BY days; 
        //SELECT distinct YEAR(FROM_UNIXTIME(date)) as nianfen FROM think_article ORDER BY nianfen DESC
        $nian = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();

        $yue = Article::order('days','desc')
        ->field('FROM_UNIXTIME(date,"%Y年-%m月") as days,COUNT(*) as COUNT')
        ->GROUP('days')
        ->select();


        

        //友情链接
        $links = Link::order('taxis','asc')
        ->select();

        //输出
        $this->assign('url', $url);
        $this->assign('tag', $tag);
        $this->assign('links', $links);
        $this->assign('nian', $nian);
        $this->assign('yue', $yue);
        $this->assign('articlesort', $articlesort);
        $this->assign('result', $result);
        $this->assign('page', $page);


        
        return $this->fetch();
        // return action('index/index');
    }

}