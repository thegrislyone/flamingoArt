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

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CodeController;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function getUserInfo() {
        
        $userInfo = Auth::user()->only('id', 'name', 'avatar', 'login', 'banner', 'created_at', 'views', 'likes');
        $favorites = [];


        $favoritesConnections = FavoritesModel::where('user_id', '=', $userInfo['id'])->get();

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        $userInfo['favorites'] = $favorites;

        return $userInfo;
    }

    public function loginRequest(Request $request) {

        $credentials = $request->only('login', 'password');

        return $this->login($credentials);

    }

    public function getAuthor(Request $request) {

        $authorId = $request['author_id'];

        $author = User::find($authorId);

        return response()->json($author, 200);

    }

    public function login($credentials) {

        if (Auth::attempt($credentials, true)) {

            $success = [
                'success' => 'Вы успешно авторизовались',
                'user' => $this->getUserInfo()
            ];

            return $success;

        } else {

            $errors = [
                'errors' => ['Пароль или логин введены неправильно']
            ];

            return $errors;

        }

    }

    public function unicDataCheck(Request $request) {
        if ($request['email']) {
            
            $email = $request->all();

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
            $success = [
                'success' => 'Все правильно'
            ];
            return response()->json($success, 200);
        }
    }

    public function compactErrorsResponse($messages) {
        $errors = [];
        foreach($messages as $errorArray) {
            foreach($errorArray as $error) {
                array_push($errors, $error);
            }
        }
        return $errors;
    }

    public function logout() {
        Auth::logout();
        if (!Auth::check()) {
            $success = [
                'success' => 'Вы успешно вышли'
            ];
            return response()->json($success, 200);
        }
    }

    public function register(Request $request) {

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

        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'login' => $request['login'],
                'password' => Hash::make($request['password']),
                'views' => 0,
                'likes' => 0
            ]);
        } catch (Exception $e) {
            $res = [
                'errors' => ['Ошибка создания пользователя, ' . $e]
            ];
            return response()->json($res, 200);
        }

        
        $code = $this->generateCode(8);
        Code::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        $success = [
            'success' => 'Вы успешно зарегестрированы'
        ];

        $success['user'] = $this->login($request->only('email', 'password'))['user'];

        return response()->json($success, 200);
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    public static function generateCode($length = 10) {
        $num = range(0, 9);	   
        $alf = range('a', 'z');	   
        $_alf = range('A', 'Z');   
        $symbols = array_merge($num, $alf, $_alf);   
        shuffle($symbols);	   
        $code_array = array_slice($symbols, 0, (int)$length);  
        $code = implode("", $code_array);
    
        return $code;
    }

}
