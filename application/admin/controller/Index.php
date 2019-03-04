<?php
namespace app\admin\controller;

/**
 * Class Index
 * 后台首页
 * @author Pendant 861618191@qq.com
 * @package app\admin\controller
 */
class Index extends Common
{
    /**
     * 退出
     */
    public function loginOut()
    {
        session(null);
        $this->redirect('login/index');
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $last_ip = session('last_ip');
        $last_time = session('last_time');

        $this->assign('last_ip', $last_ip);
        $this->assign('last_time', $last_time);

        return $this->fetch();
    }
}
