<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Items\ItemsModel;
use App\Models\Items\FavoritesModel;

use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{

    /**
     * * Method that adding item to user's favorites and returns favorites list with added element to update it on client
     * @param request - get parameters for this api-address
     * * returns full favorites list with added element
    */

    public function addToFavorite(Request $request) {

        $userId = Auth::user()['id'];   // get user id
        $itemId = $request['item_id'];  // get item id

        /* insert favorite into favorites table */

        FavoritesModel::create([
            'user_id' => $userId,
            'item_id' => $itemId
        ]);

        /* increase favorited counter */

        $favoritedAmount = ItemsModel::find($itemId)['favorited'];
        ItemsModel::find($itemId)->update(['favorited' => $favoritedAmount + 1]);

        /* get favorites list to update it on client */

        $favoritesConnections = FavoritesModel::where('user_id', '=', $userId)->get();
        $favorites = [];

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        /* set thumbnail */

        $thumbnail = 'thumbnail_item-page';

        $favorites = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $favorites);

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Товар добавлен в избранное'
            ],
            'status' => true,
            'favorites' => $favorites
        ];
        
        return response()->json($status, 200);

    }

    /**
     * * Method that removint item from user's favorites and returns favorites list without removed element to update it on client
     * @param request - get parameters for this api-address
     * * returns full favorites list without removed element
    */

    public function removeFromFavorite(Request $request) {

        $userId = Auth::user()['id'];   // get user id
        $itemId = $request['item_id'];  // get item id


        FavoritesModel::where('user_id', '=', $userId)->where('item_id', '=', $itemId)->delete();   // get item by user and item id

        /* decrease favorited counter */

        $favoritedAmount = ItemsModel::find($itemId)['favorited'];
        ItemsModel::find($itemId)->update(['favorited' => $favoritedAmount - 1]);

        /* get favorites list to update it on client */

        $favoritesConnections = FavoritesModel::where('user_id', '=', $userId)->get();
        $favorites = [];

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        /* set thumbnail */

        $thumbnail = 'thumbnail_item-page';

        $favorites = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $favorites);

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Товар удалён из избранного'
            ],
            'status' => true,
            'favorites' => $favorites
        ];
        
        return response()->json($status, 200);

    }

    /**
     * * Method that returns favorites list by user id
     * @param request - get parameters for this api-address
     * * returns user's items list
    */

    public function getUserFavorites(Request $request) {

        // it's not so need right now

    }
    
}
