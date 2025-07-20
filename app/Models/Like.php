<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    // ユーザーリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品リレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
