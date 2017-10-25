<?php
namespace app\admin\controller;
use app\admin\model\Manageuser;
use think\Controller;
use think\Exception;
use think\Session;
use think\Db;

class Login extends Controller
{
	
	public function checkLogin()	//登陆检测
	{
		$captcha = $_POST['captcha'];
		if($this->check_verify($captcha)==false){
            $data['error']=1;
            $data['msg']="验证码错误";
            $this->ajaxReturn($data);
        }
		// echo($captcha);
	}
	
	
	//视图显示
    public function Login($username='',$password='')
	{
		$result = Manageuser::get([
		'username'=>$username,
		'password'=>$password
		]);
		$hash = password_hash($password, PASSWORD_BCRYPT);
		
		// $PHPASS = new password_hash(8, true);
		// $password = $PHPASS->password_hash($password);
		// $userData['password'] = $password;
		
		if (password_verify($password,'$2y$10$rH5nwLWoGo/mSWwQZS0gH.9ic2UDaMTgmPe.ac3BP5BownWAkGdp6')) 
		{ 
			echo "密码正确";
			echo '</br>';
			echo $hash;
		} else {  
			return $this->error('登录失败');
		}

	
		
    }
	
	// boolean password_verify (string $password , string $hash)
	// 
	// echo $hash;
	
	
	
	
	/**
     * 登录验证
     */
    // public function Check_Login(){
        ////验证码检测
       // $names=$_POST['Captcha'];
        // if($this->check_verify($names)==false){
            // $data['error']=1;
            // $data['msg']="验证码错误";
            // $this->ajaxReturn($data);
        // }
        ////用户检测
        // $uname=I('post.username');
        // $upasswd=I('post.password');
        // $map['uname']=$uname;
        // $map['state']=1;
        // $logins=M('login')->where($map)->find();
        // if($logins)
        // {
            // if($logins['upasswd']!=$upasswd)
            // {
                // $data['error']=1;
                // $data['msg']="密码错误";
                // $this->ajaxReturn($data);
            // }
            // session("admin",$logins);
 
            // var_dump($logins);
           // redirect(U('Index/index'));
        // }
    // }
 
    /**
     * 验证码生成
     */
    public function Verifys()
    {
        $config=array(
            'fontSzie'	=>30,	//验证码字体大小
            'length'	=>3,	//验证码位数
            'useImgBg'	=>true, //验证码背景
			'useNoise'  =>false //验证码杂点
 
        );
 
        $captcha=new Captcha();
        $captcha->useZh=true;
 
        $captcha->zhSet="梦起软件工作室";
		$captcha->fontttf = '5.ttf'; 
        $captcha->entry();
 
    }
 
    /**
     * 验证码检测
     */
    public function check_verify($code,$id="")
    {
		$captcha = new Captcha();
		return $captcha->check($code, $id);
    }
    /**
     * 退出登录
     */
    // public function out_login(){
        // session("admin",null);
        // redirect(U('Login/login'));
    // }
}