<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tags\TagsModel;

class TagsController extends Controller
{

    /**
     * * Method that increase tag popularity
     * @param request - get parameters for this api-address
     * * returns ok status
    */

    public function tagSearch(Request $request) {

        $search = $request['search-query'];

        $tags = TagsModel::search($search)->get();

        foreach ($tags as $key=>$tag) {

            TagsModel::find($tag['id'])->update(['popularity' => $tag['popularity'] + 1]); // tag popularity increase

        }

        $response = [
            'success' => true
        ];

        return response()->json($response, 200);

    }

    /**
     * * Method that returns amount of popular tags
     * @param request - get parameters for this api-address
     * * returns $amount of most popular tags
    */

    public function getPopularTags(Request $request) {

        $amount = $request['amount'];   // get required amount of tags

        $tags = TagsModel::orderBy('popularity', 'desc')->get(['id', 'name', 'popularity'])->toArray();   // select ordered desc-ordered tags
        
        if (!$amount) {
            return response()->json($tags, 200);
        }

        return response()->json(array_slice($tags, 0, $amount), 200);
    }

    /**
     * * Method that returns amount of unpopular tags
     * @param request - get parameters for this api-address
     * * returns $amount of most unpopular tags
    */

    public function getUnpopularTags(Request $request) {

        $amount = $request['amount'];   // get required amount of tags

        $tags = TagsModel::orderBy('popularity', 'asc')->get(['id', 'name', 'popularity'])->toArray();    // select ordered asc-ordered tags
        
        if (!$amount) {
            return response()->json($tags, 200);
        }

        return response()->json(array_slice($tags, 0, $amount), 200);
    }

    /**
     * * Method that returns all tags
     * * returns all tags
    */

    public function getAllTags() {

        $tags = TagsModel::get();   // select tags

        return response()->json($tags, 200);
    }
}
