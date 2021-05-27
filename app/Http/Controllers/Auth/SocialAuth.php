<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class SocialAuth extends Controller
{

    public function index() {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function callback() {

        $user = Socialite::driver('vkontakte')->user();

        $userNickname = $user->getNickname();
        $userName = $user->getName();
        $userAvatar = $user->getAvatar();
        $userEmail = $user->getEmail();

        try {

            $createdUser = User::firstOrCreate(
                ['login' => $userNickname],
                [
                    'name' => $userName,
                    'email' => $userEmail,
                    'login' => $userNickname,
                    'password' => Hash::make('default'),
                    'views' => 0,
                    'likes' => 0,
                    'avatar' => $userAvatar
                ]
            );

        } catch(Exception $e) {

            if (User::where('email', $userEmail)->first()) {
                return "почта уже занята";
            }

        }

        if ($createdUser) {

            $createdUser->login = $userNickname;
            $createdUser->email = $userEmail;
            $createdUser->avatar = $userAvatar;

            $createdUser->save();

        }

        Auth::login($createdUser);

        return redirect()->route('index');
    }

}
