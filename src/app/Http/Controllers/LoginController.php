<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログインフォームを表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        // 認証を実行（エラー時はLoginRequestの `authenticate()` で処理）
        $request->authenticate();

        // 認証成功時にリダイレクト
        return redirect()->route('items.index')->with('success', 'ログインしました');
    }

    /**
     * ログアウト処理
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.show')->with('success', 'ログアウトしました');
    }
}
