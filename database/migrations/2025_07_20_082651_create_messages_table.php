<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade'); // 取引
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 投稿者
            $table->text('body'); // 本文
            $table->string('image')->nullable(); // 画像（任意）
            $table->boolean('is_read')->default(false); // 既読管理
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
