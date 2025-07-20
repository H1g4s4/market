<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\Review;
use App\Mail\TransactionCompleted;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    /**
     * チャット画面の表示
     */
    public function show($transactionId)
    {
        $user = Auth::user();

        // 関連する取引の取得（本人が当事者かチェック）
        $transaction = Transaction::with(['item', 'buyer', 'seller'])->findOrFail($transactionId);
        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }

        // メッセージ取得
        $messages = Message::with('user')
            ->where('transaction_id', $transactionId)
            ->orderBy('created_at')
            ->get();

        // 未読→既読に更新
        Message::where('transaction_id', $transactionId)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // 自分が関わる取引一覧（サイドバー用）
        $transactions = Transaction::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with('item')
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('is_read', false)->where('user_id', '!=', $user->id);
            }])
            ->get();

        // レビュー済みか確認
        $alreadyReviewed = Review::where('transaction_id', $transaction->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        // モーダル表示条件
        $shouldShowReviewModal = !$alreadyReviewed && $transaction->is_completed;

        return view('chat.chat', compact('transaction', 'messages', 'transactions', 'user', 'shouldShowReviewModal'));
    }


    /**
     * メッセージの投稿
     */
    public function store(Request $request, $transactionId)
    {
        $request->validate([
            'body' => 'required_without:image|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }

        $message = new Message();
        $message->transaction_id = $transactionId;
        $message->user_id = $user->id;
        $message->body = $request->body;

        if ($request->hasFile('image')) {
            $message->image = $request->file('image')->store('public/message_images');
        }

        $message->save();

        return redirect()->route('chat.show', ['transaction' => $transactionId]);
    }

    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message->body = $request->body;
        $message->save();

        return back()->with('success', 'メッセージを編集しました');
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);
        $message->delete();

        return back()->with('success', 'メッセージを削除しました');
    }

    public function complete($transactionId)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrFail($transactionId);

        // 購入者のみ実行可能
        if ($transaction->buyer_id !== $user->id) {
            abort(403, '購入者のみが取引を終了できます。');
        }

        $transaction->is_completed = true;
        $transaction->save();

        // 出品者にメール送信
        Mail::to($transaction->seller->email)->send(new TransactionCompleted($transaction));

        return redirect()->route('chat.show', $transactionId)->with('success', '取引を完了しました。評価をお願いします。');
    }

}

