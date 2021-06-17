<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserRecomendationsModel;
use App\Models\Tags\TagsModel;
use App\Models\Items\ItemsModel;
use App\Models\Tags\UserTagsModel;

use Illuminate\Support\Facades\Auth;

class UserRecomendations extends Controller
{

    public function getRecomendations(Request $request) {

        if (Auth::check()) {

            $recomendations = [
                'recomendations' => UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->get()
            ];

            return response()->json($recomendations, 200);

        } else {

            $recomendations = [
                'recomendations' => NULL
            ];

            return response()->json($recomendations, 200);;
        }

        

    }
    
    public function setRecomendations(Request $request) {

        if (!Auth::check()) {
            
            $status = [
                'status' => false
            ];

            return response()->json($status, 200);

        }

        $item_id = $request['item_id'];
        $tags = UserTagsModel::where('item_id', '=', ItemsModel::find($item_id)['tags'])->get();

        foreach ($tags as $key=>$tag) {

            $alreadyIs = !!count(UserRecomendationsModel::where('tag_id', '=', $tag['id'])->get());

            if ($alreadyIs) {
                continue;
            }

            UserRecomendationsModel::create([
                'tag_id' => $tag['id'],
                'user_id' => Auth::user()->id
            ]);

            $recomendationsAmount = count(UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->get());

            if ($recomendationsAmount > 5) {
                UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->first()->delete();
            }

        }

        $status = [
            'status' => true,
            'recomendations' => UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->get()
        ];

        return response()->json($status, 200);

    }

}
