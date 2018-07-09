<?php
namespace app\admin\controller;

//use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Article;
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
    public function main(){
    	return $this->fetch();
    }

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
			// echo "you Cookie";
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
	
	public function article_list()
    {
    	// dump(input('post.'));die;
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
    	// dump($_POST);die;
		if($this->request->isPost()){
			$data['id']     = $_POST["id"];
			$data['title'] 		= $_POST["tiele"];
			$data['date'] 		= empty($_POST["time"]) ? date(time()) : $_POST["time"];
			$data['content'] 	= $_POST["content"];
			$data['sortid'] 	= $_POST["sort"];
			$data['excerpt'] 	= $this->request->ip();
			$data['status'] 	= '1';

			if (empty($_POST["id"])) {
				$result = Article::insert($data);
			}else{
				$result = Article::where('id',$data['id'])->update($data);
			}

			$update['username'] 		= session('username');
			$update['title']  			= $_POST["tiele"] ."-文章发布成功！";
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

	public function sort_list()	//分类列表
    {
        return $this->fetch();
    }
	
	public function sort_add()
    {
    	if($this->request->isPost()){
			$data['sid']     		= $_POST["sid"];
			$data['sortname'] 		= $_POST["SortName"];
			$data['alias'] 			= $_POST["SortAlias"];
			$data['description'] 	= $_POST["SortDesc"];
			$data['status'] 		= '1';

			if (empty($_POST["sid"])) {
				$result = ArticleSort::insert($data);
			}else{
				$result = ArticleSort::where('sid',$data['sid'])->update($data);
			}
		}

		return $this->fetch();
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


