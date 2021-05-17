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

function getUserInfo() {
    $userInfo = Auth::user()->only('id', 'name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes', 'is_admin', 'banned');
    // $userInfo['favorites'] = [];
    $favorites = [];


    $favoritesConnections = FavoritesModel::where('user_id', '=', $userInfo['id'])->get();

    foreach ($favoritesConnections as $favorite) {
        array_push($favorites, ItemsModel::find($favorite['item_id']));
    }

    $userInfo['favorites'] = $favorites;

    if ($userInfo['is_admin']) {
        $userInfo['is_admin'] = true;
    } else {
        $userInfo['is_admin'] = false;
    }

    if ($userInfo['banned']) {
        $userInfo['banned'] = true;
    } else {
        $userInfo['banned'] = false;
    }

    return $userInfo;
}

Route::get('/', function () {
    if (Auth::check()) {
        // $userInfo = Auth::user()->only('name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        // return view('index')->with('userInfo', $userInfo);
        return view('index')->with('userInfo', getUserInfo());
    }
    return view('index');
});

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

// Route::get('/{any}', function () {
//     return view('index');
// })->where('any', '.*');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
