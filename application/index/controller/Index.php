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
use think\Exception;

class Index extends Controller
{
	//初始化
	public function initialize(){
	}

    public function index()
    {
        $article    = Ar::lists(['page' => $this->request->param('page', 1, 'intval'), 'path' => '/list-[PAGE].html']);
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
        if ($this->request->param('id')){
            $id = $this->request->param('id');
        }

        if ($this->request->isPost() && $this->request->post('type') == 'password') {
            $result = Ar::details($id);
            if (!$result) {
                $this->error('请重新访问！');
            }
            if ($this->request->post('article_password', '', 'trim') === $result['password']) {
                session('article_password_'.$id, true);
                $this->redirect('/article-'.$id.'.html');
                return;
            }
            $links = Li::links();
            $this->assign('id', $id);
            $this->assign('links', $links);
            $this->assign('result', $result);
            $this->assign('error', '文章密码错误');
            return $this->fetch('article_password');
        }

        if ($this->request->isPost()) {
            # 调用留言请求处理方法
            $params = $this->request->param();
            if (!isset($params['captcha']) || !captcha_check($params['captcha'])) {
                return api_return('验证码错误！', '3');
            }
            unset($params['captcha']);
            $res = Co::coadd($params);
            return $res;
            // dump($this->request->param());die;
        }

        $result = Ar::details($id);
        if (!$result) {
            $this->error('请重新访问！');
        }
        if (!$this->canReadArticle($result)) {
            $links = Li::links();
            $this->assign('id', $id);
            $this->assign('links', $links);
            $this->assign('result', $result);
            $this->assign('error', '');
            return $this->fetch('article_password');
        }
        $comment = Co::list($id);

        $links      = Li::links();
        $this->assign('id', $id);
        $this->assign('links', $links);
        $this->assign('result', $result);
        $this->assign('comment', $comment);
        // dump($result);die;
        return $this->fetch();
    }

    public function articledata($id = 1){
        $result = Ar::details($id);
        if ($result && $this->canReadArticle($result)) {
            return $result;
        }else{
            $this->error('请重新访问！');    //走404
        }

    }

    public function applyLink()
    {
        if (!$this->request->isPost()) {
            return api_return('Error', '0');
        }

        $sitename = $this->request->post('sitename', '', 'trim');
        $siteurl = $this->request->post('siteurl', '', 'trim');
        $contact = $this->request->post('contact', '', 'trim');
        $captcha = $this->request->post('captcha', '', 'trim');

        if (empty($sitename) || empty($siteurl) || empty($contact) || empty($captcha)) {
            return api_return('请完整填写站点名称、站点url、联系方式和验证码', '0');
        }

        if (!captcha_check($captcha)) {
            return api_return('验证码错误！', '3');
        }

        if (!filter_var($siteurl, FILTER_VALIDATE_URL)) {
            return api_return('站点url格式不正确', '0');
        }

        Db::startTrans();
        try {
            Li::create([
                'sitename' => $sitename,
                'siteurl' => $siteurl,
                'Contact' => $contact,
                'addtime' => time(),
                'status' => Li::STATUS_PENDING,
            ]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            return api_return('提交失败', '0');
        }

        return api_return('提交成功，请等待审核', '1');
    }

    private function canReadArticle($article)
    {
        if (session('id')) {
            return true;
        }
        if (empty($article['password'])) {
            return true;
        }
        return session('article_password_'.$article['id']) === true;
    }

    public function rss($id = ''){
        $id = $this->request->param('id', $id, 'trim');
        $sort = So::where('sortname', $id)->find();
        if (!$sort) {
            abort(404, '请重新访问！');
        }

        $article = Ar::where('sort', $sort['sid'])->where('password','')->order('id desc')->limit(20)->select();
        $host = $this->request->domain();
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<rss version=\"2.0\">\n<channel>\n";
        $xml .= '<title>'.htmlspecialchars($id.' - c32\'s blog', ENT_XML1, 'UTF-8')."</title>\n";
        $xml .= '<link>'.$host.'/sort-'.rawurlencode($id).".html</link>\n";
        $xml .= '<description>'.htmlspecialchars($id.' 分类文章', ENT_XML1, 'UTF-8')."</description>\n";
        foreach ($article as $value) {
            $link = $host.'/article-'.$value['id'].'.html';
            $xml .= "<item>\n";
            $xml .= '<title>'.htmlspecialchars($value['title'], ENT_XML1, 'UTF-8')."</title>\n";
            $xml .= '<link>'.$link."</link>\n";
            $xml .= '<guid>'.$link."</guid>\n";
            $xml .= '<pubDate>'.date(DATE_RSS, $value['addtime'])."</pubDate>\n";
            $xml .= '<description>'.htmlspecialchars(mb_substr(strip_tags($value['content']), 0, 200), ENT_XML1, 'UTF-8')."</description>\n";
            $xml .= "</item>\n";
        }
        $xml .= "</channel>\n</rss>";

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=utf-8']);
    }

    public function dataList(){

        $type = $this->request->param('type');
        $id   = $this->request->param('id');
        $pageNo = $this->request->param('page', 1, 'intval');

        if ($type == 'tag') {
            $article    = Ar::taglists($id, ['page' => $pageNo, 'path' => '/tag-'.rawurlencode($id).'-[PAGE].html']);
        }
        if ($type == 'sort') {
            $article    = Ar::sortlists($id, ['page' => $pageNo, 'path' => '/sort-'.rawurlencode($id).'-[PAGE].html']);
        }
        if ($type == 'record') {
            $article    = Ar::recordlists($id);
        }
        if ($type == 'search') {
            if (empty($id)) {$id   = $this->request->param('key','','addslashes,htmlspecialchars,quotemeta');}
            $article    = Ar::searchlist($id, ['page' => $pageNo, 'path' => '/search-'.rawurlencode($id).'-[PAGE].html']);
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
}
