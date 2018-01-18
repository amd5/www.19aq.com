<?php
namespace app\api\controller;

use think\Controller;
use app\index\model\User;
/*其他第三方模块*/
use app\extra\bitcoin\bitcoin;
    // const RPC_HOST = '127.0.0.1';
    // const RPC_PORT = '18115';
    // const RPC_USER = 'user';
    // const RPC_PASS = 'P2kAlMiG3Kb8FzP';
class Index extends Controller
{
    
    public function index()
    {

        dump(21);
        die;
        $RPC_HOST = '127.0.0.1';
        $RPC_PORT = '18115';
        $RPC_USER = 'user';
        $RPC_PASS = 'P2kAlMiG3Kb8FzP';
		echo "string";
        $rpc = new Bitcoin( $RPC_USER, $RPC_PASS, $RPC_HOST, $RPC_PORT );
        $info = $rpc->getinfo();
        // dump($rpc);
        dump($info);


    }


}