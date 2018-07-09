<?php
# #########################################
# #Function:    网站首页
# #Blog:        http://www.19aq.com/
# #Datetime:    2018-2-9 11:30:59
# #Author:      c32
# #Email:       amd5@qq.com
# #感谢King east(1207877378),一指流沙(287100654)
# #########################################
namespace app\index\controller;

// use think\Cookie;
use think\Request;    //请求IP地址等
use think\Controller;
/*前台模块*/
use app\index\model\Link;
use app\index\model\ArticleRecord;
use app\index\model\Article;
use app\index\model\ArticleTag;
use app\index\model\ArticleSort;
/*后台模块*/
use app\admin\model\SystemConfig;
/*其他第三方模块*/
use app\api\controller\Sendsms;
use app\extra\rss\Rss;

/*后台继承*/
use app\admin\controller\BaseController;

class Index	extends Controller
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
    //没有加管理员权限检查
    public function index()
    {
        //获取当前访问URL
        $url = "http://".$_SERVER['HTTP_HOST'];
        $request = Request::instance();
        $config = SystemConfig::where('name',"Sendsms")->find();

        //如果配置开启才发送短信，否则不发送短信
        if($config->status=='1'){
            $sms = new Sendsms();
            echo $sms->mainsend();
        }else{
            //不发送短信
        }

        //文章列表
        $result = $this->wenz->Article_list();
        //获取分页显示
        $page = $result->render();   



        
        $tag_ls = $request->param('tag');
        if ($tag_ls == true) {
            $data = ArticleTag::where('tagname',$tag_ls)->select();
            foreach ($data as $key => $value) {
                $tid = $tid.",";
                $tid = $tid.$value['tid'];
            }
            $tid = substr($tid,1);

            $result = $this->wenz->tagArticle($tid);
            $page   = $result->render();
        }
        
        $sort_ls = $request->param('sort');
        if ($sort_ls == true) {
            $sortid = ArticleSort::where('alias','=',$sort_ls)->find();
            $sid = $sortid['sid'];
            $result = $this->wenz->SortArticlelist($sid);
            $page = $result->render();   //获取分页显示
        }
        // dump($request);die;
		$record_ls = $request->param('record');
        if ($record_ls == true) {
            $nian = substr($record_ls, 0, 4);  $yue  = substr($record_ls, 4, 5);
            $sj   = $nian.'-'.$yue.'-'.'01'.' '.'00:00:00';
            //开始时间
            $stsj = strtotime($sj);
            //结束时间
            $mdays = date( 't', strtotime($sj) );
            $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', strtotime($sj));
            $endsj = strtotime($end_time);

            //文章列表  不是管理员显示没有密码的文章
            $result = $this->record->Articlelist($stsj,$endsj);
            $page = $result->render();   //获取分页显示
        }

        $search_ls = $request->param('key');
        if ($search_ls == true) {
            $result = $this->wenz->search($search_ls);
            $page = $result->render();
        }
        // dump($request);die;

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
		// $this->assign('data', $data);
		$this->assign('links', $links);
		$this->assign('nian', $nian);
        $this->assign('yue', $yue);
		$this->assign('articlesort', $articlesort);
		$this->assign('result', $result);
		$this->assign('page', $page);


		return $this->fetch();
    }
	
	public function article($id)
    {
        //文章详情标题和内容
        $result = $this->wenz->article($id);

        //文章详情分类显示
        // $sort   = $this->sort->sortname($result['sortid']);
        $sort = ArticleSort::where('sid',$result['sortid'])->find();

        //文章详情标签显示
        // $tag =$this->tag->articletag($id);
        $tag = ArticleTag::where('tid',$id)->select();

        $this->assign('tag', $tag);
        $this->assign('sort', $sort);
		$this->assign('result', $result);
		return $this->fetch();
        // return \think\Response::create(\think\Url::build('/admin'), 'redirect');
    }


	
}
