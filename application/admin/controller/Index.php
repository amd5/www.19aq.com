<?php
namespace app\admin\controller;

use app\admin\model\Article;
use app\admin\model\ManageUser;	//加载管理员模块
use app\admin\model\SystemLog;	//加载系统日志模块
use app\extra\api_demo\SmsDemo;	//载入短信类
use think\Controller;	//继承控制器
use think\Session;		//设置Session
use think\Request;    //请求IP地址等
use think\Cache;

use think\cache\driver\Redis;  //缓存用到

class Index extends BaseController
{
	//protected表示受保护的，只有本类或子类或父类中可以访问；
	// protected $user = '';
	//private表示私有的，只有本类内部可以使用；
	// private $user = '';

	public function __construct()
    {
    	Session::init();
		$id = session_id();
        $this->id = Cache::get($id);
        parent::__construct();
    }

    

	public function index()
    {
		if ($this->id != null) {
			$result = ManageUser::where('id', $this->id)->find();
			$this->assign('result', $result);
			return $this->fetch();
		}
			$this->redirect('./admin/login');
    }
	
	// public function clear()
 //    {
 //    	//删除Cache缓存
	// 	Cache::clear();
	// 	rmdir(CACHE_PATH);
	// 	//删除Temp缓存
	// 	array_map('unlink', glob(TEMP_PATH . '/*.php'));
	// 	//空目录删除php文件报错，备用
	// 	// array_map(function($v){ if(file_exists($v)) @unlink($v); }, (array)glob(TEMP_PATH . '/*.php'));  
	// 	//如果Temp目录存在则删除，否则跳过
	// 	if (file_exists(TEMP_PATH) == true) {
	// 		rmdir(TEMP_PATH);
	// 	}
	// 	//清除Log缓存
	// 	$path = glob( LOG_PATH.'*' );
	// 	foreach ($path as $item) {
	// 	array_map( 'unlink', glob( $item.DS.'*.log' ) );
	// 	rmdir( $item );
	// 	}

 //    }
	
	
	
	
	
	
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
	        SystemLog::insert($update);

			echo $result ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'发布失败!<br />';  	
			
		} else {
			$result = new Article();
			$result = $result->ArticleSort();
			$this->assign('sortname', $result);
			// echo $this->request->domain();
			return $this->fetch();
		}

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
	
	// public function yunsms()	//融联云通信
 //    {
	// 	$code = "666666";
	// 	$min  = "6";
	// 	$phone= "15024267536";
	// 	$test = sendTemplateSMS("15024267536",array($code,$min . "分钟"),"178711");
	// 	// $test = send_verify();
	// 	echo "123";
	// 	// dump ($_POST['id']);
 //    }
	
	// public function alisms()
	// {
	// 	//设置时间限制
	// 	set_time_limit(0);
	// 	echo "</br>";
	// 	// 调用示例：
	// 	header('Content-Type: text/plain; charset=utf-8');

	// 	$demo = new SmsDemo(
	// 	    "LTAIJ7jxMyfxE9nw",
	// 	    "6QVo3Ibosh2OujQ9GUWOE4K70dOPX0"
	// 	);

	// 	echo "SmsDemo::sendSms\n";
	// 	$response = $demo->sendSms("c32博客","SMS_113460107","15024267536",Array("ip"=>"123.456.789.666","product"=>"dsd"),
	// 	    "123"
	// 	);
	// 	print_r($response);

	// }
	

    // public function system_sql()
    // {
    // 	$type=input("tp");
    //     $name=input("name");
    //     $sql=new \org\Baksql(\think\Config::get("database"));
    //     switch ($type)
    //     {
    //     case "backup": //备份
    //     	return $sql->backup();
    //     	break;  
    //     case "dowonload": //下载
    //       	$sql->downloadFile($name);
    //       	break;  
    //     case "restore": //还原
    //       	return $sql->restore($name);
    //       	break; 
    //     case "del": //删除
    //       	return $sql->delfilename($name);
    //       	break;          
    //     default: //获取备份文件列表
    //         return $this->fetch("system_sql",["list"=>$sql->get_filelist()]); 
          
    //     }

    //     return $this->fetch();
    // }

    public function main(){
    	return $this->fetch();
    }

    public function admin_list()   //管理员列表
    {
		return $this->fetch();
    }

    public function article_list()
    {
		return $this->fetch();
    }

	public function sort_list()	//分类列表
    {
        return $this->fetch();
    }

	public function system_log()
    {
        return $this->fetch();
    }

    public function system_setting(){
    	return $this->fetch();
    }

}


