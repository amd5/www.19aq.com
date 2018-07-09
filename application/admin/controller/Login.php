<?php
namespace app\admin\controller;
use app\admin\model\ManageUser;
use app\admin\controller\Base;
use think\Controller;
// use think\Session;
use think\Cookie;
use think\Request;
use think\Db;
// use think\log;	//系统日志使用

class Login extends Controller
{
	public function index()
    {
    	// if(!Session::has('username')){
    	if(!Cookie::has('username')){
    		// dump(session('username'));
			echo "Cookie null";
			// die;
		}else{
			//增加判断如果登录页面已有登陆状态直接跳转到后台
			$this->redirect('./');
		}
        return $this->fetch();
    }
	 
	public function checkLogin($username='',$password='')	//登陆检测
	{
		// dump(input('post.'));
		$result = Manageuser::where('username', $username)->find();
		$passok = password_verify($password,$result["password"]);
		// dump($result);
		if($result != NULL){
			
			if($passok == true){
				//判断验证码是否正确
				// $verify_code = $_POST['captcha'];
				// if(!captcha_check($verify_code)){
				// 	$this->error('验证码错误');
				// }
				//判断账号是否被冻结
				if($result["status"]=="y"){
					$data["status"] = "true";
					//如果管理员ID=1则设置以下Session
					if ($result['id'] == 1) 
					{
					// Session::set('id',$result["id"]);
                    // Session::set('username',$result["username"]);
					// Session::set('logintime',time());	//设置session开始时间
					// Session::set('last_login_ip',$this->request->ip());
					// dump($result["username"]);
					Cookie::set('id',$result["id"],7200);  //有效期7200秒
					Cookie::set('username',$result["username"],7200);  //有效期7200秒
					Cookie::set('logintime',time(),7200);  //有效期7200秒
					Cookie::set('last_login_ip',$this->request->ip(),7200);  //有效期7200秒

					echo cookie('username');
					// die;

					// Session::delete('username',$result["username"]);
					// $this->success('Session设置成功');
					$data["message"] = "登录成功"; 
					// $this->success("登录成功",U('Index/index'));
					// 保存登录信息
					$username = $_POST['username'];
					$update['username'] = $username;
					$update['title'] = "管理员".$username."后台登录账户";
			        $update['last_login_time'] = time();
			        $update['last_login_ip'] = $this->request->ip();
			        $update['login_status'] = "1";
			        $time['last_login_time'] = time();
			        //保存登录信息
			        Db::name("ManageUser")->where('username','=',$username)->update($time);
			        //保存登录日志
			        Db::name("SystemLog")->insert($update);
			        //登录成功后跳转到后台首页
					$this->success('登录成功', './admin/index');
                	}

				}else{
					$data["status"] = "false";  
					$data["message"] = "账号被锁定，请联系管理员！";  
					$this->error('账号被锁定，请联系管理员！');
				}
				
			}else{
				$data["status"] = "false"; 
				$data["message"] = "密码错误"; 
				$update['username'] = $_POST['username'];
				$update['title'] = "密码" .$_POST['password']. "错误";
		        $update['last_login_time'] = time();
		        $update['last_login_ip'] = $this->request->ip();
		        $update['login_status'] = "2";
		        Db::name("SystemLog")->insert($update);
		        $this->error('密码错误');
			}

		}else{
			$data["status"] = "false";  
            $data["message"] = "账号不存在，请联系管理员";
            $update['username'] = $_POST['username'];
			$update['title'] = "密码" .$_POST['password']. "错误";
	        $update['last_login_time'] = time();
	        $update['last_login_ip'] = $this->request->ip();
	        $update['login_status'] = "3";
	        Db::name("SystemLog")->insert($update);
	        $this->error('账号不存在，请联系管理员');
		}
		// echo json_encode($data, JSON_UNESCAPED_UNICODE);  
		// return json_encode($data, JSON_UNESCAPED_UNICODE);  
		// return $this->success('成功', 'Index');
		return $this->redirect('Index');

		// die();
		// session('username',null); // 删除session
	}
	
	public function logout(){
		// 取值并删除Session
        // Session::pull('username');
        // 设置session为null
        // $del = Session::delete('username');
        // $del = Session::delete('id');
        // $nul = Session('username',null);
        // $nul = Session('id',null);
        $del = Cookie::delete('id');
        $del = Cookie::delete('username');

        if ($del = 'NULL') {
        	$this->redirect('../../');
        	// echo "1";
        }
        echo "退出错误！";
        // elseif ($nul) {
        // 	$this->redirect('../../');
        // }
        // echo "2";
        // die;
        // dump(session('username'));
        // session('username',null);
        // Session::delete('username');
        //跳转到后台首页
        // $this->redirect('.');
        //跳转到网站首页
        // $this->redirect('../../');
    }


	
	//视图显示
    public function Login($username='',$password='')
	{
		echo Session::get('username');
		echo "</br>";
		echo "密码";
		echo "</br>";
		// $result = Manageuser::get([
		// 'username'=>$username,
		// 'password'=>$password
		// ]);
		$hash = password_hash($password, PASSWORD_BCRYPT);
		echo "$hash";
		
		// $PHPASS = new password_hash(8, true);
		$password = $PHPASS->password_hash($password);
		$userData['password'] = $password;
		
		if (password_verify($password,'$2y$10$rH5nwLWoGo/mSWwQZS0gH.9ic2UDaMTgmPe.ac3BP5BownWAkGdp6')) 
		{ 
			echo "密码正确";
			echo '</br>';
			echo $hash;
		} else {  
			// return $this->error('登录失败');
			echo $hash;
		}

	
		
    }
	// $hash = password_hash($password, PASSWORD_BCRYPT);
	// password_verify (string $password , string $hash)
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