<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = [
        'name', 'image'
    ];

    // relation with Product, 1 product type has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
