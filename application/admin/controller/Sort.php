<?php
namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\Sort as Sr;
use think\Db;
use think\Exception;

/**
 * Class Sort
 * 分类
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Sort extends Common
{

    /**
     * 删除分类
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delSort()
    {
        $sid = $this->request->post('sid', '', 'intval');

        if (!empty($sid)){
            $res = Sr::where('sid', $sid)->delete();
            Article::where('sort', $sid)->update(['sort'=> '']);
            if ($res){
                return api_return('', '1');
            }else{
                return api_return('Error', '0');
            }
        }

    }

    /**
     * 分类列表页
     * @return mixed
     */
    public function sortList(){
        return $this->fetch();
    }

    /**
     * 分类列表数据
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function sortListData($page=1, $limit=15){
        $res = Sr::list($page,$limit);
        if ($res) {
            $count = isset($res[0]['id_count']) ? $res[0]['id_count'] : 0 ;
            return api_return('', '', $count, $res);
        }
    }

    public function sortAdd(){
        if ($this->request->isPost()){
            $sid = $this->request->post('sortname', '', 'trim');
            if (!empty($sid)){
                Db::startTrans();
                try{
                    $res = Sr::create(['sortname' => $sid]);
                    Db::commit();
                }catch (Exception $e) {
                    Db::rollback();
                    return api_return($e, '0');
                }
                return api_return('Success', '1');
            }
        }else{
            return api_return('Error', '0');
        }
        
    }
}