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
            <div class="image-upload-box">
                <input id="image" type="file" name="image" accept="image/*" style="opacity: 0; position: absolute; width: 1px; height: 1px;">
                <label for="image" class="image-placeholder" id="image-placeholder">画像を選択する</label>
                <img id="image-preview" style="display: none; max-width: 100%; border-radius: 6px;">
            </div>
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

        <!-- ✅ ブランド名の入力 -->
        <div class="form-group">
            <label for="brand_name">ブランド名</label>
            <input id="brand_name" type="text" name="brand_name" class="brand-input">
            @error('brand_name')
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ スクリプトが正常に読み込まれました");

    const imageInput = document.getElementById("image");
    const previewImg = document.getElementById("image-preview");
    const placeholder = document.getElementById("image-placeholder");

    if (!imageInput || !previewImg || !placeholder) {
        console.error("❌ エラー: 必要な要素が見つかりません");
        return;
    }

    // ✅ クリックイベントの重複を防ぐために既存のリスナーを削除
    placeholder.replaceWith(placeholder.cloneNode(true));
    const newPlaceholder = document.getElementById("image-placeholder");

    // ✅ プレースホルダーをクリックするとファイル選択を開く
    newPlaceholder.addEventListener("click", function () {
        if (!imageInput.clicked) {
            imageInput.clicked = true; // 二重クリックを防ぐ
            imageInput.click();
        }
    });

    // ✅ 画像選択時の処理
    imageInput.addEventListener("change", function (event) {
        console.log("📸 画像が選択されました");

        const file = event.target.files[0];
        if (!file) {
            console.log("❌ ファイルが選択されませんでした");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block"; // 画像を表示
            newPlaceholder.style.display = "none"; // プレースホルダーを非表示
            console.log("🖼️ 画像プレビューを表示しました");
        };

        reader.readAsDataURL(file);
    });
});
</script>
@endsection
