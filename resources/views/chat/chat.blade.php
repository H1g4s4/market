@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('title', '取引チャット')

@section('content')
<div class="chat-container">
    <!-- サイドバー -->
    <div class="chat-sidebar">
        <h2>その他の取引</h2>
        <ul>
            @foreach ($transactions as $t)
                <li class="{{ $t->id === $transaction->id ? 'active' : '' }}">
                    <a href="{{ route('chat.show', ['transaction' => $t->id]) }}">
                        <img src="{{ Storage::url($t->item->image) }}" alt="{{ $t->item->name }}" class="thumbnail">
                        <span>{{ $t->item->name }}</span>
                        @if ($t->unread_count > 0)
                            <span class="unread">{{ $t->unread_count }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- メインチャット -->
    <div class="chat-main">
        <div class="chat-header">
            <h2>{{ $transaction->partner->name }} さんとの取引画面</h2>
            @if($user->id === $transaction->buyer_id && !$transaction->is_completed)
                <form action="{{ route('chat.complete', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="complete-button">取引を終了する</button>
                </form>
            @endif
        </div>

        <div class="chat-item-info">
            <img src="{{ Storage::url($transaction->item->image) }}" alt="商品画像">
            <div>
                <h3>{{ $transaction->item->name }}</h3>
                <p>¥{{ number_format($transaction->item->price) }}</p>
            </div>
        </div>

        <div class="chat-messages">
            @foreach ($messages as $message)
                <div class="chat-message {{ $message->user_id === $user->id ? 'mine' : 'theirs' }}">
                    <div class="message-top">
                        @if ($message->user_id !== $user->id)
                            <img src="{{ Storage::url($message->user->profile_image) }}" class="chat-profile">
                            <span class="chat-user">{{ $message->user->name }}</span>
                            <div class="chat-body">{{ $message->body }}</div>
                        @else
                            <div class="chat-body">{{ $message->body }}</div>
                            <span class="chat-user">{{ $message->user->name }}</span>
                            <img src="{{ Storage::url($message->user->profile_image) }}" class="chat-profile">
                        @endif
                    </div>

                    {{-- 下に編集・削除 --}}
                    @if ($message->user_id === $user->id)
                        <div class="chat-actions">
                            <form action="{{ route('chat.update', $message) }}" method="POST" class="inline-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="edit-btn">編集</button>
                            </form>
                            <form action="{{ route('chat.destroy', $message) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <form action="{{ route('chat.store', ['transaction' => $transaction->id]) }}" method="POST" enctype="multipart/form-data" class="chat-form">
            @csrf
            <textarea name="body" placeholder="取引メッセージを記入してください">{{ old('body') }}</textarea>

            <div class="chat-form-actions">
                <label class="image-label">
                    <input type="file" name="image" hidden>
                    <span class="image-button">画像を追加</span>
                </label>

                <button type="submit" class="send-icon-button">
                    <img src="{{ asset('storage/message_images/send_icon.png') }}" class="send-icon">
                </button>
            </div>
        </form>
    </div>
</div>

{{-- モーダル：評価フォーム --}}
@if($shouldShowReviewModal && $transaction->is_completed)
    <div id="review-modal" class="modal-overlay">
        <div class="modal-content custom-review-modal">
            <h2>取引が完了しました。</h2>
            <p>今回の取引相手はいかがでしたか？</p>
            <form action="{{ route('review.store', $transaction->id) }}" method="POST" id="star-form">
                @csrf
                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                        <label for="star{{ $i }}" title="{{ $i }}つ星">★</label>
                    @endfor
                </div>

                <div class="modal-actions">
                    <button type="submit" class="star-submit">送信する</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection
