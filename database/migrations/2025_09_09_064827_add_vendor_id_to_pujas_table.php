<?php
// Migration file
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pujas', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pujas', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
        });
    }
};
