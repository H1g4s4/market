<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'categories' => 'required|array',
            'categories.*' => 'string',
            'condition' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'categories.required' => 'カテゴリーを選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'price.required' => '販売価格を入力してください。',
            'price.numeric' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0円以上で入力してください。',
            'image.required' => '商品画像をアップロードしてください。',
            'image.image' => 'アップロードするファイルは画像である必要があります。',
            'image.mimes' => '商品画像は jpeg または png 形式でアップロードしてください。',
            'image.max' => '商品画像の最大サイズは 2MB です。',
        ];
    }
}
