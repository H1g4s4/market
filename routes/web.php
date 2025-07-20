<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChatController;

// ✅ 認証が必要なルート
Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show'); // プロフィール画面（出品・購入タブ含む）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // プロフィール編集画面
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update'); // プロフィール更新処理
    Route::get('/mypage/items', [ItemController::class, 'myItems'])->name('profile.items');
     Route::get('/mypage/transactions', [TransactionController::class, 'index'])->name('transactions.index'); // 取引中一覧
    Route::patch('/chat/message/{message}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/message/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');
    // 評価投稿（購入者・出品者共通）
    Route::post('/review/{transaction}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::get('/chat/{transaction}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{transaction}', [ChatController::class, 'store'])->name('chat.store');
    Route::patch('/chat/{transaction}/complete', [ChatController::class, 'complete'])->name('chat.complete');
    Route::post('/chat/{transaction}/complete', [ChatController::class, 'complete'])->name('chat.complete');

    // 商品出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create'); // 商品出品画面
    Route::post('/items', [ItemController::class, 'store'])->name('items.store'); // 商品出品処理

    // いいね機能（ログイン必須）
    Route::post('/items/{id}/like', [ItemController::class, 'like'])->name('items.like');
    Route::post('/items/{id}/unlike', [ItemController::class, 'unlike'])->name('items.unlike');
});

// ✅ 認証不要なルート（誰でもアクセス可能）
Route::get('/', [ItemController::class, 'index'])->name('items.index'); // 商品一覧画面（トップ画面）
Route::get('/?tab=mylist', [ItemController::class, 'mylist'])->name('items.mylist'); // マイリスト（いいねした商品）

// ✅ 認証関連ルート
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.show'); // 会員登録画面
Route::post('/register', [RegisterController::class, 'register'])->name('register.store'); // 会員登録処理

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show'); // ログイン画面
Route::post('/login', [LoginController::class, 'login'])->name('login.store'); // ログイン処理
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // ログアウト処理

// ✅ 商品詳細 & コメント機能
Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->whereNumber('item_id')
    ->name('items.detail'); // 商品詳細画面
Route::post('/item/{item_id}/comment', [ItemController::class, 'comment'])
    ->middleware('auth')
    ->name('items.comment'); // コメント送信（ログイン必須）

// ✅ 商品購入関連
Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])
    ->whereNumber('item_id')
    ->middleware('auth')
    ->name('items.purchase'); // 商品購入画面
Route::post('/purchase/{item_id}', [ItemController::class, 'completePurchase'])
    ->whereNumber('item_id')
    ->middleware('auth')
    ->name('items.completePurchase'); // 購入処理
Route::get('/purchase/address/{item_id}', [ItemController::class, 'changeAddress'])
    ->whereNumber('item_id')
    ->middleware('auth')
    ->name('items.changeAddress'); // 送付先住所変更画面
Route::post('/purchase/address/{item_id}', [ItemController::class, 'updateAddress'])
    ->whereNumber('item_id')
    ->middleware('auth')
    ->name('items.updateAddress'); // 送付先住所更新処理