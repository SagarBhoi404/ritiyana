<?php
// Create migration: php artisan make:migration add_vendor_fields_to_order_items_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            $table->decimal('vendor_commission', 8, 2)->default(0)->after('total');
            $table->decimal('vendor_earning', 8, 2)->default(0)->after('vendor_commission');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['vendor_id', 'vendor_commission', 'vendor_earning']);
        });
    }
};
