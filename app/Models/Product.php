<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //declarasi supaya bisa diisi massal
    protected $fillable = [
        'name',
        'price',
        'stock',
    ];
}
