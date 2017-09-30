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
~~~php
		$user = UserModel::get($id);
		echo $user->nickname . '<br/>';
		echo $user->email . '<br/>';
		echo date('Y/m/d', $user->birthday) . '<br/>';
~~~

## 2017-9-30 15:50:23
模型查询数据方法
</br>







# 教程地址

https://www.kancloud.cn/thinkphp/thinkphp5_quickstart

---
更多细节参阅 [README.md](README.md)
