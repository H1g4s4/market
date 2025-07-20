@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('title', 'ログイン')

@section('content')
<div class="login-container">
    <h1>ログイン</h1>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        @if ($errors->has('login'))
            <div class="error-messages">
                {{ $errors->first('login') }}
            </div>
        @endif

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

        <button type="submit" class="login-button">ログインする</button>
    </form>

    <div class="register-link">
        <a href="{{ route('register.show') }}">会員登録はこちら</a>
    </div>
</div>
@endsection
