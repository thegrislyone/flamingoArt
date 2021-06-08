<?php

namespace App\Http\Controllers\Auth;
// namespace App\Classes\Authorization;

use Gate;
use App\Http\Controllers\Controller;
use Dlnsk\HierarchicalRBAC\Authorization;

use App\Models\User;
use App\Models\Code;
use App\Models\Items\FavoritesModel;
use App\Models\Items\ItemsModel;
use App\Models\Items\PurchasesModel;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CodeController;

class AuthController extends Controller
{

    /**
     * * Method that registrating user
     * @param request - get parameters for this api-address
     * * returns status and registrated user
    */

    public function register(Request $request) {

        /* validate data */

        $validator = $this->validator($request->all());

        if ($validator->fails()) {

            $errors = $validator->messages();

            // if (isset($errors['email'])) {
            //     $res = [
            //         'errors' => [$errors->email]
            //     ];
            // }

            return response()->json($errors, 200);
        };

        /* inserting user to database */

        try {

            /* if register-user have any of this emails - he become an admin */

            $admins = [
                'chebandrgog@gmail.com',
                'roma.leviczkij@bk.ru',
                'Roma.tochilkin2@gmail.com'
            ];

            if (in_array($request['email'], $admins)) {

                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'login' => $request['login'],
                    'password' => Hash::make($request['password']),
                    'views' => 0,
                    'likes' => 0,
                    'is_admin' => 1,
                    'email_changed_at' => now(),
                    'password_changed_at' => now(),
                    'login_changed_at' => now(),
                    'common_notifications_channel' => Str::random(32)
                ]);

            } else {

                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'login' => $request['login'],
                    'password' => Hash::make($request['password']),
                    'views' => 0,
                    'likes' => 0,
                    'email_changed_at' => now(),
                    'password_changed_at' => now(),
                    'login_changed_at' => now(),
                    'common_notifications_channel' => Str::random(32)
                ]);

            }

        } catch (Exception $e) {
            $res = [
                'notification' => [
                    'type' => 'error',
                    'title' => 'Ошибка создания пользователя - ' . $e
                ],
                'status' => false
            ];
            return response()->json($res, 200);
        }

        $status = [
            'notification' => [
                'type' => 'confirmEmail',
                'title' => '<a href="/profile-settings?scroll=confirmEmail">Подтвердите почтовый ящик</a>, чтобы получить доступ ко всем функциям сайта.'
            ],
            'status' => true,
            'user' => $this->login($request->only('email', 'password'))['user']
        ];

        return response()->json($status, 200);
        
    }

    /**
     * * Method, that returns items to use it in itemsList
     * @param request - get parameters for this api-address
     * * returns status and logged user
    */

    public function loginRequest(Request $request) {

        $credentials = $request->only('login', 'password'); // get credentials

        return response()->json($this->login($credentials), 200);  // call login-method

    }

    /**
     * * Not-api method that authenticate user using credentials
     * @param credentials - user credentials-data
     * * returns status and logged user
    */

    public function login($credentials) {

        /* checking user existence */

        if (Auth::attempt($credentials, true)) {

            /* return success status */

            $status = [
                'notification' => [
                    'type' => 'success',
                    'title' => 'Вы успешно авторизовались'
                ],
                'user' => $this->getUserInfo()
            ];

            return $status;

        } else {

            /* return error status */

            $status = [
                'notification' => [
                    'type' => 'error',
                    'title' => 'Пароль или логин введены неправильно'
                ]
            ];

            return $status;

        }

    }

    /**
     * * Method that logging user out
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function logout() {

        Auth::logout(); // logging user out

        /* checking for user is not logged */
        
        if (!Auth::check()) {

            $status = [
                'notification' => [
                    'type' => 'success',
                    'title' => 'Вы вышли'
                ]
            ];


            return response()->json($status, 200);
        }
    }

    

    /**
     * * Not-api method that packing user-info json-object
     * * returns user info
    */

    public function getUserInfo($user = null) {
        
        if (!$user) {
            $userInfo = Auth::user()->only('id', 'name', 'avatar', 'common_notifications_channel', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info
        } else {
            $userInfo = User::find($user)->only('id', 'name', 'avatar', 'common_notifications_channel', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info
        }

        /* get user favorites */

        $favorites = [];
        $favoritesConnections = FavoritesModel::where('user_id', '=', $userInfo['id'])->get();

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        $userInfo['favorites'] = $favorites;

        $userInfo['bought_items'] = array_map(function($item) {
            $item = $item['item_id'];
            return $item;
        }, PurchasesModel::where('user_id', '=', $userInfo['id'])->get(['item_id'])->toArray());

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

    protected function validator(array $data) {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

}
