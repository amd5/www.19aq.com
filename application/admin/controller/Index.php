<?php
namespace app\admin\controller;

//use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Article;	//加载文章模块
use app\admin\model\ArticleTag; //加载文章分类模块
use app\admin\model\ArticleSort; //加载文章分类模块
use app\admin\model\ManageUser;	//加载管理员模块
use app\admin\model\SystemLog;	//加载系统日志模块
// use think\Loader;
use app\extra\api_demo\SmsDemo;	//载入短信类
use app\extra\ip\IpLocation;	//载入ip类
use app\extra;
use think\Controller;	//继承控制器
use think\Exception;
use think\Cookie;
// use think\Session;		//设置Session
use think\Request;    //请求IP地址等
use think\Cache;
use think\Db;
use extend\org\Baksql;

use think\cache\driver\Redis;  //缓存用到

class Index extends BaseController
{
	//protected表示受保护的，只有本类或子类或父类中可以访问；
	// protected $user = '';
	//private表示私有的，只有本类内部可以使用；
	// private $user = '';

	public function __construct()
    {
    	//parent::tank();  //调用父类的tank方法
    	//self::$love;     //调用本类的love方法 或 变量？
    	//取Cookie值
        $this->user = Cookie::get('username');
        parent::__construct();
    }
	// private $accessKeyId = 'LTAIJ7jxMyfxE9nw';
	public function index()
    {
		// $user = Cookie::has('username');
		// $user = Session::has('username');
		// echo ($this->accessKeyId);
		
		if($this->user == null)
		{
			echo "Cookie 空";
			$this->redirect('./admin/login');
		}else
		{
			echo "you Cookie";
			$result = ManageUser::where('id', $this->user)->find();
			// dump($result);
			$this->assign('result', $result);
			return $this->fetch();
		}
    }
	
	public function clear()
    {
    	//删除Cache缓存
		Cache::clear();
		rmdir(CACHE_PATH);
		//删除Temp缓存
		array_map('unlink', glob(TEMP_PATH . '/*.php'));
		//空目录删除php文件报错，备用
		// array_map(function($v){ if(file_exists($v)) @unlink($v); }, (array)glob(TEMP_PATH . '/*.php'));  
		//如果Temp目录存在则删除，否则跳过
		if (file_exists(TEMP_PATH) == true) {
			rmdir(TEMP_PATH);
		}
		//清除Log缓存
		$path = glob( LOG_PATH.'*' );
		foreach ($path as $item) {
		array_map( 'unlink', glob( $item.DS.'*.log' ) );
		rmdir( $item );
		}
		// return $this->true;
        // return $this->display();
    }
	
	public function welcome()
    {
    	// $user = Session::has('username');
    	$result = ManageUser::where('id', $this->user)->find();
		$this->assign('result', $result);
        return $this->fetch();
    }
	
	public function admin_permission()
    {
		$db1 = db('manage_user');
		$result = $db1->select();
		dump($result);
        return $this->fetch();
    }
	
	public function admin_list()   //管理员列表
    {
		$result = new ManageUser();
		$result = $result->ManageUser();
		// $this->assign('result',collection($result)->append(['Role1'])->toArray());
		$this->assign('result', $result);
		return $this->fetch();
    }


    public function aces()
    {

    	$user = Article::with('pcs')->select([1,2,3]);
    	$this->assign('result', $user);
    	return $this->fetch();

    }




	
	public function article_list()	//文章列表页           toJson();
    {
		$result = new Article();
		$result = $result->ArticleList();
		$this->assign('result', $result);
		return $this->fetch();
    }
	
	public function article($id)	//文章详情页
    {
		$result = new Article();
		$result = $result->Article($id);
        $this->assign('result', $result);
        return $this->fetch();
    }
	
	public function article_add()	//新建文章
    {
		if($this->request->isPost()){
			//dump(input('post.'));  //输出页面post过来的数据
			// $result = new Article();
			// $result = $result->ArticleAdd();
			$data['title'] 		= $_POST["articletitle"];
			$data['date'] 		= date(time());
			$data['content'] 	= $_POST["content"];
			$data['sortid'] 	= $_POST["brandclass"];
			$data['excerpt'] 	= $this->request->ip();
			$data['status'] 	= '1';
			$result = Article::insert($data);

			$update['username'] 		= session('username');
			$update['title']  			= $data['title'] ."-文章发布成功！";
			$update['content']  		= $_POST["content"];
	        $update['last_login_time']	= date(time());
	        $update['last_login_ip']	= $this->request->ip();
	        $update['login_status']		= "4";
	        Db::name("SystemLog")->insert($update);

			echo $result ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'发布失败!<br />';  	
			
		} else {
			$result = new ArticleSort();
			$result = $result->ArticleSort();
			$this->assign('sortname', $result);
			// echo $this->request->domain();
			return $this->fetch();
		}

    }
	
	public function article_edit($id)	//文章编辑
    {
		if($this->request->isPost()){
			// $result = new Article();
			// $result = $result->ArticleEdit($id);
			$result = Article::where('id', $id)
			->update([
			'title' 	=> $_POST["articletitle"],
			'content' 	=> $_POST["content"],
			'sortid'	=> $_POST["brandclass"],
			'date' 		=> strtotime($_POST["datetime"]),
			]);

			$update['username'] 		= Cookie('username');
			$update['title']  			= $_POST["articletitle"] ."文章修改成功！";
			$update['content']  		= $_POST["content"];
	        $update['last_login_time']	= time();
	        $update['last_login_ip']	= $this->request->ip();
	        $update['login_status']		= "5";
	        Db::name("SystemLog")->insert($update);

			if($result){
				$this->success("文章修改成功!");
				
			}else{
				$this->error("内容没有更新!");
			}
		} else {
			$result = Article::where('id', $id)
			->select();
			//查询所有分类
			$sort   = ArticleSort::select();
			
			//查询文章详情包含的标签
			$where="find_in_set($id,gid)";
			$tagname    = ArticleTag::where($where)->select();
			foreach ($tagname as $key => $value) {
				# code...
				$tag =$tag.$value['tagname'].',';
			}

			$this->assign('result', $result);
			$this->assign('sortname', $sort);
			$this->assign('tag', $tag);
			return $this->fetch();

		}

    }
	
	public function article_del()	//文章删除
    {
        if($this->request->isAjax()){
			$id		= $_POST['id'];
			$action = $_POST['action'];
			//-1 => '删除', 0 => '隐藏', 1 => '正常', 2 => '待审核'
			if($action == "-1"){
				$result = Article::where('id', $id)->update(['status' => '-1']);
				if($result){$this->success("删除成功!");}else{$this->error("删除失败");}
			}elseif($action == "0"){
				$result = Article::where('id', $id)->update(['status' => '0']);
				if($result){$this->success("隐藏成功!");}else{$this->error("隐藏失败");}
			}elseif($action == "1"){
				$result = Article::where('id', $id)->update(['status' => '1']);
				if($result){$this->success("显示成功!");}else{$this->error("显示失败");}
			}
			return $this->fetch();
			
		}else{
			return "请勿非法操作!";
		}
    }
	
	public function article_del_hide()	//文章显示 文章隐藏
    {
        echo ("暂时还没写");
    }
	
	public function article_sort()	//分类列表
    {
		$result = ArticleSort::order('sid ASC')
		->select();
        $this->assign('result', $result);
        return $this->fetch();
    }
	
	public function article_sort_add()	//添加分类
    {
		if($this->request->isPost()){
			$result = new ArticleSort();
			$result = $result->ArticleSortAdd();
			if($result){
				$this->success("添加成功!");
				
			}else{
				$this->error("添加失败");
			}
		}else {
			return $this->fetch();
		}
		
    }
	
	public function article_sort_edit($id)	//编辑分类
    {
		if($this->request->isPost()){
			$result = new ArticleSort();
			$result = $result->ArticleSortEdit($id);
			if($result){
				$this->success("修改分类成功!");
				
			}else{
				$this->error("内容没有更新!");
			}	

		} else {
			
			$result = ArticleSort::get($id);
			return view('article_sort_edit',['result'=>$result]);
		}	

    }
	
	public function article_sort_del()	//删除分类
    {
		$id		= $_POST['id'];
		if($this->request->isAjax()){
			$result = ArticleSort::where('sid', $id)->delete();
			if($result){
				$this->success("删除成功!");
				
			}else{
				$this->error("删除失败");
				// $this->getError();
			}
			
		}else{
			return "请勿非法操作!";
		}
    }
	
	public function article_sort_hide()	//隐藏分类
    {
		if($this->request->isAjax()){
			
			$id		= $_POST['id'];
			$action = $_POST['action'];
			//-1 => '删除', 0 => '隐藏', 1 => '正常', 2 => '待审核'
			if($action == "0"){
				$result = ArticleSort::where('sid', $id)->update(['status' => '0']);
				if($result){$this->success("隐藏成功!");}else{$this->error("隐藏失败");}
			}elseif($action == "1"){
				$result = ArticleSort::where('sid', $id)->update(['status' => '1']);
				if($result){$this->success("显示成功!");}else{$this->error("显示失败");}
			}
			
			
			
			return $this->fetch();
		}else{
			return "请勿非法操作!";
		}

    }
	
	public function article_sort_status()	//状态更改
    {
		// // dump ($_POST['id']);
		// $result = ArticleSort::where('sid', $_POST['id'])->where('status', '1')->select();
		// // dump($result);
		// if($result == 1){
			// $jinyong = ArticleSort::where('sid', $_POST['id'])->update(['status' => '0']);
				// if($jinyong){
					// $this->success("停用成功!");
					
				// }else{
					// $this->error("停用失败");
				// }
		// }else{
			// $qiyong = ArticleSort::where('sid', $_POST['id'])->update(['status' => '1']);
				// if($qiyong){
					// $this->success("启用成功!");
					
				// }else{
					// $this->error("启用失败");
				// }
		// }
		echo ("1111");
    }
	
	
	public function picture_add()
    {
    	// $result = Article::get(1);
    	$result = Article::order('id desc')
    	->where('status=1 and (sortid=1 or views=49 or checked="y")')
    	->select();
		dump ($result);
		// $this->assign('result', $result);
        // return $this->fetch();
    }
	
	public function product_add()
    {
        return $this->fetch();
    }
	
	public function member_add()
    {
        return $this->fetch();
    }
	
	public function ip()
    {
    	$ip=new IpLocation();
    	echo "</br>";
    	//返回IP地址
		// return $ip->get_client_ip();
		// dump($ip->get_client_ip());
		//返回所在区域

		dump( $ip->getlocation('219.139.33.7'));
        // return $this->fetch();
    }
	
	
	public function article1()
    {
    	$config = [
		'host' => '127.0.0.1',
		'port' => 6379,
		'password' => '',
		'select' => 0,
		'timeout' => 0,
		'expire' => 0,
		'persistent' => false,
		'prefix' => '',
		];

		$Redis=new Redis($config);
		$Redis->set("blog","test");
		echo $Redis->get("blog");
        // return $this->fetch();
    }

	public function yunsms()	//融联云通信
    {
		$code = "666666";
		$min  = "6";
		$phone= "15024267536";
		$test = sendTemplateSMS("15024267536",array($code,$min . "分钟"),"178711");
		// $test = send_verify();
		echo "123";
		// dump ($_POST['id']);
    }
	
	public function alisms()
	{
		//设置时间限制
		set_time_limit(0);
		echo "</br>";
		// 调用示例：
		header('Content-Type: text/plain; charset=utf-8');

		$demo = new SmsDemo(
		    "LTAIJ7jxMyfxE9nw",
		    "6QVo3Ibosh2OujQ9GUWOE4K70dOPX0"
		);

		echo "SmsDemo::sendSms\n";
		$response = $demo->sendSms("c32博客","SMS_113460107","15024267536",Array("ip"=>"123.456.789.666","product"=>"dsd"),
		    "123"
		);
		print_r($response);
		//查询短信接口
		// echo "SmsDemo::queryDetails\n";
		// $response = $demo->queryDetails(
		//     "SMS_109355085",  // phoneNumbers 电话号码
		//     "20170718", // sendDate 发送时间
		//     10, // pageSize 分页大小
		//     1 // currentPage 当前页码
		//     // "abcd" // bizId 短信发送流水号，选填
		// );

		// print_r($response);

	}
	
	public function picture_list()        //WdatePicker日历控件报错
    {
        return $this->fetch();
    }
	
	public function product_category()
    {
        return $this->fetch();
    }
	
	public function product_category_add()
    {
        return $this->fetch();
    }
	
	public function feedback_list()
    {
    	// echo "123";
        return $this->fetch();
    }
	
	public function member_list()
    {
        return $this->fetch();
    }
	
	public function member_del()
    {
        return $this->fetch();
    }
	
	public function member_level()
    {
        return $this->fetch();
    }
	
	public function member_scoreoperation()
    {
        return $this->fetch();
    }
	
	public function member_record_browse()
    {
        return $this->fetch();
    }
	
	public function member_record_download()
    {
        return $this->fetch();
    }
	
	public function member_record_share()
    {
        return $this->fetch();
    }
	
	public function admin_role()
    {
        return $this->fetch();
    }
	
	public function charts_1()
    {
        return $this->fetch();
    }
	
	public function charts_2()
    {
		$user = UserModel::get($id);
		echo $user['id'] . '<br/>';
		echo $user['username'] . '<br/>';
		echo $user['phone'] . '<br/>';
		echo $user['email'] . '<br/>';
		echo $user['role'] . '<br/>';
		echo $user['status'] . '<br/>';
		echo $user['description'] . '<br/>';
		echo date('Y/m/d', $user['last_login_time']) . '<br/>';
        return $this->fetch();
    }
	
	public function charts_3()
    {
        return $this->fetch();
    }
	
	public function charts_4()
    {
        return $this->fetch();
    }
	
	public function charts_5()
    {
        return $this->fetch();
    }
	
	public function charts_6()
    {
        return $this->fetch();
    }
	
	public function charts_7()
    {
        return $this->fetch();
    }
	
	public function system_base()
    {
        return $this->fetch();
    }
	
	public function system_category()
    {
        return $this->fetch();
    }
	
	public function system_data()
    {
        return $this->fetch();
    }
	
	public function system_shielding()
    {
        return $this->fetch();
    }

    public function system_sql()
    {
    	$type=input("tp");
        $name=input("name");
        $sql=new \org\Baksql(\think\Config::get("database"));
        switch ($type)
        {
        case "backup": //备份
        	return $sql->backup();
        	break;  
        case "dowonload": //下载
          	$sql->downloadFile($name);
          	break;  
        case "restore": //还原
          	return $sql->restore($name);
          	break; 
        case "del": //删除
          	return $sql->delfilename($name);
          	break;          
        default: //获取备份文件列表
            return $this->fetch("system_sql",["list"=>$sql->get_filelist()]); 
          
        }

        return $this->fetch();
    }

	
	public function system_log()
    {
    	$result = SystemLog::order('id desc')
		->select();
		// dump ($result);
		// die;
        $this->assign('result', $result);
        return $this->fetch();
    }
	
	public function product_list()
    {
        return $this->fetch();
    }
	

	
	
	
	/**
     * 登录检测
     * @return \think\response\Json
     */
    public function checkLogin1()
    {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $data = $this->request->post();
            $validate = Loader::validate('Pub');
            if (!$validate->scene('login')->check($data)) {
                return ajax_return_adv_error($validate->getError());
            }

            $map['account'] = $data['account'];
            $map['status'] = 1;
            $auth_info = \Rbac::authenticate($map);

            // 使用用户名、密码和状态的方式进行认证
            if (null === $auth_info) {
                return ajax_return_adv_error('帐号不存在或已禁用！');
            } else {
                if ($auth_info['password'] != password_hash_tp($data['password'])) {
                    return ajax_return_adv_error('密码错误！');
                }

                // 记录登录日志
                $log['uid'] = $auth_info['id'];
                $log['login_ip'] = $this->request->ip();
                $log['login_location'] = implode(" ", \Ip::find($log['login_ip']));
                $log['login_browser'] = \Agent::getBroswer();
                $log['login_os'] = \Agent::getOs();
                Db::name("LoginLog")->insert($log);

                // 缓存访问权限
                \Rbac::saveAccessList();

                return ajax_return_adv('登录成功！', '');
            }
        } else {
            // throw new Exception("非法请求");
			echo '11111';
        }
    }
	

}


