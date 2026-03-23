<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\SchoolRequest;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Cache dashboard stats for 1 hour
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;
        
        $cacheKey = 'dashboard_stats_' . ($adminCampus ?? 'all') . '_' . ($adminSchoolId ?? 'all');
        
        $stats = Cache::remember($cacheKey, 3600, function () use ($adminCampus, $adminSchoolId) {
            return [
                'totalStudents' => Student::query()
                    ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                    ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
                    ->count(),
                'totalTeachers' => User::where('role', 'teacher')
                    ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                    ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
                    ->count(),
                'totalAdmins' => User::where('role', 'admin')
                    ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                    ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
                    ->count(),
                'totalCourses' => Course::count(),
                'totalSubjects' => Subject::count(),
                'totalClasses' => ClassModel::count(),
                'pendingSchoolRequests' => SchoolRequest::where('status', 'pending')->count(),
            ];
        });

        // Get classes with student counts (optimized with withCount)
        $classesWithStudents = ClassModel::withCount('students')
            ->orderBy('students_count', 'desc')
            ->limit(10)
            ->get();

        // Get average grade by class
        $gradeAverages = DB::table('grades as g')
            ->select('g.class_id', 'c.class_name', DB::raw('AVG(g.final_grade) as avg_grade, COUNT(*) as student_count'))
            ->leftJoin('classes as c', 'g.class_id', '=', 'c.id')
            ->whereNotNull('g.final_grade')
            ->groupBy('g.class_id', 'c.class_name')
            ->get();

        // Get all teachers and classes for filters
        $teachers = User::where('role', 'teacher')->get();
        $classes = ClassModel::all();
        $periods = ['midterm', 'finals', 'first quarter', 'second quarter', 'third quarter', 'fourth quarter'];

        return view('admin.index', array_merge($stats, [
            'classesWithStudents' => $classesWithStudents,
            'gradeAverages' => $gradeAverages,
            'teachers' => $teachers,
            'classes' => $classes,
            'periods' => $periods,
        ]));
    }

    /**
     * Get grades grouped by class with advanced filtering
     */
    public function getGradesByClass(Request $request)
    {
        $query = Grade::with(['student.user', 'class', 'teacher', 'subject'])
            ->whereNotNull('final_grade');

        // Apply filters
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->has('period') && $request->period) {
            $query->where('grading_period', 'like', '%'.$request->period.'%');
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $grades = $query->orderBy('class_id')
            ->orderBy('final_grade', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'grades' => $grades,
                'filters' => [
                    'class_id' => $request->class_id,
                    'teacher_id' => $request->teacher_id,
                    'period' => $request->period,
                    'search' => $request->search,
                ],
            ],
        ]);
    }

    /**
     * Get attendance data grouped by class
     */
    public function getAttendanceByClass(Request $request)
    {
        $query = Attendance::with(['student.user', 'class'])
            ->orderBy('date', 'desc');

        // Apply filters
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $attendance = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'attendance' => $attendance,
                'filters' => [
                    'class_id' => $request->class_id,
                    'status' => $request->status,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'search' => $request->search,
                ],
            ],
        ]);
    }

    /**
     * Get class details with students, attendance, and grades
     */
    public function getClassDetails($classId)
    {
        $class = ClassModel::with(['students.user', 'teacher'])->findOrFail($classId);

        // Get attendance summary for this class
        $attendanceSummary = Attendance::where('class_id', $classId)
            ->selectRaw('student_id, COUNT(*) as total_days, SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_days')
            ->groupBy('student_id')
            ->get();

        // Get grade summary for this class
        $gradeSummary = Grade::where('class_id', $classId)
            ->selectRaw('student_id, AVG(final_grade) as avg_grade, MAX(final_grade) as highest, MIN(final_grade) as lowest')
            ->groupBy('student_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'class' => $class,
                'attendance' => $attendanceSummary,
                'grades' => $gradeSummary,
            ],
        ]);
    }

    /**
     * Clear dashboard cache when data changes
     */
    public static function clearCache()
    {
        Cache::forget('dashboard_stats');
    }
}
