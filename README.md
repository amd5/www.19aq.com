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
`WdatePicker({ minDate:'#F{ $dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })`
`如果提示语法不正确，就在大括号后面加空格就可以了。`
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

## 2017-10-10 21:43:36 模型关联
了解模型关联  一对一  一对多  多对多关联</br>

## 2017-10-14 17:54:30 验证码
后台增加验证码和重置按钮</br>

## 2017-10-16 18:53:35 联表查询
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

## 2017-10-16 22:02:06 联表查询
解决联表查询多字段重复的问题</br>
```->field('a.*,b.*,c.id as id1,c.username as username')```</br>

## 2017-10-17 21:41:06 添加编辑文章
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

## 2017-10-18 20:33:59 编辑文章
处理好文章编辑按钮和获取文章标题</br>

## 2017-10-19 23:01:54 调试输出POST
感谢QQ群316497602的群主973873838的帮助</br>
以下是html表单post的输出调试方法</br>
```php
if($this->request->isPost()){
			dump(input('post.'));
			exit;
		} else {
			$result = Article::get($id);
			return view('article_edit',['result'=>$result]);
		}
```
一直提示错误 `article_save_submit is not defined`</br>
官方Demo也提示是这个错误</br>

## 2017-10-20 17:24:47 编辑分类
暂时性解决上面的post产生的错误</br>
解决文章发布，文章编辑问题</br>
解决`ueditor`文章内代码高亮的问题</br>
备用   ps:查询并输出数据</br>
```php
public function article_sort_edit($id)	//编辑分类
{
	dump(input('post.'));  //输出post过来的数据
	//读取页面传递过来的参数查询数据库对应内容
	$result = ArticleSort::get($id);   
	//显示参数数据库内容到模板
	dump($result);
	return view('article_sort_edit',['result'=>$result]);
}
```


## 2017-10-21 18:36:20 添加编辑分类
发布文章`添加数据`的二种写法</br>
写法一：
```php
public function article_sort_add()	//添加分类
    {
		if($this->request->isPost()){
			$result = ArticleSort::insert([
			'taxis'=>$_POST["sort_taxis"],
			'sortname'=>$_POST["sort_name"],
			'alias'=>$_POST["sort_alias"],
			'template'=>$_POST["sort_template"],
			'description'=>$_POST["description"]
			]);
		echo $result ? "<center><font color='red'><h1>添加分类成功!</h1></font></center><br />":'发布失败!<br />';  
			
		}else {
			return $this->fetch();
		}
    }
```
写法二：</br>

```php
public function article_sort_add()	//添加分类
    {
		if($this->request->isPost()){
			$data['taxis'] = $_POST["sort_taxis"];
			$data['sortname'] = $_POST["sort_name"];
			$data['alias'] = $_POST["sort_alias"];
			$data['template'] = $_POST["sort_template"];
			$data['description'] = $_POST["description"];
			$result = ArticleSort::insert($data);
			// dump(input('post.'));
			echo $result ? "<center><font color='red'><h1>添加分类成功!</h1></font></center><br />":'发布失败!<br />';  
			
		}else {
			return $this->fetch();
		}
    }
```
编辑`更新数据`的二种写法</br>
写法一：</br>

```php
public function article_sort_edit($id)	//编辑分类
    {
		dump(input('post.'));  //输出post过来的数据
		if($this->request->isPost()){
			$result1 = ArticleSort::name('article_sort')
			->where('sid', $id)
			->update([
			'title' => $_POST["title"],          //标题
			]);
			// echo '要添加到表中的数据如下:<br/>';
			// dump(input('post.'));  //输出页面post过来的数据
			//判断是否插入成功
			echo $result1 ? "<center><font color='red'><h1>发布成功!</h1></font></center><br />":'内容没有更新!<br />';  	

		} else {
			$result = ArticleSort::get($id);
			return view('article_sort_edit',['result'=>$result]);
			
		}
    }
```
</br>
写法二：</br>

```php
$result1 = ArticleSort::where('sid', $id)
			->update([
			'title' => $_POST["title"],          //标题
```

## 2017-10-22 01:20:34 删除分类
ajax删除数据和提交到后台执行`以下代码仅做参考`
```html
<a title="删除" href="javascript:;" onclick="member_del(this,'{$result.sid}')" class="ml-5" style="text-decoration:none">
```
```php
function member_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			//url: '',
			url: "{:Url('')}" + ".html" + "?id=" + id,
			dataType: 'json',
			success: function(data){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
			},
			error:function(data) {
				console.log(data.msg);
			},
		});		
	});
}
```
</br>

## 2017-10-22 17:43:10 ajax删除
解决ajax前台传值和后台删除数据的问题</br>
`onclick="article_sort_del(this,'{$result.sid}')"`</br>

```html
function member_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: '',
			data: {"id":id},  
			dataType: 'json',
			success: function(data){
				<!-- alert(data.msg); -->
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
			},
			error:function(data) {
				console.log(data.msg);
			},
		});		
	});
}
```

## 2017-10-22 20:24:14 系统提示
增加系统提示并自动返回页面,并解决掉ajax文章删除</br>

```php
if($result){
	$this->success("文章修改成功!");
}else{
	$this->error("内容没有更新!");
}
```

查询每个分类一共有多少文章</br>
```mysql
SELECT distinct sortname,sid,(SELECT count(*) FROM think_article where sortid=sid) FROM think_article_sort LEFT JOIN think_article ON think_article_sort.sid=think_article.sortid ORDER BY sid asc
```

## 2017-10-22 22:46:57
调试ajax文章启用按钮</br>

## 2017-10-23 19:11:40
文章显示隐藏前台ajax做完</br>
解决前台ajax删除文章，文章状态提示为已删除</br>
```html
$(obj).parents("tr").find("a:eq(1)").remove();
```

## 2017-10-24 18:49:34
解决文章显示隐藏删除ajax传递的id为空的问题</br>
解决前台点击显示隐藏后台怎么执行判断的问题</br>
解决分类列表点击显示隐藏的status问题，和点击删除后直接删除分类的问题</br>
分类加入分类状态显示</br>
分类和文章加入标签判断，根据状态不同显示不同颜色</br>
```html
{if condition="($result.status11 == '删除')"/}label label-defaunt radius
{elseif condition="$result.status11 == '隐藏'"/}label label-defaunt radius
{elseif condition="($result.status11 == '正常')"/}label label-success radius
{else /} label-success radius
{/if}
```

## 2017-10-24 22:09:43 
解决文章时间戳显示和修改</br>
学习文章内其他分类选择</br>

## 2017-10-25 22:13:46 标签循环
使用`foreach`标签循环</br>
`exit;`执行到这里退出</br>
解决文章下拉框分类的问题</br>
编辑文章增加取消功能</br>
UE编辑器处理自动加P标签的问题 使用 `enterTag : 'br'`处理</br>
初步了解password_hash加密方式和效验方法</br>
完成password_hash密码验证方法</br>
初步测试Session使用方法</br>

## 2017-10-26 19:27:53 MVC重构
发现开发逻辑不对，计划重构MVC结构</br>

## 2017-10-27 00:20:07 MVC重构
重构管理员列表mvc逻辑</br>

thinkphp5自带分页案例</br>
```php
// 查询分页数据
$list = User::where('status', 1)->paginate();
// 创建分页显示
$this->assign('page', $list);
// 模板渲染输出
return $this->fetch();
```

```html
<div>
总记录数：{$page->total()}
<ul>
{volist name='page' id='user'}
    <li> {$user.name}</li>
{/volist}
</ul>
</div>
{$page->render()}
```

## 2017-10-30 22:23:52
初步解决 Model执行数据存储的问题</br>
管理员列表和文章列表重写</br>
重写部分功能，把数据读写全部写到模型里面</br>

## 2017-11-1 21:53:39
找前端文章模板

## 2017-11-2 22:11:43
搭建首页模板和文章详情页模板上去，其他模板暂未开工  

## 2017-11-6 21:35:52
解决云通讯短信验证码发送问题
初步了解QQ第三方登陆

## 2017-11-19 22:51:24
初步增加首页文章分类和日期归档列表

## 2017-11-20 19:30:20
增加友情链接显示

## 2017-11-21 00:27:47
修改好登录页面</br>
登录成功后设置session</br>
设计日志表</br>
增加管理员登录自动记录日志到数据库</br>
增加控制器继承BaseController</br>

## 2017-11-21 18:00:45
解决继承控制器后后台登陆页面死循环的问题</br>

## 2017-11-21 20:45:07
解决后台继承后,所有页面访问自动经过继承判断是否存在Session</br>
解决管理员退出删除Session
增加Session有效期判断

## 2017-11-21 23:40:10
查看阿里云短信接入方式

## 2017-11-22 20:44:09
解决阿里短信接入问题</br>
修复使用全局变量后后台右上角不显示管理员名称

## 2017-11-23 20:28:54
增加IP纯真数据库</br>
Base继承到后台，并继承到前台，根据Base判断Sessiond管理员身份来显示不同的文章

## 2017-11-24 00:10:00
文章发布从模型迁移到数据库</br>
增加文章发布日志</br>
增加文章编辑日志</br>
增加Session管理员判断</br>
增加普通管理员和超级管理员查看文章权限不同

## 2017-11-24 13:03:20
安装测试Redis成功
SELECT * from think_article_tag where FIND_IN_SET('1',gid)

## 2017-11-25 01:20:36
后台增加一键删除缓存</br>
增加清除缓存询问框</br>
增加清除缓存后成功提示</br>

## 2017-11-28 00:31:00
测试部分模型关联

</br>
继承：如果IP地址为XXX禁止访问网站
QQ登陆是管理员
文章标签
文章评论
rss
数据库备份恢复
上传图片格式为BLOB
文章加密码查看权限
文章访问图表
博客标题 小标题后台设置
ip访问黑名单
图片防盗链
手机发博客
博客单页
分类排序
前台其他人评论编辑
全局置顶 分类置顶
=========================
BUG
文章编辑后，时间会减少12小时
管理员退出后台不能跳首页

=========================
1、系统收费规则
2、可否自定义域名
3、分销可否一级多比例？
4、小程序界面是不是自己设计的。
5、有没有优惠券。折上折，  直接折扣
6、部分商品活动折扣
7、根据会员等级 自动显示全部商品8折
8、微信支付是对接你们的，还是对接我们自己的
9、你们的三级分销有风险，怎么规避其他商家使用三级分销对我们只使用一级分销的风险？
10、会员充值、消费、是否有短信通知
11、会员消费、充值、是否支持通知管理员
12、是否只是会员发布产品体验并分享到朋友圈
13、客服设置是官方默认客服，还是允许设置我们自己的，还是无法设置？

## 教程地址

https://www.kancloud.cn/thinkphp/thinkphp5_quickstart

---
更多细节参阅 [README.md](README.md)
