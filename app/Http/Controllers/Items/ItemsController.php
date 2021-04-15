<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\Models\Items\ItemsModel;
use App\Models\Tags\TagsModel;
use App\Models\Tags\UserTagsModel;
use App\Models\User;


use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    public function itemsGet() {
        $items = ItemsModel::paginate(30);
        return response()->json($items, 200);
    }

    public function getSingleItem(Request $request) {
        $itemId = $request['item_id'];

        $item = ItemsModel::where('id', '=', $itemId)->first();

        // getting author items

        $authorId = $item['author'];
        $item['author'] = User::find($authorId);
        $item['author']['items'] = ItemsModel::where('author', '=', $authorId)->get();

        // getting item tag

        $tagsConnections = UserTagsModel::where('item_id', '=', $itemId)->get();

        $findedTags = [];

        foreach ($tagsConnections as $tag) {
            $tagId = $tag['tag_id'];
            array_push($findedTags, TagsModel::find($tagId)); 
        }

        $item['tags'] = $findedTags;

        return response()->json($item, 200);
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

        return response()->json($items, 200);

    }

    public function itemLoad(Request $request) {

        // form data

        $itemName = $request['name'];
        $itemTags = $request['tags'];
        $itemDescription = $request['description'] || null;
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
            'likes' => 0,
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
