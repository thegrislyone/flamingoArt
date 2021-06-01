<?php

namespace App\Http\Controllers\Items;

use Intervention\Image\Facades\Image;

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

    public function formatItems($items) {

    }

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
            $items = ItemsModel::whereNull('banned')->simplePaginate(30)->toArray();
        } elseif ($feed == 'popular') {
            $items = ItemsModel::whereNull('banned')->orderBy('transitions', 'desc')->simplePaginate(30)->toArray();
        } elseif ($feed == 'new') {
            $items = $items = ItemsModel::whereNull('banned')->orderBy('created_at', 'desc')->simplePaginate(30)->toArray();
        }

        /* detach pagination information from items list */

        $meta = [];                 // pagination information
        $data = $items['data'];     // items list

        /* set thumbnail */

        $thumbnail = 'thumbnail_items-list';

        $data = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $data);
        
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

        unset($item['thumbnail_original']); // delete original img path

        /* getting author items for more-section */

        $authorId = $item['author'];
        $item['author'] = User::find($authorId);

        $item['author']['items'] = ItemsModel::where('author', '=', $authorId)->get()->toArray();

        /* set thumbnail */

        $thumbnail = 'thumbnail_item-page';

        $item['thumbnail'] = $item[$thumbnail];

        $item['author']['items'] = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $item['author']['items']);

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

        /* set thumbnail */

        $thumbnail = 'thumbnail_item-page';

        $favorites = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $favorites);

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Товар добавлен в избранное'
            ],
            'status' => true,
            'favorites' => $favorites
        ];
        
        return response()->json($status, 200);

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

        /* set thumbnail */

        $thumbnail = 'thumbnail_item-page';

        $favorites = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $favorites);

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Товар удалён из избранного'
            ],
            'status' => true,
            'favorites' => $favorites
        ];
        
        return response()->json($status, 200);

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

        $status = [
            'status' => true,
        ];
        
        return response()->json($status, 200);
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

        $items = ItemsModel::where('author', '=', $userId)->get()->toArray(); // get items by author

        /* set thumbnail */

        $thumbnail = 'thumbnail_items-list';

        $items = array_map(function($item) use ($thumbnail) {
            unset($item['thumbnail_original']);
            $item['thumbnail'] = $item[$thumbnail];
            return $item;
        }, $items);

        return response()->json($items, 200);

    }

    /**
     * * Method that uploads item: saving thumbnail to storage, insert into database
     * @param request - get parameters for this api-address
     * * returns status
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

        $savedFile = Storage::put('public/items/originals', $request['img']);  // saving file, getting path
        $savedFiles = $this->saveAllFileVersions($savedFile, $img);
        
        // saving item

        $savedFilePathArray = explode('/', $savedFile);
        
        array_shift($savedFilePathArray);
        array_unshift($savedFilePathArray, 'storage');

        // $path = '/storage/' . implode('/', $pathArray);     // creating reght path to insert into img-tag

        $item = ItemsModel::create([
            'name' => $itemName,
            'price' => $itemsPrice,
            'description' => $itemDescription,
            'thumbnail_original' => '/' . implode('/', $savedFilePathArray),
            'thumbnail_item-page' => $savedFiles['thumbnail_item-page'],
            'thumbnail_items-list' => $savedFiles['thumbnail_items-list'],
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

        $status = [
            'status' => true
        ];

        return response()->json($status, 200);
        
    }

    /**
     * * Method that save all three types of item thumbnail
     * @param originalPath - path of original img
     * * returns array with paths
    */

    public function saveAllFileVersions($originalPath, $img) {

        \Tinify\setKey("BCqq8bclMFByt56n3VJckRVbN9jTKg61"); // set tinypng api key
        
        $originalPathExploded = explode('/', $originalPath);
        $fileName = end($originalPathExploded);

        $originalPathExploded_optimized = $originalPathExploded;
        array_shift($originalPathExploded_optimized);
        array_unshift($originalPathExploded_optimized, 'storage');

        /* MAKE ITEMS-LIST VERSION */

        if (!file_exists('storage/items/items_list/')) {
            mkdir('storage/items/items_list/', 0777, true);
        }

        $image = Image::make(implode('/', $originalPathExploded_optimized));
        $image->save('storage/items/items_list/' . $fileName, 10);

        /* tinypng compress */

        $source = \Tinify\fromFile('storage/items/items_list/' . $fileName);
        $source->toFile('storage/items/items_list/' . $fileName); 

        /* MAKE ITEM-PAGE VERSION */

        if (!file_exists('storage/items/item_page/')) {
            mkdir('storage/items/item_page/', 0777, true);
        }

        $image = Image::make(implode('/', $originalPathExploded_optimized));
        // $image = $this->putWatermark($image);
        $image->save('storage/items/item_page/' . $fileName, 30);

        /* tinypng compress */

        $source = \Tinify\fromFile('storage/items/item_page/' . $fileName);
        $source->toFile('storage/items/item_page/' . $fileName); 
        

        return [
            'thumbnail_item-page' => '/storage/items/item_page/' . $fileName,
            'thumbnail_items-list' => '/storage/items/items_list/' . $fileName
        ];
        
    }

    /**
     * * Method that put watermark on item thumbnail
     * @param path - path of img that will be watermarked
     * * returns image with watermark
    */

    public function putWatermark($img) {

        $image = $img;
        $watermark = Image::make('storage/watermark.png')->opacity(60);

        if (gettype($image) == 'string') {
            $image = $image = Image::make($image);
        }

        return $image->insert($watermark, 'bottom-right', 10, 10);

    }

    /**
     * * Method that deleting item
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function deleteItem(Request $request) {

        $item_id = $request['item_id']; // get id of item to delete
        $request_from = $request['request_from'];  // get id of user who deleting that

        if ($request_from != Auth::user()->id && Auth::user()->is_admin) {

            $status = [
                'notification' => [
                    'type' => 'error',
                    'title' => 'Ошибка доступа'
                ]
            ];
            
            return response()->json($status, 200);

        }

        try {

            $item = ItemsModel::find($item_id);

            $images = [$item['thumbnail_item-page'], $item['thumbnail_items-list'], $item['thumbnail_original']];

            $images = array_map(function ($image) {

                $imageExploded = explode('/', $image);
                
                array_shift($imageExploded);
                array_shift($imageExploded);

                $image = 'public/' . implode('/', $imageExploded);

                return $image;

            }, $images);

            Storage::delete($images);
            
            $tagsId = $item->tags;

            $itemTags = UserTagsModel::where('item_id', '=', $tagsId);

            foreach ($itemTags->get() as $key=>$tag) {

                $tagsUsing = UserTagsModel::where('tag_id', '=', $tag['tag_id']);
                $tag = TagsModel::find($tag['tag_id']);

                if (count($tagsUsing->get()) == 1) {
                    $tagsUsing->delete();
                    $tag->delete();
                }

            }
            
            $itemTags->delete();

            FavoritesModel::where('item_id', '=', $item->id)->delete();
            
            $item->delete();

        } catch (Exception $e) {

            $status = [
                'notification' => [
                    'type' => 'error',
                    'title' => 'Ошибка при удалении работы - ' . $e
                ]
            ];

            return response()->json($status, 200);

        }

        $status = [
            'notification' => [
                'type' => 'success',
                'title' => 'Работа успешно удалена'
            ],
            'status' => true
        ];

        return response()->json($status, 200);

    }
}
