<?php

use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OptimizedCourseController;
use App\Http\Controllers\Admin\OptimizedDashboardController;
use App\Http\Controllers\Admin\OptimizedTeacherController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminGradeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Super\DashboardController as SuperDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* -------- ROOT REDIRECT -------- */
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if (in_array($user->role, ['super', 'superadmin'])) {
            return redirect()->route('super.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
})->name('home');

/* -------- HEALTH CHECK -------- */
Route::get('/health', function () {
    $status = [
        'status'    => 'ok',
        'timestamp' => now()->toIso8601String(),
        'php'       => PHP_VERSION,
        'env'       => app()->environment(),
        'debug'     => config('app.debug'),
        'database'  => [
            'driver'   => config('database.default'),
            'host'     => config('database.connections.'.config('database.default').'.host'),
            'database' => config('database.connections.'.config('database.default').'.database'),
            'status'   => 'unknown',
            'error'    => null,
        ],
    ];

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $status['database']['status'] = 'connected';

        // Verify migrations table exists (proxy for migrations having run)
        $migrated = \Illuminate\Support\Facades\Schema::hasTable('migrations');
        $status['database']['migrations_table'] = $migrated ? 'exists' : 'missing';

        if ($migrated) {
            $status['database']['migration_count'] = \Illuminate\Support\Facades\DB::table('migrations')->count();
        }
    } catch (\Exception $e) {
        $status['status']                    = 'degraded';
        $status['database']['status']        = 'failed';
        $status['database']['error']         = $e->getMessage();
    }

    $httpStatus = $status['status'] === 'ok' ? 200 : 503;

    return response()->json($status, $httpStatus);
})->name('health');

/* -------- AUTH -------- */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

/* -------- FORGOT PASSWORD -------- */
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink']);
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

/* -------- SUPER ADMIN ONLY -------- */
Route::middleware(['role:super'])->prefix('super')->name('super.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');

    // User Management (CRUD + Import/Export)
    Route::get('/users', [SuperDashboardController::class, 'listUsers'])->name('users.index');
    Route::get('/users/create', [SuperDashboardController::class, 'createUser'])->name('users.create');
    Route::get('/users/{id}', [SuperDashboardController::class, 'showUser'])->name('users.show');
    Route::post('/users', [SuperDashboardController::class, 'createUser'])->name('users.store');
    Route::put('/users/{id}', [SuperDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [SuperDashboardController::class, 'deleteUser'])->name('users.destroy');
    Route::post('/users/{id}/role', [SuperDashboardController::class, 'toggleRole'])->name('users.toggle-role');
    Route::get('/users/export/csv', [SuperDashboardController::class, 'exportUsersCSV'])->name('users.export');
    Route::post('/users/import/csv', [SuperDashboardController::class, 'importUsersCSV'])->name('users.import');

    // Course Management
    Route::get('/courses', [SuperDashboardController::class, 'manageCourses'])->name('courses.index');
    Route::get('/courses/create', [SuperDashboardController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [SuperDashboardController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{id}', [SuperDashboardController::class, 'showCourse'])->name('courses.show');
    Route::put('/courses/{id}', [SuperDashboardController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [SuperDashboardController::class, 'deleteCourse'])->name('courses.destroy');
    Route::delete('/courses/delete-all', [SuperDashboardController::class, 'deleteAllCourses'])->name('courses.delete-all');

    // Class Management
    Route::get('/classes', [SuperDashboardController::class, 'manageClasses'])->name('classes.index');
    Route::get('/classes/create', [SuperDashboardController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [SuperDashboardController::class, 'storeClass'])->name('classes.store');
    Route::get('/classes/{id}', [SuperDashboardController::class, 'showClass'])->name('classes.show');
    Route::put('/classes/{id}', [SuperDashboardController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{id}', [SuperDashboardController::class, 'deleteClass'])->name('classes.destroy');
    Route::delete('/classes/delete-all', [SuperDashboardController::class, 'deleteAllClasses'])->name('classes.delete-all');

    // Student Management
    Route::get('/students', [SuperDashboardController::class, 'manageStudents'])->name('students.index');
    Route::get('/students/create', [SuperDashboardController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [SuperDashboardController::class, 'createStudent'])->name('students.store');
    Route::get('/students/{id}', [SuperDashboardController::class, 'showStudent'])->name('students.show');
    Route::put('/students/{id}', [SuperDashboardController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [SuperDashboardController::class, 'deleteStudent'])->name('students.destroy');
    Route::delete('/students/delete-all', [SuperDashboardController::class, 'deleteAllStudents'])->name('students.delete-all');

    // Grade Management
    Route::get('/grades', [SuperDashboardController::class, 'manageGrades'])->name('grades.index');
    Route::get('/grades/{id}', [SuperDashboardController::class, 'showGrade'])->name('grades.show');
    Route::put('/grades/{id}', [SuperDashboardController::class, 'updateGrade'])->name('grades.update');
    Route::delete('/grades/{id}', [SuperDashboardController::class, 'deleteGrade'])->name('grades.destroy');
    Route::delete('/grades/delete-all', [SuperDashboardController::class, 'deleteAllGrades'])->name('grades.delete-all');

    // Attendance Management
    Route::get('/attendance', [SuperDashboardController::class, 'manageAttendance'])->name('attendance.index');
    Route::get('/attendance/{id}', [SuperDashboardController::class, 'showAttendance'])->name('attendance.show');
    Route::put('/attendance/{id}', [SuperDashboardController::class, 'updateAttendance'])->name('attendance.update');
    Route::delete('/attendance/{id}', [SuperDashboardController::class, 'deleteAttendance'])->name('attendance.destroy');
    Route::delete('/attendance/delete-all', [SuperDashboardController::class, 'deleteAllAttendance'])->name('attendance.delete-all');

    // Database Tools
    Route::get('/tools/query', function () {
        return view('super.tools.query');
    })->name('tools.query');
    Route::post('/tools/query', [SuperDashboardController::class, 'runSafeQuery'])->name('tools.query.run');
    Route::get('/tools/database', [SuperDashboardController::class, 'databaseStats'])->name('tools.database');
    Route::get('/tools/backup', [SuperDashboardController::class, 'backupDatabase'])->name('tools.backup');

    // System Management
    Route::post('/system/cache-clear', [SuperDashboardController::class, 'clearCaches'])->name('system.cache-clear');
    Route::post('/system/migrate', [SuperDashboardController::class, 'runMigrations'])->name('system.migrate');
    Route::post('/system/seed', [SuperDashboardController::class, 'runSeeders'])->name('system.seed');

    // Logs & Monitoring
    Route::get('/logs', [SuperDashboardController::class, 'viewLogs'])->name('logs.view');
    Route::get('/health', [SuperDashboardController::class, 'systemHealth'])->name('health');
    Route::post('/health/refresh', [SuperDashboardController::class, 'systemHealth'])->name('health.refresh');

    // Database Cleanup
    Route::get('/cleanup', [SuperDashboardController::class, 'databaseCleanup'])->name('cleanup');
    Route::post('/cleanup', [SuperDashboardController::class, 'cleanupDatabase'])->name('cleanup.perform');
    Route::post('/cleanup/logs', [SuperDashboardController::class, 'cleanupLogs'])->name('cleanup.logs');
});

/* -------- ADMIN (ADMIN + SUPER CAN ACCESS) -------- */
Route::middleware(['role:admin,super'])->prefix('admin')->name('admin.')->group(function () {
    // Optimized Dashboard
    Route::get('/dashboard', [OptimizedDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filtered-data', [OptimizedDashboardController::class, 'getFilteredData'])->name('dashboard.filtered-data');
    Route::get('/dashboard/system-health', [OptimizedDashboardController::class, 'getSystemHealth'])->name('dashboard.system-health');
    Route::get('/dashboard/export', [OptimizedDashboardController::class, 'exportData'])->name('dashboard.export');

    // Optimized Courses Routes
    Route::resource('courses', OptimizedCourseController::class);
    Route::get('courses/{course}/subjects', [OptimizedCourseController::class, 'manageSubjects'])->name('courses.manageSubjects');
    Route::post('/courses/bulk-action', [OptimizedCourseController::class, 'bulkAction'])->name('courses.bulk-action');
    Route::get('/courses/export', [OptimizedCourseController::class, 'export'])->name('courses.export');
    Route::get('/api/courses/by-department', [OptimizedCourseController::class, 'getCoursesByDepartment'])->name('courses.by-department');
    Route::get('/api/courses/search', [OptimizedCourseController::class, 'searchCourses'])->name('courses.search');

    // Subjects Routes
    Route::resource('subjects', SubjectController::class);
    Route::post('/subjects/sync', [SubjectController::class, 'syncAll'])->name('subjects.syncAll');

    // Classes Routes
    Route::resource('classes', ClassController::class);
    Route::post('/classes/get-students', [ClassController::class, 'getStudents'])->name('classes.get-students');
    Route::post('/classes/assign-students', [ClassController::class, 'assignStudentsToClass'])->name('classes.assign-students');
    Route::post('/classes/{class}/add-student-manually', [ClassController::class, 'addStudentManually'])->name('classes.add-student-manually');
    Route::delete('/classes/{class}/remove-student', [ClassController::class, 'removeStudentFromClass'])->name('classes.remove-student');
    Route::post('/classes/{class}/import-excel', [ClassController::class, 'importStudentsExcel'])->name('classes.import-excel');

    // Students Routes
    Route::resource('students', StudentController::class);

    // Optimized Teachers Routes
    Route::resource('teachers', OptimizedTeacherController::class);
    Route::get('teachers/{teacher}/subjects', [OptimizedTeacherController::class, 'subjects'])->name('teachers.subjects');
    Route::post('teachers/{teacher}/assign-subjects', [OptimizedTeacherController::class, 'assignSubjects'])->name('teachers.assign-subjects');
    Route::delete('teachers/{teacher}/subjects/{subject}', [OptimizedTeacherController::class, 'removeSubject'])->name('teachers.remove-subject');
    Route::post('/teachers/bulk-action', [OptimizedTeacherController::class, 'bulkAction'])->name('teachers.bulk-action');
    Route::get('/teachers/export', [OptimizedTeacherController::class, 'export'])->name('teachers.export');
    Route::post('/teachers/import', [OptimizedTeacherController::class, 'import'])->name('teachers.import');

    // Campus Approval Routes (Optimized)
    Route::get('/campus-approvals', [OptimizedTeacherController::class, 'campusApprovals'])->name('teachers.campus-approvals');
    Route::post('/teachers/{teacher}/approve-campus', [OptimizedTeacherController::class, 'approveCampus'])->name('teachers.approve-campus');
    Route::post('/teachers/{teacher}/reject-campus', [OptimizedTeacherController::class, 'rejectCampus'])->name('teachers.reject-campus');
    Route::post('/teachers/{teacher}/revoke-campus', [OptimizedTeacherController::class, 'revokeCampus'])->name('teachers.revoke-campus');

    // Course Access Request Routes (Optimized)
    Route::get('/course-access-requests', [OptimizedTeacherController::class, 'courseAccessRequests'])->name('teachers.course-access-requests');
    Route::post('/course-access-requests/{request}/approve', [OptimizedTeacherController::class, 'approveCourseAccess'])->name('teachers.approve-course-access');
    Route::post('/course-access-requests/{request}/reject', [OptimizedTeacherController::class, 'rejectCourseAccess'])->name('teachers.reject-course-access');

    // Teacher Assignments Routes
    Route::resource('teacher-assignments', TeacherAssignmentController::class);
    Route::get('/teacher-assignments/get-students', [TeacherAssignmentController::class, 'getStudentsByFilter'])->name('teacher-assignments.get-students');

    // Attendance Routes
    Route::resource('attendance', AdminAttendanceController::class);
    Route::get('/attendance-by-class', [DashboardController::class, 'getAttendanceByClass'])->name('attendance.by-class');

    // Grades Routes
    Route::resource('grades', AdminGradeController::class);
    Route::get('/grades-by-class', [DashboardController::class, 'getGradesByClass'])->name('grades.by-class');
    Route::get('/class/{classId}/details', [DashboardController::class, 'getClassDetails'])->name('class.details');
    Route::post('/grades/export-class/{classId}', [AdminGradeController::class, 'exportClass'])->name('grades.export-class');
    Route::get('/grades/print-student/{classId}/{studentId}', [AdminGradeController::class, 'printStudent'])->name('grades.print-student');
    Route::get('/grades/print-midterm/{classId}/{studentId}', [AdminGradeController::class, 'printMidtermGrades'])->name('grades.print-midterm');
    Route::get('/grades/print-finals/{classId}/{studentId}', [AdminGradeController::class, 'printFinalGrades'])->name('grades.print-finals');
    // User Management Routes
    Route::resource('users', AdminUserController::class);

    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'showAdminProfile'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'editAdminProfile'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'updateAdminProfile'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.change-password.update');
});

/* -------- TEACHER (TEACHER + SUPER CAN ACCESS) -------- */
Route::middleware(['role:teacher,super'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\TeacherController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\TeacherController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\TeacherController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\TeacherController::class, 'showChangePassword'])->name('profile.change-password');
    Route::put('/profile/change-password', [\App\Http\Controllers\TeacherController::class, 'updatePassword'])->name('profile.update-password');

    // Settings Management
    Route::get('/settings', [\App\Http\Controllers\TeacherController::class, 'showSettings'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\TeacherController::class, 'updateSettings'])->name('settings.update');

    // Campus Management
    Route::post('/request/campus-change', [\App\Http\Controllers\TeacherController::class, 'requestCampusChange'])->name('request.campus-change');

    Route::get('/classes', [\App\Http\Controllers\TeacherController::class, 'classes'])->name('classes');
    Route::get('/subjects', [\App\Http\Controllers\TeacherController::class, 'subjectsIndex'])->name('subjects');
    Route::post('/subjects/request', [\App\Http\Controllers\TeacherController::class, 'requestSubject'])->name('subjects.request');
    Route::post('/subjects/create', [\App\Http\Controllers\TeacherController::class, 'createSubject'])->name('subjects.create');
    Route::delete('/subjects/{subject}', [\App\Http\Controllers\TeacherController::class, 'removeSubjectFromTeacher'])->name('subjects.remove');
    Route::get('/classes/create', [\App\Http\Controllers\TeacherController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [\App\Http\Controllers\TeacherController::class, 'storeClass'])->name('classes.store');
    Route::get('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'classDetail'])->name('classes.show');
    Route::get('/classes/{classId}/edit', [\App\Http\Controllers\TeacherController::class, 'editClass'])->name('classes.edit');
    Route::put('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'destroyClass'])->name('classes.destroy');

    // AJAX / helper endpoints
    Route::post('/classes/get-students', [\App\Http\Controllers\TeacherController::class, 'getStudents'])->name('classes.get-students');

    // Students
    Route::post('/students/import', [\App\Http\Controllers\TeacherController::class, 'importStudents'])->name('students.import');
    Route::get('/students/import/{classId}', [\App\Http\Controllers\TeacherController::class, 'showImportStudents'])->name('students.import.form');
    Route::get('/students/add/{classId}', [\App\Http\Controllers\TeacherController::class, 'showAddStudent'])->name('students.add.form');
    Route::post('/students', [\App\Http\Controllers\TeacherController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/search', [\App\Http\Controllers\TeacherController::class, 'searchStudents'])->name('students.search');
    Route::post('/students/add-existing', [\App\Http\Controllers\TeacherController::class, 'addExistingStudents'])->name('students.add-existing');
    Route::get('/classes/{classId}/students', [\App\Http\Controllers\TeacherController::class, 'indexStudents'])->name('students.index');
    Route::delete('/classes/{classId}/students/{studentId}', [\App\Http\Controllers\TeacherController::class, 'removeStudentFromClass'])->name('classes.students.remove');
    Route::get('/students/{studentId}/edit', [\App\Http\Controllers\TeacherController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{studentId}', [\App\Http\Controllers\TeacherController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{studentId}', [\App\Http\Controllers\TeacherController::class, 'destroyStudent'])->name('students.destroy');

    // Grades Management - KSA Grading System (Knowledge 40%, Skills 50%, Attitude 10%)
    // Grades Management
    Route::get('/grades', [\App\Http\Controllers\TeacherController::class, 'grades'])->name('grades');
    Route::get('/grades/index', [\App\Http\Controllers\TeacherController::class, 'grades'])->name('grades.index');

    // Grade Entry Form with Term Parameter (Simplified Dropdown Approach)
    Route::get('/grades/entry/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryByTerm'])->name('grades.entry');
    Route::post('/grades/entry/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradeEntryByTerm'])->name('grades.store');
    Route::post('/grades/entry/{classId}/upload', [\App\Http\Controllers\TeacherController::class, 'uploadGradeEntry'])->name('grades.upload');

    // Advanced Grade Entry System (NEW)
    Route::get('/grades/advanced/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryAdvanced'])->name('grades.advanced');
    Route::get('/grades/content/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeContent'])->name('grades.content');
    Route::post('/grades/advanced/{classId}/save-config', [\App\Http\Controllers\TeacherController::class, 'saveGradeConfig'])->name('grades.advanced.save-config');
    Route::post('/grades/advanced/{classId}/save-grades', [\App\Http\Controllers\TeacherController::class, 'saveAdvancedGrades'])->name('grades.advanced.save-grades');

    // Grade Statistics and Export/Import
    Route::get('/grades/statistics/{classId}', [\App\Http\Controllers\TeacherController::class, 'getGradeStatistics'])->name('grades.statistics');
    Route::get('/grades/export/{classId}', [\App\Http\Controllers\TeacherController::class, 'exportGrades'])->name('grades.export');
    Route::post('/grades/import/{classId}', [\App\Http\Controllers\TeacherController::class, 'importGrades'])->name('grades.import');

    // Legacy Routes (kept for backward compatibility)
    Route::post('/grades/store-new/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradeEntryAdvanced'])->name('grades.store.new');
    Route::get('/grades/grade-entry/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryAdvanced'])->name('grades.grade_entry');
    Route::post('/grades/grade-entry/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradeEntryAdvanced'])->name('grades.grade_entry.store');

    // Utility Routes
    Route::post('/grades/save-field', [\App\Http\Controllers\TeacherController::class, 'saveGradeField'])->name('grades.save-field');
    Route::post('/grades/entry/{classId}/save-cell', [\App\Http\Controllers\TeacherController::class, 'saveGradeEntryCell'])->name('grades.entry.save-cell');
    Route::delete('/grades/entry/{classId}/clear-cell', [\App\Http\Controllers\TeacherController::class, 'clearGradeEntryCell'])->name('grades.entry.clear-cell');
    Route::delete('/grades/entry/{classId}/clear-student', [\App\Http\Controllers\TeacherController::class, 'clearStudentGradeEntry'])->name('grades.entry.clear-student');
    Route::get('/grades/scores/{studentId}/{classId}/{term}', [\App\Http\Controllers\TeacherController::class, 'getStudentScores'])->name('grades.scores');
    Route::get('/grades/analytics/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeAnalytics'])->name('grades.analytics');
    Route::get('/grades/results', [\App\Http\Controllers\TeacherController::class, 'gradeResults'])->name('grades.results');
    Route::get('/grades/summary-detailed', [\App\Http\Controllers\TeacherController::class, 'gradeSummaryDetailed'])->name('grades.summary.detailed');

    // Assessment Configuration (Legacy)
    Route::get('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'configureAssessmentRanges'])->name('assessment.configure');
    Route::post('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeAssessmentRanges'])->name('assessment.configure.store');

    // Grade Settings - Dynamic Components & KSA Percentages (NEW)
    Route::prefix('grades/settings')->name('grades.settings.')->group(function () {
        Route::get('/{classId}', [\App\Http\Controllers\GradeSettingsController::class, 'index'])->name('index');
        Route::post('/{classId}/ksa', [\App\Http\Controllers\GradeSettingsController::class, 'updateKsaPercentages'])->name('update-ksa');
        Route::post('/{classId}/attendance', [\App\Http\Controllers\GradeSettingsController::class, 'updateAttendanceSettings'])->name('update-attendance');
        Route::post('/{classId}/component', [\App\Http\Controllers\GradeSettingsController::class, 'addComponent'])->name('add-component');
        Route::put('/{classId}/component/{componentId}', [\App\Http\Controllers\GradeSettingsController::class, 'updateComponent'])->name('update-component');
        Route::delete('/{classId}/component/{componentId}', [\App\Http\Controllers\GradeSettingsController::class, 'deleteComponent'])->name('delete-component');
        Route::post('/{classId}/reorder', [\App\Http\Controllers\GradeSettingsController::class, 'reorderComponents'])->name('reorder');
        Route::post('/{classId}/{term}/toggle-lock', [\App\Http\Controllers\GradeSettingsController::class, 'toggleLock'])->name('toggle-lock');
        Route::post('/{classId}/initialize', [\App\Http\Controllers\GradeSettingsController::class, 'initializeDefaults'])->name('initialize');
    });

    // Dynamic Grade Entry Save Route
    Route::post('/grades/dynamic/{classId}/save', [\App\Http\Controllers\GradeSettingsController::class, 'saveDynamicGrades'])->name('grades.dynamic.save');
    Route::post('/grades/save/{classId}', [\App\Http\Controllers\TeacherController::class, 'saveComponentGrades'])->name('grades.save');

    // Dynamic Assessment Components Management (NEW)
    Route::prefix('components')->name('components.')->group(function () {
        Route::get('/{classId}', [\App\Http\Controllers\AssessmentComponentController::class, 'getComponents'])->name('index');
        Route::post('/{classId}', [\App\Http\Controllers\AssessmentComponentController::class, 'addComponent'])->name('store');
        Route::put('/{classId}/{componentId}', [\App\Http\Controllers\AssessmentComponentController::class, 'updateComponent'])->name('update');
        Route::delete('/{classId}/{componentId}', [\App\Http\Controllers\AssessmentComponentController::class, 'deleteComponent'])->name('destroy');
        Route::post('/{classId}/reorder', [\App\Http\Controllers\AssessmentComponentController::class, 'reorderComponents'])->name('reorder');
        Route::get('/templates/all', [\App\Http\Controllers\AssessmentComponentController::class, 'getTemplates'])->name('templates');
        Route::get('/{classId}/subcategories/{category}', [\App\Http\Controllers\AssessmentComponentController::class, 'getSubcategories'])->name('subcategories');
        Route::post('/{classId}/apply-template', [\App\Http\Controllers\AssessmentComponentController::class, 'applyTemplate'])->name('apply-template');
        Route::get('/{classId}/stats', [\App\Http\Controllers\AssessmentComponentController::class, 'getComponentStats'])->name('stats');
        Route::post('/{classId}/bulk-delete', [\App\Http\Controllers\AssessmentComponentController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{classId}/{componentId}/duplicate', [\App\Http\Controllers\AssessmentComponentController::class, 'duplicateComponent'])->name('duplicate');
        Route::post('/{classId}/update-weights', [\App\Http\Controllers\AssessmentComponentController::class, 'updateWeights'])->name('update-weights');
        Route::post('/{classId}/toggle-attendance', [\App\Http\Controllers\AssessmentComponentController::class, 'toggleAttendanceAffectsGrade'])->name('toggle-attendance');
    });

    // Dynamic Grade Entry Routes (NEW)
    Route::prefix('grades/dynamic')->name('grades.dynamic.')->group(function () {
        Route::get('/{classId}', [\App\Http\Controllers\GradeEntryDynamicController::class, 'show'])->name('show');
        Route::get('/{classId}/entries', [\App\Http\Controllers\GradeEntryDynamicController::class, 'getEntries'])->name('entries');
        Route::post('/{classId}/entries', [\App\Http\Controllers\GradeEntryDynamicController::class, 'saveEntries'])->name('entries.store');
        Route::get('/{classId}/averages', [\App\Http\Controllers\GradeEntryDynamicController::class, 'getAverages'])->name('averages');
        Route::get('/{classId}/{studentId}/entries', [\App\Http\Controllers\GradeEntryDynamicController::class, 'getStudentEntries'])->name('student.entries');
        Route::delete('/entries/{entryId}', [\App\Http\Controllers\GradeEntryDynamicController::class, 'deleteEntry'])->name('entries.destroy');
        Route::delete('/{classId}/{studentId}/entries', [\App\Http\Controllers\GradeEntryDynamicController::class, 'deleteStudentEntries'])->name('student.entries.destroy');
    });

    // Grade Settings & Configuration Routes (NEW)
    Route::prefix('grade-settings')->name('grades.settings.')->group(function () {
        Route::get('/{classId}/{term}', [\App\Http\Controllers\GradeSettingsController::class, 'show'])->name('show');
        Route::get('/{classId}/{term}/settings', [\App\Http\Controllers\GradeSettingsController::class, 'getSettings'])->name('get');
        Route::post('/{classId}/{term}/percentages', [\App\Http\Controllers\GradeSettingsController::class, 'updatePercentages'])->name('percentages.update');
        Route::post('/{classId}/{term}/weight-mode', [\App\Http\Controllers\GradeSettingsController::class, 'updateWeightMode'])->name('weight-mode.update');
        Route::post('/{classId}/components', [\App\Http\Controllers\AssessmentComponentController::class, 'addComponent'])->name('components.store');
        Route::put('/{classId}/components/{componentId}', [\App\Http\Controllers\AssessmentComponentController::class, 'updateComponent'])->name('components.update');
        Route::delete('/{classId}/components/{componentId}', [\App\Http\Controllers\AssessmentComponentController::class, 'deleteComponent'])->name('components.destroy');
        Route::post('/{classId}/components/reorder', [\App\Http\Controllers\GradeSettingsController::class, 'reorderComponents'])->name('components.reorder');
    });

    // Shortened routes for easier access
    Route::get('/grades/settings/{classId}/{term?}', [\App\Http\Controllers\GradeSettingsController::class, 'show'])->name('grades.settings');
    Route::get('/grades/entry/{classId}/{term?}', [\App\Http\Controllers\GradeEntryDynamicController::class, 'show'])->name('grades.entry.dynamic');

    // Grading Mode Management (Standard, Manual, Automated, Hybrid)
    Route::prefix('grades/mode')->name('grades.mode.')->group(function () {
        Route::get('/{classId}', [\App\Http\Controllers\GradingModeController::class, 'show'])->name('show');
        Route::post('/{classId}', [\App\Http\Controllers\GradingModeController::class, 'update'])->name('update');
        Route::get('/{classId}/calculate', [\App\Http\Controllers\GradingModeController::class, 'calculate'])->name('calculate');
    });

    // Attendance Management
    Route::get('/attendance/manage/{classId}', [\App\Http\Controllers\TeacherController::class, 'manageAttendance'])->name('attendance.manage');
    Route::post('/attendance/record/{classId}', [\App\Http\Controllers\TeacherController::class, 'recordAttendance'])->name('attendance.record');
    Route::get('/attendance/settings/{classId}', [\App\Http\Controllers\TeacherController::class, 'attendanceSettings'])->name('attendance.settings');
    Route::put('/attendance/settings/{classId}', [\App\Http\Controllers\TeacherController::class, 'updateAttendanceSettings'])->name('attendance.settings.update');
    Route::get('/attendance/fetch/{classId}', [\App\Http\Controllers\TeacherController::class, 'fetchAttendance'])->name('attendance.fetch');

    // Attendance-Grade Integration
    Route::get('/attendance/grade-integration/{classId}/{term?}', [\App\Http\Controllers\TeacherController::class, 'showGradeIntegration'])->name('attendance.grade-integration');
    Route::get('/attendance/grades/{classId}/{term?}', [\App\Http\Controllers\TeacherController::class, 'getAttendanceGrades'])->name('attendance.grades');
    Route::post('/attendance/sync-grades/{classId}', [\App\Http\Controllers\TeacherController::class, 'syncAttendanceToGrades'])->name('attendance.sync-grades');
    // Attendance History / Search
    Route::get('/attendance/history/{classId}', [\App\Http\Controllers\TeacherController::class, 'attendanceHistory'])->name('attendance.history');
    Route::get('/attendance/sheet/{classId}', [\App\Http\Controllers\TeacherController::class, 'attendanceSheet'])->name('attendance.sheet');

    // Assignment Management
    // TODO: Implement assignment management feature
    // Route::get('/assignments/list/{classId}', [\App\Http\Controllers\TeacherController::class, 'listAssignments'])->name('assignments.list');
    // Route::get('/assignments/create/{classId}', [\App\Http\Controllers\TeacherController::class, 'createAssignment'])->name('assignments.create');
    // Route::post('/assignments/store/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeAssignment'])->name('assignments.store');
    // Route::get('/assignments/edit/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'editAssignment'])->name('assignments.edit');
    // Route::post('/assignments/update/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'updateAssignment'])->name('assignments.update');
    // Route::delete('/assignments/delete/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'deleteAssignment'])->name('assignments.delete');
    // Route::get('/assignments/grade/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'gradeAssignments'])->name('assignments.grade');
    // Route::post('/assignments/score/{classId}/{assignmentId}/{submissionId}', [\App\Http\Controllers\TeacherController::class, 'submitAssignmentScore'])->name('assignments.score');

    // Legacy routes (keeping for backward compatibility)
    Route::get('/grades/entry/old/{classId}', [\App\Http\Controllers\TeacherController::class, 'gradeEntry'])->name('grades.entry.old');
    Route::post('/grades/store/old/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGrades'])->name('grades.store.old');

    Route::get('/attendance', [\App\Http\Controllers\TeacherController::class, 'attendance'])->name('attendance');
    Route::get('/assignments', [\App\Http\Controllers\TeacherController::class, 'assignments'])->name('assignments');
    Route::get('/ksa-info', [\App\Http\Controllers\TeacherController::class, 'ksaInfo']);

    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/theme', [\App\Http\Controllers\SettingsController::class, 'changeTheme'])->name('settings.theme');

    // Course Access Management
    Route::get('/courses', [\App\Http\Controllers\TeacherController::class, 'coursesIndex'])->name('courses');
    Route::post('/courses/request', [\App\Http\Controllers\TeacherController::class, 'requestCourseAccess'])->name('courses.request');
    Route::delete('/courses/requests/{requestId}/cancel', [\App\Http\Controllers\TeacherController::class, 'cancelCourseRequest'])->name('courses.requests.cancel');

    // Unified teacher request center (subjects, courses, class and school connection)
    Route::get('/requests', [\App\Http\Controllers\TeacherController::class, 'requestHistory'])->name('requests');
    Route::post('/courses/request-legacy', [\App\Http\Controllers\TeacherController::class, 'requestCourse'])->name('courses.request-legacy');
    Route::post('/classes/request', [\App\Http\Controllers\TeacherController::class, 'requestClass'])->name('classes.request');

    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'showTeacherProfile'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'editTeacherProfile'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'updateTeacherProfile'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.change-password.update');
});

/* -------- API ROUTES FOR DYNAMIC DROPDOWNS -------- */
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Subject API routes
    Route::get('/subjects', [\App\Http\Controllers\Api\SearchController::class, 'subjects'])->name('subjects');
    Route::get('/subjects/search', [\App\Http\Controllers\Api\SearchController::class, 'searchSubjects'])->name('subjects.search');

    // Course API routes
    Route::get('/courses', [\App\Http\Controllers\Api\SearchController::class, 'courses'])->name('courses');
    Route::get('/courses/search', [\App\Http\Controllers\Api\SearchController::class, 'searchCourses'])->name('courses.search');

    // Student API routes
    Route::get('/students', [\App\Http\Controllers\Api\SearchController::class, 'students'])->name('students');
    Route::get('/students/search', [\App\Http\Controllers\Api\SearchController::class, 'searchStudents'])->name('students.search');

    // Teacher API routes
    Route::get('/teachers', [\App\Http\Controllers\Api\SearchController::class, 'teachers'])->name('teachers');
    Route::get('/teachers/search', [\App\Http\Controllers\Api\SearchController::class, 'searchTeachers'])->name('teachers.search');

    // Class API routes
    Route::get('/classes', [\App\Http\Controllers\Api\SearchController::class, 'classes'])->name('classes');
    Route::get('/classes/search', [\App\Http\Controllers\Api\SearchController::class, 'searchClasses'])->name('classes.search');

    // Department API routes
    Route::get('/departments', [\App\Http\Controllers\Api\SearchController::class, 'departments'])->name('departments');
    Route::get('/departments/search', [\App\Http\Controllers\Api\SearchController::class, 'searchDepartments'])->name('departments.search');
});

/* -------- STUDENT (STUDENT + SUPER CAN ACCESS) -------- */
Route::middleware(['role:student,super'])->prefix('student')->name('student.')->group(function () {
    // Dashboard and Profile
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [StudentController::class, 'updateProfile'])->name('update-profile');
    Route::post('/change-password', [StudentController::class, 'changePassword'])->name('change-password');

    // Attendance
    Route::get('/attendance', [StudentController::class, 'getAttendance'])->name('attendance');

    // Grades
    Route::get('/grades', [StudentController::class, 'getGrades'])->name('grades');

    // E-Signature
    Route::get('/signature/form', [StudentController::class, 'showSignatureForm'])->name('signature.form');
    Route::post('/signature/update', [StudentController::class, 'updateSignature'])->name('signature.update');
});
