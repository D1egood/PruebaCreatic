<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');
//routas mediante jwt
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::delete('user/{id}', 'App\Http\Controllers\UserController@deleteUser');
    Route::get('products', 'App\Http\Controllers\ProductController@getProducts');
    Route::post('products', 'App\Http\Controllers\ProductController@newProducts');
    Route::put('product/{id}', 'App\Http\Controllers\ProductController@editProduct');
    Route::delete('product/{id}', 'App\Http\Controllers\ProductController@deleteProduct');
});