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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->unique()->after('role');
            }
            if (!Schema::hasColumn('users', 'qualification')) {
                $table->string('qualification')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('users', 'specialization')) {
                $table->string('specialization')->nullable()->after('qualification');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('specialization');
            }
            if (!Schema::hasColumn('users', 'campus')) {
                $table->string('campus')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'connected_school')) {
                $table->string('connected_school')->nullable()->after('campus');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('connected_school');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
            if (Schema::hasColumn('users', 'connected_school')) {
                $table->dropColumn('connected_school');
            }
            if (Schema::hasColumn('users', 'campus')) {
                $table->dropColumn('campus');
            }
            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('users', 'specialization')) {
                $table->dropColumn('specialization');
            }
            if (Schema::hasColumn('users', 'qualification')) {
                $table->dropColumn('qualification');
            }
            if (Schema::hasColumn('users', 'employee_id')) {
                $table->dropUnique(['employee_id']);
                $table->dropColumn('employee_id');
            }
        });
    }
};
