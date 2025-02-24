<?php

namespace App\Models;

use App\Models\Carts;
use App\Models\Order;
use App\Models\Products;
use App\Models\ProductsSKU;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
   protected $fillable = [
       'order_id',
       'cart_id',
       'product_id',
       'sku_id',
       'price',
       'total_price',
       'quantity',
   ];

   public function order()
   {
       return $this->belongsTo(Order::class);
   }

   public function cart()
   {
       return $this->belongsTo(Carts::class);
   }

   public function product()
   {
       return $this->belongsTo(Products::class);
   }

   public function sku()
   {
       return $this->belongsTo(ProductsSKU::class);
   }
   
}
