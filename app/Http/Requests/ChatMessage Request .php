<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ChatMessage;

class ChatMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認証済みであることを前提に true
    }

    public function rules()
    {
        return [
            'body' => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max' => '本文は400文字以内で入力してください',
            'image.image' => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }

    public function destroy(ChatMessage $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json(['success' => true]);
    }
}
