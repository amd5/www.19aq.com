<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];
use think\Route;

Route::rule('c','index/c/php');
// Route::rule('article/:id','index/index/article');
// Route::rule('article/[:id]','index/index/article',['ext'=>'html']);

//文章详情页面URL
Route::get('article-<id>','index/index/article',['ext'=>'html']);
// Route::rule('article-<id>','index/index/article');
//分类页面URL
Route::get('sort-<name>','index/sort/index',['ext'=>'html']);
//标签页面URL
Route::get('tag/:name','index/tag/index',['ext'=>'html']);
// Route::get('Tag-<id>','index/tag/index',['ext'=>'html']);
// dump($name);

//api-webhook
// Route::get('/api/Webhooks/a','api/Webhooks/a1',['ext'=>'html']);
// Route::get('article-<id>','index/index/article',['ext'=>'html']);
// Route::get('article-<id>','index/index/article',['ext'=>'html']);



