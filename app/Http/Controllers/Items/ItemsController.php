<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items\ItemsModel;

class ItemsController extends Controller
{
    public function items() {
        $page = $_REQUEST['page'];
        return response()->json(ItemsModel::get(), 200);
    }
}
