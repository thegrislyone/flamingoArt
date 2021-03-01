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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('items','App\Http\Controllers\Items\ItemsController@items');
Route::get('categories','App\Http\Controllers\Categories\CategoriesController@categories');

/*
    Authorization
*/

Route::get('auth/login','App\Http\Controllers\Auth\AuthController@login');
Route::post('auth/login','App\Http\Controllers\Auth\AuthController@login');
Route::get('auth/logout','App\Http\Controllers\Auth\AuthController@logout');

Route::get('auth/register','App\Http\Controllers\Auth\AuthController@register');
Route::post('auth/register','App\Http\Controllers\Auth\AuthController@register');