<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index($name = 'thinkphp')
    {
        $data = Db::name('think_data')->find();
        $this->assign('result', $data);
        return $this->fetch();	
    }
}