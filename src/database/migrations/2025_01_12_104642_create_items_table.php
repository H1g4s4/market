<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 商品名
            $table->string('image'); // 商品画像
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 出品者
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null'); // 購入者
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}

