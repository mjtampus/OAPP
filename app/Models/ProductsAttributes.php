<?php 

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductsAttributes extends Model
{
    protected $fillable = [
        'products_id',
        'type',
    ];
    
    public function product_attribute_values(): HasMany
    {
        return $this->hasMany(ProductsAttributesValues::class);
    }

    public function products() :BelongsToMany
    {
        return $this->belongsToMany(Products::class);
    }
}