ThinkPHP 5.0 学习日志
===============

[![Blog From](https://poser.pugx.org/topthink/think/downloads)](http://www.19aq.com/)
[![c32's blog](https://poser.pugx.org/topthink/think/v/stable)](http://www.19aq.com/)
[![Latest Unstable Version](https://poser.pugx.org/topthink/think/v/unstable)](https://packagist.org/packages/topthink/think)
[![License](https://poser.pugx.org/topthink/think/license)](https://packagist.org/packages/topthink/think)


有兴趣的可以一起学习~ [![QQ群](http://pub.idqqimg.com/wpa/images/group.png "QQ群")](http://shang.qq.com/wpa/qunwpa?idkey=d5effdf51b3f89a78965f95a9ee2a3c44e1c6850add29572818613f20fa6e635)

## 2017-9-14 21:14:00 熟悉框架和写法
熟悉tp5框架和语法

> http://www.tp5.com/模型/控制器/应用操作
> http://www.tp5.com/index.php/index/index/index
> http://www.tp5.com/index.php/index/index/hello

```
<?php
namespace app\index\controller;
use think\Db;
class Index 
{
    public function index()
    {
        // 后面的数据库查询代码都放在这个位置
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
读取数据
```
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
~~~
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

更多细节参阅 [README.md](README.md)
