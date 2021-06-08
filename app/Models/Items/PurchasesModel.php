<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesModel extends Model
{
    
    protected $table = 'purchases';

    protected $filltable = [
        'id',
        'item_id',
        'buyer_id'
    ];

    protected $guarded = [];

}
