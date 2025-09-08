<?php
// Create migration: php artisan make:migration create_puja_kit_puja_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('puja_kit_puja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puja_kit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('puja_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('puja_kit_puja');
    }
};
