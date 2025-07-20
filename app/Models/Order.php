<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'status',
    ];

    // ユーザーとのリレーション（注文者）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品とのリレーション（購入された商品）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
