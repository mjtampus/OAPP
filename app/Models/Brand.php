<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id'
    ];

    public function products() :HasMany
    {
        return $this->hasMany(Products::class);
    }
    public function category() :BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
