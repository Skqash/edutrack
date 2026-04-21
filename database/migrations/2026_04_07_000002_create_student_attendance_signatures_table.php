<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates table for storing student e-signatures for attendance records
     */
    public function up(): void
    {
        Schema::create('student_attendance_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            // Signature Data
            $table->string('signature_type')->comment('Type: digital, upload, pen-based, etc');
            $table->text('signature_data')->comment('Base64 or path to signature file');
            $table->string('signature_filename')->nullable()->comment('Original filename if uploaded');
            $table->string('signature_mime_type')->nullable()->comment('MIME type: image/png, etc');
            $table->integer('signature_size')->nullable()->comment('File size in bytes');
            
            // Metadata
            $table->enum('term', ['midterm', 'final', 'general'])->default('general');
            $table->date('signed_date')->comment('Date when signature was captured');
            $table->string('ip_address')->nullable()->comment('IP address of signer');
            $table->string('user_agent')->nullable()->comment('Browser user agent');
            $table->text('additional_metadata')->nullable()->comment('JSON metadata');
            
            // Verification
            $table->boolean('is_verified')->default(false)->comment('Admin verification status');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'archived'])->default('pending');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'class_id']);
            $table->index(['class_id', 'term']);
            $table->index(['signed_date']);
            $table->index(['status']);
            $table->unique(['student_id', 'class_id', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance_signatures');
    }
};
