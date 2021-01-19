<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';

    protected $filltable = [
        'id_categorie',
        'name',
        'background_color',
        'background_img'
    ];

    
}
