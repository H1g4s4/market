@extends('layouts.app')

@section('title', '商品の出品')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endpush

@section('content')
<div class="item-create-container">
    <h1 class="title">商品の出品</h1>
    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- ✅ 商品画像 -->
        <h2 class="section-title">商品の詳細</h2>
        <div class="form-group">
            <label for="image">商品画像</label>
            <input id="image" type="file" name="image" accept="image/*" hidden>
            <button type="button" class="image-button" onclick="document.getElementById('image').click();">画像を選択する</button>
            @error('image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ 商品の詳細 -->
        <h2 class="section-title">商品の詳細</h2>
        <div class="form-group category-selection">
            <label>カテゴリー</label>
            <div class="category-buttons">
                @foreach(['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
                    <label class="category-btn">
                        <input type="checkbox" name="categories[]" value="{{ $category }}" class="category-input"> 
                        <span>{{ $category }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition">商品の状態</label>
            <select id="condition" name="condition">
                <option value="" disabled selected>選択してください</option>
                <option value="新品">新品</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
            @error('condition')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <!-- ✅ 商品名と説明 -->
        <h2 class="section-title">商品名と説明</h2>
        <div class="form-group">
            <label for="name">商品名</label>
            <input id="name" type="text" name="name">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea id="description" name="description"></textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group price-group">
            <label for="price">販売価格</label>
            <div class="price-input">
                <span>¥</span>
                <input id="price" type="number" name="price" min="0">
            </div>
            @error('price')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>
@endsection
