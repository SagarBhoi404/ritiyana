<?php

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
        Schema::create('puja_kits', function (Blueprint $table) {
            $table->id();
            $table->string('kit_name');
            $table->string('slug')->unique();
            $table->foreignId('puja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->text('kit_description')->nullable();
            $table->json('included_items')->nullable(); // Store product IDs with quantities
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('puja_kits', function (Blueprint $table) {
            // Remove single puja_id and product_id columns if they exist
            $table->dropForeign(['puja_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['puja_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puja_kits');
    }
};
