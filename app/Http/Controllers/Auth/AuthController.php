<?php

namespace App\Http\Controllers\Auth;
// namespace App\Classes\Authorization;

use Gate;
use App\Http\Controllers\Controller;
use Dlnsk\HierarchicalRBAC\Authorization;

use App\Models\User;
use App\Models\Code;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CodeController;

class AuthController extends Controller
{

    public function login(Request $request) {
        $validator = $this->validator($request->all());
        // if ($validator->fails()) {          
        //     return "ошибка";
        // };
        $user = User::create($request->all());
        $code = $this->generateCode(8);
        Code::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);
        //Генерируем ссылку и отправляем письмо на указанный адрес
        // $url = url('/').'/auth/activate?id='.$user->id.'&code='.$code;   
        // Mail::send('index', array('url' => $url), function($message) use ($request)
        // {          
        //     $message->to($request->email)->subject('Registration');
        // });
        
        return 'Регистрация прошла успешно, на Ваш email отправлено письмо со ссылкой для активации аккаунта';
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public static function generateCode($length = 10)
    {
        $num = range(0, 9);	   
        $alf = range('a', 'z');	   
        $_alf = range('A', 'Z');   
        $symbols = array_merge($num, $alf, $_alf);   
        shuffle($symbols);	   
        $code_array = array_slice($symbols, 0, (int)$length);  
        $code = implode("", $code_array);
    
        return $code;
    }

    public function logout() {
        return 'logout';
    }

    public function register() {
        return $_GET;
    }

    public function verified() {
        return 'verified';
    }

}
