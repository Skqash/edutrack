<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminGradeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Super\DashboardController as SuperDashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

/* -------- FORGOT PASSWORD -------- */
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink']);
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

/* -------- SUPER ADMIN ONLY -------- */
Route::middleware(['role:super'])->group(function () {
    Route::get('/super/dashboard', [SuperDashboardController::class, 'index'])->name('super.dashboard');
});

/* -------- ADMIN (ADMIN + SUPER CAN ACCESS) -------- */
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Courses Routes
    Route::resource('courses', CourseController::class);
    
    // Subjects Routes
    Route::resource('subjects', SubjectController::class);
    
    // Classes Routes
    Route::resource('classes', ClassController::class);
    
    // Students Routes
    Route::resource('students', StudentController::class);
    
    // Teachers Routes
    Route::resource('teachers', TeacherController::class);
    
    // Departments Routes
    Route::resource('departments', AdminDepartmentController::class);
    
    // Attendance Routes
    Route::resource('attendance', AdminAttendanceController::class);
    
    // Grades Routes
    Route::resource('grades', AdminGradeController::class);
    
    // User Management Routes
    Route::resource('users', AdminUserController::class);
});

/* -------- TEACHER (TEACHER + SUPER CAN ACCESS) -------- */
Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/classes', [\App\Http\Controllers\TeacherController::class, 'classes'])->name('classes');
    Route::get('/classes/{classId}', [\App\Http\Controllers\TeacherController::class, 'classDetail'])->name('classes.show');
    
    // Students
    Route::get('/students/add', [\App\Http\Controllers\TeacherController::class, 'showAddStudent'])->name('students.add');
    Route::post('/students', [\App\Http\Controllers\TeacherController::class, 'storeStudent'])->name('students.store');
    Route::post('/students/import', [\App\Http\Controllers\TeacherController::class, 'importStudents'])->name('students.import');
    
    // Grades (CHED System)
    Route::get('/grades', [\App\Http\Controllers\TeacherController::class, 'grades'])->name('grades');
    Route::get('/grades/entry/{classId}/{term?}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryChed'])->name('grades.entry');
    Route::post('/grades/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradesChed'])->name('grades.store');
    
    // Enhanced Grading with Configurable Ranges
    Route::get('/grades/entry-enhanced/{classId}/{term?}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryEnhanced'])->name('grades.entry.enhanced');
    Route::post('/grades/store-enhanced/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradesEnhanced'])->name('grades.store.enhanced');
    
    // Assessment Range Configuration
    Route::get('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'configureAssessmentRanges'])->name('assessment.configure');
    Route::post('/assessment/configure/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeAssessmentRanges'])->name('assessment.store');
    
    // NEW ENHANCED GRADING SYSTEM ROUTES
    Route::get('/grades/entry-inline/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeEntryInline'])->name('grades.entry.inline');
    Route::post('/grades/store-inline/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeGradesInline'])->name('grades.store.inline');
    Route::get('/grades/analytics/{classId}', [\App\Http\Controllers\TeacherController::class, 'showGradeAnalytics'])->name('grades.analytics');
    
    // Attendance Management
    Route::get('/attendance/manage/{classId}', [\App\Http\Controllers\TeacherController::class, 'manageAttendance'])->name('attendance.manage');
    Route::post('/attendance/record/{classId}', [\App\Http\Controllers\TeacherController::class, 'recordAttendance'])->name('attendance.record');
    // Attendance History / Search
    Route::get('/attendance/history/{classId}', [\App\Http\Controllers\TeacherController::class, 'attendanceHistory'])->name('attendance.history');
    
    // Assignment Management
    Route::get('/assignments/list/{classId}', [\App\Http\Controllers\TeacherController::class, 'listAssignments'])->name('assignments.list');
    Route::get('/assignments/create/{classId}', [\App\Http\Controllers\TeacherController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/assignments/store/{classId}', [\App\Http\Controllers\TeacherController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('/assignments/edit/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'editAssignment'])->name('assignments.edit');
    Route::post('/assignments/update/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'updateAssignment'])->name('assignments.update');
    Route::delete('/assignments/delete/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'deleteAssignment'])->name('assignments.delete');
    Route::get('/assignments/grade/{classId}/{assignmentId}', [\App\Http\Controllers\TeacherController::class, 'gradeAssignments'])->name('assignments.grade');
    Route::post('/assignments/score/{classId}/{assignmentId}/{submissionId}', [\App\Http\Controllers\TeacherController::class, 'submitAssignmentScore'])->name('assignments.score');
    
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
});

/* -------- STUDENT (STUDENT + SUPER CAN ACCESS) -------- */
Route::middleware(['role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});