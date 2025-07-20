<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryAddressToItemsTable extends Migration
{
    public function up()
    {
    Schema::table('items', function (Blueprint $table) {
        $table->string('delivery_postal_code')->nullable()->after('buyer_id'); // 配送先郵便番号
        $table->string('delivery_address')->nullable()->after('delivery_postal_code'); // 配送先住所
        $table->string('delivery_building')->nullable()->after('delivery_address'); // 配送先建物名
    });
    }

    public function down()
    {
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn(['delivery_postal_code', 'delivery_address', 'delivery_building']);
    });
    }

}
