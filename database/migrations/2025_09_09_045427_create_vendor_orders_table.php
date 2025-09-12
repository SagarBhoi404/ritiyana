<?php
// Create migration: php artisan make:migration create_vendor_orders_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_order_number')->unique();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Order Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Commission Calculation
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('vendor_earning', 10, 2);
            
            // Order Status
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Shipping Info
            $table->string('tracking_number')->nullable();
            $table->json('shipping_address')->nullable();
            $table->text('vendor_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Order Items (JSON storage for flexibility)
            $table->json('order_items');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_orders');
    }
};
