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
Route::get('article-<id>','index/index/article',['ext'=>'html']);

//api-webhook
// Route::get('/api/Webhooks/a','api/Webhooks/a1',['ext'=>'html']);
// Route::get('article-<id>','index/index/article',['ext'=>'html']);
// Route::get('article-<id>','index/index/article',['ext'=>'html']);



