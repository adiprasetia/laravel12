<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //declarasi supaya bisa diisi massal
        protected $fillable = [
            'name',
            'image',
            'is_active',
        ];
    //
}

