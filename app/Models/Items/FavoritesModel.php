<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritesModel extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    protected $guarded = ['id'];

}
