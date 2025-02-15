@extends('layouts.app')

@section('title', 'プロフィール設定')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')
<div class="profile-edit-container">
    <h1 class="profile-title">プロフィール設定</h1>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        @if (session('success'))
            <div class="flash-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- ✅ プロフィール画像 -->
        <div class="form-group profile-image-section">
            <label for="profile_image" class="profile-image-label">プロフィール画像</label>
            <div class="profile-image-preview">
                <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('images/default-profile.png') }}" alt="プロフィール画像">
            </div>
            <label for="profile_image" class="custom-file-upload">画像を選択する</label>
            <input id="profile_image" type="file" name="profile_image" accept="image/*" class="file-input">
            @error('profile_image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ ユーザー名 -->
        <div class="form-group">
            <label for="username">ユーザー名</label>
            <input id="username" type="text" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
            <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ 建物名 -->
        <div class="form-group">
            <label for="building">建物名</label>
            <input id="building" type="text" name="building" value="{{ old('building', $user->building) }}">
            @error('building')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit-button">更新する</button>
    </form>
</div>
@endsection
