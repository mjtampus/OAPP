<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = [
        'user_id',
        'sku_id',
        'products_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sku()
    {
        return $this->belongsToMany(ProductsSKU::class);
    }

    public function products()
    {
        return $this->belongsToMany(Products::class);
    }
}
