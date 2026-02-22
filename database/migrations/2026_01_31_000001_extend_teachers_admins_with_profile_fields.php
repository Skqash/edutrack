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
        // Extend teachers table with profile fields
        if (!Schema::hasColumn('teachers', 'bio')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('qualification');
                $table->string('specialization')->nullable()->after('bio');
                $table->string('department')->nullable()->after('specialization');
            });
        }

        // Create admins table if it doesn't exist
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('employee_id')->unique();
                $table->text('bio')->nullable();
                $table->string('department')->nullable();
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->timestamps();
            });
        } else {
            // Add fields to existing admins table if they don't exist
            if (!Schema::hasColumn('admins', 'bio')) {
                Schema::table('admins', function (Blueprint $table) {
                    $table->text('bio')->nullable();
                    $table->string('department')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'bio')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn(['bio', 'specialization', 'department']);
            });
        }

        if (Schema::hasTable('admins')) {
            Schema::dropIfExists('admins');
        }
    }
};
