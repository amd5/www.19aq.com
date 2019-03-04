<?php
namespace app\admin\controller;

use app\admin\model\AuthRule;
use think\facade\Config;
use think\Controller;
use think\facade\Log;
use think\Db;

/**
 * Class Common
 * 后台控制器基类
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Common extends Controller
{
    /**
     * 权限验证
     */
    public function initialize()
    {
        parent::initialize();
        $this->checkLogin();   // 登陆校验
        $this->actionLog();
    }


    /**
     * 记录日志 - 数据库
     * @param $data
     * @param string $funcName
     */
    public function actionLog()
    {
        $action = request()->module() . '/' . request()->controller() . '/' . request()->action();
        $ip = request()->ip();
        $time = time();
        Db::name('log')->insert(['action'=>$action, 'ip'=>$ip, 'time'=>$time]);
    }


    /**
     * 记录日志 - 文件
     * @param $data
     * @param string $funcName
     */
    public function wLog($data, $funcName = 'Default')
    {
        $str_s = $funcName.'-'.date('Ymd H:i:s', time())."\r\n";
        $str_e = "\r\n[ log ] ".$funcName.'-'.date('Ymd H:i:s', time());
        if (is_array($data)) {
            Log::write($str_s.var_export($data, true).$str_e);
        } else {
            Log::write($str_s.$data.$str_e);
        }
    }

    /**
     *登陆验证
     */
    public function checkLogin()
    {
        if (!session('id')) {
            $this->redirect('/admin/login/index.html');
            return ;
        }
        $config = Config::get('');
        if ((time() - session('start_time')) > $config['session']['expire']) {
            session(null);
            $this->redirect('/admin/login/index.html');
            return ;
        }
        //如果通过登陆则重新设置当前时间为登陆时间
        session('start_time', time());
    }
}
