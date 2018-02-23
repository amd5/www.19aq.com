<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\User;
/*其他第三方模块*/
use app\extra\bitcoin\bitcoin;
    const RPC_HOST = '127.0.0.1';
    const RPC_PORT = '3233';
    const RPC_USER = 'user';
    const RPC_PASS = 'P2kAlMiG3Kb8FzP';
class Demo extends Controller
{
    public function index()
    {
        echo "6";

   //      $result = Article::with('sort')
   //          ->order('id','desc')
			// ->limit(15)
			// ->paginate();
			// $page = $result->render();
    }



}