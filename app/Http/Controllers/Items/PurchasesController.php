<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Items\ItemsModel;
use App\Models\Items\PurchasesModel;
use App\Models\Items\FavoritesModel;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class PurchasesController extends Controller
{
    /**
     * * Method that add item into user's bought items
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function buyItem(Request $request) {

        $item_id = $request['item_id'];

        $item_id = ItemsModel::find($item_id)['id'];
        $user_id = Auth::user()->id;

        PurchasesModel::create([
            'item_id' => $item_id,
            'user_id' => $user_id
        ]);

        $bought_items = array_map(function($item) {
            $item = $item['item_id'];
            return $item;
        }, PurchasesModel::where('user_id', '=', $user_id)->get(['item_id'])->toArray());

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Товар приобретён'
            ],
            'status' => true,
            'bought_items' => $bought_items
        ];
        
        return response()->json($status, 200);

    }

    /**
     * * Method returns user bought items
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function getUserBoughtItems(Request $request) {

        $user_id = $request['user_id'];



    }

}
