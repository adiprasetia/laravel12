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
}
