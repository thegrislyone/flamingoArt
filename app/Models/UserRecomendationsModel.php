<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecomendationsModel extends Model
{

    protected $table = 'recomendations';

    protected $guarded = ['id'];
    
}
