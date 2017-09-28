<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
	// 设置单独的数据库连接
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'demo',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => 'root',
        // 数据库连接端口
        'hostport'    => '',
        // 数据库连接参数
        'params'      => [],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'think_',
        // 数据库调试模式
        'debug'       => true,
    ];
	
	// 设置完整的数据表（包含前缀）
    //protected $table = 'think_user';
	
	// 设置数据表（不含前缀）
    protected $name = 'manage_user';
	
	
}