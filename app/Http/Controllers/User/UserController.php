<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Items\ItemsModel;
use App\Models\Items\FavoritesModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

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

        $user = Auth::user();
        $user['avatar'] = '/' . implode('/', $avatarSrcArray);

        $status = [
            'success' => true,
            'user' => $user,
            'notification' => [
                'type' => 'success',
                'title' => 'Аватар успешно загружен'
            ]
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

        $user = Auth::user();
        $user['banner'] = '/' . implode('/', $bannerSrcArray);

        $status = [
            'success' => true,
            'user' => $user,
            'notification' => [
                'type' => 'success',
                'title' => 'Баннер успешно загружен'
            ]
        ];

        return response()->json($status, 200);
        
    }

    /**
     * * Method that sets user nickname
     * @param request - get parameters for this api-address
     * * returns status and notification
    */

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
        
        $userInfo = Auth::user()->only('id', 'name', 'avatar', 'common_notifications_channel', 'login', 'banner', 'created_at', 'views', 'likes', 'banned', 'is_admin', 'vkontakte', 'facebook', 'twitter', 'instagram', 'email', 'email_verified_at', 'email_changed_at', 'password_changed_at', 'login_changed_at');  // selecting user info

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

    /**
     * * Method that returns items list by author id
     * @param request - get parameters for this api-address
     * * returns items list
    */

    public function getUserItems(Request $request) {

        /* checking for authorization */

        if (!Auth::check() && !$request['author_id']) {
            $res = [
                'errors' => ['Вы не авторизованы']
            ];
            return response()->json($res, 200);
        }

        /* get author id, it's depends of who is owner */

        $userId;

        if ($request['author_id']) {
            $userId = $request['author_id'];
        } else {
            $userId = Auth::user()->only('id')['id'];
        }

        $items = ItemsModel::where('author', '=', $userId)->get()->toArray(); // get items by author

        /* set thumbnail */

        $thumbnail = 'thumbnail_items-list';

        $items = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $items);

        return response()->json($items, 200);

    }

}
