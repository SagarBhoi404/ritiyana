<?php
// Create new migration file: database/migrations/xxxx_xx_xx_xxxxxx_add_puja_kit_to_cart_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('puja_kit_id')->nullable()->after('product_id');
            $table->string('item_type')->default('product')->after('puja_kit_id'); // 'product' or 'puja_kit'
            $table->string('item_name')->nullable()->after('item_type'); // Store kit name for display
            $table->string('item_image')->nullable()->after('item_name'); // Store kit image
            
            $table->foreign('puja_kit_id')->references('id')->on('puja_kits')->onDelete('cascade');
            $table->index(['item_type', 'puja_kit_id']);
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['puja_kit_id']);
            $table->dropColumn(['puja_kit_id', 'item_type', 'item_name', 'item_image']);
        });
    }
};
