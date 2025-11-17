<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    //declarasi supaya bisa diisi massal
        protected $fillable = [
            'category_id',
            'name',
            'image',
            'is_active',
        ];
        
        //relasi ke category (many to one) banyak subcategory punya satu category
        public function category()
        {
            return $this->belongsTo(Category::class);
        }
}
