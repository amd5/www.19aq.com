<?php
namespace app\admin\controller;

use app\admin\model\Link;
use app\admin\model\Config as Co;
use app\admin\model\Log as Lo;
use think\Db;
use think\Exception;
use think\facade\Log;
use think\facade\Env;
use think\facade\Cache;

/**
 * Class Others
 * 其他
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Others extends Common
{
	/**
	 * 友链列表页
	 * @return [type] [description]
	 */
	public function friendLink()
	{
		return $this->fetch();
	}

    /**
     * [friendLinkData 友链数据]
     * @param  integer $page  [description]
     * @param  integer $limit [description]
     * @return [type]         [description]
     */
    public function friendLinkData($page=1, $limit=15){
        $res = Link::list($page,$limit);
        if ($res) {
            $count = isset($res[0]['id_count']) ? $res[0]['id_count'] : 0 ;
            return api_return('', '', $count, $res);
        }
    }

    /**
     * 删除友链
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delLink()
    {
        $lid = $this->request->post('lid', '', 'intval');

        if (!empty($lid)){
            $res = Link::destroy($lid);
            Link::where('id', $lid)->delete();
            if ($res){
                return api_return('', '1');
            }else{
                return api_return('Error', '0');
            }
        }
    }

    /**
     * 友链添加
     * @return [type] [description]
     */
    public function linkAdd(){
        $sitename = $this->request->post('sitename');
        $sitelink = $this->request->post('sitelink');
        $contact = $this->request->post('contact');
        if (!empty($sitename) || !empty($sitename)){
            Db::startTrans();
            try{
                $res = Link::create([
                    'sitename' => $sitename, 
                    'siteurl' => $sitelink, 
                    'Contact' => $contact,
                    'addtime' => time()
                ]);
                Db::commit();
            }catch (Exception $e) {
                Db::rollback();
                return api_return($e, '0');
            }
            return api_return('Success', '1');
        }
    }

    /**
     * 删除缓存
     */
    public static function delCache(){
        //----------------cache------------------
        $data['cache'] = Cache::clear();
        $data['ram_log'] = Log::clear(); //清空内存日志
        //----------------temp------------------
        $temp = Env::get('runtime_path'). 'temp/';
        $is_temp = is_dir($temp);
        if ($is_temp) {
            array_map('unlink', glob($temp . '/*.php'));
            $data['temp'] = rmdir($temp);

        //----------------log------------------
            $sj = date('Ym', time());
            $log = Env::get('runtime_path'). 'log/'.$sj;
            array_map('unlink', glob($log . '/*.log'));
            $data['log'] = rmdir($log);

            return api_return('Success', '1' ,'' , $data);
        }
        return api_return('Error', '0','' , '');
    }

    /**
     * 系统设置
     */
    public function setting(){
        return $this->fetch();
    }

    public function settingAdd(){
        if ($this->request->isPost()){
            Db::startTrans();
            try{
                $ls = $this->request->param();
                foreach ($ls as $key => $value) {
                    $res = Co::where('name',$key)->update(['content'=>$value]);
                }
                Db::commit();
            }catch (Exception $e) {
                Db::rollback();
                return api_return($e, '0');
            }
            return api_return('Success', '1');
        }else{
            return api_return('Error', '0');
        }

    }

    public function settingData(){
        $res = Co::list();
        $data = [];
        foreach ($res as $key => $value) {
            $data[$value['name']] = $value['content'];
        }
        return api_return('', '1','',$data);
    }

    /**
     * 系统日志
     */
    public function systemLog(){
        return $this->fetch();
    }

    public function systemData($page=1, $limit=15){
        $res = Lo::list($page,$limit);
        if ($res) {
            $count = isset($res[0]['id_count']) ? $res[0]['id_count'] : 0 ;
            return api_return('', '', $count, $res);
        }
    }

}