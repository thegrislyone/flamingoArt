<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Models\Items\ItemsModel;
use App\Models\Items\FavoritesModel;

use App\Models\Tags\TagsModel;
use App\Models\Tags\UserTagsModel;

use App\Models\User;


use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{

    /**
     * * Method, that returns items to use it in itemsList 
     * @param request - get parameters for this api-address
     * * returns items list
    */

    public function getItems(Request $request) {

        $feed = $request['feed'];   // get feed-type

        $items = [];    // init empty items arr

        /* get items depending on feed type */

        if (!$feed || $feed == 'main') {
            $items = ItemsModel::simplePaginate(30)->toArray();
        } elseif ($feed == 'popular') {
            $items = ItemsModel::orderBy('transitions', 'desc')->simplePaginate(30)->toArray();
        } elseif ($feed == 'new') {
            $items = $items = ItemsModel::orderBy('created_at', 'desc')->simplePaginate(30)->toArray();
        }

        /* detach pagination information from items list */

        $meta = [];                 // pagination information
        $data = $items['data'];     // items list
        
        foreach ($items as $key=>$value) {
            if ($key != 'data') {
                $meta[$key] = $value;
            }
        }
        
        $items = [
            'meta' => $meta,
            'data' => $data
        ];
        
        return response()->json($items, 200);

    }

    /**
     * * Method that returns single items by id. It uses in single item page 
     * @param request - get parameters for this api-address
     * * returns single item
    */

    public function getSingleItem(Request $request) {

        $itemId = $request['item_id'];  // get item id

        $item = ItemsModel::find($itemId);  // find item by id

        /* getting author items for more-section */

        $authorId = $item['author'];
        $item['author'] = User::find($authorId);
        $item['author']['items'] = ItemsModel::where('author', '=', $authorId)->get();

        /* getting item tags */

        $tagsConnections = UserTagsModel::where('item_id', '=', $itemId)->get();

        $findedTags = [];

        foreach ($tagsConnections as $tag) {
            $tagId = $tag['tag_id'];
            array_push($findedTags, TagsModel::find($tagId)); 
        }

        $item['tags'] = $findedTags;

        return response()->json($item, 200);

    }

    /**
     * * Method that adding item to user's favorites and returns favorites list with added element to update it on client
     * @param request - get parameters for this api-address
     * * returns full favorites list with added element
    */

    public function addToFavorite(Request $request) {

        $userId = Auth::user()['id'];   // get user id
        $itemId = $request['item_id'];  // get item id

        /* insert favorite into favorites table */

        FavoritesModel::create([
            'user_id' => $userId,
            'item_id' => $itemId
        ]);

        /* increase favorited counter */

        $favoritedAmount = ItemsModel::find($itemId)['favorited'];
        ItemsModel::find($itemId)->update(['favorited' => $favoritedAmount + 1]);

        /* get favorites list to update it on client */

        $favoritesConnections = FavoritesModel::where('user_id', '=', $userId)->get();
        $favorites = [];

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        $success = [
            'success' => 'Товар добавлен в избранное',
            'favorites' => $favorites
        ];
        
        return response()->json($success, 200);

    }

    /**
     * * Method that removint item from user's favorites and returns favorites list without removed element to update it on client
     * @param request - get parameters for this api-address
     * * returns full favorites list without removed element
    */

    public function removeFromFavorite(Request $request) {

        $userId = Auth::user()['id'];   // get user id
        $itemId = $request['item_id'];  // get item id


        FavoritesModel::where('user_id', '=', $userId)->where('item_id', '=', $itemId)->delete();   // get item by user and item id

        /* decrease favorited counter */

        $favoritedAmount = ItemsModel::find($itemId)['favorited'];
        ItemsModel::find($itemId)->update(['favorited' => $favoritedAmount - 1]);

        /* get favorites list to update it on client */

        $favoritesConnections = FavoritesModel::where('user_id', '=', $userId)->get();
        $favorites = [];

        foreach ($favoritesConnections as $favorite) {
            array_push($favorites, ItemsModel::find($favorite['item_id']));
        }

        $success = [
            'success' => 'Товар удалён из избранного',
            'favorites' => $favorites
        ];
        
        return response()->json($success, 200);

    }

    /**
     * * Method that returns favorites list by user id
     * @param request - get parameters for this api-address
     * * returns user's items list
    */

    public function getUserFavorites(Request $request) {

        // it's not so need right now

    }

    /**
     * * Method that increase item's transitions counter, that uses as popularity flag
     * @param request - get parameters for this api-address
     * * returns success status
    */

    public function transitionToItem(Request $request) {

        $itemId = $request['item_id'];  // get item id

        /* increase transitions counter */

        $transitionsAmount = ItemsModel::find($itemId)['transitions'];
        ItemsModel::find($itemId)->update(['transitions' => $transitionsAmount + 1]);

        $success = [
            'success' => 'Ок',
        ];
        
        return response()->json($success, 200);
    }

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
                'success' => false,
                'errors' => ['Нет результатов']
            ];
        } else {
            $result = [
                'success' => true,
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

        $items = ItemsModel::search($search)->get();

        /* search tags */

        $tags = TagsModel::search($search)->get();

        /* search items by tags */

        $items_by_tags = [];

        foreach ($tags as $key=>$tag) {

            // TagsModel::find($tag['id'])->update(['popularity' => $tag['popularity'] + 1]); // tag popularity increase

            $connections = UserTagsModel::where('tag_id', '=', $tag['id'])->get()->toArray();

            foreach ($connections as $index=>$connection) {
                $item = ItemsModel::find($connection['item_id']);
                array_push($items_by_tags, $item);
            }

        }

        // return result

        $result;

        if (!count($items) && !count($items_by_tags) && !count($tags)) {
            $result = [
                'success' => false,
                'errors' => ['Нет результатов']
            ];
        } else {
            $result = [
                'success' => true,
                'items' => $items,
                'tags' => $tags,
                'items_by_tags' => $items_by_tags
            ];
        }

        return $result;

    }

    /**
     * * Method that returns items list by author id
     * @param request - get parameters for this api-address
     * * returns items list
    */

    public function getUserItems(Request $request) {

        /* checking for authorization */

        if (!Auth::check() && !$request['author_id']) {
            $res = [
                'errors' => ['Вы не авторизованы']
            ];
            return response()->json($res, 200);
        }

        /* get author id, it's depends of who is owner */

        $userId;

        if ($request['author_id']) {
            $userId = $request['author_id'];
        } else {
            $userId = Auth::user()->only('id')['id'];
        }

        $items = ItemsModel::where('author', '=', $userId)->get(); // get items by author

        return response()->json($items, 200);

    }

    /**
     * * Method that uploads item: saving thumbnail to storage, insert into database
     * @param request - get parameters for this api-address
     * * returns thumbnail-path // ! it's useless for now
    */

    public function itemUpload(Request $request) {

        // form data

        $itemName = $request['name'];
        $itemTags = $request['tags'];
        $itemDescription = $request['description'];
        $itemsPrice = intval($request['price']);
        $isAuction = $request['auction'];

        $img = $request['img'];

        // getting id of user who uploading the item

        $userId = Auth::user()->only('id')['id'];

        $fileName = Storage::put('public/items', $request['img']);  // saving file, getting path
        $pathArray = explode('/', $fileName);                       // explode file path to array

        // renaming file to make the first char an user id

        $fileNameWithAuthor = implode('/', array_slice($pathArray, 0, count($pathArray) - 1)) . '/' . $userId . '_' . end($pathArray);

        $pathArray = explode('/', $fileNameWithAuthor);

        Storage::move($fileName, $fileNameWithAuthor);
        
        // saving item
        
        array_shift($pathArray);

        $path = '/storage/' . implode('/', $pathArray);     // creating reght path to insert into img-tag

        $item = ItemsModel::create([
            'name' => $itemName,
            'price' => $itemsPrice,
            'description' => $itemDescription,
            'thumbnail' => $path,
            'tags' => '',
            'favorited' => 0,
            'views' => 0,
            'transitions' => 0,
            'author' => $userId
        ]);

        // set tags

        foreach (explode(',', $itemTags) as $tag) {
            echo !!TagsModel::where('name', '=', $tag);
        }

        foreach (explode(',', $itemTags) as $tag) {

            $searchedTag = TagsModel::where('name', '=', $tag)->first();

            if ($searchedTag === null) {

                $createdTag = TagsModel::create([
                    'name' => $tag,
                    'popularity' => 0,
                    'background_color' => null,
                    'background_img' => null
                ]);

                UserTagsModel::create([
                    'item_id'=> $item['id'],
                    'user_id'=> $userId,
                    'tag_id'=> $createdTag['id']
                ]);

            } else {

                UserTagsModel::create([
                    'item_id'=> $item['id'],
                    'user_id'=> $userId,
                    'tag_id'=> $searchedTag['id']
                ]);

            }
        }

        $item->tags = $item['id'];

        $item->save();

        return $request['img'];
        
    }
}
