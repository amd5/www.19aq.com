<?php
namespace app\admin\controller;

//use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Article;
use app\admin\model\ArticleSort;
use app\admin\model\ManageUser;
// use think\Loader;
use app\extra\api_demo\SmsDemo;
use app\extra\ip\IpLocation;
use app\extra;
use think\Controller;
use think\Exception;
use think\Session;
use think\Request;
use think\Db;


class Index extends BaseController
{
	// private $accessKeyId = 'LTAIJ7jxMyfxE9nw';
	public function index()
    {
		$user = Session::has('username');
		$id   = Session::has('id');
		// echo ($this->accessKeyId);
		if($user == null)
		{
			echo "Session 空";
			$this->redirect('./admin/login');
		}else
		{
			echo "you session";
			$result = ManageUser::where('id', $user)->find();
			// dump($result);
			$this->assign('result', $result);
			return $this->fetch();
		}
    }
	
	public function login()
    {
        // return $this->fetch();
    }
	
	public function welcome()
    {
    	$user = Session::has('username');
    	$result = ManageUser::where('id', $user)->find();
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
			$update['content']  		= $_POST["content"] ."文章发布成功！";
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

			$update['username'] 		= session('username');
			$update['content']  		= $_POST["articletitle"] ."文章修改成功！";
	        $update['last_login_time']	= date(time());
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
			$sort   = ArticleSort::select();
			// $result = new Article();
			// $sort	= new ArticleSort();
			// $result = $result->Article($id);
			// $sort 	= $sort->ArticleSort();
			$this->assign('result', $result);
			$this->assign('sortname', $sort);
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
		$result = new ArticleSort();
		$result = $result->ArticleSort();
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


