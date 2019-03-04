<?php
namespace app\admin\controller;

use app\admin\model\Article as Ar;
use app\admin\model\Sort;
use app\admin\model\Tag;
use app\admin\model\Taga;
use app\admin\controller\Others as Ot;
use think\Db;
use think\Exception;
use think\facade\Cache;

/**
 * Class Article
 * 文章
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Article extends Common
{

    /**
     * 删除文章
     * @return string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public  function delArticle()
    {
        $aid = $this->request->post('aid', '', 'intval');

        if (!empty($aid)){
            $res = Ar::destroy($aid);
            Taga::where('gid', $aid)->delete();
            if ($res){
                return api_return('', '1');
            }else{
                return api_return('Error', '0');
            }
        }
    }
    /**
     * 添加文章
     * @return mixed|string
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function articleAdd(){
        if ($this->request->isPost()){
            $aid                = $this->request->post('id', '', 'trim');
            $data['title']      = $this->request->post('title', '', 'trim');
            $data['content']    = $this->request->post('content', '', 'trim');
            $data['addtime']    = $this->request->post('addtime', time(), 'trim');  //如果有则使用旧的，否则取时时间戳
            $data['sort']       = $this->request->post('sort', '99', 'intval');     //如果有则使用旧的，没有则接收前台传值。
            $data['view']       = $this->request->post('view', '1', 'intval');     
            $data['password']   = $this->request->post('password', '', 'trim');

            Db::startTrans();
            try{
                if (empty($aid)) {
                    $result = Ar::create($data);
                    $aid = $result->id;
                    $update = false;
                }else{
                    Ar::where('id',$aid)->update($data);
                    $update = true;
                }
                
                $tags = $this->request->post('tags', '', 'trim');

                $add_tags_id = [];
                //标签数据处理
                if (!empty($tags)) {
                    $tags_arr = explode(',', $tags);
                    foreach ($tags_arr as &$v) {
                        if (empty($v)){
                            continue;
                        }
                        $res = Tag::create(['tagname' => strtolower($v), 'addtime'=>time()]);
                        array_push($add_tags_id, $res->id);
                    }
                }

                // 标签整合
                if ($tag_check = $this->request->post('tag')){
                    $tag = array_merge($tag_check, $add_tags_id);
                }elseif ($add_tags_id) {
                    $tag = $add_tags_id;
                }else{
                    $tag = [];
                }
                // 更新文章的标签处理
                if ($update) {
                    $old_tags = $this->request->post('oldtags', '', 'trim');
                    if (!empty($old_tags)) {
                        $old_tags = explode(',', $old_tags);
                        $tmp = array_diff($tag, $old_tags);
                        $del = array_diff($old_tags, $tag);
                        if ($del) {
                            Taga::where('tid', "in", $del)->where('gid', $aid)->delete();
                        }
                    }else{
                        $tmp = $tag;
                    }
                }

                // 存入标签
                if (isset($tmp) && !empty($tmp)) {
                    foreach ($tmp as $v) {
                        $arr[] = ['gid'=>$aid, 'tid'=>$v];
                    }
                    $taga = new Taga;
                    $taga->saveAll($arr);
                }
                
                # 清空缓存
                Ot::delCache();
                # 提交事务
                Db::commit();

            }catch (Exception $e) {
                echo 1;die;
                Db::rollback();
                return api_return($e, '0');
            }
            return api_return('Success', '1');

        }else{
            $sort_list = Sort::field('sid, sortname')->cache('articleAddSortList',2592000)->select();
            $tag_list = Tag::field('tid, tagname')->cache('articleAddTagList',2592000)->select();
            $this->assign('tag', $tag_list);
            $this->assign('sort', $sort_list);
            return $this->fetch();
        }
    }

    /**
     * 文章列表数据
     * @param int $page
     * @param int $limit
     * @return string
     */
    public function articleListData($page=1, $limit=15, $title=false){
        if ($title) {
            $res = Ar::list($page,$limit,$title);
        }else{
            $res = Ar::list($page,$limit);
        }
		if ($res) {
			$count = isset($res[0]['id_count']) ? $res[0]['id_count'] : 0 ;
			return api_return('', '', $count, $res);
		}
	}

    /**
     * 文章列表页
     * @return mixed
     */
    public function articleList(){
        return $this->fetch();
    }

    /**
     * 编辑文章
     * @return mixed
     */
    public function articleEdit(){
        if ($this->request->param('id')){
            $id = $this->request->param('id');
            $Tags = Taga::alias('ta')
                ->where('ta.gid', $id)
                ->join('tag t', 't.tid = ta.tid', 'left')
                ->field('t.tid')
                ->cache('articleEdit'.$id,2592000)
                ->select()
                ->toArray();

            $Tags = implode(',', array_column($Tags, 'tid'));

            $result = Ar::details($id);
            $this->assign('result', $result);
            $this->assign('tags', $Tags);
        }

        if ($this->request->isPost()){
            // dump($this->request->param());die;
            //如果是编辑提交文章，则插入数据库  然后返回  return api_return
            return $this->articleAdd();
        }
        $sort_list = Sort::field('sid, sortname')->cache('articleEditSortList',2592000)->select();
        $tag_list = Tag::field('tid, tagname')->cache('articleEditTagList',2592000)->select();
        $this->assign('tag', $tag_list);
        $this->assign('sort', $sort_list);
        return $this->fetch();
    }

}