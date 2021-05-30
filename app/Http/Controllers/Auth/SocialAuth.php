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

    /* GOOGLE */
    
    public function googleIndex() {

        return Socialite::driver('google')->redirect();

    }

    public function googleCallback() {

        $user = Socialite::driver('google')->user();

        $this->userHandle($user);

        return redirect()->route('index');

    }

    /* VKONTAKTE */

    public function vkIndex() {

        return Socialite::driver('vkontakte')->redirect();

    }

    public function vkCallback() {

        $user = Socialite::driver('vkontakte')->user();

        $this->userHandle($user);

        return redirect()->route('index');
        
    }

    /* FACEBOOK */

    public function facebookIndex() {

        return Socialite::driver('facebook')->redirect();

    }

    public function facebookCallback() {

        $user = Socialite::driver('facebook')->user();

        $this->userHandle($user);

        return redirect()->route('index');
        
    }

    /* DATA HANDLING */

    public function userHandle($user) {

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

    }

}
