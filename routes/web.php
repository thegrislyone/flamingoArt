<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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

Route::get('/profile', function () {
    if (Auth::check()) {
        $userInfo = Auth::user()->only('name', 'avatar', 'nickname', 'banner');
        return view('index')->with('userInfo', $userInfo);
    }
    return view('index');
});

Route::get('/', function () {
    if (Auth::check()) {
        $userInfo = Auth::user()->only('name', 'avatar', 'nickname', 'banner');
        return view('index')->with('userInfo', $userInfo);
    }
    return view('index');
});


// Route::get('/{any}', function () {
//     return view('index');
// })->where('any', '.*');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
