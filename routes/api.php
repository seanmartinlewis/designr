<?php

use Illuminate\Http\Request;
use App\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

Use App\Order;



Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('recover', 'AuthController@recover');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('orders/{order}', 'OrderController@show');
    Route::get('orders', 'OrderController@index');
    Route::post('orders', 'OrderController@store');
    Route::put('orders/{order}', 'OrderController@update');
    Route::delete('orders/{order}', 'OrderController@delete');
});
