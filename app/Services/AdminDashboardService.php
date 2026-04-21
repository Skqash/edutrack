<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    /**
     * Get dashboard statistics with campus filtering
     */
    public function getDashboardStats(?string $adminCampus): array
    {
        $cacheKey = "admin_dashboard_stats_" . ($adminCampus ? str_replace(' ', '_', $adminCampus) : 'all');
        
        return Cache::remember($cacheKey, 300, function () use ($adminCampus) {
            $query = function ($model) use ($adminCampus) {
                if ($adminCampus && in_array('campus', $model::make()->getFillable())) {
                    return $model::where('campus', $adminCampus);
                }
                return $model::query();
            };

            // Basic counts
            $stats = [
                'total_teachers' => $this->getTeacherStats($adminCampus),
                'total_students' => $query(Student::class)->count(),
                'total_classes' => $query(ClassModel::class)->count(),
                'total_courses' => $query(Course::class)->count(),
                'total_subjects' => $query(Subject::class)->count(),
            ];

            // Approval statistics
            $stats['pending_approvals'] = [
                'campus_approvals' => \App\Models\User::where('role', 'teacher')
                    ->where('campus_status', 'pending')
                    ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                    ->count(),
                'course_requests' => CourseAccessRequest::where('status', 'pending')
                    ->when($adminCampus, function ($q) use ($adminCampus) {
                        $q->whereHas('teacher', fn($tq) => $tq->where('campus', $adminCampus));
                    })
                    ->count(),
            ];

            // Activity statistics
            $stats['recent_activity'] = [
                'new_registrations_today' => Teacher::whereDate('created_at', today())
                    ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                    ->count(),
                'grades_updated_today' => Grade::whereDate('updated_at', today())
                    ->when($adminCampus, function ($q) use ($adminCampus) {
                        $q->whereHas('class', fn($cq) => $cq->where('campus', $adminCampus));
                    })
                    ->count(),
                'classes_created_this_week' => $query(ClassModel::class)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
            ];

            return $stats;
        });
    }

    /**
     * Get teacher statistics with campus filtering
     */
    private function getTeacherStats(?string $adminCampus): array
    {
        // Query users table with role='teacher' since campus_status is in users table
        $baseQuery = \App\Models\User::where('role', 'teacher');
        
        if ($adminCampus) {
            $baseQuery->where('campus', $adminCampus);
        }

        return [
            'total' => $baseQuery->count(),
            'approved' => (clone $baseQuery)->where('campus_status', 'approved')->count(),
            'pending' => (clone $baseQuery)->where('campus_status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('campus_status', 'rejected')->count(),
            'independent' => \App\Models\User::where('role', 'teacher')->whereNull('campus')->count(),
        ];
    }

    /**
     * Get pending approvals for admin's campus
     */
    public function getPendingApprovals(?string $adminCampus): array
    {
        // Campus approvals - query users table with role='teacher'
        $campusApprovals = \App\Models\User::where('role', 'teacher')
            ->where('campus_status', 'pending')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->latest()
            ->limit(5)
            ->get();

        // Course access requests
        $courseRequests = CourseAccessRequest::where('status', 'pending')
            ->when($adminCampus, function ($q) use ($adminCampus) {
                $q->whereHas('teacher', fn($tq) => $tq->where('campus', $adminCampus));
            })
            ->with(['teacher', 'course'])
            ->latest()
            ->limit(5)
            ->get();

        return [
            'campus_approvals' => $campusApprovals,
            'course_requests' => $courseRequests,
        ];
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(?string $adminCampus): array
    {
        $activities = [];

        // Recent teacher registrations
        $newTeachers = Teacher::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($teacher) {
                return [
                    'type' => 'teacher_registered',
                    'message' => $teacher->first_name . ' ' . $teacher->last_name . " registered as teacher",
                    'timestamp' => $teacher->created_at,
                    'user' => $teacher,
                ];
            });

        // Recent class creations
        $newClasses = ClassModel::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->with('teacher')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($class) {
                return [
                    'type' => 'class_created',
                    'message' => "Class {$class->class_name} created by {$class->teacher->name}",
                    'timestamp' => $class->created_at,
                    'class' => $class,
                ];
            });

        // Merge and sort activities
        $activities = $newTeachers->concat($newClasses)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();

        return $activities->toArray();
    }

    /**
     * Get chart data for visualizations
     */
    public function getChartData(?string $adminCampus): array
    {
        $cacheKey = "admin_chart_data_" . ($adminCampus ? str_replace(' ', '_', $adminCampus) : 'all');
        
        return Cache::remember($cacheKey, 600, function () use ($adminCampus) {
            // Student enrollment by course
            $enrollmentData = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                ->withCount(['classes as student_count' => function ($q) {
                    $q->join('students', 'classes.id', '=', 'students.class_id');
                }])
                ->get()
                ->map(function ($course) {
                    return [
                        'course' => $course->program_name,
                        'students' => $course->student_count ?? 0,
                    ];
                });

            // Grade distribution
            $gradeDistribution = Grade::when($adminCampus, function ($q) use ($adminCampus) {
                    $q->whereHas('class', fn($cq) => $cq->where('campus', $adminCampus));
                })
                ->selectRaw('
                    CASE 
                        WHEN final_grade >= 90 THEN "A"
                        WHEN final_grade >= 80 THEN "B"
                        WHEN final_grade >= 70 THEN "C"
                        WHEN final_grade >= 60 THEN "D"
                        ELSE "F"
                    END as grade_letter,
                    COUNT(*) as count
                ')
                ->whereNotNull('final_grade')
                ->groupBy(DB::raw('CASE 
                    WHEN final_grade >= 90 THEN "A"
                    WHEN final_grade >= 80 THEN "B"
                    WHEN final_grade >= 70 THEN "C"
                    WHEN final_grade >= 60 THEN "D"
                    ELSE "F"
                END'))
                ->get();

            // Monthly teacher registrations
            $monthlyRegistrations = Teacher::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => date('M', mktime(0, 0, 0, $item->month, 1)),
                        'count' => $item->count,
                    ];
                });

            return [
                'enrollment' => $enrollmentData,
                'grades' => $gradeDistribution,
                'registrations' => $monthlyRegistrations,
            ];
        });
    }

    /**
     * Get filtered data for dashboard widgets
     */
    public function getFilteredData(string $type, array $filters): array
    {
        switch ($type) {
            case 'grades':
                return $this->getGradeData($filters);
            case 'attendance':
                return $this->getAttendanceData($filters);
            case 'classes':
                return $this->getClassData($filters);
            default:
                return [];
        }
    }

    /**
     * Get grade data with filters
     */
    private function getGradeData(array $filters): array
    {
        $query = Grade::with(['student.user', 'class']);

        if (isset($filters['campus'])) {
            $query->whereHas('class', fn($q) => $q->where('campus', $filters['campus']));
        }

        if (isset($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (isset($filters['course_id'])) {
            $query->whereHas('class', fn($q) => $q->where('course_id', $filters['course_id']));
        }

        return $query->latest()->paginate(20)->toArray();
    }

    /**
     * Get attendance data with filters
     */
    private function getAttendanceData(array $filters): array
    {
        $query = DB::table('attendance')
            ->join('students', 'attendance.student_id', '=', 'students.id')
            ->join('classes', 'attendance.class_id', '=', 'classes.id')
            ->select([
                'attendance.*',
                DB::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                'classes.class_name',
                'students.student_id'
            ]);

        if (isset($filters['campus'])) {
            $query->where('classes.campus', $filters['campus']);
        }

        if (isset($filters['class_id'])) {
            $query->where('attendance.class_id', $filters['class_id']);
        }

        if (isset($filters['date_range'])) {
            $dates = explode(' to ', $filters['date_range']);
            if (count($dates) === 2) {
                $query->whereBetween('attendance.date', $dates);
            }
        }

        return $query->orderBy('attendance.date', 'desc')->paginate(20)->toArray();
    }

    /**
     * Get class data with filters
     */
    private function getClassData(array $filters): array
    {
        $query = ClassModel::with(['teacher', 'course', 'students']);

        if (isset($filters['campus'])) {
            $query->where('campus', $filters['campus']);
        }

        if (isset($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        return $query->latest()->paginate(20)->toArray();
    }

    /**
     * Get system health metrics
     */
    public function getSystemHealth(?string $adminCampus): array
    {
        $health = [
            'database' => $this->checkDatabaseHealth(),
            'cache' => $this->checkCacheHealth(),
            'storage' => $this->checkStorageHealth(),
            'data_integrity' => $this->checkDataIntegrity($adminCampus),
        ];

        $health['overall'] = $this->calculateOverallHealth($health);

        return $health;
    }

    /**
     * Check database health
     */
    private function checkDatabaseHealth(): array
    {
        try {
            DB::connection()->getPdo();
            $connectionTime = microtime(true);
            DB::select('SELECT 1');
            $queryTime = (microtime(true) - $connectionTime) * 1000;

            return [
                'status' => 'healthy',
                'response_time' => round($queryTime, 2) . 'ms',
                'message' => 'Database connection is working properly',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache health
     */
    private function checkCacheHealth(): array
    {
        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $value = Cache::get($testKey);
            Cache::forget($testKey);

            return [
                'status' => $value === 'test' ? 'healthy' : 'warning',
                'message' => $value === 'test' ? 'Cache is working properly' : 'Cache read/write issue',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache system error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage health
     */
    private function checkStorageHealth(): array
    {
        try {
            $diskSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usedPercentage = (($totalSpace - $diskSpace) / $totalSpace) * 100;

            $status = 'healthy';
            if ($usedPercentage > 90) {
                $status = 'error';
            } elseif ($usedPercentage > 80) {
                $status = 'warning';
            }

            return [
                'status' => $status,
                'used_percentage' => round($usedPercentage, 2),
                'free_space' => $this->formatBytes($diskSpace),
                'message' => "Storage is {$usedPercentage}% full",
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check data integrity
     */
    private function checkDataIntegrity(?string $adminCampus): array
    {
        $issues = [];

        // Check for classes without teachers
        $classesWithoutTeachers = ClassModel::whereNull('teacher_id')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->count();
        if ($classesWithoutTeachers > 0) {
            $issues[] = "{$classesWithoutTeachers} classes without assigned teachers";
        }

        // Check for grades without students
        $gradesWithoutStudents = Grade::whereDoesntHave('student')->count();
        if ($gradesWithoutStudents > 0) {
            $issues[] = "{$gradesWithoutStudents} grades without valid students";
        }

        return [
            'status' => empty($issues) ? 'healthy' : 'warning',
            'issues' => $issues,
            'message' => empty($issues) ? 'Data integrity is good' : 'Data integrity issues found',
        ];
    }

    /**
     * Calculate overall health status
     */
    private function calculateOverallHealth(array $health): array
    {
        $statuses = array_column($health, 'status');
        
        if (in_array('error', $statuses)) {
            return ['status' => 'error', 'message' => 'System has critical issues'];
        }
        
        if (in_array('warning', $statuses)) {
            return ['status' => 'warning', 'message' => 'System has minor issues'];
        }
        
        return ['status' => 'healthy', 'message' => 'All systems operational'];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Export dashboard data
     */
    public function exportData(string $type, string $format, ?string $adminCampus)
    {
        // Implementation for data export
        // This would handle CSV, PDF, Excel exports based on type and format
        // For now, returning a placeholder
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
}