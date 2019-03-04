<?php

namespace app\index\controller;
use think\Controller;

class A extends Controller
{
    public function index()
    {
    	$key	= "ghawvtiq";
    	$data	= "B4F733BBA2CD88AD";  //加密后的用户密文

		$check  =new a();
		echo "密码为: " . $check->test($data,$key);
    }

    public function test($str,$key)
    {
    	$str = hex2bin($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }
  
  	public function ttt(){
      	return $this->fetch();
    }

}
