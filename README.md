## 安装

拉取核心框架

~~~
git clone https://gitee.com/liu21st/framework.git thinkphp
cd thinkphp
git checkout 5.1
~~~

解决trace无法点击的问题

~~~
/thinkphp/tpl/page_trace.tpl     
think_page_trace_open方法
添加
z-index: 999999;
~~~


