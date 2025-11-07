<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //buat relasi ke tabel orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //declarasi supaya bisa diisi massal
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}
