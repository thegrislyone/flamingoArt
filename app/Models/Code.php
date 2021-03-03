<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'codes';
    protected $fillable = ['user_id', 'code'];
}
