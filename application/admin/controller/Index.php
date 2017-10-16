<?php
namespace app\admin\controller;

use app\admin\model\User as UserModel;  //载入模型 并设置别名
use app\admin\model\Manageuser;
use app\admin\model\Articlelist;
use app\admin\model\ArticleSort;
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
	
	public function product_brand()
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
	
	public function article_add()
    {
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
		// $result = Articlelist::where('checked','=','y')->order('id', 'asc')->select();
		// $this->assign('result',collection($result)->append(['status1','sortid1'])->toArray());
		// return $this->fetch();
		
		$result = Articlelist::table('think_article')->alias('a')//给主表取别名
		->join('think_article_sort b','a.sortid = b.sid')
		->join('think_manage_user c','a.author = c.id')
		->where('think_article.status','=','1')
		->field('a.*,b.*,c.id as id1,c.username as username')
		// ->order('id', 'asc')
		->select();
		$this->assign('result',collection($result)->append(['status11'])->toArray());
		return $this->fetch();
		// echo $result;
		// print $result;


	}
	
	public function article_zhang()	//文章详情页
    {
		$data = Db::name('article')->find();
        $this->assign('result', $data);
        return $this->fetch();
		
        // return $this->fetch();
    }

	public function test()
    {
		$users  = ArticleSort::select();
		foreach ($users as $user) {
			dump($user->items);
		}

		
		
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


