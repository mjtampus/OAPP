<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLikes extends Model
{
    protected $table = 'comment_likes';

    protected $fillable = [
        'user_id',
        'comments_id',
        'like_status',
    ];

    public function comments() :BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
