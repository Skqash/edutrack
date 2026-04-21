<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Teacher;
use App\Services\AdminCourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptimizedCourseController extends Controller
{
    protected $courseService;

    public function __construct(AdminCourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display courses with campus filtering
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $filters = $request->only(['search', 'campus', 'department_id', 'status']);
        
        // Apply campus restriction for campus admins
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        $courses = $this->courseService->getFilteredCourses($filters);
        $statistics = $this->courseService->getCourseStatistics($adminCampus);
        $departments = Department::orderBy('department_name')->get();

        return view('admin.courses.index', compact(
            'courses', 
            'statistics', 
            'departments', 
            'adminCampus'
        ));
    }

    /**
     * Show course creation form
     */
    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $departments = Department::orderBy('department_name')->get();
        $heads = Teacher::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('last_name')
            ->get();

        return view('admin.courses.create', compact('departments', 'heads', 'adminCampus'));
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code|string|max:10',
            'program_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'program_head_id' => 'nullable|exists:users,id',
            'total_years' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',
        ]);

        // Handle department - create if doesn't exist
        $departmentName = $validated['department'];
        $department = Department::firstOrCreate(
            ['department_name' => $departmentName],
            ['description' => "Auto-created department: {$departmentName}"]
        );
        
        // Replace department name with department_id
        $validated['department_id'] = $department->id;
        unset($validated['department']);

        // Verify program head belongs to admin's campus
        if ($adminCampus && $validated['program_head_id']) {
            $head = User::find($validated['program_head_id']);
            if ($head->campus !== $adminCampus) {
                return back()->withErrors(['program_head_id' => 'Program head must be from your campus.']);
            }
        }

        $course = $this->courseService->createCourse($validated, $adminCampus);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Show course details
     */
    public function show(Course $course)
    {
        $this->authorizeCourseAccess($course);
        
        $course->load(['head', 'department', 'subjects', 'classes.teacher', 'students']);
        $statistics = $this->courseService->getCourseDetailStatistics($course);

        return view('admin.courses.show', compact('course', 'statistics'));
    }

    /**
     * Show course edit form
     */
    public function edit(Course $course)
    {
        $this->authorizeCourseAccess($course);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $departments = Department::orderBy('department_name')->get();
        $heads = Teacher::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('last_name')
            ->get();

        return view('admin.courses.edit', compact('course', 'departments', 'heads', 'adminCampus'));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        $this->authorizeCourseAccess($course);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code,' . $course->id . '|string|max:10',
            'program_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'program_head_id' => 'nullable|exists:users,id',
            'total_years' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',
        ]);

        // Verify program head belongs to admin's campus
        if ($adminCampus && $validated['program_head_id']) {
            $head = User::find($validated['program_head_id']);
            if ($head->campus !== $adminCampus) {
                return back()->withErrors(['program_head_id' => 'Program head must be from your campus.']);
            }
        }

        $this->courseService->updateCourse($course, $validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        $this->authorizeCourseAccess($course);
        
        $result = $this->courseService->deleteCourse($course);

        if (!$result['success']) {
            return redirect()->route('admin.courses.index')
                ->with('error', $result['message']);
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Manage course subjects
     */
    public function manageSubjects(Course $course)
    {
        $this->authorizeCourseAccess($course);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $subjects = $this->courseService->getCourseSubjects($course, $adminCampus);

        return view('admin.courses.subjects', compact('course', 'subjects', 'adminCampus'));
    }

    /**
     * Bulk Actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $admin = Auth::user();
        $result = $this->courseService->performBulkAction(
            $validated['action'],
            $validated['course_ids'],
            $admin
        );

        return response()->json($result);
    }

    /**
     * Export courses data
     */
    public function export(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $format = $request->input('format', 'csv');
        $filters = $request->only(['campus', 'department_id', 'status']);
        
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        return $this->courseService->exportCourses($format, $filters);
    }

    /**
     * Get courses by department for AJAX
     */
    public function getCoursesByDepartment(Request $request)
    {
        $departmentId = $request->input('department_id');
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $courses = Course::where('department_id', $departmentId)
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get(['id', 'program_name', 'program_code']);

        return response()->json($courses);
    }

    /**
     * Search courses for dynamic dropdown
     */
    public function searchCourses(Request $request)
    {
        $search = $request->input('search');
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $courses = Course::when($search, function ($q) use ($search) {
                $q->where('program_name', 'like', "%{$search}%")
                  ->orWhere('program_code', 'like', "%{$search}%");
            })
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->limit(20)
            ->get(['id', 'program_name', 'program_code', 'department_id']);

        return response()->json([
            'results' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'text' => $course->program_name . ' (' . $course->program_code . ')',
                    'program_code' => $course->program_code,
                    'department_id' => $course->department_id,
                ];
            })
        ]);
    }

    /**
     * Authorize course access based on campus
     */
    private function authorizeCourseAccess(Course $course)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        // Campus admins can only manage courses from their campus
        if ($adminCampus && $course->campus !== $adminCampus) {
            abort(403, 'You can only manage courses from your campus');
        }
    }
}