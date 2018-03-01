<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Record extends Controller
{
    protected $wenz;
    protected $tag;
    protected $sort;
    protected $record;
    protected $link;

    public function __construct()
    {
        $this->link     = new Link;
        $this->record   = new ArticleRecord;
        $this->wenz     = new Article;
        $this->tag      = new ArticleTag;
        $this->sort     = new ArticleSort;
        parent::__construct();
    }

    public function index()
    {
        $request = Request::instance();
        $tagname = $request->param('name');
        // echo($tagname);
        $nian = substr($tagname, 0, 4);
        $yue  = substr($tagname, 4, 5);
        $sj   = $nian.'-'.$yue.'-'.'01'.' '.'00:00:00';
        //开始时间
        $stsj = strtotime($sj);
        //结束时间
        $mdays = date( 't', strtotime($sj) );
        $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', strtotime($sj));
        $endsj = strtotime($end_time);
        // echo $sj;
        // echo $stsj.'</br>';
        // echo $endsj.'</br>';

        if(!session('username') || session('username') !== "c32")
        {
            //文章列表  不是管理员显示没有密码的文章
            $result = $this->record->Articlelist($stsj,$endsj);
            $page = $result->render();   //获取分页显示
        }else
        {
            //文章列表  管理员显示全部文章
            $result = $this->record->ArticleALL($stsj,$endsj);
            $page = $result->render();
        }

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
        $this->assign('links', $links);
        $this->assign('nian', $nian);
        $this->assign('yue', $yue);
        $this->assign('articlesort', $articlesort);
        $this->assign('result', $result);
        $this->assign('page', $page);


        return $this->fetch();
    }

}