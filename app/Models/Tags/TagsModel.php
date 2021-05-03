<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Nicolaslopezj\Searchable\SearchableTrait;   // search plugin

class TagsModel extends Model
{

    /**
     * * SEARCH PLUGIN => 
    */

    use SearchableTrait;    

    protected $searchable = [
        'columns' => [
            'tags.name' => 10,
        ],
    ];

    /**
     * * <= SEARCH PLUGIN
    */

    protected $table = 'tags';

    protected $filltable = [
        'created_at',
        'updated_at',
        'name',
        'background_color',
        'background_img',
        'popularity'
    ];

    protected $guarded = [];

    static public function mostPopulate($tagsAmount) {
        
        return $tagsAmount;
    }

}
