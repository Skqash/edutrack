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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('program_code')->unique();
            $table->string('program_name');
            $table->string('department')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('head_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('duration')->nullable();
            $table->integer('max_students')->nullable();
            $table->integer('current_students')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
