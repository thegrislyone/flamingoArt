<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Cookie;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\snRegistration;

use Illuminate\Support\Str;

class SocialAuth extends Controller
{

    /* GOOGLE */
    
    public function googleIndex(Request $request) {

        setcookie('login', $request['login'], time() + (86400 * 30), "/");

        return Socialite::driver('google')->redirect();

    }

    public function googleCallback() {

        $user = Socialite::driver('google')->user();

        $this->userHandle($user);

        return redirect()->route('index');

    }

    /* VKONTAKTE */

    public function vkIndex(Request $request) {
        
        setcookie('login', $request['login'], time() + (86400 * 30), "/");

        return Socialite::driver('vkontakte')->redirect();

    }

    public function vkCallback() {

        $user = Socialite::driver('vkontakte')->user();

        $this->userHandle($user);

        return redirect()->route('index');
        
    }

    /* DATA HANDLING */

    public function userHandle($user) {

        $userNickname = $_COOKIE['login'];

        unset($_COOKIE['login']);

        $userName = $user->getName();
        $userAvatar = $user->getAvatar();
        $userEmail = $user->getEmail();

        if (User::where('email', $userEmail)->first()) {

            $user = User::where('email', $userEmail)->first();
            
            $user->login = $userNickname;
            $user->email = $userEmail;
            $user->avatar = $userAvatar;

            $user->save();

            Auth::login($user);

        } else {

            $password = Str::random(6);

            $user = User::create(
                [
                    'name' => $userName,
                    'email' => $userEmail,
                    'login' => $userNickname,
                    'password' => Hash::make($password),
                    'views' => 0,
                    'likes' => 0,
                    'avatar' => $userAvatar
                ]
            );

            Mail::to($user->email)->send(new snRegistration($userNickname, $password));
            
            Auth::login($user);

        } 

    }

}
