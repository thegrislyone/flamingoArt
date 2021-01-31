<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return 'login';
    }

    public function register() {
        return 'register';
    }

    public function verified() {
        return 'verified';
    }
}
