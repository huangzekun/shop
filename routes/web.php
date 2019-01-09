<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/test','User\UserController@test');
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');
Route::get('/test1','User\UserController@test1');

//中间件
Route::get('/checkcookie','Test\TestController@checkCookie')->middleware('check.cookie');

// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>40300]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


Route::match(['get','post'],'/test/abc','Test\TestController@abc');
//Route::any('/test/abc','Test\TestController@abc');

//注册
Route::get('reg','User\UserController@reg');
Route::post('regadd','User\UserController@regadd');

//登陆
Route::get('login','User\UserController@login');
Route::post('loginadd','User\UserController@loginadd');

//个人
Route::get('center','User\UserController@center');

//购物车
Route::get('/cart','Cart\CartController@index')->middleware('check.login');
Route::get('/cartadd/{goods_id}','Cart\CartController@cartadd')->middleware('check.login');
Route::get('/cartdel/{goods_id}','Cart\CartController@cartdel')->middleware('check.login');
Route::get('/addcart/{goods_id}','Cart\CartController@addcart')->middleware('check.login');
Route::post('/addcart2','Cart\CartController@addcart2')->middleware('check.login');
Route::any('/del/{goods_id}','Cart\CartController@del')->middleware('check.login');

//商品
Route::get('goods','Goods\GoodsController@index');








