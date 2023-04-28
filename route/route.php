<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// Route::get('think', function () {
//     return 'hello,ThinkPHP5!';
// });

Route::get('hello/:name', 'index/hello');
Route::rule('article-<id>','index/index/article','GET|POST',['ext'=>'html']);
Route::get('sort-<id>','index/index/dataList?type=sort',['ext'=>'html']);
Route::get('tag-<id>','index/index/dataList?type=tag',['ext'=>'html']);
Route::get('record-<id>','index/index/dataList?type=record',['ext'=>'html']);
Route::get('search','index/index/dataList?type=search');
    
    
    Route::rule('news/<id>','index/News/article','GET|POST',['ext'=>'html']);
    Route::rule('news','index/News/dataList?type=sort','GET|POST');
    Route::rule('games/<id>','index/Games/article','GET|POST',['ext'=>'html']);
    Route::rule('games','index/Games/dataList?type=sort','GET|POST');



#webhook
Route::rule('pull','admin/login/pull','GET|POST',['ext'=>'html']);
Route::rule('pullf','admin/login/pullf','GET|POST',['ext'=>'html']);

return [

];
