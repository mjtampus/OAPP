<?php

namespace App\Models;

use App\Models\Carts;
use App\Models\Products;
use App\Models\OrderItems;
use App\Models\ProductsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductsAttributesValues;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductsSKU extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'products_id',
        'sku',
        'attributes',
        'sku_image_dir',
        'stock',
        'price',
    ];

    protected $casts = [
        'attributes' => 'json',
    ];    

    public function product() :BelongsTo
    {
        return $this->belongsTo(Products::class,'products_id');
    }

    public function product_attribute() :BelongsTo
    {
        return $this->belongsTo(ProductsAttributes::class);
    }

    public function product_attribute_value() :BelongsTo
    {
        return $this->belongsTo(ProductsAttributesValues::class);
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


// Schema::create('products_s_k_u_s', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('product_id')->constrained()->cascadeOnDelete();
//     $table->foreignId('product_attribute_id')->constrained('products_attributes')->cascadeOnDelete();
//     $table->foreignId('product_attribute_value_id')->constrained('products_attributes_values')->cascadeOnDelete();
//     $table->string('sku_image_dir');
//     $table->bigInteger('stock');
//     $table->integer('price');
//     $table->timestamps();
// });