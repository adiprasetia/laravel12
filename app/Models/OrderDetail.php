<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //declarasi supaya bisa diisi massal
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    //relasi ke order (many to one) banyak order detail punya satu order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //relasi ke product (many to one) banyak order detail punya satu product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
