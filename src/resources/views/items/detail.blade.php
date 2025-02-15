@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endpush

@section('title', $item->name)

@section('content')
<div class="item-detail-container">
    <!-- ✅ 左側：商品画像 -->
    <div class="item-image">
        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
    </div>

    <!-- ✅ 右側：商品情報 -->
    <div class="item-info">
        <h1>{{ $item->name }}</h1>
        <p class="brand">{{ $item->brand_name }}</p>
        <p class="price">¥{{ number_format($item->price) }}（税込）</p>

        <!-- いいね & コメント数 -->
        <div class="icons">
            <form method="POST" action="{{ $liked ? route('items.unlike', $item->id) : route('items.like', $item->id) }}">
                @csrf
                <button type="submit" class="like-button">
                    {{ $liked ? '★' : '☆' }} {{ $item->likes->count() }}
                </button>
            </form>
            <span>💬 {{ $item->comments->count() }}</span>
        </div>

        <a href="{{ route('items.purchase', $item->id) }}" class="purchase-button">購入手続きへ</a>

        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>

        <h3>商品情報</h3>
        <p>カテゴリー: {{ implode(', ', json_decode($item->categories, true) ?? []) }}</p>
        <p>商品の状態：{{ $item->condition }}</p>

        <h3>コメント（{{ $item->comments->count() }}）</h3>
        <div class="comments">
            @foreach ($item->comments as $comment)
                <div class="comment">
                    <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>

        @auth
        <form method="POST" action="{{ route('items.comment', $item->id) }}" class="comment-form">
            @csrf
            <textarea name="comment" required></textarea>
            <button type="submit">コメントを送信する</button>
        </form>
        @endauth
    </div>
</div>
@endsection
