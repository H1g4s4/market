<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $transactionId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($transactionId);
        $user = Auth::user();

        // 自分がこの取引の出品者または購入者であるかを確認
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403, 'この取引にはアクセスできません。');
        }

        // 評価される相手を決定
        $revieweeId = $transaction->buyer_id === $user->id
            ? $transaction->seller_id
            : $transaction->buyer_id;

        // 重複レビュー防止
        $existing = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'この取引にはすでに評価を投稿しています。');
        }

        Review::create([
            'transaction_id' => $transaction->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('chat.show', $transaction->id)->with('success', 'レビューを投稿しました。');
    }
}
