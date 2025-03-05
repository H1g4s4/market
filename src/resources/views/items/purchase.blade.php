@extends('layouts.app')

@section('title', '商品購入')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    <!-- ✅ 左側（商品情報 + 支払い方法 + 配送先） -->
    <div class="purchase-left">
        <div class="item-info-container">
            <!-- 商品画像 -->
            <div class="item-image">
                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
            </div>
            <!-- 商品名と価格 -->
            <div class="item-text">
                <h2>{{ $item->name }}</h2>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <!-- ✅ 商品情報と支払い方法の間に棒線 -->
        <div class="divider"></div>

        <!-- ✅ 支払い方法 -->
        <form method="POST" action="{{ route('items.completePurchase', $item->id) }}">
            @csrf
            <div class="form-group">
                <label for="payment_method">支払い方法</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="コンビニ支払い">コンビニ支払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>
            </div>

            <!-- ✅ 支払い方法と配送先の間に棒線 -->
            <div class="divider"></div>

            <!-- ✅ 配送先情報 -->
            <div class="form-group">
                <div class="address-header">
                    <label>配送先</label>
                    <a href="{{ route('items.changeAddress', $item->id) }}" class="change-address">変更する</a>
                </div>
                @if(Auth::check())
                    <p>〒 {{ Auth::user()->postal_code }}</p>
                    <p>{{ Auth::user()->address }}</p>
                    <p>{{ Auth::user()->building ?? '建物名なし' }}</p>
                @else
                    <p>ログインが必要です。</p>
                    <a href="{{ route('login.show') }}" class="login-button">ログインする</a>
                @endif
            </div>
    </div>

    <!-- ✅ 右側（購入情報 + 購入ボタン） -->
    <div class="purchase-right">
        <div class="summary-box">
            <p>商品代金: <span>¥{{ number_format($item->price) }}</span></p>
            <p>支払い方法: <span id="selected-payment">コンビニ支払い</span></p>
        </div>

        <!-- ✅ 購入ボタン（ボックス外に配置） -->
        @if(Auth::check())
            <button type="submit" class="purchase-button">購入する</button>
        @else
            <p class="login-warning">購入にはログインが必要です。</p>
            <a href="{{ route('login.show') }}" class="login-button">ログインする</a>
        @endif
    </div>
    </form>
</div>
@endsection
