<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Message;

class TransactionController extends Controller
{
    /**
     * 取引中商品一覧（マイページ用）
     */
    public function index()
    {
        $user = Auth::user();

        // 自分が関わっている取引（購入 or 出品）
        $transactions = Transaction::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with('item')
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('is_read', false)->where('user_id', '!=', $user->id);
            }])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('transaction_id', 'transactions.id')
                    ->latest()
                    ->take(1)
            )
            ->get();

        return view('user.transactions', compact('transactions', 'user'));
    }
}
