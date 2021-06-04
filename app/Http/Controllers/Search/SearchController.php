<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Items\ItemsModel;
use App\Models\Items\FavoritesModel;
use App\Models\Tags\TagsModel;
use App\Models\Tags\UserTagsModel;

class SearchController extends Controller
{
    
    /**
     * * Method that returns search tips
     * @param request - get parameters for this api-address
     * * returns search tips or no-tips error
    */

    public function getSearchTips(Request $request) {
        
        $search = $request['search-query'];   // get search

        /* search items */

        $items = ItemsModel::search($search)->get();

        /* filter search only by name */

        $items = array_filter($items->toArray(), function($item) use ($search) {
            $name = strtolower($item['name']);
            if (stristr($name, $search)) {
                return true;
            }
            return false;
        });

        /* search tags */

        $tags = TagsModel::search($search)->get();

        // return result

        $result;

        if (!count($items) && !count($tags)) {
            $result = [
                'status' => false,
                'errors' => ['Нет результатов']
            ];
        } else {
            $result = [
                'status' => true,
                'items' => $items,
                'tags' => $tags
            ];
        }

        return $result;

    }

    /**
     * * Method that returns search results
     * @param request - get parameters for this api-address
     * * returns search results or no-results error
    */

    public function getSearchResults(Request $request) {

        $search = $request['search-query'];   // get search

        /* search items */

        $items = ItemsModel::search($search)->get()->toArray();

        /* search tags */

        $tags = TagsModel::search($search)->get();

        /* search items by tags */

        $items_by_tags = [];

        foreach ($tags as $key=>$tag) {

            // TagsModel::find($tag['id'])->update(['popularity' => $tag['popularity'] + 1]); // tag popularity increase

            $connections = UserTagsModel::where('tag_id', '=', $tag['id'])->get()->toArray();

            // return $connections;

            foreach ($connections as $index=>$connection) {

                $item = ItemsModel::find($connection['item_id']);

                if ($item) {
                    array_push($items_by_tags, $item);
                }

            }

        }

        /* sorting that removes the same elements that match the search term in the name / description and in tags */

        foreach($items_by_tags as $key=>$item_by_tag) {
            foreach($items as $index=>$item) {

                if ($item['id'] == $item_by_tag['id']) {
                    unset($items_by_tags[$key]);
                }

            }
        }

        /* set thumbnail for search-items */

        $thumbnail = 'thumbnail_items-list';

        $items = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $items);

        /* set thumbnail for tag-items */

        $thumbnail = 'thumbnail_items-list';

        $items_by_tags = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $items_by_tags);

        /* return result */

        $result;

        if (!count($items) && !count($items_by_tags) && !count($tags)) {
            $result = [
                'status' => false,
                'errors' => ['Нет результатов']
            ];
        } else {
            $result = [
                'status' => true,
                'items' => $items,
                'tags' => $tags,
                'items_by_tags' => $items_by_tags
            ];
        }

        return response()->json($result, 200);

    }

}
