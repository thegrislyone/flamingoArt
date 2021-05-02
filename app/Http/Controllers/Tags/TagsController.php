<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tags\TagsModel;

class TagsController extends Controller
{
    public function getPopularTags(Request $request) {
        $amount = $request['amount'];
        $tags = TagsModel::orderBy('popularity', 'desc')->get(['id', 'name'])->toArray();
        return response()->json(array_slice($tags, 0, $amount), 200);
    }
}
