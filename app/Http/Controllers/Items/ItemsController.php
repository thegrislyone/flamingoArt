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
use App\Models\UserRecomendationsModel;

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

            if (Auth::check() && count(UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->get())) {

                $recomendationsTags = UserRecomendationsModel::where('user_id', '=', Auth::user()->id)->get('tag_id');

                $recomendationsTags = array_map(function($tag) {

                    $tag = $tag['tag_id'];

                    return $tag;

                }, $recomendationsTags->toArray());

                // return $recomendationsTags;

                $connectedTags = UserTagsModel::whereIn('tag_id', $recomendationsTags)->get();

                $connectedItems = [];

                foreach ($connectedTags as $key=>$item) {

                    array_push($connectedItems, $item['item_id']);

                }

                $connectedItems = array_unique($connectedItems);

                $recomendedItems = ItemsModel::whereNull('banned')->whereIn('id', $connectedItems)->get()->toArray();

                $items = ItemsModel::whereNull('banned')->whereNotIn('id', $connectedItems)->simplePaginate(5)->toArray();;

                if ($request['page'] == '1') {
                    $items['data'] = array_merge($recomendedItems, $items['data']);
                }

                // if (count($recomendedItems['data']) < 30) {

                //     $fullCount = count(ItemsModel::whereNull('banned')->whereIn('id', $connectedItems)->get());
                //     $pages = ceil($fullCount / 30);

                //     if (count($items['data'])) {
                //         $items['data'] = array_merge($items['data'], ItemsModel::whereNull('banned')->whereNotIn('id', $connectedItems)->paginate(30 - count($recomendedItems['data']), ['*'], 'page', $request['page'] - ($pages - 1))->toArray()['data']);
                //     } else {
                //         $items['data'] = array_merge($items['data'], ItemsModel::whereNull('banned')->whereNotIn('id', $connectedItems)->paginate(30, ['*'], 'page', $request['page'] - ($pages - 1))->toArray()['data']);
                //     }

                // } else {
                //     return "3";
                //     $items = $recomendedItems;
                // }

            } else {

                $items = ItemsModel::whereNull('banned')->simplePaginate(30)->toArray();

            }
            
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
     * * Method that uploads item: saving thumbnail to storage, insert into database
     * @param request - get parameters for this api-address
     * * returns status
    */

    public function itemUpload(Request $request) {

        // form data

        $itemName = $request['name'];
        $itemTags = $request['tags'];
        $itemDescription = $request['description'];
        $itemsPrice = (!$request['price'] && $request['noSell']) ? NULL : intval($request['price']);
        $isAuction = $request['auction'];

        $img = $request['img'];

        // getting id of user who uploading the item

        if ($this->isImageAlreadyExist($img)) {

            return $status = [
                'notification' => [
                    'type' => 'error',
                    'title' => 'Данное изображение уже было загружено'
                ],
                'status' => false
            ];

        }

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

        if (!!$itemTags) {

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

        }

        $item->tags = $item['id'];

        $item->save();

        $status = [
            'notification' => [
                'type'=> 'success',
                'title' => 'Работа загружена'
            ],
            'status' => true
        ];

        return response()->json($status, 200);
        
    }

    public function isImageAlreadyExist($img) {
        
        $imageSize = Image::make($img)->filesize();
        
        $images = Storage::files('public/items/originals');

        foreach ($images as $key=>$image) {

            $imageSrc = explode('/', $image);
            array_shift($imageSrc);
            array_unshift($imageSrc, 'storage');
            $imageSrc = implode('/', $imageSrc);

            if (Image::make($imageSrc)->filesize() == $imageSize) {
                
                return true;

            }

        }

        return false;

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
