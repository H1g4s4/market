<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * 権限確認（常に true を返す）
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルールの定義
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * カスタムエラーメッセージの定義
     */
    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '正しいメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }

    /**
     * 認証エラーの処理
     */
    public function authenticate()
    {
        if (!Auth::attempt($this->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'login' => 'ログイン情報が登録されていません。',
            ]);
        }
    }
}
