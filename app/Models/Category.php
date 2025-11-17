<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //declarasi supaya bisa diisi massal
        protected $fillable = [
            'name',
            'image',
            'is_active',
        ];
        
    //relasi ke subcategory (one to many) satu category punya banyak subcategory
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
