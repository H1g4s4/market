<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // 会員登録画面を表示
    public function showRegistrationForm()
    {
        return view('auth.register'); // 会員登録画面の Blade ファイル
    }

    // 会員登録処理
    public function register(RegisterRequest $request)
    {
        // バリデーション済みデータを取得
        $validated = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 作成したユーザーでログイン
        Auth::login($user);

        // プロフィール設定画面にリダイレクト
        return redirect()->route('profile.edit')->with('success', '会員登録が完了しました。プロフィールを設定してください。');
    }
}

