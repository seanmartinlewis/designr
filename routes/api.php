<?php

use Illuminate\Http\Request;

Use App\Order;

Route::post('/user/login', 'UserController@login');
Route::get('/user/getByToken', 'UserController@getByToken');

Route::resource('/todo', 'TodoController');
Route::resource('/user', 'UserController');

Route::get('orders', 'OrderController@index');
Route::get('orders/{order}', 'OrderController@show');
Route::post('orders', 'OrderController@store');
Route::put('orders/{order}', 'OrderController@update');
Route::delete('orders/{order}', 'OrderController@delete');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
