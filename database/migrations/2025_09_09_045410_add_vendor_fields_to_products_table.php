<?php
// Create migration: php artisan make:migration add_vendor_fields_to_products_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Link product to vendor
            $table->foreignId('vendor_id')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            
            // Approval system
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved')->after('is_active');
            $table->timestamp('approved_at')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            
            // Vendor-specific fields
            $table->boolean('is_vendor_product')->default(false)->after('approved_by');
            $table->decimal('vendor_commission_rate', 5, 2)->nullable()->after('is_vendor_product');
            
            // Sales tracking
            $table->integer('total_sales')->default(0)->after('vendor_commission_rate');
            $table->decimal('total_revenue', 12, 2)->default(0)->after('total_sales');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'vendor_id', 
                'approval_status', 
                'approved_at', 
                'approved_by',
                'is_vendor_product',
                'vendor_commission_rate',
                'total_sales',
                'total_revenue'
            ]);
        });
    }
};
