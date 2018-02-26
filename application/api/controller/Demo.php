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
    private $wenz = '1';
    // protected $wenz = 1;

    public function index()
    {
        // echo "8";
        dump(RPC_HOST);die;


    }

    public function array(){
    	echo "string";
    }


}