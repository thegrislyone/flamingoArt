<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Models\Items\ItemsModel;

use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    public function itemsGet() {
        $items = ItemsModel::paginate(15);
        return response()->json($items, 200);
    }

    public function userItems() {

        if (!Auth::check()) {
            $res = [
                'errors' => ['Вы не авторизованы']
            ];
            return response()->json($res, 200);
        }

        $userId = Auth::user()->only('id')['id'];

        $items = ItemsModel::where('author', '=', $userId)->get();

        return response()->json($items, 200);;

    }

    public function itemLoad(Request $request) {

        $itemName = $request['name'];
        $itemTags = $request['tags'];
        $itemDescription = $request['description'] || null;
        $itemsPrice = intval($request['price']);
        $isAuction = $request['auction'];

        $img = $request['img'];
        $imgExtension = $request->file('img')->extension();

        $userId = Auth::user()->only('id')['id'];

        $pathArray = explode('/', Storage::putFileAs('public/items', $request['img'], $userId . '_' . $itemName . '.' . $imgExtension));
        
        array_shift($pathArray);

        $path = '/storage/' . implode('/', $pathArray);

        ItemsModel::create([
            'name' => $itemName,
            'price' => $itemsPrice,
            'description' => $itemDescription,
            'thumbnail' => $path,
            'tags' => '',
            'likes' => 0,
            'author' => $userId
        ]);

        return $request['img'];
    }
}
