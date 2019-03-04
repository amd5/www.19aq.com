<?php
namespace app\admin\model;

use think\Model;
use think\facade\Validate;

class Comment extends Model
{
	public static function list($id='1'){
		$result = self::where('pid',$id)->order('aid asc')->cache('CommentList'.$id,2592000)->select();
		foreach ($result as $key => $value) {
			$value['addtime'] = date('Y-m-d H:i:s', $value['addtime'] ?: time());
		}
		return $result;
	}

	public static function coadd($id){
		$count = self::cache(true,8640000)->count();
		$num   = $count+1;
		$mail  = $id['email'];
		$des   = $id['desc'];

		$ism = Validate::isEmail($mail);
		if (!$ism) {
			return api_return('请重新输入正确邮箱！', '2');
		}
		$email = str_replace('@', '#', $mail);
		$desc  = str_replace('@', '#', $des);
		#删除缓存
		Cache::rm('CommentList'.$id); 
		$res = self::insert([
			'pid' 		=> (int)$id['id'],
			'aid' 		=> $num,
			'email' 	=> $email,
			'desc' 		=> $desc,
			'addtime' 	=> time(),
			'if_show' 	=> '1',
		]);
		if (!$res) {
			return api_return('', '0');
		}
		return api_return('', '1');
	}
}