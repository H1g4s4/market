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
    </div>

    <!-- 商品一覧 -->
    <div class="items-grid">
        @foreach ($items as $item)
            <div class="item">
                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                <p>{{ $item->name }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
