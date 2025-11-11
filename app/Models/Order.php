<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //buat relasi dari customer (one to many) customer punya banyak order
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    //declarasi supaya bisa diisi massal
    protected $fillable = [
        'customer_id',
        'total_price',
        'order_date',
    ];

    //relasi ke order details (one to many) satu order punya banyak order detail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Backwards-compatible alias for snake_case relationship name used by some packages/components
    // e.g. when a form component calls ->relationship() with the key 'order_details'.
    public function order_details()
    {
        return $this->orderDetails();
    }
}
