<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\CategoriesModel;

class CategoriesController extends Controller
{
    public function categories() {
        
        // $items = ItemsModel::paginate(5);
        return response()->json(CategoriesModel::all(), 200);
    }
}
