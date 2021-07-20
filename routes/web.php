<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'check.dirty'], function(){
    Route::resource('/product', 'ProductController');
});

Route::get('/', function () {
    return view('welcome');
});




Route::post('/signup', 'AuthController@signup');
Route::post('/login', 'AuthController@login');
Route::group(['middleware'=>'auth:api'], function(){ // 對應到config/auth裡的guard下定義的驗證方式
    Route::get('/user', 'AuthController@user');
    Route::get('/logout', 'AuthController@logout');
    Route::post('carts/checkout', 'CartController@checkout');
    Route::resource('/carts', 'CartController');
    Route::resource('/cart-items', 'CartItemsController');
});