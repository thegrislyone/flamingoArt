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
                    'login_changed_at' => now()
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
                    'login_changed_at' => now()
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
                'title' => '<a href="#">Подтвердите почтовый ящик</a>, чтобы получить доступ ко всем функциям сайта.'
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
                    'title' => 'Вы успешно вышли'
                ]
            ];


            return response()->json($status, 200);
        }
    }

    /**
     * * Method, that returns user by id
     * @param request - get parameters for this api-address
     * * returns user
    */

    public function getAuthor(Request $request) {

        $authorId = $request['author_id'];  // get author id

        $author = User::find($authorId);    // select user

        return response()->json($author, 200);

    }

    /**
     * * Method, that returns users for admin-panel
     * * returns users
    */

    public function getUsers() {

        return User::get();

    }

    /**
     * * Method, that ban user
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function banUser(Request $request) {

        $user_id = $request['user_id'];
        $user = User::find($user_id);
        $isBanned = ($user['banned']) ? true : false;

        if ($user['is_admin']) {
            $status = [
                'errors' => ['нельзя забанить админа']
            ];
            return response()->json($status, 200);
        }

        $user->update(['banned' => ($isBanned) ? null : 1]);

        $userItems = ItemsModel::where('author', '=', $user_id)->update(['banned' => ($isBanned) ? null : 1]);

        return $user;

    }

    /**
     * * Method that checking for uniqueness login and email while registration
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function unicDataCheck(Request $request) {

        /* check for checking parameter */

        if ($request['email']) {
            
            $email = $request->all();

            /* check if email already engaged by standart validator */

            $validator =  Validator::make($email, [
                'email' => 'unique:users'
            ]);

            if ($validator->fails()) {
                $errors = [
                    'errors' => ['Этот адрес электронной почты уже занят']
                ];
                return response()->json($errors, 200);
            } else {
                $success = [
                    'success' => 'Этот адрес электронной не занят'
                ];
                return response()->json($success, 200);
            }

        } elseif ($request['login']) {

            $login = $request->all();

            /* check if login already engaged by standart validator */

            $validator =  Validator::make($login, [
                'login' => 'unique:users'
            ]);

            if ($validator->fails()) {
                $errors = [
                    'errors' => ['Этот никнейм уже занят']
                ];
                return response()->json($errors, 200);
            } else {
                $success = [
                    'success' => 'Этот никнейм не занят'
                ];
                return response()->json($success, 200);
            }
        } else {

            /* if parameter is not need to be checked */

            $success = [
                'success' => 'Все правильно'
            ];

            return response()->json($success, 200);

        }
    }

    /**
     * * Method that sets user socials
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function setSocials(Request $request) {

        $vkLink = ($request['vkLink']) ? $request['vkLink'] : null;
        $twitterLink = ($request['vkLink']) ? $request['twitterLink'] : null;
        $instagramLink = ($request['instagramLink']) ? $request['instagramLink'] : null;
        $facebookLink = ($request['facebookLink']) ? $request['facebookLink'] : null;

        User::find(Auth::user()->id)->update([
            'vkontakte' => $vkLink,
            'facebook' => $facebookLink,
            'twitter' => $twitterLink,
            'instagram' => $instagramLink,
        ]);

        $status = [
            'success' => true
        ];

        return response()->json($status, 200);
        
    }

    /**
     * * Method that sets user avatar
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function setAvatar(Request $request) {

        $avatar = $request['avatar'];

        $avatarSrc = Storage::put('public/avatars', $avatar);

        $avatarSrcArray = explode('/', $avatarSrc);
        
        array_shift($avatarSrcArray);
        array_unshift($avatarSrcArray, 'storage');

        User::find(Auth::user()->id)->update([
            'avatar' => '/' . implode('/', $avatarSrcArray)
        ]);

        $status = [
            'success' => true
        ];

        return response()->json($status, 200);
        
    }

    /**
     * * Method that sets user banner
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function setBanner(Request $request) {

        $banner = $request['banner'];

        $bannerSrc = Storage::put('public/banners', $banner);

        $bannerSrcArray = explode('/', $bannerSrc);
        
        array_shift($bannerSrcArray);
        array_unshift($bannerSrcArray, 'storage');

        User::find(Auth::user()->id)->update([
            'banner' => '/' . implode('/', $bannerSrcArray)
        ]);

        $status = [
            'success' => true
        ];

        return response()->json($status, 200);
        
    }

    public function setLogin(Request $request) {

        $login = $request['login'];

        User::find(Auth::user()->id)->update([
            'login' => $login,
            'login_changed_at' => now()
        ]);

        $user = $this->getUserInfo();
        $user['login'] = $login;
        $user['login_changed_at'] = now();

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Никнейм изменён'
            ],
            'status' => true,
            'user' => $user
        ];

        return response()->json($status, 200);

    }

    /**
     * * Not-api method that packing user-info json-object
     * * returns user info
    */

    public function getUserInfo() {
        
        $userInfo = Auth::user()->only('id', 'name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info

        /* get user favorites */

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

    protected function validator(array $data) {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

}
