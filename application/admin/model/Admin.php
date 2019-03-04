<?php
namespace app\admin\model;

use think\Db;
use think\Model;
use think\Exception;

/**
 * Class Admin
 * @author Pendant 861618191@qq.com
 * @package app\admin\model
 */
class Admin extends Model
{
    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    /**
     * 用户信息
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function userMsg($id)
    {
        $result = db('Admin a')
            ->field('a.*,ag.id as gid')
            ->join('group_access ga', 'ga.uid = a.id', 'left')
            ->join('auth_group ag', 'ag.id = ga.group_id ', 'left')
            ->where('a.id', $id)
            ->cache('userMsg',2592000)
            ->find();
        return $result;
    }

    /**
     * 登陆校验
     * @param $data
     * @return array
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function loginIn($data)
    {
        $res=self::where('username', '=', $data['username'])
            ->field('password, id, nickname, last_ip, last_time, if_use')
            ->cache('loginIn',2592000)
            ->find();
        if (!$res) {
            return ['code'=>'-1','msg'=>'用户名不存在'];
        }

        
        if (!password_verify($data['password'], $res['password'])) {
            return ['code'=>'-2','msg'=>'密码错误'];
        }

        if (!$res['if_use']) {
            return ['code'=>'-3','msg'=>'禁止登陆'];
        }

        session('id', $res['id']);
        session('nickname', $res['nickname']);
        session('start_time', time());
        session("last_ip", $res['last_ip']);
        session("last_time", $res['last_time']);

        self::where('id', $res['id'])->update(['last_ip'=>$data['last_ip'],'last_time'=>$data['last_time']]);

        return ['code'=>'1','msg'=>'登陆成功'];
    }
}
