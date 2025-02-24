<?php

namespace App\Models;

use App\Models\OrderItems;
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

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
