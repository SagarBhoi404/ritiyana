<?php
// Create this file: database/migrations/2025_09_13_add_image_to_puja_kits_table.php

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
        Schema::table('puja_kits', function (Blueprint $table) {
            $table->string('image')->nullable()->after('kit_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puja_kits', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
