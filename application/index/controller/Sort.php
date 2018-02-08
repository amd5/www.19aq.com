<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\Record;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Sort extends Controller
{
    public function index()
    {
        $wenz = new Article;
        $request = Request::instance();

        $sortid = ArticleSort::where('alias','=',$request->param('name'))->find();
        $sid = $sortid['sid'];

        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
        
        if(!session('username'))
        {
            //文章列表  不是管理员显示没有密码的文章
            $result = $wenz->Articlelist($sid);
            $page = $result->render();   //获取分页显示
        }elseif(session('username') == "1")
        {
            //管理员ID不是1
            $result = $wenz->Articlelist($sid);
            $page = $result->render();
        }else
        {
            //文章列表  管理员显示全部文章
            $result = $wenz->Articlea($sid);
            $page = $result->render();
        }

        // $sort = 

        //文章标签
        $taglist = new ArticleTag;
        $tag     = $taglist->taglist();

        //分类列表
        $sort = new ArticleSort;
        $articlesort = $sort->sortlist();
        
        //存档列表
        $record = new Record;
        $nian = $record->nian();
        $yue  = $record->yue();

        //友情链接
        $link = new link;
        $links = $link->links();

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