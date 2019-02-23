<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods', GoodsController::class);
    $router->resource('/user', UserController::class);
    $router->resource('/wxuser', WeixinController::class);
    $router->resource('/wxmodia', WeixinModiaController::class);
    $router->resource('/wxmaterial',WxmaterialController::class);
    $router->post('/wxmaterial',"\App\Http\Controllers\Weixin\WeixinController@formTest");
});
