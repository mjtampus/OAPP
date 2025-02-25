<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Carts;
use App\Models\Category;
use App\Models\ProductsSKU;
use App\Models\ProductsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_image_dir',
        'brand_id',
        'category_id',
        'is_new_arrival',
        'is_featured',
        'price',
        'stock'
    ];


    public function brand() :BelongsTo 
    {
        return $this->belongsTo(Brand::class);
    }

    public function category() :BelongsTo 
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes() :HasMany
    {
        return $this->hasMany(ProductsAttributes::class);
    }

    public function sku() :HasMany
    {
        return $this->hasMany(ProductsSKU::class);
    }

    public function carts() :HasMany
    {
        return $this->hasMany(Carts::class);
    }

    public function items() :HasMany
    {
        return $this->hasMany(OrderItems::class);
    }
    
}
