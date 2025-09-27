<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_inventory_columns_to_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add reorder level column
            $table->integer('reorder_level')->nullable()->default(10)->after('stock_quantity');
            
            // Add other inventory-related columns if needed
            $table->decimal('cost_price', 10, 2)->nullable()->after('sale_price');
            $table->string('supplier_name')->nullable()->after('cost_price');
            $table->string('supplier_sku')->nullable()->after('supplier_name');
            $table->date('last_restocked_at')->nullable()->after('supplier_sku');
            $table->text('stock_notes')->nullable()->after('last_restocked_at');
            
            // Add indexes for better performance
            $table->index(['stock_quantity', 'reorder_level']);
            $table->index('last_restocked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['stock_quantity', 'reorder_level']);
            $table->dropIndex(['last_restocked_at']);
            
            // Drop columns
            $table->dropColumn([
                'reorder_level',
                'cost_price',
                'supplier_name',
                'supplier_sku', 
                'last_restocked_at',
                'stock_notes'
            ]);
        });
    }
};
