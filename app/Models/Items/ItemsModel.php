<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Nicolaslopezj\Searchable\SearchableTrait;   // search plugin

class ItemsModel extends Model
{

    /**
     * * SEARCH PLUGIN => 
    */

    use SearchableTrait;    

    protected $searchable = [
        'columns' => [
            'items.name' => 10,
            'items.description' => 10,
        ],
    ];

    /**
     * * <= SEARCH PLUGIN
    */

    protected $table = 'items';

    protected $filltable = [
        'name',
        'price',
        'description',
        'thumbnail',
        'tags'
    ];

    protected $guarded = [];

}
