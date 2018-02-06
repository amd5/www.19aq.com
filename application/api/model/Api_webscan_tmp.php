<?php
# #########################################
# #Function:    http扫描临时数据
# #Blog:        http://www.19aq.com/
# #Datetime:    2018-2-1 19:37:14
# #Author:		c32
# #Email:		amd5@qq.com
# #########################################
namespace app\api\model;

use think\Model;
use app\api\model\Api_webscan_tmp;

class Api_webscan_tmp extends Model
{
	public function Abc($cmsver)
    {
    	if($cmsver == 'b'){
    		echo "1111";
    		dump($cmsver);
    	}else{
    		echo "2222";
    	}
		$result = Api_webscan_tmp::all();
		return $result;
    }
}