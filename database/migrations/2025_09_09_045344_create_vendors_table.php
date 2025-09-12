<?php
// Create migration: php artisan make:migration create_vendors_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            
            // Business Information
            $table->string('business_name');
            $table->enum('business_type', ['individual', 'partnership', 'company', 'proprietorship'])->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_phone', 15)->nullable();
            $table->string('business_email')->nullable();
            
            // Tax & Legal Details
            $table->string('tax_id', 50)->nullable();
            $table->string('pan_number', 10)->nullable();
            $table->string('gst_number', 15)->nullable();
            
            // Bank Details
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code', 11)->nullable();
            $table->string('account_holder_name')->nullable();
            
            // Business Settings
            $table->decimal('commission_rate', 5, 2)->default(8.00);
            $table->string('store_logo')->nullable();
            $table->text('store_description')->nullable();
            $table->json('business_hours')->nullable();
            
            // Status & Approval
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            
            // Additional Fields
            $table->boolean('is_featured')->default(false);
            $table->integer('priority_order')->default(0);
            $table->json('social_links')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};
