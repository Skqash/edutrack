<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\School;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherDashboardService
{
    /**
     * Get comprehensive dashboard data for teacher with campus isolation
     */
    public function getDashboardData(): array
    {
        $teacher = Auth::user();
        $teacherId = $teacher->id;
        $teacherCampus = $teacher->campus;
        $teacherSchoolId = $teacher->school_id;

        // Check if teacher is approved for campus access
        $isApproved = $this->isTeacherApproved($teacher);

        // Get teacher's school information
        $school = $teacherSchoolId ? School::find($teacherSchoolId) : null;

        // Get teacher's classes with campus isolation
        $myClasses = $this->getTeacherClasses($teacherId, $teacherCampus, $teacherSchoolId);

        // Get statistics
        $statistics = $this->getTeacherStatistics($teacherId, $teacherCampus, $teacherSchoolId);

        // Get recent activities
        $recentActivities = $this->getRecentActivities($teacherId, $teacherCampus, $teacherSchoolId);

        // Get pending tasks
        $pendingTasks = $this->getPendingTasks($teacherId, $teacherCampus, $teacherSchoolId);

        // Get teacher's subjects
        $assignedSubjects = $this->getAssignedSubjects($teacherId, $teacherCampus, $teacherSchoolId);

        // Get campus-specific courses
        $availableCourses = $this->getAvailableCourses($teacherCampus, $teacherSchoolId);

        return [
            'teacher' => $teacher,
            'school' => $school,
            'isApproved' => $isApproved,
            'myClasses' => $myClasses ?? collect(),
            'statistics' => $statistics ?? [],
            'recentActivities' => $recentActivities ?? [],
            'pendingTasks' => $pendingTasks ?? [],
            'assignedSubjects' => $assignedSubjects ?? collect(),
            'availableCourses' => $availableCourses ?? collect(),
            'campusInfo' => $this->getCampusInfo($teacher, $school),
            'securityPolicies' => $this->getSecurityPolicies($teacher),
            'profileManagement' => $this->getProfileManagementData($teacher),
        ];
    }

    /**
     * Check if teacher is approved for campus access
     */
    private function isTeacherApproved(User $teacher): bool
    {
        // Independent teachers (no campus) are always approved
        if (empty($teacher->campus)) {
            return true;
        }

        // Campus teachers need approval
        return $teacher->campus_status === 'approved';
    }

    /**
     * Get teacher's classes with proper campus isolation
     */
    private function getTeacherClasses(int $teacherId, ?string $campus, ?int $schoolId)
    {
        $query = ClassModel::where('teacher_id', $teacherId)
            ->with(['course', 'subject', 'school']);

        if ($campus) {
            $query->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        $classes = $query->orderBy('class_name')->get();

        // Batch student counts — one query per unique course_id instead of N queries
        $courseIds = $classes->whereNotNull('course_id')->pluck('course_id')->unique();
        $studentCountsByCourse = Student::whereIn('course_id', $courseIds)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->selectRaw('course_id, COUNT(*) as cnt')
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        foreach ($classes as $class) {
            $count = $class->course_id
                ? ($studentCountsByCourse[$class->course_id] ?? 0)
                : Student::where('class_id', $class->id)->count();
            $class->setAttribute('student_count', $count);
        }

        return $classes;
    }

    /**
     * Get security policies for teacher based on campus status
     */
    public function getSecurityPolicies(User $teacher): array
    {
        $policies = [];
        
        // Campus-specific policies
        if ($teacher->campus) {
            $policies[] = [
                'title' => 'Campus Data Isolation',
                'description' => 'You can only access data from your assigned campus: ' . $teacher->campus,
                'type' => 'info',
                'icon' => 'fas fa-shield-alt',
                'enforced' => true,
            ];
            
            if ($teacher->campus_status === 'approved') {
                $policies[] = [
                    'title' => 'Full Campus Access',
                    'description' => 'You have full access to create classes, manage grades, and view campus resources',
                    'type' => 'success',
                    'icon' => 'fas fa-check-circle',
                    'enforced' => true,
                ];
            } else {
                $policies[] = [
                    'title' => 'Limited Access',
                    'description' => 'Campus approval required for full functionality. Contact your campus admin.',
                    'type' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle',
                    'enforced' => true,
                ];
            }
        } else {
            $policies[] = [
                'title' => 'Independent Teacher',
                'description' => 'You can create and manage your own subjects and classes independently',
                'type' => 'secondary',
                'icon' => 'fas fa-user-graduate',
                'enforced' => true,
            ];
        }

        // Grade security policies
        $policies[] = [
            'title' => 'Grade Data Protection',
            'description' => 'All grade entries are encrypted and audit-logged for security compliance',
            'type' => 'info',
            'icon' => 'fas fa-lock',
            'enforced' => true,
        ];

        // Student privacy policies
        $policies[] = [
            'title' => 'Student Privacy Protection',
            'description' => 'Student data is protected under privacy regulations. Access is logged and monitored.',
            'type' => 'info',
            'icon' => 'fas fa-user-shield',
            'enforced' => true,
        ];

        return $policies;
    }

    /**
     * Get teacher profile management data
     */
    public function getProfileManagementData(User $teacher): array
    {
        return [
            'profile_completion' => $this->calculateProfileCompletion($teacher),
            'campus_connections' => $this->getCampusConnections($teacher) ?? [],
            'security_settings' => $this->getSecuritySettings($teacher) ?? [],
            'notification_preferences' => $this->getNotificationPreferences($teacher) ?? [],
        ];
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(User $teacher): array
    {
        $fields = [
            'name' => !empty($teacher->name),
            'email' => !empty($teacher->email),
            'phone' => !empty($teacher->phone),
            'employee_id' => !empty($teacher->employee_id),
            'qualification' => !empty($teacher->qualification),
            'specialization' => !empty($teacher->specialization),
            'department' => !empty($teacher->department),
            'campus' => !empty($teacher->campus),
        ];

        $completed = array_sum($fields);
        $total = count($fields);
        $percentage = round(($completed / $total) * 100);

        return [
            'percentage' => $percentage,
            'completed_fields' => $completed,
            'total_fields' => $total,
            'missing_fields' => array_keys(array_filter($fields, fn($v) => !$v)),
        ];
    }

    /**
     * Get campus connections for teacher
     */
    private function getCampusConnections(User $teacher): array
    {
        $connections = [];

        if ($teacher->campus && $teacher->school_id) {
            $school = School::find($teacher->school_id);
            if ($school) {
                $connections[] = [
                    'type' => 'primary',
                    'name' => $school->school_name,
                    'short_name' => $school->short_name,
                    'status' => $teacher->campus_status ?? 'approved',
                    'role' => 'Teacher',
                    'since' => $teacher->created_at,
                ];
            }
        }

        return $connections;
    }

    /**
     * Get security settings for teacher
     */
    private function getSecuritySettings(User $teacher): array
    {
        return [
            'two_factor_enabled' => false, // Placeholder for future 2FA implementation
            'login_notifications' => true,
            'grade_change_notifications' => true,
            'password_last_changed' => $teacher->password_changed_at ?? $teacher->updated_at,
            'last_login' => $teacher->last_login_at ?? null,
            'active_sessions' => 1, // Placeholder for session management
        ];
    }

    /**
     * Get notification preferences for teacher
     */
    private function getNotificationPreferences(User $teacher): array
    {
        return [
            'email_notifications' => true,
            'grade_reminders' => true,
            'attendance_reminders' => true,
            'system_updates' => true,
            'campus_announcements' => !empty($teacher->campus),
        ];
    }

    /**
     * Get comprehensive teacher statistics
     */
    private function getTeacherStatistics(int $teacherId, ?string $campus, ?int $schoolId): array
    {
        // Base queries with campus isolation
        $classesQuery = ClassModel::where('teacher_id', $teacherId);
        
        // Get teacher's class IDs for student queries with proper campus isolation
        $teacherClassIds = ClassModel::where('teacher_id', $teacherId);
        
        // Apply campus isolation to classes
        if ($campus) {
            $classesQuery->where('campus', $campus);
            $teacherClassIds->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $classesQuery->where('school_id', $schoolId);
            $teacherClassIds->where('school_id', $schoolId);
        }
        
        $classIds = $teacherClassIds->pluck('id');
        
        // Get course IDs from teacher's classes for accurate student count
        $teacherCourseIds = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->whereNotNull('course_id')
            ->distinct()->pluck('course_id');

        // Students query — count by course_id to include students in non-primary classes
        $studentsQuery = Student::whereIn('course_id', $teacherCourseIds);
        if ($campus) {
            $studentsQuery->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $studentsQuery->where('school_id', $schoolId);
        }
        
        // Grades query with campus isolation
        $gradesQuery = Grade::where('teacher_id', $teacherId);
        if ($campus) {
            $gradesQuery->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $gradesQuery->where('school_id', $schoolId);
        }

        // Get counts
        $totalClasses = $classesQuery->count();
        $totalStudents = $studentsQuery->count();
        $gradesPosted = $gradesQuery->whereNotNull('final_grade')->count();
        
        // Get attendance records through class relationship with campus isolation
        $attendanceRecords = Attendance::whereIn('class_id', $classIds)->count();

        // Get pending grades count — use aggregated queries instead of per-class loop
        $pendingGrades = 0;
        $classesWithCourses = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->get(['id', 'course_id']);

        // Batch student counts by course_id
        $courseIds = $classesWithCourses->whereNotNull('course_id')->pluck('course_id')->unique();
        $studentCountsByCourse = Student::whereIn('course_id', $courseIds)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->selectRaw('course_id, COUNT(*) as cnt')
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        // Batch grade counts by class_id
        $gradeCountsByClass = Grade::where('teacher_id', $teacherId)
            ->whereIn('class_id', $classesWithCourses->pluck('id'))
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->whereNotNull('final_grade')
            ->selectRaw('class_id, COUNT(*) as cnt')
            ->groupBy('class_id')
            ->pluck('cnt', 'class_id');

        foreach ($classesWithCourses as $cls) {
            $studentCount = $cls->course_id
                ? ($studentCountsByCourse[$cls->course_id] ?? 0)
                : 0;
            $gradeCount = $gradeCountsByClass[$cls->id] ?? 0;
            if ($gradeCount < $studentCount) {
                $pendingGrades++;
            }
        }

        // Get grade averages with campus isolation
        $gradeAverages = $gradesQuery->whereNotNull('final_grade')
            ->selectRaw('
                AVG(knowledge_average) as avg_knowledge,
                AVG(skills_average) as avg_skills,
                AVG(attitude_average) as avg_attitude,
                AVG(final_grade) as avg_final_grade
            ')
            ->first();

        return [
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
            'gradesPosted' => $gradesPosted,
            'pendingGrades' => $pendingGrades,
            'attendanceRecords' => $attendanceRecords,
            'averages' => [
                'knowledge' => round($gradeAverages->avg_knowledge ?? 0, 2),
                'skills' => round($gradeAverages->avg_skills ?? 0, 2),
                'attitude' => round($gradeAverages->avg_attitude ?? 0, 2),
                'final_grade' => round($gradeAverages->avg_final_grade ?? 0, 2),
            ],
        ];
    }

    /**
     * Get recent activities for teacher
     */
    private function getRecentActivities(int $teacherId, ?string $campus, ?int $schoolId): array
    {
        $activities = [];

        // Recent grades
        $recentGrades = Grade::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->with(['student', 'class'])
            ->whereNotNull('final_grade')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentGrades as $grade) {
            $activities[] = [
                'type' => 'grade',
                'icon' => 'fas fa-star',
                'color' => 'success',
                'title' => 'Grade Posted',
                'description' => "Posted grade for {$grade->student->first_name} {$grade->student->last_name} in {$grade->class->class_name}",
                'time' => $grade->updated_at,
                'link' => route('teacher.grades.entry', $grade->class_id),
            ];
        }

        // Recent attendance - query through classes owned by teacher
        $teacherClassIds = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->pluck('id');

        $recentAttendance = Attendance::whereIn('class_id', $teacherClassIds)
            ->with(['class'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentAttendance as $attendance) {
            $activities[] = [
                'type' => 'attendance',
                'icon' => 'fas fa-calendar-check',
                'color' => 'info',
                'title' => 'Attendance Recorded',
                'description' => "Recorded attendance for {$attendance->class->class_name}",
                'time' => $attendance->created_at,
                'link' => route('teacher.attendance.manage', $attendance->class_id),
            ];
        }

        // Sort by time and limit
        usort($activities, fn($a, $b) => $b['time'] <=> $a['time']);
        return array_slice($activities, 0, 8);
    }

    /**
     * Get pending tasks for teacher
     */
    private function getPendingTasks(int $teacherId, ?string $campus, ?int $schoolId): array
    {
        $tasks = [];

        // Classes without complete grades — batch queries to avoid N+1
        $classes = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->get(['id', 'class_name', 'course_id']);

        $courseIds = $classes->whereNotNull('course_id')->pluck('course_id')->unique();
        $studentCountsByCourse = Student::whereIn('course_id', $courseIds)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->selectRaw('course_id, COUNT(*) as cnt')
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        $gradeCountsByClass = Grade::where('teacher_id', $teacherId)
            ->whereIn('class_id', $classes->pluck('id'))
            ->whereNotNull('final_grade')
            ->selectRaw('class_id, COUNT(*) as cnt')
            ->groupBy('class_id')
            ->pluck('cnt', 'class_id');

        foreach ($classes as $class) {
            $studentCount = $class->course_id
                ? ($studentCountsByCourse[$class->course_id] ?? 0)
                : 0;
            $gradeCount = $gradeCountsByClass[$class->id] ?? 0;

            if ($gradeCount < $studentCount) {
                $tasks[] = [
                    'type' => 'grades',
                    'priority' => 'high',
                    'title' => 'Complete Grades',
                    'description' => "Complete grades for {$class->class_name} ({$gradeCount}/{$studentCount} done)",
                    'link' => route('teacher.grades.content', $class->id),
                    'class_id' => $class->id,
                ];
            }
        }

        // Pending subject requests
        $pendingSubjects = Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'pending');
        })
        ->when($campus, fn($q) => $q->where('campus', $campus))
        ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
        ->get();

        foreach ($pendingSubjects as $subject) {
            $tasks[] = [
                'type' => 'subject_request',
                'priority' => 'medium',
                'title' => 'Subject Request Pending',
                'description' => "Your request to teach {$subject->subject_name} is pending approval",
                'link' => route('teacher.subjects'),
            ];
        }

        return $tasks;
    }

    /**
     * Get teacher's assigned subjects with campus isolation
     */
    private function getAssignedSubjects(int $teacherId, ?string $campus, ?int $schoolId)
    {
        return Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'active');
        })
        ->when($campus, fn($q) => $q->where('campus', $campus))
        ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
        ->with(['program', 'campusSchool'])
        ->orderBy('subject_name')
        ->get();
    }

    /**
     * Get available courses for teacher's campus
     */
    private function getAvailableCourses(?string $campus, ?int $schoolId)
    {
        $query = Course::where('status', 'Active');

        if ($campus) {
            $query->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        return $query->orderBy('program_name')->get();
    }

    /**
     * Get campus information for display
     */
    private function getCampusInfo(User $teacher, ?School $school): array
    {
        $campus = $teacher->campus;
        $campusStatus = $teacher->campus_status ?? 'approved';

        if (empty($campus)) {
            return [
                'type' => 'independent',
                'name' => 'Independent Teacher',
                'description' => 'You are not affiliated with any specific campus or institution',
                'status' => 'approved',
                'color' => 'secondary',
                'icon' => 'fas fa-user-graduate',
            ];
        }

        $statusConfig = [
            'approved' => [
                'color' => 'success',
                'icon' => 'fas fa-university',
                'description' => 'You are approved and affiliated with this CPSU campus',
            ],
            'pending' => [
                'color' => 'warning',
                'icon' => 'fas fa-clock',
                'description' => 'Your campus affiliation is pending admin approval. Limited access until approved.',
            ],
            'rejected' => [
                'color' => 'danger',
                'icon' => 'fas fa-times-circle',
                'description' => 'Your campus affiliation was not approved. Contact admin for more information.',
            ],
        ];

        $config = $statusConfig[$campusStatus] ?? $statusConfig['pending'];

        return [
            'type' => 'campus',
            'name' => $school ? $school->school_name : $campus,
            'short_name' => $school ? $school->short_name : str_replace('CPSU - ', '', $campus),
            'description' => $config['description'],
            'status' => $campusStatus,
            'color' => $config['color'],
            'icon' => $config['icon'],
            'school' => $school,
        ];
    }

    /**
     * Get teacher performance metrics
     */
    public function getPerformanceMetrics(int $teacherId, ?string $campus, ?int $schoolId): array
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Current month stats
        $currentStats = $this->getMonthlyStats($teacherId, $campus, $schoolId, $currentMonth);
        $previousStats = $this->getMonthlyStats($teacherId, $campus, $schoolId, $lastMonth);

        // Calculate trends
        $trends = [
            'grades' => $this->calculateTrend($currentStats['grades'], $previousStats['grades']),
            'attendance' => $this->calculateTrend($currentStats['attendance'], $previousStats['attendance']),
            'students' => $this->calculateTrend($currentStats['students'], $previousStats['students']),
        ];

        return [
            'current' => $currentStats,
            'previous' => $previousStats,
            'trends' => $trends,
        ];
    }

    /**
     * Get monthly statistics
     */
    private function getMonthlyStats(int $teacherId, ?string $campus, ?int $schoolId, $month): array
    {
        $endOfMonth = $month->copy()->endOfMonth();

        $gradesCount = Grade::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->whereBetween('created_at', [$month, $endOfMonth])
            ->count();

        // Get teacher's class IDs for attendance and student queries with campus isolation
        $teacherClassIds = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->pluck('id');

        $attendanceCount = Attendance::whereIn('class_id', $teacherClassIds)
            ->whereBetween('created_at', [$month, $endOfMonth])
            ->count();

        // Count students with proper campus isolation — use course_id for non-primary class support
        $teacherCourseIds = ClassModel::where('teacher_id', $teacherId)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->whereNotNull('course_id')
            ->distinct()->pluck('course_id');

        $studentsCount = Student::whereIn('course_id', $teacherCourseIds)
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->count();

        return [
            'grades' => $gradesCount,
            'attendance' => $attendanceCount,
            'students' => $studentsCount,
        ];
    }

    /**
     * Calculate trend percentage
     */
    private function calculateTrend(int $current, int $previous): array
    {
        if ($previous == 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'direction' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;
        
        return [
            'percentage' => round(abs($percentage), 1),
            'direction' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral'),
        ];
    }
}
