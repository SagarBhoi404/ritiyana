<?php
// Create migration: php artisan make:migration create_puja_kit_product_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('puja_kit_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puja_kit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->nullable(); // Override price if needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('puja_kit_product');
    }
};
