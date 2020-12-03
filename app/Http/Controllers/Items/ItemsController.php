<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items\ItemsModel;

class ItemsController extends Controller
{
    public function items() {
        print_r(utf8_encode(ItemsModel::get()));
        return 5;
    }
}
