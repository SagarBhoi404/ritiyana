<?php
// Create migration: php artisan make:migration create_vendor_analytics_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            
            // Sales Metrics
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->integer('total_products_sold')->default(0);
            
            // Product Metrics
            $table->integer('active_products')->default(0);
            $table->integer('pending_products')->default(0);
            $table->integer('product_views')->default(0);
            $table->integer('product_clicks')->default(0);
            
            // Customer Metrics
            $table->integer('new_customers')->default(0);
            $table->integer('returning_customers')->default(0);
            
            $table->timestamps();
            $table->unique(['vendor_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_analytics');
    }
};
