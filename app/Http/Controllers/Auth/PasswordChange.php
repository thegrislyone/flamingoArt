<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;

use App\Mail\PasswordChangeEmail;

class PasswordChange extends Controller
{
    
    public function request() {

        $token = Str::random(16);
        $user = Auth::user();

        User::find(Auth::user()->id)->update(['password_update_token' => $token]);


        Mail::to($user->email)->send(new PasswordChangeEmail($user, $token));

        return $token;
        
    }

    public function emailRedirect() {

        $password_update_token = User::find(Auth::user()->id)->password_update_token;

        return redirect('/profile-settings?password-update-token=' . $password_update_token);

    }

    public function change(Request $request) {

        $password = $request['password'];

        if (!$password) {
            return User::find(Auth::user()->id)->update(['password_update_token' => null]);
        }

        User::find(Auth::user()->id)->update(['password' => Hash::make($password), 'password_update_token' => null]);

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Пароль изменён'
            ],
            'status' => true
        ];

        return response()->json($status, 200);

    }

    public function isProcess(Request $request) {

        $token = $request['token'];

        $isProcess = !!User::where('password_update_token', '=', $token)->first();

        $status = [
            'status' => $isProcess
        ];

        return $status;

    }

}
