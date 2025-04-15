<?php

namespace App\Models;

use App\Models\User;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Likes extends Model
{
    protected $table = 'likes';

    protected $fillable = [
        'user_id',
        'products_id',
        'is_liked',
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function product() :BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
}
