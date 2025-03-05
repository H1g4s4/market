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

        <!-- ✅ プロフィール画像の枠は残し、テキストを削除 -->
        <div class="form-group profile-image-section">
            <div class="profile-image-preview" id="profile-image-preview">
                <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : '' }}" 
                    alt="プロフィール画像"
                    id="profile-image">
                <!-- ✅ プレースホルダーのテキストを削除 -->
                <span class="profile-image-placeholder" id="profile-placeholder" style="display: none;"></span>
            </div>
            <!-- ✅ 「画像を選択する」ボタンのテキストのみ表示 -->
            <label for="profile_image" class="custom-file-upload" id="profile-image-label">画像を選択する</label>
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ スクリプトが正常に読み込まれました");

    const profileImageInput = document.getElementById("profile_image");
    const profileImage = document.getElementById("profile-image");
    const profilePlaceholder = document.getElementById("profile-placeholder");
    const profileImageLabel = document.getElementById("profile-image-label");
    const profileForm = document.querySelector(".profile-form");

    if (!profileImageInput || !profileImage || !profilePlaceholder || !profileImageLabel || !profileForm) {
        console.error("❌ エラー: 必要な要素が見つかりません");
        return;
    }

    // ✅ ページロード時に現在のプロフィール画像を表示
    const currentProfileImage = profileImage.getAttribute("src");
    if (currentProfileImage && !currentProfileImage.includes("default-profile.png")) {
        profileImage.style.display = "block";
        profilePlaceholder.style.display = "none";
    } else {
        profileImage.style.display = "none";
        profilePlaceholder.style.display = "block";
    }

    // ✅ 画像選択ボタンを押したとき、1回だけファイル選択ダイアログを開く
    profileImageLabel.addEventListener("click", function (event) {
        event.preventDefault();
        profileImageInput.click();
    });

    // ✅ ファイルが選択された時の処理
    profileImageInput.addEventListener("change", function (event) {
        console.log("📁 ファイルが選択されました");

        const file = event.target.files[0];

        if (!file) {
            console.warn("⚠️ 選択されたファイルがありません");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            profileImage.src = e.target.result;
            profileImage.style.display = "block";
            profilePlaceholder.style.display = "none";
            console.log("🖼️ プロフィール画像のプレビューを表示しました");
        };
        reader.readAsDataURL(file);
    });

    // ✅ 成功メッセージを表示する関数
    function showSuccessMessage(message) {
        const messageContainer = document.createElement("div");
        messageContainer.classList.add("success-message");
        messageContainer.innerText = message;
        document.body.appendChild(messageContainer);

        // 2秒後にメッセージをフェードアウト
        setTimeout(() => {
            messageContainer.style.opacity = "0";
            setTimeout(() => messageContainer.remove(), 500);
        }, 2000);
    }

    // ✅ フォーム送信時に `FormData` に画像を含めて送信
    profileForm.addEventListener("submit", function (event) {
        event.preventDefault(); // デフォルトの送信を防ぐ
        console.log("🚀 フォーム送信開始");

        const formData = new FormData(profileForm);

        fetch(profileForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("📩 サーバーからのレスポンス:", data);
            if (data.success) {
                console.log("✅ プロフィールが更新されました");
                showSuccessMessage("プロフィールが更新されました");

                // ✅ 2秒後にプロフィール画面へリダイレクト
                setTimeout(() => {
                    window.location.href = "{{ route('profile.show') }}";
                }, 2000);
            } else {
                console.error("❌ 更新に失敗しました", data);
            }
        })
        .catch(error => console.error("❌ エラー:", error));
    });
});
</script>

<style>
/* ✅ 成功メッセージのデザイン */
.success-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #28a745;
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    opacity: 1;
    transition: opacity 0.5s ease-in-out;
}
</style>
@endsection
