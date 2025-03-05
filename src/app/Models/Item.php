<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'user_id',
        'buyer_id',
        'categories',
        'condition',
        'description',
        'price',
        'category_id',
    ];

    protected $casts = [
        'categories' => 'array', // JSON形式を配列として扱う
    ];

    // 出品者リレーション
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 購入者リレーション
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // いいねリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメントリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
