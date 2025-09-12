<?php
// Create migration: php artisan make:migration create_vendor_documents_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            
            // Document Information
            $table->enum('document_type', [
                'business_license', 
                'tax_certificate', 
                'bank_statement', 
                'identity_proof', 
                'address_proof',
                'gst_certificate',
                'pan_card',
                'other'
            ]);
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type', 10);
            $table->integer('file_size');
            
            // Verification Status
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            
            // Additional Info
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_documents');
    }
};
