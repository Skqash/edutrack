<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('school_requests', 'request_type')) {
                $table->enum('request_type', ['school', 'subject', 'course', 'class'])->default('school')->after('status');
            }
            if (!Schema::hasColumn('school_requests', 'related_id')) {
                $table->unsignedBigInteger('related_id')->nullable()->after('request_type');
            }
            if (!Schema::hasColumn('school_requests', 'related_name')) {
                $table->string('related_name')->nullable()->after('related_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('school_requests', function (Blueprint $table) {
            if (Schema::hasColumn('school_requests', 'related_name')) {
                $table->dropColumn('related_name');
            }
            if (Schema::hasColumn('school_requests', 'related_id')) {
                $table->dropColumn('related_id');
            }
            if (Schema::hasColumn('school_requests', 'request_type')) {
                $table->dropColumn('request_type');
            }
        });
    }
};
