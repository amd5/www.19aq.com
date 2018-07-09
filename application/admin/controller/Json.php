<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Article;
use app\admin\model\ArticleSort;
use app\admin\model\SystemLog;
use app\admin\model\Navs;

class Json extends BaseController
{
	public function __construct(){
        $this->navs   = new Navs;
        parent::__construct();
    }
	public function index()
    {
    	echo "string";
    }

    public function navs()
    {
    	echo json_encode($this->navs->navs());
    }

    public function article_list($page='1' ,$limit='15' ,$key='')
    {
    	$result = new Article();
		$result = $result->ArticleList($page,$limit,$key);
    	# code...
    	if ($result != false) {
            echo json_encode(array('code'=>0,'msg'=>'','count'=>$result['0']['id_count'],'data'=>$result));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
        }
    }

    public function sort_list(){
    	$result = new ArticleSort();
		$result = $result->ArticleSort();
    	echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
    }

    public function system_log($page='',$limit=''){
    	$result = new SystemLog;
    	$result = $result->loglist($page,$limit);
		if ($result != false) {
            echo json_encode(array('code'=>0,'msg'=>'','count'=>$result['0']['id_count'],'data'=>$result));
        }else{
            echo json_encode(array('code'=>0,'msg'=>'','count'=>0,'data'=>$result));
        }
    }

}