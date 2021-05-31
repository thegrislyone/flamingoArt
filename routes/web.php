<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Items\FavoritesModel;
use App\Models\Items\ItemsModel;


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

    function getUserInfo() {

        $userInformation = Auth::user()->only('id', 'name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes', 'is_admin', 'banned');
        // $userInfo['favorites'] = [];
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

Route::get('/', function () {

    if (Auth::check()) {

        return view('index')->with('userInfo', getUserInfo());
    }

    return view('index');

})->name('index');

Route::get('/', function () {
    
    return view('index')->with(['userInfo' => getUserInfo(), 'message' => 'Почта успешно подтверждена']);

})->name('email-confirmed');

Route::get('/profile', function () {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/profile/{author_id}', function ($author_id = '') {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/upload-item', function () {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/item/{item_id}', function ($item_id = '') {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/search', function () {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

Route::get('/admin-panel', function () {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});