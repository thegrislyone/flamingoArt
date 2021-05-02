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

/*
    ! ITEMS
*/

Route::get('items/get-items','App\Http\Controllers\Items\ItemsController@itemsGet');
Route::post('items/item-load','App\Http\Controllers\Items\ItemsController@itemLoad');
Route::get('items/user-items','App\Http\Controllers\Items\ItemsController@userItems');
Route::get('items/single-item','App\Http\Controllers\Items\ItemsController@getSingleItem');
Route::put('items/transition-to-item','App\Http\Controllers\Items\ItemsController@transitionToItem');

/*
 ! favorites
*/

Route::get('items/get-user-favorites','App\Http\Controllers\Items\ItemsController@getUserFavorites'); // * it's useless for now
Route::get('items/add-to-favorite','App\Http\Controllers\Items\ItemsController@addToFavorite');
Route::get('items/remove-from-favorite','App\Http\Controllers\Items\ItemsController@removeFromFavorite');


/* 
    ! TAGS
*/

Route::get('tags/get-tags','App\Http\Controllers\Tags\TagsController@getPopularTags');

/* 
    ! AUTH
*/

Route::get('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::post('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::get('auth/logout','App\Http\Controllers\Auth\AuthController@logout');

Route::post('auth/data-check','App\Http\Controllers\Auth\AuthController@unicDataCheck');

Route::get('auth/register','App\Http\Controllers\Auth\AuthController@register');
Route::post('auth/register','App\Http\Controllers\Auth\AuthController@register');

/* 
    ! AUTHOR
*/

Route::get('auth/get-author','App\Http\Controllers\Auth\AuthController@getAuthor');