<?php
namespace app\api\controller;

use think\Controller;
use app\api\model\User;
use app\api\model\Api_bitcoin_addr;
/*其他第三方模块*/
use app\extra\bitcoin\bitcoin;
    const RPC_HOST = '127.0.0.1';
    const RPC_PORT = '3233';
    const RPC_USER = 'user';
    const RPC_PASS = 'P2kAlMiG3Kb8FzP';
class Bit extends Controller
{
    //查看节点信息
    public function index()
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getpeerinfo();
        dump($info);
    }
    //取得客户端信息
    public function getinfo()
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getinfo();
        dump($info);
    }
    //帮助
    public function help()
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->help();
        dump($info);
    }
    //创建新的钱包地址,并存入数据库
    public function getnewaddress($name)
    {
        //如果数据库有地址则不创建，没有则创建
        $result = Api_bitcoin_addr::where('name',$name)
        ->find();
        if(!$result){
            $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
            $info = $rpc->getnewaddress($name);
            dump($info);
            $data['name']       = $name;
            $data['address']    = $info;
            $data['time']       = time();
            $result = Api_bitcoin_addr::insert($data);
        }else{
            echo "repeat"; //提示 数据库已存在
        }
    }
    //查询钱包地址是否有效
    public function validateaddress($addr)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->validateaddress($addr);
        // dump($info);
        dump($info[isvalid]);
    }
    //查询交易
    public function gettransaction($txid)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->gettransaction($txid);
        dump($info);
    }
    //转账                 
    public function move($fromname,$toname,$amount)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->move($fromname,$toname,(float)$amount);
        dump($info);
    }
    //从指定地址发送币到指定地址  
    public function sendfrom($name,$addr,$amount)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        // echo $name,$addr,"====".$amount."</br>";
        $info = $rpc->sendfrom($name,$addr,(float)$amount);
        dump($info);
        // sendfrom 13800138000 HJJSGngVajcPCzqx91jF5obKey1sqGjVih 0.01
    }
    //发送币到地址               
    public function sendtoaddress($addr,$amount)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->sendtoaddress($addr,(float)$amount);
        dump($info);
    }
    //查询余额
    public function getbalance()
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getbalance();
        dump($info);
    }
    //查询区块信息
    public function getblock($hash)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getblock($hash);
        dump($info);
    }
    //用户名查地址   
    public function getaccountaddress($name)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getaccountaddress($name);
        dump($info);
    }
    //地址查用户名
    public function getaccount($addr)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $info = $rpc->getaccount($addr);
        dump($info);
    }
    //取得原始交易信息
    public function getrawtransaction($txid)
    {
        $rpc  = new Bitcoin( RPC_USER, RPC_PASS, RPC_HOST, RPC_PORT );
        $hex  = $rpc->getrawtransaction($txid);
        $info = $rpc->decoderawtransaction($hex);
        dump($info);
    }



}