<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name' => '腕時計', 'price' => 15000, 'description' => 'スタイリッシュなデザインのメンズ腕時計', 'image' => 'watch.jpg', 'condition' => '良好'],
            ['name' => 'HDD', 'price' => 5000, 'description' => '高速で信頼性の高いハードディスク', 'image' => 'hdd.jpg', 'condition' => '目立った傷や汚れなし'],
            ['name' => '玉ねぎ3束', 'price' => 500, 'description' => '新鮮な玉ねぎ3束のセット', 'image' => 'vegi.jpg', 'condition' => 'やや傷や汚れあり'],
            ['name' => '革靴', 'price' => 4000, 'description' => 'クラシックなデザインの革靴', 'image' => 'shoes.jpg', 'condition' => '状態が悪い'],
            ['name' => 'ノートPC', 'price' => 45000, 'description' => '高性能なノートパソコン', 'image' => 'laptop.jpg', 'condition' => '良好'],
            ['name' => 'マイク', 'price' => 8000, 'description' => '高音質のレコーディング用マイク', 'image' => 'mic.jpg', 'condition' => '目立った傷や汚れなし'],
            ['name' => 'ショルダーバッグ', 'price' => 3000, 'description' => 'おしゃれなショルダーバッグ', 'image' => 'bag.jpg', 'condition' => 'やや傷や汚れあり'],
            ['name' => 'タンブラー', 'price' => 500, 'description' => '使いやすいタンブラー', 'image' => 'Tumbler.jpg', 'condition' => '状態が悪い'],
            ['name' => 'コーヒーミル', 'price' => 4000, 'description' => '手動のコーヒーミル', 'image' => 'Coffee.jpg', 'condition' => '良好'],
            ['name' => 'メイクセット', 'price' => 2500, 'description' => '便利なメイクアップセット', 'image' => 'make.jpg', 'condition' => '目立った傷や汚れなし'],
        ];

        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image' => 'item_images/' . $item['image'], // ✅ 修正: 'img' → 'image'
                'condition' => $item['condition'],
                'user_id' => User::inRandomOrder()->first()->id, // ランダムなユーザーに紐付け
                'category_id' => Category::inRandomOrder()->first()->id, // ランダムなカテゴリに紐付け
            ]);
        }
    }
}
