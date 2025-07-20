<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Review;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * プロフィールを更新
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public/profile_images');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'プロフィールを更新しました');
    }

    /**
     * プロフィール画面を表示（出品商品・購入商品を取得）
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');

        // 🔹 レビュー情報の取得
        $averageRating = Review::where('reviewee_id', $user->id)->avg('rating');
        $reviewCount = Review::where('reviewee_id', $user->id)->count();
        $averageRating = $reviewCount > 0 ? round($averageRating, 1) : null;

        $transactions = collect(); // 初期化

        if ($tab === 'buy') {
            $items = Item::where('buyer_id', $user->id)
                ->with('seller:id,name')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($tab === 'transaction') {
            $transactions = Transaction::where(function ($query) use ($user) {
                    $query->where('buyer_id', $user->id)
                        ->orWhere('seller_id', $user->id);
                })
                ->whereDoesntHave('reviews', function ($query) use ($user) {
                    $query->where('reviewer_id', $user->id); // 自分が評価済みの取引は除外
                })
                ->with('item')
                ->get();

            $items = $transactions->map(function ($transaction) {
                $item = $transaction->item;
                $item->transaction_id = $transaction->id;
                return $item;
            });
        } else {
            $items = Item::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('profile.show', compact(
            'user',
            'items',
            'tab',
            'averageRating',
            'reviewCount',
            'transactions'
        ));
    }
}