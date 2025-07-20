@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('title', 'プロフィール')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-image">
            <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('images/default-profile.png') }}" alt="プロフィール画像">
        </div>
        <div class="profile-info">
            <h1>{{ $user->name }}</h1>
            <!-- ★ 評価星表示（評価がある場合） -->
            @if (!is_null($averageRating))
                <div class="user-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($averageRating))
                            <span style="color: #FFD700;">★</span>
                        @else
                            <span style="color: #ccc;">★</span>
                        @endif
                    @endfor
                </div>
            @else
                <!-- ★ 評価がない場合は灰色星5つ -->
                <div class="user-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <span style="color: #ccc;">★</span>
                    @endfor
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="edit-button">プロフィールを編集</a>
        </div>
    </div>

    <!-- タブメニュー -->
    <div class="tabs">
        <a href="{{ route('profile.show', ['tab' => 'sell']) }}" class="{{ request('tab', 'sell') === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.show', ['tab' => 'buy']) }}" class="{{ request('tab') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
        <a href="{{ route('profile.show', ['tab' => 'transaction']) }}" class="{{ request('tab') === 'transaction' ? 'active' : '' }}">
            取引中の商品
            @if($transactions->count() > 0)
                <span class="badge">{{ $transactions->count() }}</span>
            @endif
        </a>

        </a>
    </div>

    <!-- 商品一覧表示 -->
    <div class="items-grid">
        @if(request('tab', 'sell') === 'sell')
            @foreach ($items as $item)
                <div class="item">
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                    <p>{{ $item->name }}</p>
                </div>
            @endforeach

        @elseif(request('tab') === 'buy')
            @foreach ($items as $item)
                <div class="item">
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                    <p>{{ $item->name }}</p>
                </div>
            @endforeach

        @elseif(request('tab') === 'transaction')
            @if(isset($transactions) && $transactions->count() > 0)
                <div class="items-grid">
                    @foreach($transactions as $transaction)
                        <div class="item">
                            <a href="{{ route('chat.show', ['transaction' => $transaction->id]) }}">
                                <img src="{{ Storage::url($transaction->item->image) }}" alt="{{ $transaction->item->name }}">
                                <p>{{ $transaction->item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>取引中の商品はありません。</p>
            @endif
        @endif
    </div>
</div>
@endsection
