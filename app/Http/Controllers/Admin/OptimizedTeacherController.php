<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Subject;
use App\Models\User;
use App\Services\AdminTeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OptimizedTeacherController extends Controller
{
    protected $teacherService;

    public function __construct(AdminTeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display teachers with campus filtering
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $filters = $request->only(['search', 'campus', 'status', 'approval_status']);
        
        // Apply campus restriction for campus admins
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        $teachers = $this->teacherService->getFilteredTeachers($filters);
        $statistics = $this->teacherService->getTeacherStatistics($adminCampus);

        return view('admin.teachers.index', compact('teachers', 'statistics', 'adminCampus'));
    }

    /**
     * Show teacher creation form
     */
    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;
        
        // Get available schools/campuses for dropdown
        $schools = \App\Models\School::orderBy('school_name')->get();
        
        $campuses = $this->teacherService->getAvailableCampuses($adminCampus);
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();

        return view('admin.teachers.create', compact('schools', 'campuses', 'courses', 'adminCampus', 'adminSchoolId'));
    }

    /**
     * Store new teacher
     */
    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
        ]);

        // Automatically assign admin's campus and school
        if ($adminCampus) {
            $validated['campus'] = $adminCampus;
            $validated['auto_approve'] = true; // Auto-approve teachers created by campus admin
        }
        if ($adminSchoolId) {
            $validated['school_id'] = $adminSchoolId;
        }

        $teacher = $this->teacherService->createTeacher($validated, $admin->id);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Show teacher details
     */
    public function show(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $teacher->load(['subjects', 'classes.course', 'courseAccessRequests.course']);
        $statistics = $this->teacherService->getTeacherDetailStatistics($teacher);

        return view('admin.teachers.show', compact('teacher', 'statistics'));
    }

    /**
     * Show teacher edit form
     */
    public function edit(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;
        
        // Get available schools/campuses for dropdown
        $schools = \App\Models\School::orderBy('school_name')->get();
        
        $campuses = $this->teacherService->getAvailableCampuses($adminCampus);
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();

        return view('admin.teachers.edit', compact('teacher', 'schools', 'campuses', 'courses', 'adminCampus', 'adminSchoolId'));
    }

    /**
     * Update teacher
     */
    public function update(Request $request, User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $teacher->id,
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
            'status' => 'required|in:Active,Inactive',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Keep the teacher in the same campus and school as admin
        if ($adminCampus) {
            $validated['campus'] = $adminCampus;
        }
        if ($adminSchoolId) {
            $validated['school_id'] = $adminSchoolId;
        }

        $this->teacherService->updateTeacher($teacher, $validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Delete teacher
     */
    public function destroy(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $this->teacherService->deleteTeacher($teacher);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Campus Approvals Management
     */
    public function campusApprovals()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $approvals = $this->teacherService->getCampusApprovals($adminCampus);

        return view('admin.teachers.campus_approvals', $approvals);
    }

    /**
     * Approve campus affiliation
     */
    public function approveCampus(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $result = $this->teacherService->approveCampusAffiliation($teacher, Auth::id());

        return response()->json($result);
    }

    /**
     * Reject campus affiliation
     */
    public function rejectCampus(Request $request, User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $reason = $request->input('reason');
        $result = $this->teacherService->rejectCampusAffiliation($teacher, Auth::id(), $reason);

        return response()->json($result);
    }

    /**
     * Revoke campus affiliation
     */
    public function revokeCampus(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $result = $this->teacherService->revokeCampusAffiliation($teacher);

        return response()->json($result);
    }

    /**
     * Course Access Requests Management
     */
    public function courseAccessRequests()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $requests = $this->teacherService->getCourseAccessRequests($adminCampus);

        return view('admin.teachers.course_access_requests', $requests);
    }

    /**
     * Approve course access request
     */
    public function approveCourseAccess(CourseAccessRequest $request)
    {
        $this->authorizeCourseAccessRequest($request);
        
        $result = $this->teacherService->approveCourseAccess($request, Auth::id());

        return response()->json($result);
    }

    /**
     * Reject course access request
     */
    public function rejectCourseAccess(Request $httpRequest, CourseAccessRequest $request)
    {
        $this->authorizeCourseAccessRequest($request);
        
        $adminNote = $httpRequest->input('admin_note');
        $result = $this->teacherService->rejectCourseAccess($request, Auth::id(), $adminNote);

        return response()->json($result);
    }

    /**
     * Subject Management for Teacher
     */
    public function subjects(User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $subjects = $this->teacherService->getTeacherSubjects($teacher, $adminCampus);

        return view('admin.teachers.subjects', compact('teacher', 'subjects', 'adminCampus'));
    }

    /**
     * Assign subjects to teacher
     */
    public function assignSubjects(Request $request, User $teacher)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $validated = $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $admin = Auth::user();
        $result = $this->teacherService->assignSubjects($teacher, $validated['subject_ids'], $admin->campus);

        return redirect()->route('admin.teachers.subjects', $teacher)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Remove subject from teacher
     */
    public function removeSubject(User $teacher, Subject $subject)
    {
        $this->authorizeTeacherAccess($teacher);
        
        $result = $this->teacherService->removeSubject($teacher, $subject);

        return redirect()->route('admin.teachers.subjects', $teacher)
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Bulk Actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve_campus,reject_campus,activate,deactivate,delete',
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:users,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $admin = Auth::user();
        $result = $this->teacherService->performBulkAction(
            $validated['action'],
            $validated['teacher_ids'],
            $admin,
            $validated['reason'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Export teachers data
     */
    public function export(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $format = $request->input('format', 'csv');
        $filters = $request->only(['campus', 'status', 'approval_status']);
        
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        return $this->teacherService->exportTeachers($format, $filters);
    }

    /**
     * Import teachers from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:2048',
            'auto_approve' => 'boolean',
        ]);

        $admin = Auth::user();
        $result = $this->teacherService->importTeachers(
            $request->file('file'),
            $admin,
            $request->boolean('auto_approve')
        );

        return redirect()->route('admin.teachers.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Authorize teacher access based on campus
     */
    private function authorizeTeacherAccess(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $admin = Auth::user();
        $adminCampus = $admin->campus;

        // Campus admins can only manage teachers from their campus
        if ($adminCampus && $teacher->campus !== $adminCampus) {
            abort(403, 'You can only manage teachers from your campus');
        }
    }

    /**
     * Authorize course access request based on campus
     */
    private function authorizeCourseAccessRequest(CourseAccessRequest $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        // Campus admins can only manage requests from their campus teachers
        if ($adminCampus && $request->teacher->campus !== $adminCampus) {
            abort(403, 'You can only manage requests from your campus teachers');
        }
    }
}