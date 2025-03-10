<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function products() :HasMany
    {
        return $this->hasMany(Products::class);
    }
    public function brands() :HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
