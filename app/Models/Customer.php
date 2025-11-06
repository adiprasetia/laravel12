<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //declarasi supaya bisa diisi massal
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}
