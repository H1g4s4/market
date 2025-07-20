<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Message;
use App\Http\Requests\ChatMessageRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use App\Models\Review;

class ChatMessageController extends Controller
{
    /**
     * チャット画面の表示
     */
    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        // 取引に関係ないユーザーがアクセスしないようにする
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }

        // サイドバー用：このユーザーに関連するすべての取引を取得（新着順）
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

        // 現在の取引のメッセージを取得
        $messages = $transaction->messages()->with('user')->get();

        // 自分以外の未読メッセージを既読にする
        $transaction->messages()
            ->where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->update(['is_read' => true]);

        return view('chat.show', compact('transaction', 'transactions', 'messages', 'user'));

        $isCompleted = $transaction->item->buyer_id !== null;
        $hasReviewed = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', Auth::id())
            ->exists();

        return view('chat.chat', compact('transaction', 'transactions', 'messages', 'user', 'isCompleted', 'hasReviewed'));

    }

    /**
     * チャット投稿処理
     */
    public function store(ChatMessageRequest $request, Transaction $transaction)
    {
        $user = Auth::user();

        // アクセス制限
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }

        $message = new Message();
        $message->transaction_id = $transaction->id;
        $message->user_id = $user->id;
        $message->body = $request->input('body');
        $message->is_read = false;

        // 画像があれば保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/message_images');
            $message->image = $path;
        }

        $message->save();

        return redirect()->route('chat.show', ['item' => $transaction->id])->withInput(); // 入力保持
    }

    public function update(Request $request, ChatMessage $message)
    {
        $this->authorize('update', $message);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message->body = $request->body;
        $message->save();

        return response()->json(['success' => true, 'body' => $message->body]);
    }

}
