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
        @if (!empty($item->brand_name))
            <p class="brand">{{ $item->brand_name }}</p>
        @endif
        <p class="price">¥{{ number_format($item->price) }}（税込）</p>

        <!-- いいね & コメント数 -->
        <div class="icons">
            @if(Auth::check())
                <!-- ログインしている場合-->
                <form method="POST" action="{{ $liked ? route('items.unlike', $item->id) : route('items.like', $item->id) }}">
                    @csrf
                    <button type="submit" class="like-button">
                    {{ $liked ? '★' : '☆' }} {{ $item->likes->count() }}
                    </button>
                </form>
            @else
                <!--　ログインしていない場合-->
                <button type="button" class="like-button" onclick="window.location.href='{{ route('login.show') }}'">
                    ☆ {{ $item->likes->count() }}
                </button>
            @endif
            <span>💬 {{ $item->comments->count() }}</span>
        </div>

        <!-- ✅ 購入ボタン -->
        @if(Auth::check())
            <a href="{{ route('items.purchase', $item->id) }}" class="purchase-button">購入手続きへ</a>
        @else
            <a href="{{ route('login.show') }}" class="purchase-button">購入手続きへ</a>
        @endif

        <!-- ✅ 商品説明 -->
        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>

        <!-- ✅ 商品情報 -->
        <h3>商品情報</h3>
        <div class="category-container">
            @if (!empty($item->categories))
                @foreach ($item->categories as $category) <!-- ✅ ここを修正 -->
                    <span class="category-tag">{{ $category }}</span>
                @endforeach
            @else
                <p>カテゴリーなし</p>
            @endif
        </div>

        <p>商品の状態：{{ $item->condition }}</p>

        <!-- ✅ コメント -->
        <h3>コメント（{{ $item->comments->count() }}）</h3>

        <!-- ✅ プロフィール画像 + 名前表示 -->
        @auth
        <div class="comment-user-info">
            <img src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : asset('images/default-profile.png') }}" alt="プロフィール画像">
            <span class="comment-username">{{ Auth::user()->name }}</span>
        </div>
        @endauth

        <!-- ✅ コメント一覧 -->
        <div class="comments">
            @foreach ($item->comments as $comment)
                <div class="comment">
                    <div class="comment-user">
                        <strong>{{ $comment->user->name }}</strong>
                    </div>
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>

        <!-- ✅ コメント投稿フォーム -->
        <form method="POST" action="{{ Auth::check() ? route('items.comment', $item->id) : route('login.show') }}" class="comment-form">
            @csrf
            <div class="comment-input-container">
                <textarea name="comment" required placeholder="コメントを入力してください"></textarea>
            </div>
            <button type="submit"
                @if(!Auth::check()) onclick="event.preventDefault(); window.location.href='{{ route('login.show') }}';" @endif>
                コメントを送信する
            </button>
        </form>
    </div>
</div>
@endsection
