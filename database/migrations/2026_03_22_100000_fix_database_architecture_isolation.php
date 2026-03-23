<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Add school_id to teachers table (CRITICAL - missing isolation field)
        if (!Schema::hasColumn('teachers', 'school_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable()->after('campus');
                $table->index('school_id');
            });
        }

        // 2. Add campus and school_id to course_instructors (for data isolation)
        if (!Schema::hasColumn('course_instructors', 'campus')) {
            Schema::table('course_instructors', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('role');
                $table->unsignedBigInteger('school_id')->nullable()->after('campus');
                $table->index(['campus', 'school_id']);
            });
        }

        // 3. Add campus and school_id to course_access_requests (for data isolation)
        if (!Schema::hasColumn('course_access_requests', 'campus')) {
            Schema::table('course_access_requests', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id');
                $table->unsignedBigInteger('school_id')->nullable()->after('campus');
                $table->index(['campus', 'school_id']);
            });
        }

        // 4. Populate missing school_id in teachers from users table
        DB::statement('
            UPDATE teachers t
            INNER JOIN users u ON t.user_id = u.id
            SET t.school_id = u.school_id
            WHERE t.school_id IS NULL AND u.school_id IS NOT NULL
        ');

        // 5. Populate campus and school_id in course_instructors from users
        DB::statement('
            UPDATE course_instructors ci
            INNER JOIN users u ON ci.user_id = u.id
            SET ci.campus = u.campus, ci.school_id = u.school_id
            WHERE ci.campus IS NULL AND u.campus IS NOT NULL
        ');

        // 6. Populate campus and school_id in course_access_requests from users
        DB::statement('
            UPDATE course_access_requests car
            INNER JOIN users u ON car.teacher_id = u.id
            SET car.campus = u.campus, car.school_id = u.school_id
            WHERE car.campus IS NULL AND u.campus IS NOT NULL
        ');
    }

    public function down()
    {
        if (Schema::hasColumn('teachers', 'school_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('school_id');
            });
        }

        if (Schema::hasColumn('course_instructors', 'campus')) {
            Schema::table('course_instructors', function (Blueprint $table) {
                $table->dropColumn(['campus', 'school_id']);
            });
        }

        if (Schema::hasColumn('course_access_requests', 'campus')) {
            Schema::table('course_access_requests', function (Blueprint $table) {
                $table->dropColumn(['campus', 'school_id']);
            });
        }
    }
};
