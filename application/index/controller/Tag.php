<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\Record;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Tag extends Controller
{
    public function index()
    {
        $request = Request::instance();
        $tagname = $request->param('name');
        // dump($request->param('name'));
        // echo "=======================";
        // $data = ArticleTag::where('tagname',$tagname)->select();
        $data = ArticleTag::where('tagname',$tagname)->find();
        $body = $data['gid'];
        //去掉最后一个字符
        $str  = substr($body,0,strlen($body)-1); 
        //去掉第一个字符
        $bo   = substr($str,1);
        //以逗号分割数据为数组
        // $doh  = explode(',',$bo);
        // dump($bo);
        
        //文章列表
        $wenz = new Article;
        $result = $wenz->tagArticle($bo);
        // $result = Article::all($bo,'',false);



        //文章标签
        $taglist = new ArticleTag;
        $tag     = $taglist->taglist();

        // 分类列表
        $sort = new ArticleSort;
        $articlesort = $sort->sortlist();
        
        //存档列表
        $record = new Record;
        $nian = $record->nian();
        $yue  = $record->yue();

        // 友情链接
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
    }

}