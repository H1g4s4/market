<?php

return [
    'required' => ':attribute は必須です。',
    'string' => ':attribute は文字列で入力してください。',
    'numeric' => ':attribute は数値で入力してください。',
    'min' => [
        'numeric' => ':attribute は :min 以上で入力してください。',
    ],
    'image' => ':attribute は画像ファイルをアップロードしてください。',
    'mimes' => ':attribute は jpeg または png 形式でアップロードしてください。',
    'max' => ':attribute のサイズは :max KB 以下にしてください。',

    'attributes' => [
        'categories' => 'カテゴリー',
        'condition' => '商品の状態',
        'name' => '商品名',
        'description' => '商品の説明',
        'price' => '販売価格',
        'image' => '商品画像',
    ],

    'required' => ':attribute を入力してください。',
    'regex' => ':attribute の形式が正しくありません。',
    'max' => [
        'string' => ':attribute は最大 :max 文字まで入力できます。',
    ],
];
