<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'campus_status')) {
                $table->enum('campus_status', ['pending', 'approved', 'rejected'])->default('pending')->after('campus');
            }
            if (!Schema::hasColumn('users', 'campus_approved_at')) {
                $table->timestamp('campus_approved_at')->nullable()->after('campus_status');
            }
            if (!Schema::hasColumn('users', 'campus_approved_by')) {
                $table->foreignId('campus_approved_by')->nullable()->constrained('users')->after('campus_approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'campus_approved_by')) {
                $table->dropForeign(['campus_approved_by']);
                $table->dropColumn('campus_approved_by');
            }
            if (Schema::hasColumn('users', 'campus_approved_at')) {
                $table->dropColumn('campus_approved_at');
            }
            if (Schema::hasColumn('users', 'campus_status')) {
                $table->dropColumn('campus_status');
            }
        });
    }
};