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
Route::get('/cart','Cart\CartController@index');
Route::get('/cartadd/{goods_id}','Cart\CartController@cartadd');
Route::get('/cartdel/{goods_id}','Cart\CartController@cartdel');
Route::get('/addcart/{goods_id}','Cart\CartController@addcart');
Route::post('/addcart2','Cart\CartController@addcart2');
Route::any('/del/{goods_id}','Cart\CartController@del');

//商品
Route::get('goods','Goods\GoodsController@index');

//分页搜索
Route::get('pay','Goods\GoodsController@pay');
Route::post('payadd','Goods\GoodsController@payadd');

//生成订单
Route::any('/order/add','Order\OrderController@add');

//我的订单
Route::any('/order/myorder','Order\OrderController@myorder');

//支付成功
Route::any('/order/good/{order_id}','Order\OrderController@good');



//支付
Route::get('/pay/alipay/pay/{order_id}','Pay\alipayController@pay');


//同步异步
Route::post('pay/alipay/notify','Pay\alipayController@aliNotify');//异步
Route::get('pay/alipay/return','Pay\alipayController@aliReturn'); //同步

//定时删除
Route::any('/crontab/delete','Crontab\IndexController@delete');


Route::middleware(['log.click'])->group(function(){
    Route::any('/test/abc','Test\TestController@abc');
});

Route::any('/movie','Movie\IndexController@index');
Route::any('/movie/buy/{pos}/{status}','Movie\IndexController@buy');





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//微信
Route::get('/weixin/test','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/info/{openid}','Weixin\WeixinController@getUserInfo');
Route::get('/weixin/menu','Weixin\WeixinController@menu');
Route::get('/weixin/qunfa','Weixin\WeixinController@qunfa');


Route::get('/form/show','Weixin\WeixinController@formShow');     //表单测试
Route::post('/form/test','Weixin\WeixinController@formTest');     //表单测试




Route::get('/weixin/material/list','Weixin\WeixinController@materialList');     //获取永久素材列表
Route::get('/weixin/material/upload','Weixin\WeixinController@upMaterial');     //上传永久素材
Route::post('/weixin/material','Weixin\WeixinController@materialTest');     //创建菜单
//Route::post('/weixin/material','Weixin\WeixinController@materialTest');     //创建菜单


Route::get('/kefu/show/{id}','Weixin\WeixinController@kefu');     //客服测试
Route::get('/kefu/chat','Weixin\WeixinController@chat');     //聊天测试
Route::post('/chat/msg','Weixin\WeixinController@chatmsg');  //客服发送消息


//微信支付
Route::get('/weixin/pay/test/{order_id}','Weixin\PayController@test');     //微信支付测试
Route::post('/weixin/pay/notice','Weixin\PayController@notice');     //微信支付通知回调
Route::get('/weixin/pay/success','Weixin\PayController@success');

Route::get('/weixin/wxlogin','Weixin\PayController@wxlogin');

