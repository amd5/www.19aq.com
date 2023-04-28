<?php
namespace app\index\controller;

use think\Controller;
// use parser\Parser;
use app\admin\model\Article as Ar;
use app\admin\model\Tag as Ta;
use app\admin\model\Sort as So;
use app\admin\model\Link as Li;
use app\admin\model\Comment as Co;
use think\Db;

class Index extends Controller
{
	//初始化
	public function initialize(){
	}

    public function index()
    {
        $article    = Ar::lists();
        $page       = $article->render();
        $tagList    = Ta::qiantai();
        $sortList   = So::qiantai();
        $nian       = Ar::nian();
        $yue        = Ar::yue();
        $links      = Li::links();
        // dump($article);die;
        $this->assign('article', $article);
        $this->assign('page', $page);
        $this->assign('tagList', $tagList);
        $this->assign('sortList', $sortList);
        $this->assign('nian', $nian);
        $this->assign('yue', $yue);
        $this->assign('links', $links);
        return $this->fetch();
    }

    public function article($id = 1)
    {
        if ($this->request->isPost()) {
            # 调用留言请求处理方法
            $id = $this->request->param();
            $res = Co::coadd($id);
            return $res;
            // dump($this->request->param());die;
        }
        if ($this->request->param('id')){
            $id = $this->request->param('id');
            $this->assign('id', $id);
        }
        $result = self::articledata($id);
        $comment = Co::list($id);

        $links      = Li::links();
        $this->assign('links', $links);
        $this->assign('result', $result);
        $this->assign('comment', $comment);
        // dump($result);die;
        return $this->fetch();
    }

    public function articledata($id = 1){
        $result = Ar::details($id);
        if ($result) {
            return $result;
        }else{
            $this->error('请重新访问！');    //走404
        }
        
    }

    public function dataList(){
        
        $type = $this->request->param('type');
        $id   = $this->request->param('id');

        if ($type == 'tag') {
            $article    = Ar::taglists($id);
        }
        if ($type == 'sort') {
            $article    = Ar::sortlists($id);
        }
        if ($type == 'record') {
            $article    = Ar::recordlists($id);
        }
        if ($type == 'search') {
            if (empty($id)) {$id   = $this->request->param('key','','addslashes,htmlspecialchars,quotemeta');}
            $article    = Ar::searchlist($id);
        }
        if ($article) {
            $page   = $article->render();
        }else{
            $page   = '';
        }

        $tagList    = Ta::qiantai();
        $sortList   = So::qiantai();
        $nian       = Ar::nian();
        $yue        = Ar::yue();
        $links      = Li::links();
        // dump($id);die;
        $this->assign('article', $article);
        $this->assign('page', $page);
        $this->assign('tagList', $tagList);
        $this->assign('sortList', $sortList);
        $this->assign('nian', $nian);
        $this->assign('yue', $yue);
        $this->assign('links', $links);
        $title = "$id  - ";
        $this->assign('title', $title);
        return $this->fetch('index');
    }

    // public function aa(){
    //     //迁移博客使用
    //     set_time_limit(0);
    //     $res = Db::table('think_aaaaa')->select();
    //     $rrrr = Db::table('think_article')->select();

    //     foreach ($res as $key => $value) {
    //         // dump($value['views']);die;
    //         foreach ($rrrr as $kk => $vv) {
    //             // dump($vv['view']);die;
    //             if ($value['id'] == $vv['id']) {
    //                 # code...
    //                 Db::table('think_article')->where('id', $vv['id'])->update(['view' => $value['views']]);
    //             }
    //         }
    //     }

    //     echo "ok";
    //     die;
    // }


}
