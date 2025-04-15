<?php

namespace App\Models;

use App\Models\User;
use App\Models\Replies;
use App\Models\Products;
use App\Models\CommentLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comments extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'products_id',
        'is_liked',
        'comment',
    ];

    public function replies() :HasMany
    {
        return $this->hasMany(Replies::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products() :BelongsTo
    {
        return $this->belongsTo(Products::class);
    }

    public function likes() :HasMany
    {
        return $this->hasMany(CommentLikes::class);
    }
}
