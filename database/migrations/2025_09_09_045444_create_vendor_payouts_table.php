<?php
// Create migration: php artisan make:migration create_vendor_payouts_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->unique();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            
            // Payout Details
            $table->decimal('amount', 10, 2);
            $table->enum('payout_method', ['bank_transfer', 'upi', 'wallet', 'cheque'])->default('bank_transfer');
            
            // Period Information
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_orders');
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_commission', 10, 2);
            
            // Status & Processing
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Bank Details Snapshot
            $table->json('bank_details')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_payouts');
    }
};
