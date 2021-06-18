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
    ! ITEMS CONTROLLER
*/

Route::get('items/get-items','App\Http\Controllers\Items\ItemsController@getItems');
Route::post('items/item-load','App\Http\Controllers\Items\ItemsController@itemUpload');
Route::get('items/single-item','App\Http\Controllers\Items\ItemsController@getSingleItem');
Route::put('items/transition-to-item','App\Http\Controllers\Items\ItemsController@transitionToItem');
Route::delete('items/delete-item','App\Http\Controllers\Items\ItemsController@deleteItem');

/*
    ! PURCHASE CONTROLLER
*/

Route::post('items/buy-item','App\Http\Controllers\Items\PurchasesController@buyItem');
Route::get('items/get-user-bought-items','App\Http\Controllers\Items\PurchasesController@getUserBoughtItems');
Route::get('items/download-item','App\Http\Controllers\Items\PurchasesController@downloadItem');

/*
    ! SEARCH CONTROLLER
*/

Route::get('search/get-search-tips','App\Http\Controllers\Search\SearchController@getSearchTips');
Route::get('search/get-search-results','App\Http\Controllers\Search\SearchController@getSearchResults');

/*
    ! FAVORITES CONTROLLER
*/


Route::get('items/favorite/add-to-favorite','App\Http\Controllers\Items\FavoritesController@addToFavorite');
Route::get('items/favorite/remove-from-favorite','App\Http\Controllers\Items\FavoritesController@removeFromFavorite');
Route::get('items/favorite/get-user-favorites','App\Http\Controllers\Items\FavoritesController@getUserFavorites'); // * it's useless for now


/* 
    ! TAGS CONTROLLER
*/

Route::get('tags/get-tags','App\Http\Controllers\Tags\TagsController@getAllTags');
Route::get('tags/get-popular-tags','App\Http\Controllers\Tags\TagsController@getPopularTags');
Route::get('tags/get-unpopular-tags','App\Http\Controllers\Tags\TagsController@getUnpopularTags');
Route::get('tags/tags-popular-increase','App\Http\Controllers\Tags\TagsController@tagSearch');

/* 
    ! AUTH CONTROLLER
*/

Route::get('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::post('auth/login','App\Http\Controllers\Auth\AuthController@loginRequest');
Route::get('auth/logout','App\Http\Controllers\Auth\AuthController@logout');

Route::get('auth/register','App\Http\Controllers\Auth\AuthController@register');
Route::post('auth/register','App\Http\Controllers\Auth\AuthController@register');

/* 
    ! EMAIL CONFIRM CONTROLLER
*/

Route::get('auth/email/email-confirm-request','App\Http\Controllers\Auth\EmailConfirm@emailConfirmRequest');
Route::get('auth/email/email-confirm','App\Http\Controllers\Auth\EmailConfirm@emailConfirm')->name('confirm-email');

/* 
    ! PASSWORD CHANGE CONTROLLER
*/

Route::get('auth/password/password-change-request','App\Http\Controllers\Auth\PasswordChange@request');
Route::get('auth/password/password-change-redirect','App\Http\Controllers\Auth\PasswordChange@emailRedirect')->name('password-change-redirect');
Route::post('auth/password/password-change','App\Http\Controllers\Auth\PasswordChange@change');
Route::post('auth/password/is-password-change-process','App\Http\Controllers\Auth\PasswordChange@isProcess');

/* 
    ! USER CONTROLLER
*/

Route::get('user/get-author','App\Http\Controllers\User\UserController@getAuthor');
Route::get('user/get-users','App\Http\Controllers\User\UserController@getUsers');
Route::get('user/ban-user','App\Http\Controllers\User\UserController@banUser');
Route::post('user/data-check','App\Http\Controllers\User\UserController@unicDataCheck');

Route::post('user/set-user-socials', 'App\Http\Controllers\User\UserController@setSocials');
Route::post('user/set-user-avatar', 'App\Http\Controllers\User\UserController@setAvatar');
Route::post('user/set-user-banner', 'App\Http\Controllers\User\UserController@setBanner');
Route::post('user/set-user-login', 'App\Http\Controllers\User\UserController@setLogin');

Route::get('user/user-items','App\Http\Controllers\User\UserController@getUserItems');

/* 
    ! RECOMENDATIONS CONTROLLER
*/

Route::post('user/recomendations/set-recomendation','App\Http\Controllers\User\UserRecomendations@setRecomendations');
Route::get('user/recomendations/get-recomendation','App\Http\Controllers\User\UserRecomendations@getRecomendations');

/* 
    ! MESSAGES CONTROLLER
*/

Route::post('chat/messages/send-message','App\Http\Controllers\Chat\MessagesController@sendMessage');
Route::get('chat/messages/get-chat-messages','App\Http\Controllers\Chat\MessagesController@getChatMessages');
Route::post('chat/messages/check-message','App\Http\Controllers\Chat\MessagesController@checkMessage');

/* 
    ! CHAT CONTROLLER
*/

Route::get('chat/get-user-chats','App\Http\Controllers\Chat\ChatController@getUserChats');
Route::get('chat/get-chat-channel','App\Http\Controllers\Chat\ChatController@getChat');
Route::get('chat/chat-init','App\Http\Controllers\Chat\ChatController@chatInit');

/* 
    ! NOTIFICATIONS CONTROLLER
*/

Route::get('notifications/get-notifications','App\Http\Controllers\Notifications\NotificationsController@getNotifications');
Route::get('notifications/check-notification','App\Http\Controllers\Notifications\NotificationsController@checkNotification');

/* 
    ! DOCUMENTS CONTROLLER
*/

Route::get('documents/download-privacy','App\Http\Controllers\Documents\DocumentsController@downloadPrivacy');

/* 
    ! FEEDBACKS CONTROLLER
*/

Route::post('feedbacks/send-feedback','App\Http\Controllers\User\FeedbacksController@sendFeedback');
Route::get('feedbacks/get-feedbacks','App\Http\Controllers\User\FeedbacksController@getFeedbacks');