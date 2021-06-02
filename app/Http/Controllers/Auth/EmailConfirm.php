<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use App\Mail\EmailConfirmation;

class EmailConfirm extends Controller
{
    /**
     * * Method, that sending verification email
     * @param request - get parameters for this api-address
     * * returns status and logged user
    */

    public function emailConfirmRequest() {

        $token = Str::random(16);
        $user = Auth::user();

        if ($user['email_verified_at']) {

            $status = [
                'errors' => ['Эта почта уже подтверждена']
            ];

            return response()->json($status, 200);

        }

        User::find(Auth::user()->id)->update(['email_verify_token' => $token]);

        Mail::to($user->email)->send(new EmailConfirmation($user, $token));

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Письмо с подтверждением выслано на вашу почту'
            ],
            'status' => true
        ];

        return response()->json($status, 200);

    }

    /**
     * * Method, that process verify-link
     * @param request - get parameters for this api-address
     * * returns status and logged user
    */

    public function emailConfirm(Request $request) {
        
        $userId = $request['user'];
        $token = $request['token'];

        $target = User::where('email_verify_token', $token);

        if ($target->first()) {

            $target->update(['email_verify_token' => null, 'email_verified_at' => now()]);

            // return redirect()->route('email-confirmed');
        }

        return redirect()->route('index');
        
    }
}
