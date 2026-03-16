<?php

use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminGradeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SchoolRequestController;
use App\Http\Controllers\Super\DashboardController as SuperDashboardController;
use Illuminate\Support\Facades\Route;

/* -------- ROOT REDIRECT -------- */
Route::get('/', function () {
    if (auth('super')->check()) {
        return redirect()->route('super.dashboard');
    }
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
})->name('home');

/* -------- AUTH -------- */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
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
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courses Routes
    Route::resource('courses', CourseController::class);
    Route::get('/courses/{course}/subjects', [CourseController::class, 'manageSubjects'])->name('courses.manageSubjects');

    // Subjects Routes
    Route::resource('subjects', SubjectController::class);
    Route::post('/subjects/sync', [SubjectController::class, 'syncAll'])->name('subjects.syncAll');

    // Classes Routes
    Route::resource('classes', ClassController::class);
    Route::post('/classes/get-students', [ClassController::class, 'getStudents'])->name('classes.get-students');
    Route::post('/classes/assign-students', [ClassController::class, 'assignStudentsToClass'])->name('classes.assign-students');

    // Students Routes
    Route::resource('students', StudentController::class);

    // Teachers Routes
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/subjects', [TeacherController::class, 'subjects'])->name('teachers.subjects');
    Route::post('teachers/{teacher}/assign-subjects', [TeacherController::class, 'assignSubjects'])->name('teachers.assign-subjects');
    Route::delete('teachers/{teacher}/subjects/{subject}', [TeacherController::class, 'removeSubject'])->name('teachers.remove-subject');

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
Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classes', [\App\Http\Controllers\TeacherController::class, 'classes'])->name('classes');
    Route::get('/subjects', [\App\Http\Controllers\TeacherController::class, 'subjectsIndex'])->name('subjects');
    Route::post('/subjects/request', [\App\Http\Controllers\TeacherController::class, 'requestSubject'])->name('subjects.request');
    Route::post('/subjects/create', [\App\Http\Controllers\TeacherController::class, 'createSubject'])->name('subjects.create');
    Route::get('/classes/create', [\App\Http\Controllers\TeacherController::class, 'createClass'])->name('classes.create');
    Route::post('/classes', [\App\Http\Controllers\TeacherController::class, 'storeClass'])->name('classes.store');
    Route::get('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'classDetail'])->name('classes.show');
    Route::get('/classes/{classId}/edit', [\App\Http\Controllers\TeacherController::class, 'editClass'])->name('classes.edit');
    Route::put('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'destroyClass'])->name('classes.destroy');

    // AJAX / helper endpoints
    Route::post('/classes/get-students', [\App\Http\Controllers\TeacherController::class, 'getStudents'])->name('classes.get-students');

    // Students
    Route::get('/students/add', [\App\Http\Controllers\TeacherController::class, 'showAddStudent'])->name('students.add');
    Route::post('/students', [\App\Http\Controllers\TeacherController::class, 'storeStudent'])->name('students.store');
    Route::post('/students/import', [\App\Http\Controllers\TeacherController::class, 'importStudents'])->name('students.import');
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

    // Assessment Configuration
    Route::get('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'configureAssessmentRanges'])->name('assessment.configure');
    Route::post('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeAssessmentRanges'])->name('assessment.configure.store');

    // Attendance Management
    Route::get('/attendance/manage/{classId}', [\App\Http\Controllers\TeacherController::class, 'manageAttendance'])->name('attendance.manage');
    Route::post('/attendance/record/{classId}', [\App\Http\Controllers\TeacherController::class, 'recordAttendance'])->name('attendance.record');
    // Attendance History / Search
    Route::get('/attendance/history/{classId}', [\App\Http\Controllers\TeacherController::class, 'attendanceHistory'])->name('attendance.history');

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

    // School connection request (teacher)
    Route::get('/school-request', [SchoolRequestController::class, 'create'])->name('school-request.create');
    Route::post('/school-request', [SchoolRequestController::class, 'store'])->name('school-request.store');
    Route::get('/school-requests', [SchoolRequestController::class, 'history'])->name('school-requests.history');

    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'showTeacherProfile'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'editTeacherProfile'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'updateTeacherProfile'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.change-password.update');
});

/* -------- ADMIN (ADMIN + SUPER CAN ACCESS) -------- */
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/school-requests', [SchoolRequestController::class, 'index'])->name('school-requests.index');
    Route::get('/school-requests/{schoolRequest}', [SchoolRequestController::class, 'show'])->name('school-requests.show');
    Route::post('/school-requests/{schoolRequest}', [SchoolRequestController::class, 'update'])->name('school-requests.update');
});

/* -------- STUDENT (STUDENT + SUPER CAN ACCESS) -------- */
Route::middleware(['role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});
