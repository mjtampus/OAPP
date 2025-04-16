<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Carts;
use App\Models\Order;
use App\Models\Comments;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function carts()
    {
        return $this->hasMany(Carts::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, 'admin@admin.com');
    }
    public function comments() :HasMany
    {
        return $this->hasMany(Comments::class);
    }
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }

}
