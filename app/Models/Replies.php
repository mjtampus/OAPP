<?php

namespace App\Models;

use App\Models\Comments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Replies extends Model
{
    protected $table = 'replies';

    protected $fillable = [
        'comments_id',
        'user_id',
        'comment',
        'is_liked',
    ];

    public function comment() :BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
