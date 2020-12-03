<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsModel extends Model
{
    protected $table = 'items';

    protected $filltable = [
        'id_item',
        'name',
        'price',
        'description',
        'thumbnail',
        'tags'
    ];
}
