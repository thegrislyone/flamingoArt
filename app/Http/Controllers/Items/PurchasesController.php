<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Items\ItemsModel;
use App\Models\Items\PurchasesModel;
use App\Models\Items\FavoritesModel;
use App\Models\User;
use App\Models\Notifications\NotificationsModel;

use App\Events\NotificationSend;

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

        NotificationsModel::create([
            'item_id' => $item_id,
            'from' => $user_id,
            'to' => ItemsModel::find($item_id)['author'],
            'type' => 'purchase'
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

            $item = PurchasesModel::where('item_id', '=', $item['id'])->first();

            $item['buyer'] = User::find($item['buyer_id']);
            $item['item'] = ItemsModel::find($item['item_id']);
            $item['author'] = User::find($item['item']['author']);

            return $item;

        }, $sells->toArray());

        $purchases = PurchasesModel::where('buyer_id', '=', $user_id)->leftJoin('items', 'purchases.item_id', '=', 'items.id')->get();

        $purchases = array_map(function($item) {

            $item = PurchasesModel::where('item_id', '=', $item['id'])->first();

            $item['buyer'] = User::find($item['buyer_id']);
            $item['item'] = ItemsModel::find($item['item_id']);
            $item['author'] = User::find($item['item']['author']);

            return $item;

        }, $purchases->toArray());
        

        $data = [
            'sells' => $sells,
            'purchases' => $purchases
        ];

        return response()->json($data, 200);

    }

    public function downloadItem(Request $request) {

        $item_id = $request['item_id'];

        if (!count(PurchasesModel::where('item_id', '=', $item_id)->where('buyer_id', '=', Auth::user()->id)->get())) {
            $status = [
                'status' => false,
                'title' => 'Ошибка доступа'
            ];

            return response()->json($status, 401);
        }

        $item = ItemsModel::find($item_id);

        return response()->download(substr($item['thumbnail_original'], 1));

    }

}
