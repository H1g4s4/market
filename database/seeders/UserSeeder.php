<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'profile_image' => 'public/profile_images/default.jpg',
        ]);

        User::factory(10)->create(); // 追加のダミーユーザー
    }
}
