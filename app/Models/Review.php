<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'transaction_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public static function averageForUser($userId)
    {
        return self::where('reviewee_id', $userId)->avg('rating');
    }

    public static function countForUser($userId)
    {
        return self::where('reviewee_id', $userId)->count();
    }

}
