<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\Dataa;

class C extends Controller
{
    public function index()
    {
		$test = Dataa::with('sort')->where('phone','15966982315')->find();
        $this->assign('test', $test);
        return $this->fetch();

    }



}