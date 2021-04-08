<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'image', 'description', 'stock', 'price', 'product_type_id', 
    ];

    // relation with ProductType, products belong to 1 product type
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    // relationship with cart, product can be in many cart items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // relationship with detail transaction, product can be in many detail transactions
    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class);
    }
}
