<?php

namespace app\index\controller;
// use think\Db;
use think\Controller;
use app\index\model\Article;     //载入前台文章
use app\extra\cache\Redisconfig;    //载入redis配置

class Redis extends Controller
{
    public function index()
    {
        $config = new Redisconfig();
        $Redis = $config->index();
        echo($Redis->get("blog"));
        echo "111";
        // $Redis->set("blog","test");
        // echo $Redis->get("blog");
        // return $this->fetch();
    }

    public function a()
    {
        $config = new Redisconfig();
        $Redis = $config->index();
        // phpinfo();
        $result = Article::get(1);
        // dump($result['content']);
        

        // $Redis->set("article_1",$result['content']);
         $a = $Redis->hgetall("b");
        dump($a);

        // $Redis->set("blog","test");
        // $a = $Redis->hset("b","a","3");
        // $b = $Redis->smembers('blog');
        // print_r($a);
        //如果字段是哈希表中的一个新建字段，并且值设置成功，返回 1 。 如果哈希表中域字段已经存在且旧值已被新值覆盖，返回 0 。
        // dump($a);
        // dump($b['3']);
        // echo $Redis->SMEMBERS("blog");
        // echo $Redis->Getrange("blog");



        // if (redis内容 = 数据库内容 ) {
        //     显示redis文章列表
        // }else{
        //     更新redis内容 为最新数据库内容
        //     显示redis文章列表
        // }
    }


}