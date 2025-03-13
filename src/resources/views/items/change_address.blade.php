@extends('layouts.app')

@section('title', '配送先住所の変更')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/change.css') }}">
@endpush

@section('content')
<div class="address-container">
    <h1>配送先住所の変更</h1>

    <form method="POST" action="{{ route('items.updateAddress', $item->id) }}">
        @csrf

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p> <!-- ✅ 赤字エラー表示 -->
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->address) }}">
            @error('address')
                <p class="error-message">{{ $message }}</p> <!-- ✅ 赤字エラー表示 -->
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名（任意）</label>
            <input type="text" id="building" name="building" value="{{ old('building', Auth::user()->building) }}">
            @error('building')
                <p class="error-message">{{ $message }}</p> <!-- ✅ 赤字エラー表示 -->
            @enderror
        </div>

        <button type="submit" class="submit-button">変更を保存</button>
    </form>
</div>
@endsection
