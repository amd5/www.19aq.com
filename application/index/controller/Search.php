<?php
namespace app\index\controller;

/*前台模块*/
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
/*其他第三方模块*/
use think\Request;    //请求IP地址等
use think\Controller;

class Search extends Controller
{
    protected $wenz;
    protected $tag;
    protected $sort;
    protected $record;
    protected $link;
	//构造方法  初始化实例化模型
    public function __construct()
    {
        $this->link     = new Link;
        $this->record   = new ArticleRecord;
        $this->wenz     = new Article;
        $this->tag      = new ArticleTag;
        $this->sort     = new ArticleSort;
        //调用父类构造方法
        parent::__construct();
    }

    public function index()
    {
    	$request = Request::instance();
    	// $a = $this->param['key'];
    	// dump($a);die;
        
        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];

    	// dump($request->param());
        // echo "</br>Search</br>";
        // dump($request->param('key'));
        $key = $request->param('key');
        // dump($key);die;
        $result = $this->wenz->search($key);
        $page = $result->render();

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
        $this->assign('key', $key);
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