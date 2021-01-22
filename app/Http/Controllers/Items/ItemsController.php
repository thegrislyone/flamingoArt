<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items\ItemsModel;

class ItemsController extends Controller
{
    public function items() {
        $items = ItemsModel::paginate(15);
        return response()->json($items, 200);
    }
}
