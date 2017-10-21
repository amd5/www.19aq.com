<?php
namespace app\admin\controller;

use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Manageuser;
use app\admin\model\Article;
use app\admin\model\ArticleSort;
// use app\admin\model\ArticleAdd;
use think\Controller;
use think\Exception;
use think\Session;
use think\Db;

class Index extends Controller
{
	
	public function index()
    {
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
	
	public function admin_permission()
    {
		$db1 = db('manage_user');
		$result = $db1->select();
		dump($result);
        return $this->fetch();
    }
	
	public function admin_list()   //管理员列表
    {
		$result = Manageuser::all();
		$this->assign('result',collection($result)->append(['role1'])->toArray());
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
			// exit;
			
			//https://www.kancloud.cn/thinkphp/thinkphp5_quickstart/147279#_263  查询构造器插入数据
			$data['title'] 		= $_POST["articletitle"];
			$data['date'] 		= date(time());
			$data['content'] 	= $_POST["content"];
			$data['excerpt'] 	= '我是文章描述';
			$data['sortid'] 	= '1';
			$data['status'] 	= '1';
			
			//显示要添加到表中原始数据
			// echo '要添加到表中的数据如下:<br/>';
			//dump($data);
			
			//插入数据到表中，并返回受影响记录数量
			$result = Article::insert($data);
			
			//判断是否新增成功,成功则显示提示信息
			echo $result ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'发布失败!<br />';  	

			// return $this->fetch();
			
		} else {
			return $this->fetch();
		}

    }
	
	public function article_edit($id)
    {
		if($this->request->isPost()){
			$result1 = Article::where('id', $id)
			->update([
			'title' => $_POST["articletitle"],
			'content' => $_POST["content"],
			]);
			
			echo $result1 ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'内容没有更新!<br />';  	

		} else {
			$result = Article::get($id);
			return view('article_edit',['result'=>$result]);
			
		}

    }
	
	public function article_sort()
    {
		// if($this->request->isPost()){
		// if($this->request->isAjax()){
		if($this.title=="删除"){
			echo "11111111111111";
			$this->success("hello");
			
			$result = ArticleSort::where('sid', $id)->delete();
			// dump($result);
			
			echo $result ? "<center><font color='red'><h1>文章删除成功!</h1></font></center><br />":'文章删除失败!<br />';  	
			
			
			// dump($this);
			// echo $_POST['data'];
			// dump(input('post.'));
			
		}else {
			$result = ArticleSort::order('taxis', 'asc')->select();
			$this->assign('result', $result);
			return $this->fetch();
		}
		
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

			echo $result ? "<center><font color='red'><h1>添加分类成功!</h1></font></center><br />":'发布失败!<br />';  
			
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

			$result = ArticleSort::where('sid', $id)->update($data);
			echo $result ? "<center><font color='red'><h1>修改分类成功!</h1></font></center><br />":'内容没有更新!<br />';  	

		} else {
			$result = ArticleSort::get($id);
			return view('article_sort_edit',['result'=>$result]);
		}
    }
	
	public function article_sort_del()
    {
		$result = ArticleSort::order('taxis', 'asc')->select();
		$this->assign('result', $result);
        return $this->fetch();
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
	
	public function article_list()	//文章列表页           toJson();
	{
		$result = Article::alias('a')//给主表取别名
		->join('think_article_sort b','a.sortid = b.sid')
		->join('think_manage_user c','a.author = c.id')
		->where('think_article.status','=','1')
		->field('a.*,b.*,c.id as id1,c.username as username')
		->order('id', 'asc')
		->select();
		$this->assign('result',collection($result)->append(['status11'])->toArray());
		// dump('123123'); 
		// dump($result);
		return $this->fetch();
	}
		// $result = Articlelist::where('checked','=','y')->order('id', 'asc')->select();
		// $this->assign('result',collection($result)->append(['status1','sortid1'])->toArray());
		// return $this->fetch();
		
		// echo $result;
		// debug_end($result);
		// return $this->fetch();
	
	
	public function article1()
    {
        return $this->fetch();
    }

	public function test()
    {
		// $users  = ArticleSort::select();
		// foreach ($users as $user) {
			// dump($user->items);
		// }

		
		
        // return $this->fetch();
    }
	
	
	/**
     * 登录检测
     * @return \think\response\Json
     */
    public function checkLogin()
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


