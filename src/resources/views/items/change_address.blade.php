@extends('layouts.app')

@section('title', '住所の変更')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/change.css') }}">
@endpush

@section('content')
<div class="address-change-container">
    <h1>住所の変更</h1>
    <form method="POST" action="{{ route('items.updateAddress', $item->id) }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $item->delivery_postal_code ?? $user->postal_code) }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $item->delivery_address ?? $user->address) }}">
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $item->delivery_building) }}">
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
