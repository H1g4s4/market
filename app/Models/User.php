<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 出品した商品のリレーション
     */
    public function listedItems(): HasMany
    {
        return $this->hasMany(Item::class, 'user_id');
    }

    /**
     * 購入した商品のリレーション
     */
    public function purchasedItems(): HasMany
    {
        return $this->hasMany(Item::class, 'buyer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id')->orWhere('seller_id', $this->id);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
