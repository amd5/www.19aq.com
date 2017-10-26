<?php
# #########################################
# #Function:    文档功能
# #Blog:        http://www.19aq.com/
# #Datetime:    2017-10-24 18:40:33
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\admin\model;

use think\Model;

class Test extends Model
{
	
	protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }
	
	
	
	public function index($id)
    {
		// $id = "1";
		// $id		= $_POST['id'];
		echo ($_POST['id']);
		$result = Test::get($id);
		return $result;

    }
	
	public function ccc()
    {
		// $result = Test::get('2')->select();
		// return $result;
		echo "my is ccc";
    }
	
}