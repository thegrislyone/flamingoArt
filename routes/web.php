<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Items\FavoritesModel;
use App\Models\Items\ItemsModel;
use App\Models\Items\PurchasesModel;


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

if (!function_exists('getUserInfo')) {

    function getUserInfo($user = null) {

        if (!$user) {
            $userInformation = Auth::user()->only('id', 'name', 'avatar', 'common_notifications_channel', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info
        } else {
            $userInformation = User::find($user)->only('id', 'name', 'avatar', 'common_notifications_channel', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info
        }

        $favorites = [];
    
    
        $favoritesConnections = FavoritesModel::where('user_id', '=', $userInformation['id'])->get();
    
        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }
    
        /* set thumbnail */
    
        $thumbnail = 'thumbnail_item-page';
    
        $favorites = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $favorites);
    
        $userInformation['favorites'] = $favorites;

        $userInformation['bought_items'] = array_map(function($item) {
            $item = $item['item_id'];
            return $item;
        }, PurchasesModel::where('buyer_id', '=', $userInformation['id'])->get(['item_id'])->toArray());
    
        if ($userInformation['is_admin']) {
            $userInformation['is_admin'] = true;
        } else {
            $userInformation['is_admin'] = false;
        }
    
        if ($userInformation['banned']) {
            $userInformation['banned'] = true;
        } else {
            $userInformation['banned'] = false;
        }
    
        return $userInformation;

    }

}

// Route::get('/', function () {
    
//     return view('index')->with(['userInfo' => getUserInfo(), 'message' => 'Почта успешно подтверждена']);

// })->name('email-confirmed');

Route::get('/', function () {

    if (Auth::check()) {

        return view('index')->with('userInfo', getUserInfo());
    }

    return view('index');

})->name('index');

Route::get('/profile', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/profile-settings', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/profile/{author_id}', function ($author_id = '') {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/upload-item', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/item/{item_id}', function ($item_id = '') {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/search', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/admin-panel', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/chat', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/my-deals', function () {
    if (Auth::check()) {
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

/* SOCIAL NETWORK AUTHENTIFICATION */

// through vk

Route::group([], function () {
    Route::get('/vk/auth', 'App\Http\Controllers\Auth\SocialAuth@vkIndex');
    Route::get('/vk/auth/callback', 'App\Http\Controllers\Auth\SocialAuth@vkCallback');
});

// through google

Route::group([], function () {
    Route::get('/google/auth', 'App\Http\Controllers\Auth\SocialAuth@googleIndex');
    Route::get('/google/auth/callback', 'App\Http\Controllers\Auth\SocialAuth@googleCallback');
});

// through facebook

Route::group([], function () {
    Route::get('/facebook/auth', 'App\Http\Controllers\Auth\SocialAuth@facebookIndex');
    Route::get('/facebook/auth/callback', 'App\Http\Controllers\Auth\SocialAuth@facebookCallback');
});