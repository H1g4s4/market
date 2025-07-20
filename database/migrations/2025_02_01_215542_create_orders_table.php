<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null'); // 商品ID
            $table->string('status')->default('pending'); // 注文ステータス（例: pending, paid, shipped）
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
