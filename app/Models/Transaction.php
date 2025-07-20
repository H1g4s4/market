<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'seller_id', 'buyer_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPartnerAttribute()
    {
        $currentUserId = auth()->id();

        if ($this->buyer_id == $currentUserId) {
            return $this->seller;
        } elseif ($this->seller_id == $currentUserId) {
            return $this->buyer;
        }

        return null;
    }

}
