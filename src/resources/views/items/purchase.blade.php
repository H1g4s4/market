@extends('layouts.app')

@section('title', '商品購入')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    <!-- ✅ 左側（商品情報 + 支払い方法 + 配送先） -->
    <div class="purchase-left">
        <div class="item-detail">
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
            <h2>{{ $item->name }}</h2>
            <p class="price">価格: ¥{{ number_format($item->price) }}</p>
        </div>

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

            <!-- ✅ 配送先情報 -->
            <div class="form-group">
                <label>配送先</label>
                @if(Auth::check())
                    <p>〒 {{ Auth::user()->postal_code }}</p>
                    <p>{{ Auth::user()->address }}</p>
                    <p>{{ Auth::user()->building ?? '建物名なし' }}</p>
                    <a href="{{ route('items.changeAddress', $item->id) }}" class="change-address">配送先を変更する</a>
                @else
                    <p>ログインが必要です。</p>
                    <a href="{{ route('login.show') }}" class="login-button">ログインする</a> <!-- ✅ 修正 -->
                @endif
            </div>

            <!-- ✅ 右側（支払い情報 + 合計金額 + 購入ボタン） -->
            <div class="purchase-right">
                <h2>購入情報</h2>
                <div class="summary">
                    <p>商品代金: <span>¥{{ number_format($item->price) }}</span></p>
                    <p>支払い方法: <span id="selected-payment">コンビニ支払い</span></p>
                </div>

                <!-- ✅ 購入ボタン -->
                @if(Auth::check())
                    <button type="submit" class="purchase-button">購入する</button>
                @else
                    <p class="login-warning">購入にはログインが必要です。</p>
                    <a href="{{ route('login.show') }}" class="login-button">ログインする</a> <!-- ✅ 修正 -->
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
