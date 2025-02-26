<?php

namespace App\Models;

use App\Models\User;
use App\Models\Carts;
use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        // 'order_name',
        // 'order_description',
        'amount',
        'user_id',
        'is_paid',
        'payment_method',
        'order_status',
    ];

    public function payment() :HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items() :HasMany
    {
        return $this->hasMany(OrderItems::class);
    }

}
