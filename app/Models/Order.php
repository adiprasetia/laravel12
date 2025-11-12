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

    // Alias yang tetap kompatibel ke belakang untuk nama relasi dalam format snake_case yang digunakan oleh beberapa paket/komponen
    // misalnya ketika sebuah komponen formulir memanggil ->relationship() dengan kunci 'order_details'.
    public function order_details()
    {
        return $this->orderDetails();
    }
}
