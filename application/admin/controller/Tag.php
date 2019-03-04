<?php
namespace app\admin\controller;

use app\admin\model\Tag as Ta;
use app\admin\model\Taga;
use think\Db;
use think\Exception;

/**
 * Class Tag
 * 标签
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Tag extends Common
{

    /**
     * 删除标签
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public  function delTag()
    {
        $tid = $this->request->post('tid', '', 'intval');

        if (!empty($tid)){
            $res = Ta::where('tid', $tid)->delete();
            Taga::where('tid', $tid)->delete();
            if ($res){
                return api_return('', '1');
            }else{
                return api_return('Error', '0');
            }
        }
    }

    /**
     * 标签列表页
     * @return mixed
     */
    public function tagList(){
        return $this->fetch();
    }

    /**
     * 标签列表数据
     * @param int $page
     * @param int $limit
     * @return json
     */
    public function tagListData($page=1, $limit=15){
        $res = Ta::list($page,$limit);
        if ($res) {
            $count = isset($res[0]['id_count']) ? $res[0]['id_count'] : 0 ;
            return api_return('', '', $count, $res);
        }
    }

    public function tagAdd(){
        if ($this->request->isPost()){
            $tid = $this->request->post('tagname', '', 'trim');
            if (!empty($tid)){
                Db::startTrans();
                try{
                    $res = Ta::create(['tagname' => $tid]);
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