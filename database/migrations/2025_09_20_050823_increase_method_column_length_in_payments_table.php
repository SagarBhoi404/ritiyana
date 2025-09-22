<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('method', 20)->change(); // Increase from current length to 20 characters
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('method', 10)->change(); // Revert back to original length
        });
    }
};
