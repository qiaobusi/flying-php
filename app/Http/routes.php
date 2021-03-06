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

//惩恶扬善
Route::any('/web/goodevil/getgood', 'Web\GoodevilController@getGood');
Route::any('/web/goodevil/getevil', 'Web\GoodevilController@getEvil');
Route::any('/web/goodevil/insertarticle', 'Web\GoodevilController@insertArticle');


//manage
Route::controller('/manage/index', 'Manage\IndexController');
Route::controller('/manage/main', 'Manage\MainController');
Route::controller('/manage/user', 'Manage\UserController');
Route::controller('/manage/manager', 'Manage\ManagerController');
