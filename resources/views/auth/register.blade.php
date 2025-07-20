@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('title', '会員登録')

@section('content')
<div class="register-page">
    <h1>会員登録</h1>
    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="username">ユーザ名</label>
            <input id="username" type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
        </div>
        <button type="submit" class="register-button">登録する</button>
    </form>
    <div class="login-link">
        <a href="{{ route('login.show') }}">ログインはこちら</a>
    </div>
</div>
@endsection
