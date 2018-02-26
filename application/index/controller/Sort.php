<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Sort extends Controller
{
    protected $wenz;
    protected $tag;
    protected $sort;
    protected $record;
    protected $link;

    public function __construct()
    {
        $this->wenz = new Article;
        $this->tag = new ArticleTag;
        $this->sort = new ArticleSort;
        $this->record = new ArticleRecord;
        $this->link = new Link;
        parent::__construct();
    }

    public function index()
    {
        $request = Request::instance();
        $sortname    = $request->param('name');
        $sortid = ArticleSort::where('alias','=',$sortname)->find();
        $sid = $sortid['sid'];

        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
        
        if(!session('username') || session('username') !== "c32")
        {
            //文章列表  不是管理员显示没有密码的文章
            $result = $this->wenz->Articlelist($sid);
            $page = $result->render();   //获取分页显示
        }else
        {
            //文章列表  管理员显示全部文章
            $result = $this->wenz->Articlea($sid);
            $page = $result->render();
        }

        // $sort = 

        //文章标签
        $tag =$this->tag->taglist();

        //分类列表
        $articlesort = $this->sort->sortlist();
        
        //存档列表
        $nian = $this->record->nian();
        $yue = $this->record->yue();

        //友情链接
        $links = $this->link->links();

        //输出
        $this->assign('url', $url);
        $this->assign('tag', $tag);
        $this->assign('sortname', $sortname);
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