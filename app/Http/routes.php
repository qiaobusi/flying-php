<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
 * Api接口路由开始
 */

//测试
Route::any('/web/car/test', 'Web\CarController@test');

//汽车
Route::any('/web/car/getuserinfo', 'Web\CarController@getUserinfo');
Route::any('/web/car/saveuserinfo', 'Web\CarController@saveUserinfo');
Route::any('/web/car/login', 'Web\CarController@login');
Route::any('/web/car/register', 'Web\CarController@register');
Route::any('/web/car/savepassword', 'Web\CarController@savePassword');
Route::any('/web/car/resetpassword', 'Web\CarController@resetPassword');
Route::any('/web/car/checkversion', 'Web\CarController@checkVersion');

//快递
Route::any('/web/express/index', 'Web\ExpressController@index');
//Route::any('/web/express/instantquery', 'Web\ExpressController@instantQuery');
//Route::any('/web/express/numberrecognition', 'Web\ExpressController@numberRecognition');


//manage
Route::any('/manage/index/index', 'Manage\IndexController@index');
Route::any('/manage/index/login', 'Manage\IndexController@login');
Route::any('/manage/index/logout', 'Manage\IndexController@logout');

Route::any('/manage/main/index', 'Manage\MainController@index');
Route::any('/manage/user/index', 'Manage\UserController@index');