<?php
namespace app\admin\controller;

use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Article;
use app\admin\model\ArticleSort;
use app\admin\model\Test;	//测试
use app\admin\model\ManagerUser;
use think\Controller;
use think\Exception;
use think\Session;
use think\Db;

class Index extends Controller
{
	
	public function index()
    {
        // return $this->fetch(logincheck);	//默认进入登陆界面
		return $this->fetch();
    }
	
	
	public function login()
    {
        return $this->fetch();
    }
	
	public function welcome()
    {
        return $this->fetch();
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
	
	// public function test($id)
    // {
		// echo ($_POST['id']);
		// $result = new TestModel();
		// return $result->index($id);
		// $result->ccc();

    // }
	
	public function admin_permission()
    {
		$db1 = db('manage_user');
		$result = $db1->select();
		dump($result);
        return $this->fetch();
    }
	
	public function admin_list1()   //管理员列表
    {
		$result = new ManagerUser();
		// $this->assign('result',collection($result)->append(['status11'])->toArray());
		
		$this->assign('result1', $result);
		// echo ($result->ManagerUser());
		// dump ($result->ManagerUser());
		
		return $this->fetch();
		
		// return json($result->ManagerUser($id));
		// return view($result->ManagerUser($id));
		// return $result->ManagerUser($id);
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
	
	public function article_list()	//文章列表页           toJson();
	{
		$result = Article::alias('a')//给主表取别名
		->join('think_article_sort b','a.sortid = b.sid')
		->join('think_manage_user c','a.author = c.id')
		->where('think_article.type','=','blog')
		->field('a.*,b.*,c.id as id1,c.username as username,a.status as status')
		->order('id', 'asc')
		->select();
		$this->assign('result',collection($result)->append(['status11'])->toArray());
		return $this->fetch();
	}
	
	public function article($id)	//文章详情页
    {
		$result = Article::where('id','=',$id)->select();
		// $result = Db::name('article')->where('id','=',$id)->select();
        $this->assign('result', $result);
        return $this->fetch();
    }
	
	public function article_add()	//新建文章
    {
		if($this->request->isPost()){
			// dump(input('post.'));  //输出页面post过来的数据
			// 
			
			//https://www.kancloud.cn/thinkphp/thinkphp5_quickstart/147279#_263  查询构造器插入数据
			$data['title'] 		= $_POST["articletitle"];
			$data['date'] 		= date(time());
			$data['content'] 	= $_POST["content"];
			$data['sortid'] 	= $_POST["brandclass"];
			$data['excerpt'] 	= '我是文章描述';
			$data['status'] 	= '1';
			
			//显示要添加到表中原始数据
			// echo '要添加到表中的数据如下:<br/>';
			//dump($data);
			
			//插入数据到表中，并返回受影响记录数量
			$result = Article::insert($data);
			
			//判断是否新增成功,成功则显示提示信息
			echo $result ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'发布失败!<br />';  	

			return $this->fetch();
			
		} else {
			$sortname = ArticleSort::order('taxis', 'asc')->select();
			$this->assign('sortname', $sortname);
			return $this->fetch();
		}

    }
	
	public function article_edit($id)	//文章编辑
    {
		if($this->request->isPost()){
			$result = Article::where('id', $id)
			->update([
			'title' 	=> $_POST["articletitle"],
			'content' 	=> $_POST["content"],
			'sortid'	=> $_POST["brandclass"],
			'date' 		=> strtotime($_POST["datetime"]),
			
			]);
			
			if($result){
				$this->success("文章修改成功!");
				
			}else{
				$this->error("内容没有更新!");
			}
		} else {
			// $result = Article::get($id);
			// return view('article_edit',['result'=>$result]);
			//->field('a.*,b.*,c.id as id1,c.username as username,a.status as status')
			
			$result = Article::alias('a')	//给主表取别名
			->join('think_article_sort b','a.sortid = b.sid')
			->where('id',$id)
			->field('a.*,b.*')
			->order('id', 'asc')
			->select();
			
			$sortname = ArticleSort::order('taxis', 'asc')->select();
			
			$this->assign('result', $result);
			$this->assign('sortname', $sortname);
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
		$result = ArticleSort::order('taxis', 'asc')->select();
		// echo ($result);
		// dump ($result);
		// $this->assign('result', $result);
		$this->assign('result',collection($result)->append(['status11'])->toArray());
		return $this->fetch();
		

    }
	
	public function article_sort_add()	//添加分类
    {
		if($this->request->isPost()){
			$data['taxis']       = $_POST["sort_taxis"];		//分类排序
			$data['sortname']    = $_POST["sort_name"];			//分类名称
			$data['alias']       = $_POST["sort_alias"];		//分类别名
			$data['template']    = $_POST["sort_template"];		//分类模板
			$data['description'] = $_POST["description"];		//分类描述
			$result = ArticleSort::insert($data);
			
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
			$data['taxis']       = $_POST["sort_taxis"];		//分类排序
			$data['sortname']    = $_POST["sort_name"];			//分类名称
			$data['alias']       = $_POST["sort_alias"];		//分类别名
			$data['template']    = $_POST["sort_template"];		//分类模板
			$data['description'] = $_POST["description"];		//分类描述
			// dump($data);
			$result = ArticleSort::where('sid', $id)->update($data);
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
        return $this->fetch();
    }
	
	public function product_add()
    {
        return $this->fetch();
    }
	
	public function member_add()
    {
        return $this->fetch();
    }
	
	
	
	
	public function article1()
    {
        return $this->fetch();
    }

	public function test($id)
    {
		echo ($_POST['id']);
		$result = new Test();
		return $result->index($id);
		$result->ccc();

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

                // 生成session信息
                Session::set(Config::get('rbac.user_auth_key'), $auth_info['id']);
                Session::set('user_name', $auth_info['account']);
                Session::set('real_name', $auth_info['realname']);
                Session::set('last_login_ip', $auth_info['last_login_ip']);
                Session::set('last_login_time', $auth_info['last_login_time']);

                // 超级管理员标记
                if ($auth_info['id'] == 1) {
                    Session::set(Config::get('rbac.admin_auth_key'), true);
                }

                // 保存登录信息
                $update['last_login_time'] = time();
                $update['login_count'] = ['exp', 'login_count+1'];
                $update['last_login_ip'] = $this->request->ip();
                Db::name("AdminUser")->where('id', $auth_info['id'])->update($update);

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


