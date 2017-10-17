ThinkPHP 5.0 学习日志
===============

[![Blog From](https://poser.pugx.org/topthink/think/downloads)](http://www.19aq.com/)
[![c32's blog](https://secure.travis-ci.org/ThinkUpLLC/ThinkUp.png?branch=master)](http://www.19aq.com/)
[![Latest Unstable Version](https://poser.pugx.org/topthink/think/v/unstable)](http://www.19aq.com/)
[![License](https://poser.pugx.org/topthink/think/license)](http://www.19aq.com/)
[![ThinkPHP技术交流群](http://pub.idqqimg.com/wpa/images/group.png)](//shang.qq.com/wpa/qunwpa?idkey=d5effdf51b3f89a78965f95a9ee2a3c44e1c6850add29572818613f20fa6e635)

有兴趣的可以一起学习~  点击右上角的按钮加**QQ群**

## ‎2017‎-9‎-‎14 ‏‎19:56:06 下载安装ThinkPHP

根目录`build.php`拷贝到`application`目录下</br>
执行`php think build --module demo`</br>
生成一个默认的`Index`控制器文件。</br>
[教程地址](#教程地址)点击左侧传送门进入</br>
## 2017-9-14 21:14:00 熟悉框架和写法
#### 载入类库
`use think\Controller;`

#### 熟悉tp5框架和语法

修改`database.php`内的`prefix`数据库表的前缀

> http://www.tp5.com/模型/控制器/应用操作

> http://www.tp5.com/index.php/index/index/index

> http://www.tp5.com/index.php/index/index/hello

```php
<?php
namespace app\index\controller;
use think\Db;
class Index 
{
    public function index()
    {
        // 后面的数据库查询相关代码都放在这个位置
	$data = Db::name('data')->find();
        $this->assign('result', $data);
        return $this->fetch();
    }
	public function hello($name = 'World')
    {
        return 'Hello,' . $name . '!';
    }
}
```

数据库格式

| id  | title| body|
| ---------- | -----------| -----------|
| 1   | 标题1   | 内容1   |
| 2   | 标题2   | 内容2   |
| 3   | 标题3   | 内容3   |

读取数据
```html
<html>
<head>
<title>c32</title>
</head>
<body>
{$result.id}--{$result.title}
</body>
</html>
```

## 2017-9-17 18:35:19 数据查询
学习了以`Model模型`查询和`Db类`查询
~~~php
//查询数据
$list = Db::name('data')
	->where('')   //where('id', 1)
	->select();
	dump($list);
// 更新记录
$update = Db::name('data')
	->where('id', 1)
	->update(['body' => "framework"]);
	dump($update);
// 插入记录
Db::name('data')
	->insert([ 'title' => 'thinkphp','body'=> '123123']);
// 删除数据
Db::name('data')
	->where('id',2) 
	->delete();
~~~


## 2017-9-23 22:09:56 U函数 后台基础逻辑熟悉
熟悉了tp3.2.3的U函数、`URL_MODEL`、`Rewrite`、基础后台逻辑

## 2017-9-24 13:31:42 整合H-ui后台
开启调试模式</br>
`'app_debug'              => ture,`</br>
D:\wwwroot\thinkphp_5.0.10_full\application\config.php</br>
写入`D:\wwwroot\thinkphp_5.0.10_full\application\admin\controller\Index.php`</br>
内容为</br>
~~~php
<?php
namespace app\admin\controller;

use think\Controller;
//use think\Db;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
		
    }
	
}
~~~
模板下static目录css文件拷贝到public目录下

## 2017-9-25 19:48:56 学习public目录定义
学习了https://github.com/yuan1994/tpAdmin
发现没有进行整体目录定义，模板跟随thinkphp的框架和写法，把每个html模板文件的加载全部重新写了一遍。</br>
~~~php
{:\\think\\Url::build('./admin/index/welcome.html')}
~~~
URL写法

## 2017-9-28 17:31:01
熟悉驼峰写法</br>
提示 `语法错误: unexpected '(', expecting ',' or ';'`</br>
参照tp5论坛`WdatePicker日历控件报错`解决方法  转载[c32's blog](http://www.19aq.com/)</br>
完成后台页面报错的解决</br>
Url新的写法`{:Url('/')}`</br>
熟悉了基础的模型查询   `use app\admin\model\User as UserModel;`
带参数查询   `url/1` 或者`?id=1`
~~~php
public function admin_list($id='')   
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
~~~

## 2017-9-30 15:50:23

模型查询全部数据方法</br>
首先在模型写好查询</br>
~~~php
public function admin_role()
    {
		$result = UserModel::all();
		foreach ($result as $data) {
			echo $data->id . '<br/>';
			echo $data->username . '<br/>';
			echo $data->phone . '<br/>';
			echo $data->email . '<br/>';
			echo $data->role . '<br/>';
			echo $data->status . '<br/>';
		}
        return $this->fetch();
    }
~~~
然后在控制器进行输出</br>
~~~php
public function admin_list()   //管理员列表
    {
		$result = UserModel::all();
		$this->assign('result', $result);   //输出数组
        return $this->fetch();
    }
~~~
最后传递到模板视图</br>
~~~html
{volist name="result" id="result" }   <!--输出数组循环开始-->
	<td>{$result.id}</td>
	<td>{$result.username}</td>
	<td>{$result.phone}</td>
	<td>{$result.email}</td>
	<td>超级管理员{$result.role}</td>
{/volist}            <!--输出数组循环结束-->
~~~
`public` 表示全局，类内部外部子类都可以访问；</br>
`private`表示私有的，只有本类内部可以使用；</br>
`protected`表示受保护的，只有本类或子类或父类中可以访问；</br>
~~~php
// 静态调用
$user = User::get(1);
$user->name = 'thinkphp';
$user->save();

// 实例化模型
$user = new User;
$user->name= 'thinkphp';
$user->save();

// 使用 Loader 类实例化（单例）
$user = Loader::model('User');

// 或者使用助手函数`model`
$user = model('User');
$user->name= 'thinkphp';
$user->save();
~~~
静态查询驻内存，常理上比动态快</br>
动态使用完立即释放，而静态不行。</br>

~~~php
// 开启应用Trace调试
'app_trace' =>  true,
// 设置Trace显示方式
'trace'     =>  [
    // 在当前Html页面显示Trace信息
    'type'  =>  'html',
],
~~~

## 2017-9-30 22:54:58

`$user = new UserModel;`  模型实例化
`$result = UserModel::all();`  查询全部
`$user = UserModel::get($id);`  传递ID值
~~~php
$user = User::get(function($query){   //执行查询
    $query->where('name', 'thinkphp');
});
~~~
具体模型实例化例子参照[ThinkPHP官方教程](https://www.kancloud.cn/manual/thinkphp5/135191)

## 2017-9-30 23:42:39
解决H-ui WdatePicker日历控件报错的问题</br>
`WdatePicker({ minDate:'#F{ $dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })` 
大括号后面加空格就可以了</br>

## 2017-10-4 解决修改器读取数据修改问题
解决输出时间戳问题</br>
`{$result.date|date="Y-m-d h-i-s",###}`</br>

## 2017-10-9 22:04:14
简单测试datatable插件使用</br>
初步了解数据模型关联</br>

## 2017-10-10 21:43:36
了解模型关联  一对一  一对多  多对多关联</br>

## 2017-10-14 17:54:30
后台增加验证码和重置按钮</br>

## 2017-10-16 18:53:35
完成文章列表页 文章分类自动关联数据库分类</br>
使用的联表查询完成的，语句见下 </br>
`感谢QQ群316497602的群主973873838的帮助`</br>
```php
public function article_list()
	{
		$result = Articlelist::table('think_article')
		->alias('a')//给主表取别名
		->join('think_article_sort b','a.sortid = b.sid')
		->where('checked','=','y')
		->order('id', 'asc')
		->select();
		$this->assign('result',$result);
		return $this->fetch();

	}
```

## 2017-10-16 22:02:06
解决联表查询多字段重复的问题</br>
```->field('a.*,b.*,c.id as id1,c.username as username')```</br>

## 2017-10-17 21:41:06
初步编写新增文章，以及后台文章编辑功能</br>
```php
		$data['title'] = '我是标题';
		
		$data['date'] = date(time());       //date('Y-m-d',time()); 日期
		$data['content'] = '小沈阳';
		$data['excerpt'] = '小';
		$data['author'] = 1;
		$data['sortid'] = 1;
		$data['status'] = 1;
		
		//显示要添加到表中原始数据
		echo '要添加到表中的数据如下:<br/>';
		dump($data);
		
		//插入数据到表中，并返回受影响记录数量
		$result = Article::insert($data);

		//判断是否新增成功,成功则显示提示信息
		echo $result ? "新增成功!<br />":'新增失败!<br />';  
```

## 教程地址

https://www.kancloud.cn/thinkphp/thinkphp5_quickstart

---
更多细节参阅 [README.md](README.md)
