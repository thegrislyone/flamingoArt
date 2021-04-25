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

/* Items */

Route::get('items','App\Http\Controllers\Items\ItemsController@itemsGet');
Route::post('item-load','App\Http\Controllers\Items\ItemsController@itemLoad');
Route::get('user-items','App\Http\Controllers\Items\ItemsController@userItems');
Route::get('single-item','App\Http\Controllers\Items\ItemsController@getSingleItem');

Route::get('add-to-favorite','App\Http\Controllers\Items\ItemsController@addToFavorite');
// Route::get('get-user-favorites','App\Http\Controllers\Items\ItemsController@getUserFavorites');

/* Tags */

Route::get('tags','App\Http\Controllers\Tags\TagsController@tags');

/* Chat */

Route::post('chat/send-message','App\Http\Controllers\Chat\ChatController@sendMessage');
Route::get('chat/get-messages','App\Http\Controllers\Chat\ChatController@getMessages');
Route::get('chat/get-chat-channel','App\Http\Controllers\Chat\ChatController@getChatChannel');
Route::get('chat/generate-chat-id','App\Http\Controllers\Chat\ChatController@generateChatId');

/* Auth */

Route::get('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::post('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::get('auth/logout','App\Http\Controllers\Auth\AuthController@logout');

Route::post('auth/data-check','App\Http\Controllers\Auth\AuthController@unicDataCheck');

Route::get('auth/register','App\Http\Controllers\Auth\AuthController@register');
Route::post('auth/register','App\Http\Controllers\Auth\AuthController@register');

/* Author */

Route::get('auth/get-author','App\Http\Controllers\Auth\AuthController@getAuthor');