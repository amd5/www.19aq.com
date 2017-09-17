ThinkPHP 5.0 学习日志
===============

[![Blog From](http://www.19aq.com/)]
[![c32's blog](http://www.19aq.com/)]

##2017-9-17 18:35:19
学习了以`Model`模型查询和`Db`类查询
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
