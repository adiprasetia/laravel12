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

    //relasi ke order details (one to many) satu product punya banyak order detail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
