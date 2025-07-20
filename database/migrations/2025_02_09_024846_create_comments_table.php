<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // コメントを投稿したユーザー
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // 関連する商品
            $table->text('comment'); // コメント内容
            $table->timestamps(); // 作成・更新時刻
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
