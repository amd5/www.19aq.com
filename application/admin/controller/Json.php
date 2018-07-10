<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Article;
use app\admin\model\ManageUser; //加载管理员模块
use app\admin\model\Navs;

class Json extends BaseController
{
	public function __construct(){
        $this->navs   = new Navs;
        $this->wenz   = new Article;
        parent::__construct();
    }
	public function index()
    {
    	echo "json";
    }

    public function navs()
    {
    	echo json_encode($this->navs->navs());
    }

    public function article_list($page='1' ,$limit='15' ,$key='')
    {
		$result = $this->wenz->ArticleList($page,$limit,$key);
    	if ($result != false) {
            echo json_encode(array('code'=>0,'msg'=>'','count'=>$result['0']['id_count'],'data'=>$result));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
        }
    }

    public function sort_list(){
		$result = $this->wenz->ArticleSort();
    	echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
    }

    public function system_log($page='',$limit=''){
    	$result = $this->wenz->loglist($page,$limit);
		if ($result != false) {
            echo json_encode(array('code'=>0,'msg'=>'','count'=>$result['0']['id_count'],'data'=>$result));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
        }
    }

    public function admin_list(){
        $result = ManageUser::select();
        echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
    }

}