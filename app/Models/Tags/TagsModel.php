<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsModel extends Model
{
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
