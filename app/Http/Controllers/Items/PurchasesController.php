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
            'buyer_id' => $user_id
        ]);

        $bought_items = array_map(function($item) {
            $item = $item['item_id'];
            return $item;
        }, PurchasesModel::where('buyer_id', '=', $user_id)->get(['item_id'])->toArray());

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

        $user_id = Auth::user()->id;

        $sells = PurchasesModel::leftJoin('items', 'purchases.item_id', '=', 'items.id')->where('author', '=', $user_id)->get();
        
        $sells = array_map(function($item) {

            $item['buyer'] = User::find($item['buyer_id']);
            $item['author'] = Auth::user();

            return $item;

        }, $sells->toArray());

        $purchases = PurchasesModel::where('buyer_id', '=', $user_id)->leftJoin('items', 'purchases.item_id', '=', 'items.id')->get();

        $purchases = array_map(function($item) {

            $item['buyer'] = Auth::user();
            $item['author'] = User::find($item['author']);

            return $item;

        }, $purchases->toArray());
        

        $data = [
            'sells' => $sells,
            'purchases' => $purchases
        ];

        return response()->json($data, 200);

    }

}
