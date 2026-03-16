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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'school')) {
                $table->string('school')->nullable()->after('status');
            }
            if (!Schema::hasColumn('students', 'department')) {
                $table->string('department')->nullable()->after('school');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('students', 'school')) {
                $table->dropColumn('school');
            }
        });
    }
};
