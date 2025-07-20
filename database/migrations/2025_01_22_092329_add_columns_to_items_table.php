<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->json('categories')->after('image')->nullable(); // カテゴリ
            $table->string('condition')->after('categories')->nullable(); // 商品の状態
            $table->text('description')->after('condition')->nullable(); // 商品の説明
            $table->unsignedBigInteger('price')->after('description')->nullable(); // 販売価格
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['categories', 'condition', 'description', 'price']);
        });
    }
}
