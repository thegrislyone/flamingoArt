<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Items\ItemsModel;

class ItemsController extends Controller
{
    public function itemsGet() {
        $items = ItemsModel::paginate(15);
        return response()->json($items, 200);
    }

    public function itemLoad(Request $request) {
        Storage::disk('public')->put('example.txt', 'Contents');
        return $request;
    }
}
