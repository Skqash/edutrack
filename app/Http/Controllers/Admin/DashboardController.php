<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Grade;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard stats for 1 hour
        $stats = Cache::remember('dashboard_stats', 3600, function () {
            return [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalAdmins' => User::where('role', 'admin')->count(),
                'totalCourses' => Course::count(),
                'totalSubjects' => Subject::count(),
                'totalClasses' => ClassModel::count(),
            ];
        });

        // Get classes with student counts (optimized with withCount)
        $classesWithStudents = ClassModel::withCount('students')
            ->orderBy('students_count', 'desc')
            ->limit(10)
            ->get();

        // Get recent grades (with eager loading to avoid N+1)
        $recentGrades = Grade::with('student.user', 'class', 'teacher')
            ->whereNotNull('final_grade')
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        // Get average grade by class
        $gradeAverages = DB::table('grades')
            ->selectRaw('class_id, AVG(final_grade) as avg_grade, COUNT(*) as student_count')
            ->whereNotNull('final_grade')
            ->groupBy('class_id')
            ->with(['classes' => function ($query) {
                $query->select('id', 'class_name');
            }])
            ->get();

        return view('admin.index', array_merge($stats, [
            'classesWithStudents' => $classesWithStudents,
            'recentGrades' => $recentGrades,
            'gradeAverages' => $gradeAverages,
        ]));
    }

    /**
     * Clear dashboard cache when data changes
     */
    public static function clearCache()
    {
        Cache::forget('dashboard_stats');
    }
}