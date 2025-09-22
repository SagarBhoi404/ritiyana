<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add puja_kit_id column
            $table->unsignedBigInteger('puja_kit_id')->nullable()->after('product_id');
            
            // Add item_type column to distinguish between products and puja kits
            $table->string('item_type')->default('product')->after('puja_kit_id');
            
            // Add foreign key constraint
            $table->foreign('puja_kit_id')->references('id')->on('puja_kits')->onDelete('cascade');
            
            // Add index
            $table->index(['item_type', 'puja_kit_id']);
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['puja_kit_id']);
            $table->dropIndex(['item_type', 'puja_kit_id']);
            $table->dropColumn(['puja_kit_id', 'item_type']);
        });
    }
};
