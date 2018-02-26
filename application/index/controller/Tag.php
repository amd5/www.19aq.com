<?php
namespace app\index\controller;

use think\Controller;
use think\Request;    //请求IP地址等
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;

class Tag extends Controller
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

        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
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
        // dump($data);die;
        
        //文章列表
        $result = $this->wenz->tagArticle($bo);
        $page = $result->render();

        // dump($tagname);die;


        //标签列表
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
        $this->assign('tagname', $tagname);
        $this->assign('page', $page);

        return $this->fetch();
    }

}