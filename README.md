ThinkPHP 5.0 学习日志
===============

[![Blog From](https://poser.pugx.org/topthink/think/downloads)](http://www.19aq.com/)
[![c32's blog](https://secure.travis-ci.org/ThinkUpLLC/ThinkUp.png?branch=master)](http://www.19aq.com/)
[![Latest Unstable Version](https://poser.pugx.org/topthink/think/v/unstable)](http://www.19aq.com/)
[![License](https://poser.pugx.org/topthink/think/license)](http://www.19aq.com/)
[![ThinkPHP技术交流群](http://pub.idqqimg.com/wpa/images/group.png)](//shang.qq.com/wpa/qunwpa?idkey=d5effdf51b3f89a78965f95a9ee2a3c44e1c6850add29572818613f20fa6e635)

有兴趣的可以一起学习~  点击右上角的按钮加**QQ群**

## ‎2017‎-9‎-‎14 ‏‎19:56:06 下载安装ThinkPHP

根目录`build.php`拷贝到`application`目录下

执行`php think build --module demo`

生成一个默认的`Index`控制器文件。

[教程地址](#教程地址)点击左侧传送门进入

## 2017-9-14 21:14:00 熟悉框架和写法
#### 载入类库
use think\Controller;

#### 熟悉tp5框架和语法

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

| 表头1  | 表头2|
| ---------- | -----------|
| 表格单元   | 表格单元   |
| 表格单元   | 表格单元   |


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

# 教程地址

https://www.kancloud.cn/thinkphp/thinkphp5_quickstart

---
更多细节参阅 [README.md](README.md)
