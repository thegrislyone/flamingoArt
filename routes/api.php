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

Route::get('items/get-items','App\Http\Controllers\Items\ItemsController@getItems');
Route::post('items/item-load','App\Http\Controllers\Items\ItemsController@itemUpload');
Route::get('items/user-items','App\Http\Controllers\Items\ItemsController@getUserItems');
Route::get('items/single-item','App\Http\Controllers\Items\ItemsController@getSingleItem');
Route::put('items/transition-to-item','App\Http\Controllers\Items\ItemsController@transitionToItem');
Route::delete('items/delete-item','App\Http\Controllers\Items\ItemsController@deleteItem');

/*
 ! SEARCH
*/

Route::get('items/get-search-tips','App\Http\Controllers\Items\ItemsController@getSearchTips');
Route::get('items/get-search-results','App\Http\Controllers\Items\ItemsController@getSearchResults');

/*
 ! FAVORITES
*/

Route::get('items/get-user-favorites','App\Http\Controllers\Items\ItemsController@getUserFavorites'); // * it's useless for now
Route::get('items/add-to-favorite','App\Http\Controllers\Items\ItemsController@addToFavorite');
Route::get('items/remove-from-favorite','App\Http\Controllers\Items\ItemsController@removeFromFavorite');


/* 
    ! TAGS
*/

Route::get('tags/get-tags','App\Http\Controllers\Tags\TagsController@getAllTags');
Route::get('tags/get-popular-tags','App\Http\Controllers\Tags\TagsController@getPopularTags');
Route::get('tags/get-unpopular-tags','App\Http\Controllers\Tags\TagsController@getUnpopularTags');
Route::get('tags/tags-popular-increase','App\Http\Controllers\Tags\TagsController@tagSearch');

/* 
    ! AUTH
*/

Route::get('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::post('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::get('auth/logout','App\Http\Controllers\Auth\AuthController@logout');

Route::post('auth/data-check','App\Http\Controllers\Auth\AuthController@unicDataCheck');

Route::get('auth/register','App\Http\Controllers\Auth\AuthController@register');
Route::post('auth/register','App\Http\Controllers\Auth\AuthController@register');

Route::get('auth/get-author','App\Http\Controllers\Auth\AuthController@getAuthor'); // ! AUTHOR

Route::get('auth/get-users','App\Http\Controllers\Auth\AuthController@getUsers'); // ! USERS
Route::get('auth/ban-user','App\Http\Controllers\Auth\AuthController@banUser'); // ! USERS