<?php

namespace App\Http\Controllers;

use App\Models\AssessmentRange;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Grade;
use App\Models\GradeEntry;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\Notification;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\KsaSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /**
     * Show teacher dashboard
     */
    public function dashboard()
    {
        $dashboardService = new \App\Services\TeacherDashboardService();
        $data = $dashboardService->getDashboardData();
        
        // Get performance metrics
        $teacher = Auth::user();
        $performanceMetrics = $dashboardService->getPerformanceMetrics(
            $teacher->id,
            $teacher->campus,
            $teacher->school_id
        );

        // Ensure arrays are properly handled for count operations
        $pendingTasks = is_array($data['pendingTasks']) ? $data['pendingTasks'] : [];
        $pendingSubjectRequests = array_filter($pendingTasks, fn($task) => $task['type'] === 'subject_request');

        return view('teacher.dashboard', array_merge($data, [
            'performanceMetrics' => $performanceMetrics,
            // Legacy variables for backward compatibility
            'myClasses' => $data['myClasses'],
            'totalStudents' => $data['statistics']['totalStudents'],
            'gradesPosted' => $data['statistics']['gradesPosted'],
            'pendingTasksCount' => count($pendingTasks),
            'recentGrades' => $data['recentActivities'],
            'myCourses' => $data['availableCourses'],
            'assignedSubjects' => $data['assignedSubjects'],
            'pendingSubjectRequests' => count($pendingSubjectRequests),
        ]));
    }

    /**
     * Show teacher profile
     */
    public function showProfile()
    {
        $teacher = Auth::user();
        $dashboardService = new \App\Services\TeacherDashboardService();
        $profileData = $dashboardService->getProfileManagementData($teacher);
        $securityPolicies = $dashboardService->getSecurityPolicies($teacher);

        return view('teacher.profile.show', compact('teacher', 'profileData', 'securityPolicies'));
    }

    /**
     * Show teacher profile edit form
     */
    public function editProfile()
    {
        $teacher = Auth::user();
        $schools = \App\Models\School::active()->orderBy('school_name')->get();
        
        return view('teacher.profile.edit', compact('teacher', 'schools'));
    }

    /**
     * Update teacher profile with campus isolation
     */
    public function updateProfile(Request $request)
    {
        $teacher = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $teacher->id,
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Campus and school_id cannot be changed by teacher
        // Only admin can modify these fields
        
        $teacher->update($validated);

        return redirect()->route('teacher.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        return view('teacher.profile.change-password');
    }

    /**
     * Update teacher password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $teacher = Auth::user();

        if (!Hash::check($validated['current_password'], $teacher->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $teacher->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('teacher.profile.show')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Show teacher settings
     */
    public function showSettings()
    {
        $teacher = Auth::user();
        $dashboardService = new \App\Services\TeacherDashboardService();
        $securitySettings = $dashboardService->getSecuritySettings($teacher);
        $notificationPreferences = $dashboardService->getNotificationPreferences($teacher);

        return view('teacher.settings.index', compact('teacher', 'securitySettings', 'notificationPreferences'));
    }

    /**
     * Update teacher settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'nullable|in:light,dark,auto',
            'language' => 'nullable|in:en,es,fr',
            'timezone' => 'nullable|string|max:50',
            'email_notifications' => 'boolean',
            'grade_reminders' => 'boolean',
            'attendance_reminders' => 'boolean',
            'system_updates' => 'boolean',
            'campus_announcements' => 'boolean',
        ]);

        $teacher = Auth::user();
        $teacher->update([
            'theme' => $validated['theme'] ?? 'light',
            'language' => $validated['language'] ?? 'en',
            'timezone' => $validated['timezone'] ?? 'UTC',
            'settings' => json_encode([
                'email_notifications' => $validated['email_notifications'] ?? true,
                'grade_reminders' => $validated['grade_reminders'] ?? true,
                'attendance_reminders' => $validated['attendance_reminders'] ?? true,
                'system_updates' => $validated['system_updates'] ?? true,
                'campus_announcements' => $validated['campus_announcements'] ?? false,
            ]),
        ]);

        return redirect()->route('teacher.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Request campus affiliation change
     */
    public function requestCampusChange(Request $request)
    {
        $validated = $request->validate([
            'requested_campus' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
        ]);

        $teacher = Auth::user();
        
        // Create a school request for campus change
        \App\Models\SchoolRequest::create([
            'user_id' => $teacher->id,
            'school_name' => 'Campus Change Request: ' . $validated['requested_campus'],
            'school_email' => null,
            'school_phone' => null,
            'school_address' => null,
            'note' => 'Teacher requested campus change from "' . ($teacher->campus ?? 'Independent') . '" to "' . $validated['requested_campus'] . '". Reason: ' . $validated['reason'],
            'status' => 'pending',
            'request_type' => 'campus_change',
            'related_id' => null,
            'related_name' => $validated['requested_campus'],
        ]);

        // Notify admin
        \App\Models\Notification::create([
            'user_id' => 1, // Admin user
            'title' => 'Campus Change Request from ' . $teacher->name,
            'message' => 'Teacher ' . $teacher->name . ' requested campus change to: ' . $validated['requested_campus'],
            'type' => 'info',
            'icon' => 'university',
            'action_url' => route('admin.teachers.show', $teacher->id),
        ]);

        return redirect()->route('teacher.profile.show')
            ->with('success', 'Campus change request submitted successfully. Admin will review your request.');
    }

    /**
     * Show teacher's classes with campus isolation
     */
    public function classes()
    {
        $teacher = Auth::user();
        $teacherId = $teacher->id;
        $campus = $teacher->campus;
        $schoolId = $teacher->school_id;

        // Apply campus isolation
        $query = ClassModel::where('teacher_id', $teacherId)
            ->with('students', 'course', 'subject', 'school');

        if ($campus) {
            $query->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        $classes = $query->paginate(10);

        // Get available courses for this teacher's campus
        $coursesQuery = Course::orderBy('program_name');
        if ($campus) {
            $coursesQuery->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $coursesQuery->where('school_id', $schoolId);
        }
        $programs = $coursesQuery->get();

        return view('teacher.classes.index', compact('classes', 'programs', 'campus', 'schoolId'));
    }

    /**
     * Show teacher's assigned courses with campus isolation
     */
    public function subjectsIndex()
    {
        $teacher = Auth::user();
        $teacherId = $teacher->id;
        $campus = $teacher->campus;
        $schoolId = $teacher->school_id;

        // Get subjects assigned to this teacher — include campus-specific AND null-campus (GE) subjects
        $assignedSubjectsQuery = Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'active');
        })->with(['program', 'teachers', 'campusSchool']);

        if ($campus) {
            $assignedSubjectsQuery->where(function ($q) use ($campus, $schoolId) {
                $q->where('campus', $campus)
                  ->orWhereNull('campus'); // include GE / campus-neutral subjects
                if (!empty($schoolId)) {
                    $q->orWhere('school_id', $schoolId);
                }
            });
        }

        $assignedSubjects = $assignedSubjectsQuery->orderBy('subject_name')->get();

        $pendingSubjectsQuery = Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'pending');
        })->with(['program', 'teachers', 'campusSchool']);

        if ($campus) {
            $pendingSubjectsQuery->where(function ($q) use ($campus, $schoolId) {
                $q->where('campus', $campus)
                  ->orWhereNull('campus');
                if (!empty($schoolId)) {
                    $q->orWhere('school_id', $schoolId);
                }
            });
        }

        $pendingSubjects = $pendingSubjectsQuery->orderBy('subject_name')->get();

        // Get all unique courses from this teacher's classes with campus isolation
        $classesQuery = ClassModel::where('teacher_id', $teacherId);
        if (!empty($campus)) {
            $classesQuery->where('campus', $campus);
        }
        // Only filter by school_id when it's actually set (not null/empty)
        if (!empty($schoolId)) {
            $classesQuery->where('school_id', $schoolId);
        }

        $courseIds = $classesQuery->distinct()->pluck('course_id');

        $courses = Course::whereIn('id', $courseIds)
            ->orderBy('program_name')
            ->get();

        // Enhance course data with class and student counts
        $coursesData = $courses->map(function ($course) use ($teacherId, $campus, $schoolId) {
            $classesQuery = ClassModel::where('course_id', $course->id)
                ->where('teacher_id', $teacherId)
                ->with('students');

            if (!empty($campus)) {
                $classesQuery->where('campus', $campus);
            }
            if (!empty($schoolId)) {
                $classesQuery->where('school_id', $schoolId);
            }

            $classes = $classesQuery->get();

            return [
                'id' => $course->id,
                'name' => $course->program_name,
                'description' => $course->description ?? null,
                'code' => $course->program_code ?? null,
                'class_count' => $classes->count(),
                'student_count' => $classes->sum(function ($c) {
                    return $c->students()
                        ->when(!empty($c->campus), fn($q) => $q->where('campus', $c->campus))
                        ->when(!empty($c->school_id), fn($q) => $q->where('school_id', $c->school_id))
                        ->count();
                }),
                'classes' => $classes->map(fn ($c) => ['id' => $c->id, 'class_name' => $c->class_name])->toArray(),
                'campus' => $course->campus,
                'school_id' => $course->school_id,
            ];
        });

        // Add independent subjects (subjects without courses but assigned to teacher)
        $independentSubjects = $assignedSubjects->filter(function ($subject) {
            return $subject->program_id === null;
        });

        if ($independentSubjects->count() > 0) {
            $coursesData->push([
                'id' => 'independent',
                'name' => 'Independent Subjects',
                'description' => 'Subjects you created independently',
                'code' => 'IND',
                'class_count' => 0, // Independent subjects don't have classes yet
                'student_count' => 0,
                'classes' => [],
                'campus' => $campus,
                'school_id' => $schoolId,
                'subjects' => $independentSubjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->subject_name,
                        'code' => $subject->subject_code,
                        'description' => $subject->description,
                        'credit_hours' => $subject->credit_hours,
                        'category' => $subject->category,
                        'campus' => $subject->campus,
                        'school_id' => $subject->school_id,
                    ];
                })->toArray(),
            ]);
        }

        $totalClassesQuery = ClassModel::where('teacher_id', $teacherId);
        if (!empty($campus)) {
            $totalClassesQuery->where('campus', $campus);
        }
        if (!empty($schoolId)) {
            $totalClassesQuery->where('school_id', $schoolId);
        }
        $totalClasses = $totalClassesQuery->count();

        $totalStudents = $totalClassesQuery->get()
            ->sum(function ($c) {
                return $c->students()
                    ->when(!empty($c->campus), fn($q) => $q->where('campus', $c->campus))
                    ->when(!empty($c->school_id), fn($q) => $q->where('school_id', $c->school_id))
                    ->count();
            });

        return view('teacher.subjects.index', [
            'courses' => $coursesData,
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
            'assignedSubjects' => $assignedSubjects,
            'pendingSubjects' => $pendingSubjects,
            'campus' => $campus,
            'schoolId' => $schoolId,
        ]);
    }

    /**
     * List teacher requests (school connection and other types)
     */
    public function requestHistory()
    {
        $teacherId = Auth::id();

        $requests = SchoolRequest::where('user_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pendingSubjects = Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'pending');
        })->get();

        return view('teacher.requests.index', compact('requests', 'pendingSubjects'));
    }

    /**
     * Request a subject from admin
     */
    public function requestSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50',
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $teacher = User::find($teacherId);

        // Create a notification for admin
        Notification::create([
            'user_id' => 1, // Assuming admin user ID is 1, adjust as needed
            'title' => 'Subject Request from '.$teacher->name,
            'message' => "Teacher {$teacher->name} has requested to be assigned: {$validated['subject_name']}".
                        ($validated['subject_code'] ? " ({$validated['subject_code']})" : '').
                        ". Reason: {$validated['reason']}",
            'type' => 'info',
            'icon' => 'paper-plane',
            'action_url' => route('admin.teachers.subjects', $teacherId),
        ]);

        // Record request in teacher_subject pending (if subject exists)
        $subject = Subject::firstOrCreate(
            ['subject_code' => $validated['subject_code'] ?: 'REQ-'.strtoupper(str_replace(' ', '-', substr($validated['subject_name'],0,20)))],
            [
                'subject_name' => $validated['subject_name'],
                'credit_hours' => 3,
                'category' => 'Unspecified',
                'description' => 'Requested by teacher: '.$validated['reason'],
                'semester' => '1',
                'year_level' => 1,
                'program_id' => null,
            ]
        );

        $subject->teachers()->syncWithoutDetaching([
            $teacherId => [
                'status' => 'pending',
                'assigned_at' => now(),
            ],
        ]);

        // Create unified request record for admin review
        $schoolRequest = SchoolRequest::create([
            'user_id' => $teacherId,
            'school_name' => 'Subject Request: '.$validated['subject_name'],
            'school_email' => null,
            'school_phone' => null,
            'school_address' => null,
            'note' => 'Teacher requested to teach subject: '.$validated['subject_name'].' ('.$validated['subject_code'].'). Reason: '.$validated['reason'],
            'status' => 'pending',
            'request_type' => 'subject',
            'related_id' => $subject->id,
            'related_name' => $subject->subject_name,
        ]);

        \App\Models\Notification::create([
            'user_id' => 1,
            'title' => 'Subject Request from '.Auth::user()->name,
            'message' => 'Teacher '.Auth::user()->name.' requested subject: '. $validated['subject_name'],
            'type' => 'info',
            'icon' => 'book',
            'action_url' => route('admin.school-requests.show', $schoolRequest),
        ]);

        return redirect()->route('teacher.subjects')->with('success', 'Subject request sent to admin successfully!');
    }

    /**
     * Request a new course from admin
     */
    public function requestCourse(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();

        $course = Course::firstOrCreate(
            ['program_code' => $validated['course_code']],
            [
                'program_name' => $validated['course_name'],
                'description' => 'Requested by teacher: '.$validated['reason'],
                'total_years' => 4,
                'status' => 'Pending',
            ]
        );

        $schoolRequest = SchoolRequest::create([
            'user_id' => $teacherId,
            'school_name' => 'Course Request: '.$validated['course_name'],
            'school_email' => null,
            'school_phone' => null,
            'school_address' => null,
            'note' => 'Teacher requested a new course: '.$validated['course_name'].' ('.$validated['course_code'].')'.
                    ' Reason: '.$validated['reason'],
            'status' => 'pending',
            'request_type' => 'course',
            'related_id' => $course->id,
            'related_name' => $course->program_name,
        ]);

        // Notify admins through in-app notifications
        \App\Models\Notification::create([
            'user_id' => 1, // admin user fallback
            'title' => 'Course Request from '.Auth::user()->name,
            'message' => 'Teacher '.Auth::user()->name.' requested course: '. $validated['course_name'],
            'type' => 'info',
            'icon' => 'book',
            'action_url' => route('admin.school-requests.show', $schoolRequest),
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Course request submitted to admin successfully!');
    }

    /**
     * Request a new class assignment from admin
     */
    public function requestClass(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'section' => 'required|string|max:10',
            'year_level' => 'required|integer|min:1|max:4',
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();

        $course = Course::findOrFail($validated['course_id']);

        $schoolRequest = SchoolRequest::create([
            'user_id' => $teacherId,
            'school_name' => 'Class Request: '.$validated['class_name'],
            'school_email' => null,
            'school_phone' => null,
            'school_address' => null,
            'note' => 'Teacher requested class creation for course '.$course->program_name.
                        ', Section '.$validated['section'].' Year '.$validated['year_level'].'. Reason: '.$validated['reason'],
            'status' => 'pending',
            'request_type' => 'class',
            'related_id' => $course->id,
            'related_name' => $validated['class_name'],
        ]);

        \App\Models\Notification::create([
            'user_id' => 1,
            'title' => 'Class Request from '.Auth::user()->name,
            'message' => 'Teacher '.Auth::user()->name.' requested class: '. $validated['class_name'],
            'type' => 'info',
            'icon' => 'chalkboard-teacher',
            'action_url' => route('admin.school-requests.show', $schoolRequest),
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Class request submitted to admin successfully!');
    }

    /**
     * Create a new subject (for independent teachers) with campus isolation
     */
    public function createSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'credit_hours' => 'required|integer|min:1|max:6',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'year_level' => 'nullable|integer|min:1|max:4',
            'semester' => 'required|string|in:1,2',
        ]);

        $teacher = Auth::user();
        $teacherId = $teacher->id;

        // Choose category from custom field if needed
        $category = $validated['category'] ?? 'General';
        if ($request->filled('category_other')) {
            $category = $request->input('category_other');
        }

        // Create the subject with campus isolation
        $subjectData = [
            'subject_name' => $validated['subject_name'],
            'subject_code' => $validated['subject_code'],
            'credit_hours' => $validated['credit_hours'],
            'category' => $category,
            'description' => $validated['description'],
            'year_level' => $validated['year_level'] ?? 1,
            'semester' => $validated['semester'],
            'program_id' => $request->filled('program_id') ? (int) $request->input('program_id') : null,
        ];

        // Apply campus isolation
        if ($teacher->campus) {
            $subjectData['campus'] = $teacher->campus;
        }
        if ($teacher->school_id) {
            $subjectData['school_id'] = $teacher->school_id;
        }

        // Generate campus-specific subject code if teacher has campus
        if ($teacher->campus && $teacher->school_id) {
            $school = \App\Models\School::find($teacher->school_id);
            if ($school && $school->school_code) {
                // Don't modify the subject_code, but store campus_code separately
                $subjectData['campus_code'] = $school->school_code;
            }
        }

        $subject = Subject::create($subjectData);

        // Assign the teacher to this subject
        $subject->teachers()->attach($teacherId, [
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        return redirect()->route('teacher.subjects')->with('success', 'Subject created successfully! You can now create classes for this subject.');
    }

    /**
     * Remove a subject from the logged-in teacher (unassign)
     */
    public function removeSubjectFromTeacher($subjectId)
    {
        /** @var User|null $teacher */
        $teacher = Auth::user();
        
        // Security check: ensure user is authenticated and is a teacher
        if (!$teacher || $teacher->role !== 'teacher') {
            abort(403, 'Unauthorized. Only teachers can access this resource.');
        }
        
        $subject = Subject::findOrFail($subjectId);

        $teacher->subjects()->detach($subject->id);

        // If this is an independent subject and no other teacher uses it, clean it up
        if (is_null($subject->program_id) && $subject->teachers()->count() === 0) {
            $subject->delete();
        }

        return redirect()->route('teacher.subjects')->with('success', 'Subject removed from your assignments successfully.');
    }

    /**
     * Show class details with students
     */
    public function classDetail($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'course', 'attendance', 'grades')
            ->firstOrFail();

        return view('teacher.classes.show', compact('class'));
    }

    /**
     * Show teacher grade dashboard (midterm/final entry + summary)
     */
    public function grades()
    {
        $teacherId = Auth::id();
        $teacher = Auth::user();

        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('course')
            ->paginate(10);

        // Attach student_count per class using course_id (same pattern as dashboard)
        foreach ($classes as $class) {
            $query = Student::query();
            if ($class->course_id) {
                $query->where('course_id', $class->course_id);
                if ($class->campus) $query->where('campus', $class->campus);
                if ($class->school_id) $query->where('school_id', $class->school_id);
            } else {
                $query->where('class_id', $class->id);
            }
            $class->student_count = $query->count();
        }

        $courses = Course::orderBy('program_name')->get();

        return view('teacher.grades.index', compact('classes', 'courses'));
    }

    /**
     * Show grade entry form for a class
     */
    public function gradeEntry($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('course')
            ->firstOrFail();

        // Load students by course_id so non-primary classes show all course students
        $studentsQuery = Student::query()
            ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name');

        if (!$class->course_id) {
            $studentsQuery = Student::where('class_id', $classId);
        }

        $students = $studentsQuery->get();
        // Attach students to class object for view compatibility
        $class->setRelation('students', $students);

        // Get existing grades for this class
        $existingGrades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->get()
            ->keyBy('student_id');

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        return view('teacher.grades.entry', compact('class', 'existingGrades', 'range'));
    }

    /**
     * Show UNIFIED grade entry form (New Comprehensive Form)
     */
    public function showGradeEntryUnified($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store or update grades (KSA Grading System)
     */
    public function storeGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Validate teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)->firstOrFail();

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.knowledge_score' => 'required|numeric|min:0|max:100',
            'grades.*.skills_score' => 'required|numeric|min:0|max:100',
            'grades.*.attitude_score' => 'required|numeric|min:0|max:100',
            'grades.*.remarks' => 'nullable|string|max:255',
        ], [
            'grades.*.knowledge_score.required' => 'Knowledge score is required',
            'grades.*.skills_score.required' => 'Skills score is required',
            'grades.*.attitude_score.required' => 'Attitude score is required',
        ]);

        $gradesCreated = 0;
        $gradesUpdated = 0;

        foreach ($validated['grades'] as $gradeData) {
            $knowledge = (float) $gradeData['knowledge_score'];
            $skills = (float) $gradeData['skills_score'];
            $attitude = (float) $gradeData['attitude_score'];

            // Calculate final grade using KSA formula
            $finalGrade = Grade::calculateFinalGrade($knowledge, $skills, $attitude);

            // Update or create grade
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                ],
                [
                    'knowledge_score' => $knowledge,
                    'skills_score' => $skills,
                    'attitude_score' => $attitude,
                    'final_grade' => $finalGrade,
                    'remarks' => $gradeData['remarks'] ?? null,
                    'grading_period' => date('Y-m').'-'.(intdiv((int) date('m') - 1, 3) + 1),
                ]
            );

            if ($grade->wasRecentlyCreated) {
                $gradesCreated++;
            } else {
                $gradesUpdated++;
            }
        }

        return redirect()->route('teacher.grades')
            ->with('success', "Grades saved! Created: {$gradesCreated}, Updated: {$gradesUpdated}");
    }

    /**
     * Show attendance page
     */
    public function attendance(Request $request)
    {
        $teacherId = Auth::id();

        $currentTerm = $request->query('term', 'Midterm');
        if (! in_array($currentTerm, ['Midterm', 'Final'])) {
            $currentTerm = 'Midterm';
        }

        $today = $request->query('date', now()->format('Y-m-d'));

        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->get();

        return view('teacher.attendance.index', compact('classes', 'currentTerm', 'today'));
    }

    /**
     * Manage attendance for a specific class
     */
    public function manageAttendance(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::with('course')->findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $currentTerm = $request->query('term', 'Midterm');
        if (! in_array($currentTerm, ['Midterm', 'Final'])) {
            $currentTerm = 'Midterm';
        }

        $today = $request->query('date', now()->format('Y-m-d'));

        // Load students by course_id (not just class_id) so non-primary classes show all course students
        $studentsQuery = Student::query()
            ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name');

        // Fallback: if no course_id, use class_id (primary class)
        if (!$class->course_id) {
            $studentsQuery = $class->students()
                ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                ->orderBy('last_name')->orderBy('first_name');
        }

        $students = $studentsQuery->get();

        // Get attendance for selected date and term
        $attendances = Attendance::where('class_id', $classId)
            ->where('date', $today)
            ->where('term', $currentTerm)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.manage', compact('class', 'students', 'attendances', 'today', 'currentTerm'));
    }

    /**
     * Record attendance
     */
    public function recordAttendance(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'term' => 'required|in:Midterm,Final',
        ]);

        $date = $validated['date'];
        $term = $validated['term'];
        $attendanceData = $request->input('attendance', []);

        if (empty($attendanceData)) {
            return redirect()->back()->with('error', 'No attendance data submitted. Please mark at least one student.');
        }

        $saved = 0;
        foreach ($attendanceData as $studentId => $data) {
            $status = $data['status'] ?? null;
            if (! $status || ! in_array($status, ['Present', 'Absent', 'Late', 'Leave'])) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $date,
                    'term' => $term,
                ],
                [
                    'status' => $status,
                    'term' => $term,
                    'teacher_id' => $teacherId,
                    'campus' => $class->campus,
                    'school_id' => $class->school_id,
                ]
            );
            $saved++;
        }

        if ($saved === 0) {
            return redirect()->back()->with('error', 'No valid attendance records were saved.');
        }

        return redirect()->route('teacher.attendance.manage', ['classId' => $classId, 'term' => $term, 'date' => $date])
            ->with('success', "Attendance saved for {$saved} student(s) on {$date}.");
    }

    /**
     * Attendance history / search for a class
     */
    public function attendanceHistory(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        // Load students by course_id so non-primary classes show all course students
        $studentsQuery = Student::query()
            ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name');

        if (!$class->course_id) {
            $studentsQuery = Student::where('class_id', $classId);
        }

        $students = $studentsQuery->get();

        $term = $request->input('term');

        $query = Attendance::where('class_id', $classId);

        if ($term && in_array($term, ['Midterm', 'Final'])) {
            $query->where('term', $term);
        }

        // Filters
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $studentId = $request->input('student_id');

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($start && $end) {
            // ensure proper ordering
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end));
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($start) {
            $query->where('date', '>=', date('Y-m-d', strtotime($start)));
        } elseif ($end) {
            $query->where('date', '<=', date('Y-m-d', strtotime($end)));
        }

        $attendances = $query->with('student')->orderBy('date', 'desc')->paginate(25)->appends($request->query());

        return view('teacher.attendance.history', compact('class', 'students', 'attendances', 'start', 'end', 'studentId', 'term'));
    }

    /**
     * Get KSA grading information
     */
    public function ksaInfo()
    {
        return response()->json([
            'knowledge' => [
                'weight' => 40,
                'description' => 'Quizzes 40% + Exams 60% = 40% of term',
            ],
            'skills' => [
                'weight' => 50,
                'description' => 'Output 40% + Class Part 30% + Activities 15% + Assignments 15% = 50% of term',
            ],
            'attitude' => [
                'weight' => 10,
                'description' => 'Behavior 50% + Awareness 50% = 10% of term',
            ],
            'formula' => 'Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)',
        ]);
    }

    /**
     * Show student addition form
     */
    public function showAddStudent()
    {
        $teacherId = Auth::id();
        $teacher = Auth::user();
        $teacherCampus = $teacher->campus;
        $teacherSchoolId = $teacher->school_id;

        // Get teacher's classes with campus isolation
        $myClasses = ClassModel::where('teacher_id', $teacherId)
            ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
            ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
            ->get();

        $students = Student::whereIn('class_id', $myClasses->pluck('id'))
            ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
            ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
            ->paginate(20);

        return view('teacher.students.add', compact('myClasses', 'students'));
    }

    /**
     * Store manually added student
     */
    public function storeStudent(Request $request)
    {
        $teacherId = Auth::id();

        // Verify class belongs to teacher
        $class = ClassModel::where('teacher_id', $teacherId)
            ->findOrFail($request->class_id);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'year_level' => 'required|integer|in:1,2,3,4',
            'section' => 'required|string|in:A,B,C,D,E',
        ]);

        // Combine firstname and surname
        $fullName = trim($validated['firstname']).' '.trim($validated['surname']);

        // Create user
        $user = User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Generate student ID in format: YYYY-XXXX-S (e.g., 2022-0233-A)
        $enrollmentYear = date('Y');
        $nextStudentNumber = Student::count() + 1;
        $sequentialNumber = str_pad($nextStudentNumber, 4, '0', STR_PAD_LEFT);
        $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $validated['section']);

        // Create student
        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $validated['class_id'],
            'student_id' => $studentId,
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'status' => 'Active',
        ]);

        return redirect()->back()->with('success', "Student {$studentId} added successfully!");
    }

    /**
     * Search for existing students to add to class
     */
    public function searchStudents(Request $request)
    {
        $teacherId = Auth::id();
        $classId = $request->input('class_id');
        $searchTerm = $request->input('search', '');
        $unassignedOnly = $request->input('unassigned_only', false);
        $sameYearOnly = $request->input('same_year_only', false);

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        // Build query
        $query = Student::with('user', 'class');

        // Search by name, email, or student ID
        if (! empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($subQ) use ($searchTerm) {
                    $subQ->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                })
                    ->orWhere('student_id', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by same year level if requested
        if ($sameYearOnly && $class->year) {
            $query->where('year', $class->year);
        }

        // Filter unassigned if requested
        if ($unassignedOnly) {
            $query->whereNull('class_id')
                ->orWhere('class_id', '!=', $classId);
        }

        // Exclude students already in this class
        $query->where(function ($q) use ($classId) {
            $q->whereNull('class_id')
                ->orWhere('class_id', '!=', $classId);
        });

        $students = $query->limit(50)->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name ?? 'N/A',
                'email' => $student->email ?? 'N/A',
                'student_id' => $student->student_id,
                'year' => $student->year,
                'section' => $student->section,
                'current_class' => $student->class ? $student->class->class_name : null,
                'status' => $student->status,
            ];
        });

        return response()->json(['students' => $students]);
    }

    /**
     * Add existing students to class
     */
    public function addExistingStudents(Request $request)
    {
        $teacherId = Auth::id();
        $classId = $request->input('class_id');
        $studentIds = $request->input('student_ids', []);

        // Validate input
        if (empty($classId) || empty($studentIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Class and students are required',
            ], 400);
        }

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or access denied',
            ], 404);
        }

        $addedCount = 0;
        $errors = [];

        foreach ($studentIds as $studentId) {
            try {
                $student = Student::findOrFail($studentId);

                // Check if student is already in this class
                if ($student->class_id == $classId) {
                    $errors[] = "Student {$student->student_id} is already in this class";

                    continue;
                }

                // Update student's class assignment + school/department metadata
                $studentUpdate = ['class_id' => $classId];
                if (! empty($class->course->college)) {
                    $studentUpdate['school'] = $class->course->college;
                }
                if (! empty($class->course->department)) {
                    $studentUpdate['department'] = $class->course->department;
                }
                $student->update($studentUpdate);
                $addedCount++;

            } catch (\Exception $e) {
                $errors[] = "Error adding student ID {$studentId}: ".$e->getMessage();
            }
        }

        if ($addedCount > 0) {
            $message = "Successfully added {$addedCount} student(s) to {$class->class_name}";
            if (! empty($errors)) {
                $message .= '. Some errors occurred: '.implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'added_count' => $addedCount,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No students were added. '.implode(', ', $errors),
            ], 400);
        }
    }

    /**
     * Show CHED grade entry form
     */
    public function showGradeEntryChed($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Show term selection view (Step 1: Choose midterm or final)
     */
    public function showGradeEntryByTerm($classId)
    {
        $teacherId = Auth::id();

        // Get term from query parameter
        $term = request()->query('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            abort(400, 'Invalid term');
        }

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('course')
            ->firstOrFail();

        $students = $this->getStudentsForClass($class);
        $class->setRelation('students', $students);

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Load existing grade entries for this term
        $entries = GradeEntry::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        // Create KSA settings object to match expected structure
        $ksaSettings = (object) [
            'knowledge_percentage' => 40,
            'skills_percentage' => 50,
            'attitude_percentage' => 10,
            'knowledge_weight' => 0.4,
            'skills_weight' => 0.5,
            'attitude_weight' => 0.1,
            'components' => (object) [
                'knowledge' => [
                    (object) ['name' => 'Exam', 'weight' => 60, 'max_score' => 100],
                    (object) ['name' => 'Quiz 1', 'weight' => 20, 'max_score' => 100],
                    (object) ['name' => 'Quiz 2', 'weight' => 20, 'max_score' => 100],
                ],
                'skills' => [
                    (object) ['name' => 'Output', 'weight' => 40, 'max_score' => 100],
                    (object) ['name' => 'Class Participation', 'weight' => 30, 'max_score' => 100],
                    (object) ['name' => 'Activities', 'weight' => 30, 'max_score' => 100],
                ],
                'attitude' => [
                    (object) ['name' => 'Behavior', 'weight' => 50, 'max_score' => 100],
                    (object) ['name' => 'Awareness', 'weight' => 50, 'max_score' => 100],
                ],
            ],
        ];

        // Use the advanced grade entry view with full component management
        return view('teacher.grades.advanced_grade_entry', compact('class', 'students', 'term', 'entries', 'range', 'ksaSettings'));
    }

    /**
     * Store grade entry for a specific term (using query parameter)
     */
    public function storeGradeEntryByTerm(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Get term from query parameter
        $term = $request->query('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            abort(400, 'Invalid term');
        }

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $saved = 0;
        $errors = [];

        foreach ($request->input('grades', []) as $studentId => $gradeData) {
            try {
                // Validate student belongs to this class's course and teacher's campus
                $student = Student::where('id', $studentId)
                    ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id),
                           fn($q) => $q->where('class_id', $classId))
                    ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                    ->first();

                if (! $student) {
                    continue;
                }

                // Extract component scores
                $exam = floatval($gradeData['exam'] ?? 0);
                $quiz1 = floatval($gradeData['quiz_1'] ?? 0);
                $quiz2 = floatval($gradeData['quiz_2'] ?? 0);

                $output = floatval($gradeData['output'] ?? 0);
                $classParticipation = floatval($gradeData['class_participation'] ?? 0);
                $activities = floatval($gradeData['activities'] ?? 0);

                $behavior = floatval($gradeData['behavior'] ?? 0);
                $awareness = floatval($gradeData['awareness'] ?? 0);

                // Normalize scores: (score/100) * 50 + 50 → range 50-100
                $normalize = fn($s) => min(100, ($s / 100) * 50 + 50);

                // Calculate component averages using weights (on normalized scores)
                // Knowledge: Exam (60%), Quiz 1 (20%), Quiz 2 (20%)
                $knowledgeAvg = ($normalize($exam) * 0.6) + ($normalize($quiz1) * 0.2) + ($normalize($quiz2) * 0.2);

                // Skills: Output (40%), Class Participation (30%), Activities (30%)
                $skillsAvg = ($normalize($output) * 0.4) + ($normalize($classParticipation) * 0.3) + ($normalize($activities) * 0.3);

                // Attitude: Behavior (50%), Awareness (50%)
                $attitudeAvg = ($normalize($behavior) * 0.5) + ($normalize($awareness) * 0.5);

                // Get KSA weights from settings
                $ksaSetting = \App\Models\KsaSetting::where('class_id', $classId)->where('term', $term)->first();
                $kW = ($ksaSetting->knowledge_weight ?? 40) / 100;
                $sW = ($ksaSetting->skills_weight    ?? 50) / 100;
                $aW = ($ksaSetting->attitude_weight  ?? 10) / 100;

                // Final grade = weighted KSA average
                $computedFinalGrade = round(($knowledgeAvg * $kW) + ($skillsAvg * $sW) + ($attitudeAvg * $aW), 2);
                $finalGrade = $computedFinalGrade; // Use computed, not submitted value

                // Create or update grade entry
                $entry = GradeEntry::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    [
                        // Store individual component scores
                        'exam' => $exam,
                        'quiz_1' => $quiz1,
                        'quiz_2' => $quiz2,
                        'output' => $output,
                        'class_participation' => $classParticipation,
                        'activities' => $activities,
                        'behavior' => $behavior,
                        'awareness' => $awareness,

                        // Store calculated averages
                        'knowledge_average' => round($knowledgeAvg, 2),
                        'skills_average'    => round($skillsAvg, 2),
                        'attitude_average'  => round($attitudeAvg, 2),

                        // Store final grade
                        'final_grade' => $finalGrade,
                        'graded_at' => now(),
                    ]
                );

                $saved++;
            } catch (\Exception $e) {
                Log::error('Grade entry error for student '.$studentId.': '.$e->getMessage());
                $errors[] = "Error saving grades for student ID {$studentId}";

                continue;
            }
        }

        if ($saved > 0) {
            return redirect()->route('teacher.grades')->with('success', "✅ Saved {$saved} grade records for ".ucfirst($term).' term');
        } else {
            return back()->with('error', 'No grades were saved. '.implode(', ', $errors));
        }
    }

    /**
     * Show the new professional grade entry page with selectable grading schemes
     */
    public function showGradeEntryAdvanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->query('term', 'midterm'); // Get from query parameter, default to midterm

        $class = ClassModel::where('teacher_id', $teacherId)
            ->with('course')
            ->findOrFail($classId);

        $students = $this->getStudentsForClass($class);
        $class->setRelation('students', $students);

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Load existing grade entries for this term from GradeEntry model
        $entries = GradeEntry::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        // Create KSA settings object to match expected structure
        $ksaSettings = (object) [
            'knowledge_percentage' => 40,
            'skills_percentage' => 50,
            'attitude_percentage' => 10,
            'knowledge_weight' => 0.4,
            'skills_weight' => 0.5,
            'attitude_weight' => 0.1,
            'components' => (object) [
                'knowledge' => [
                    (object) ['name' => 'Exam', 'weight' => 60, 'max_score' => 100],
                    (object) ['name' => 'Quiz 1', 'weight' => 20, 'max_score' => 100],
                    (object) ['name' => 'Quiz 2', 'weight' => 20, 'max_score' => 100],
                ],
                'skills' => [
                    (object) ['name' => 'Output', 'weight' => 40, 'max_score' => 100],
                    (object) ['name' => 'Class Participation', 'weight' => 30, 'max_score' => 100],
                    (object) ['name' => 'Activities', 'weight' => 30, 'max_score' => 100],
                ],
                'attitude' => [
                    (object) ['name' => 'Behavior', 'weight' => 50, 'max_score' => 100],
                    (object) ['name' => 'Awareness', 'weight' => 50, 'max_score' => 100],
                ],
            ],
        ];

        // Use grade_content as platform with advanced features
        return view('teacher.grades.grade_content', compact('class', 'students', 'term', 'entries', 'range', 'ksaSettings'));
    }

    /**
     * Store grades from the grade entry form
     */
    public function storeGradeEntryAdvanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->query('term', 'midterm');

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $saved = 0;
        $errors = [];

        foreach ($request->input('grades', []) as $studentId => $gradeData) {
            try {
                // Prepare data for GradeEntry
                $data = [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => $term,
                ];

                // Knowledge Component
                $data['exam_pr'] = floatval($gradeData['exam_pr'] ?? 0);
                $data['exam_md'] = floatval($gradeData['exam_md'] ?? 0);
                $data['quiz_1'] = floatval($gradeData['quiz_1'] ?? 0);
                $data['quiz_2'] = floatval($gradeData['quiz_2'] ?? 0);
                $data['quiz_3'] = floatval($gradeData['quiz_3'] ?? 0);
                $data['quiz_4'] = floatval($gradeData['quiz_4'] ?? 0);
                $data['quiz_5'] = floatval($gradeData['quiz_5'] ?? 0);

                // Skills Component
                $data['output_1'] = floatval($gradeData['output_1'] ?? 0);
                $data['output_2'] = floatval($gradeData['output_2'] ?? 0);
                $data['output_3'] = floatval($gradeData['output_3'] ?? 0);
                $data['classpart_1'] = floatval($gradeData['classpart_1'] ?? 0);
                $data['classpart_2'] = floatval($gradeData['classpart_2'] ?? 0);
                $data['classpart_3'] = floatval($gradeData['classpart_3'] ?? 0);
                $data['activity_1'] = floatval($gradeData['activity_1'] ?? 0);
                $data['activity_2'] = floatval($gradeData['activity_2'] ?? 0);
                $data['activity_3'] = floatval($gradeData['activity_3'] ?? 0);
                $data['assignment_1'] = floatval($gradeData['assignment_1'] ?? 0);
                $data['assignment_2'] = floatval($gradeData['assignment_2'] ?? 0);
                $data['assignment_3'] = floatval($gradeData['assignment_3'] ?? 0);

                // Attitude Component
                $data['behavior_1'] = floatval($gradeData['behavior_1'] ?? 0);
                $data['behavior_2'] = floatval($gradeData['behavior_2'] ?? 0);
                $data['behavior_3'] = floatval($gradeData['behavior_3'] ?? 0);
                $data['awareness_1'] = floatval($gradeData['awareness_1'] ?? 0);
                $data['awareness_2'] = floatval($gradeData['awareness_2'] ?? 0);
                $data['awareness_3'] = floatval($gradeData['awareness_3'] ?? 0);

                // Update or create the entry
                $entry = GradeEntry::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    $data
                );

                // Compute averages and save
                $weights = [
                    'knowledge' => 40,
                    'skills' => 50,
                    'attitude' => 10,
                ];
                $computedValues = $entry->computeAverages($weights);
                $entry->update($computedValues);

                $saved++;
            } catch (\Exception $e) {
                Log::error('Grade entry error for student '.$studentId.': '.$e->getMessage());
                $errors[] = "Error saving grades for student ID {$studentId}";

                continue;
            }
        }

        if ($saved > 0) {
            return redirect()->route('teacher.grades')->with('success', "✅ Saved {$saved} grade records for ".ucfirst($term).' term');
        } else {
            return back()->with('error', 'No grades were saved. '.implode(', ', $errors));
        }
    }

    /**
     * Compute grades for a specific period (midterm or final)
     */
    private function computePeriodGrades(array &$data, string $period, array $weights)
    {
        // EXAM AVERAGES
        $examPr = $data["{$period}_exam_pr"] ?? 0;
        $examMd = $data["{$period}_exam_md"] ?? 0;
        $examAve = ($examPr + $examMd) / 2;

        // QUIZ AVERAGES
        $quizzes = [
            $data["{$period}_quiz_1"] ?? 0,
            $data["{$period}_quiz_2"] ?? 0,
            $data["{$period}_quiz_3"] ?? 0,
            $data["{$period}_quiz_4"] ?? 0,
            $data["{$period}_quiz_5"] ?? 0,
        ];
        $quizAve = array_sum($quizzes) / count($quizzes);

        // KNOWLEDGE = (Exam 60% + Quiz 40%)
        $knowledge = ($examAve * 0.60) + ($quizAve * 0.40);
        $data["{$period}_knowledge_average"] = $knowledge;

        // OUTPUT AVERAGE
        $outputs = [
            $data["{$period}_output_1"] ?? 0,
            $data["{$period}_output_2"] ?? 0,
            $data["{$period}_output_3"] ?? 0,
        ];
        $outputAve = array_sum($outputs) / count($outputs);

        // CLASS PARTICIPATION AVERAGE
        $classparts = [
            $data["{$period}_classpart_1"] ?? 0,
            $data["{$period}_classpart_2"] ?? 0,
            $data["{$period}_classpart_3"] ?? 0,
        ];
        $classpartAve = array_sum($classparts) / count($classparts);

        // ACTIVITIES AVERAGE
        $activities = [
            $data["{$period}_activity_1"] ?? 0,
            $data["{$period}_activity_2"] ?? 0,
            $data["{$period}_activity_3"] ?? 0,
        ];
        $activityAve = array_sum($activities) / count($activities);

        // ASSIGNMENTS AVERAGE
        $assignments = [
            $data["{$period}_assignment_1"] ?? 0,
            $data["{$period}_assignment_2"] ?? 0,
            $data["{$period}_assignment_3"] ?? 0,
        ];
        $assignmentAve = array_sum($assignments) / count($assignments);

        // SKILLS = (Output 40% + ClassPart 30% + Activities 15% + Assignments 15%)
        $skills = ($outputAve * 0.40) + ($classpartAve * 0.30) + ($activityAve * 0.15) + ($assignmentAve * 0.15);
        $data["{$period}_skills_average"] = $skills;

        // BEHAVIOR AVERAGE
        $behaviors = [
            $data["{$period}_behavior_1"] ?? 0,
            $data["{$period}_behavior_2"] ?? 0,
            $data["{$period}_behavior_3"] ?? 0,
        ];
        $behaviorAve = array_sum($behaviors) / count($behaviors);

        // AWARENESS AVERAGE
        $awareness_arr = [
            $data["{$period}_awareness_1"] ?? 0,
            $data["{$period}_awareness_2"] ?? 0,
            $data["{$period}_awareness_3"] ?? 0,
        ];
        $awarenessAve = array_sum($awareness_arr) / count($awareness_arr);

        // ATTITUDE = (Behavior 50% + Awareness 50%)
        $attitude = ($behaviorAve * 0.50) + ($awarenessAve * 0.50);
        $data["{$period}_attitude_average"] = $attitude;

        // PERIOD FINAL GRADE = (Knowledge % + Skills % + Attitude %)
        $k = $weights['knowledge'] / 100;
        $s = $weights['skills'] / 100;
        $a = $weights['attitude'] / 100;
        $periodGrade = ($knowledge * $k) + ($skills * $s) + ($attitude * $a);
        $data["{$period}_final_grade"] = $periodGrade;
    }

    /**
     * Get 5-point grade scale
     */
    private function getGrade5ptScale(float $grade): string
    {
        if ($grade >= 90) {
            return '5.0';
        }
        if ($grade >= 80) {
            return '4.0';
        }
        if ($grade >= 70) {
            return '3.0';
        }
        if ($grade >= 60) {
            return '2.0';
        }
        if ($grade >= 50) {
            return '1.0';
        }

        return '0.0';
    }

    /**
     * Get grade remarks
     */
    private function getGradeRemark(float $grade): string
    {
        if ($grade >= 90) {
            return 'Excellent';
        }
        if ($grade >= 80) {
            return 'Very Good';
        }
        if ($grade >= 70) {
            return 'Good';
        }
        if ($grade >= 60) {
            return 'Fair';
        }
        if ($grade >= 50) {
            return 'Poor';
        }

        return 'Fail';
    }

    /**
     * Recalculate scores using a provided scheme weights array
     */
    private function recalculateGradeScoresWithScheme($grade, array $weights)
    {
        // Knowledge Score (default internal: exams 60%, quizzes 40%)
        $quizzes = array_filter([$grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5]);
        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $exams = array_filter([$grade->exam_prelim ?? null, $grade->midterm_exam ?? null, $grade->final_exam ?? null]);
        $examAvg = count($exams) > 0 ? array_sum($exams) / count($exams) : 0;
        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills (internal component weights kept as standard)
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : ($grade->output_1 ?? 0);

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : ($grade->class_participation_1 ?? 0);

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : ($grade->activities_1 ?? 0);

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : ($grade->assignments_1 ?? 0);

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : ($grade->behavior_1 ?? 0);
        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : ($grade->awareness_1 ?? 0);
        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final grade using provided KSA weights (percent values expected)
        $k = ($weights['knowledge'] ?? 40) / 100;
        $s = ($weights['skills'] ?? 50) / 100;
        $a = ($weights['attitude'] ?? 10) / 100;

        $grade->final_grade = (
            ($grade->knowledge_score * $k) +
            ($grade->skills_score * $s) +
            ($grade->attitude_score * $a)
        );

        $grade->save();
    }

    /**
     * Store CHED grades with improved validation and error handling
     * Handles both unified and legacy form formats
     */
    public function storeGradesChed(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        $grades = $request->input('grades', []);
        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return redirect()->back()->with('error', 'Invalid term selected');
        }

        $successCount = 0;
        $errors = [];

        foreach ($grades as $index => $gradeData) {
            try {
                // Handle both numeric keys (from form submission) and student_id keys
                $studentId = $gradeData['student_id'] ?? $index;

                $student = Student::findOrFail($studentId);

                // Prepare update data
                $updateData = [
                    'subject_id' => $class->course_id,
                    'teacher_id' => $teacherId,
                ];

                // Knowledge component - always at base level
                $quizzes = [
                    floatval($gradeData['q1'] ?? 0),
                    floatval($gradeData['q2'] ?? 0),
                    floatval($gradeData['q3'] ?? 0),
                    floatval($gradeData['q4'] ?? 0),
                    floatval($gradeData['q5'] ?? 0),
                ];

                $exams = [
                    'midterm' => floatval($gradeData['midterm_exam'] ?? 0),
                    'final' => floatval($gradeData['final_exam'] ?? 0),
                ];

                // Store quiz and exam scores
                $updateData['q1'] = $quizzes[0];
                $updateData['q2'] = $quizzes[1];
                $updateData['q3'] = $quizzes[2];
                $updateData['q4'] = $quizzes[3];
                $updateData['q5'] = $quizzes[4];
                $updateData['midterm_exam'] = $exams['midterm'];
                $updateData['final_exam'] = $exams['final'];

                // Skills entries - handle both formats (with and without term suffix)
                $skillFields = [
                    'output_1', 'output_2', 'output_3',
                    'class_participation_1', 'class_participation_2', 'class_participation_3',
                    'activities_1', 'activities_2', 'activities_3',
                    'assignments_1', 'assignments_2', 'assignments_3',
                ];

                foreach ($skillFields as $field) {
                    // Try without suffix first (unified form), then with suffix (legacy form)
                    $value = $gradeData[$field] ?? $gradeData[$field.'_'.$term] ?? null;
                    if ($value !== null) {
                        $updateData[$field] = floatval($value);
                    }
                }

                // Attitude entries - handle both formats
                $attitudeFields = [
                    'behavior_1', 'behavior_2', 'behavior_3',
                    'awareness_1', 'awareness_2', 'awareness_3',
                ];

                foreach ($attitudeFields as $field) {
                    // Try without suffix first (unified form), then with suffix (legacy form)
                    $value = $gradeData[$field] ?? $gradeData[$field.'_'.$term] ?? null;
                    if ($value !== null) {
                        $updateData[$field] = floatval($value);
                    }
                }

                // Create or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    $updateData
                );

                // Calculate component scores
                $this->recalculateGradeScores($grade);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Student {$index}: ".$e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "✓ {$successCount} grades saved successfully for {$term} term!";
            if (! empty($errors)) {
                $message .= ' ('.count($errors).' errors encountered)';
            }

            return redirect()->route('teacher.grades')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No grades were saved. Please check for errors.');
        }
    }

    /**
     * Recalculate all grade scores and update the database
     */
    private function recalculateGradeScores($grade)
    {
        // Knowledge Score (40%)
        $quizzes = array_filter([
            $grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5,
            $grade->q6, $grade->q7, $grade->q8, $grade->q9, $grade->q10,
        ]);

        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $examAvg = (($grade->midterm_exam ?? 0) + ($grade->final_exam ?? 0)) / 2;

        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills Score (50%) - Calculate from individual entries
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : 0;
        $grade->output_score = $outputAvg;

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : 0;
        $grade->class_participation_score = $cpAvg;

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : 0;
        $grade->activities_score = $actAvg;

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : 0;
        $grade->assignments_score = $asgAvg;

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude Score (10%) - Calculate from individual entries
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : 0;
        $grade->behavior_score = $behaviorAvg;

        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : 0;
        $grade->awareness_score = $awarenessAvg;

        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final Grade (K=40%, S=50%, A=10%)
        $grade->final_grade = (
            ($grade->knowledge_score * 0.40) +
            ($grade->skills_score * 0.50) +
            ($grade->attitude_score * 0.10)
        );

        $grade->save();
    }

    /**
     * Import students from Excel file
     */
    public function importStudents(Request $request)
    {
        $teacherId = Auth::id();

        // Verify class belongs to teacher
        $class = ClassModel::where('teacher_id', $teacherId)
            ->findOrFail($request->class_id);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('excel_file');
            $path = $file->getRealPath();
            $imported = 0;
            $errors = [];

            if (($handle = fopen($path, 'r')) !== false) {
                $header = fgetcsv($handle);
                $lineNo = 1;
                $enrollmentYear = date('Y');

                while (($row = fgetcsv($handle)) !== false) {
                    $lineNo++;
                    try {
                        $data = array_combine($header, $row);

                        if (empty($data['Email'])) {
                            continue;
                        }

                        // Validate required fields
                        $name = $data['Name'] ?? null;
                        $email = $data['Email'] ?? null;
                        $year = $data['Year'] ?? null;
                        $section = $data['Section'] ?? null;

                        if (! $name || ! $email || ! $year || ! $section) {
                            $errors[] = "Row {$lineNo}: Missing required fields (Name, Email, Year, Section)";

                            continue;
                        }

                        // Validate year
                        if (! in_array($year, [1, 2, 3, 4])) {
                            $errors[] = "Row {$lineNo}: Invalid year '{$year}'. Must be 1, 2, 3, or 4.";

                            continue;
                        }

                        // Validate section
                        if (! in_array($section, ['A', 'B', 'C', 'D', 'E'])) {
                            $errors[] = "Row {$lineNo}: Invalid section '{$section}'. Must be A, B, C, D, or E.";

                            continue;
                        }

                        // Check if user already exists
                        $existingUser = User::where('email', $email)->first();
                        if ($existingUser) {
                            $errors[] = "Row {$lineNo}: User with email '{$email}' already exists.";

                            continue;
                        }

                        // Create user
                        $user = User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => bcrypt('password'),
                            'role' => 'student',
                        ]);

                        // Generate student ID
                        $nextStudentNumber = Student::count() + 1;
                        $sequentialNumber = str_pad($nextStudentNumber, 4, '0', STR_PAD_LEFT);
                        $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $section);

                        // Create student
                        Student::create([
                            'user_id' => $user->id,
                            'class_id' => $class->id,
                            'student_id' => $studentId,
                            'year' => $year,
                            'section' => $section,
                            'status' => 'Active',
                        ]);

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Row {$lineNo}: ".$e->getMessage();
                    }
                }

                fclose($handle);
            }

            if ($imported > 0) {
                $message = "✓ {$imported} students imported successfully!";
                if (! empty($errors)) {
                    $message .= ' ('.count($errors).' rows had errors)';
                }

                return redirect()->back()->with('success', $message);
            } else {
                $errorMsg = ! empty($errors) ? implode(' | ', array_slice($errors, 0, 3)) : 'No valid records found';

                return redirect()->back()->with('error', "Import failed: {$errorMsg}");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Excel import error: '.$e->getMessage());
        }
    }

    /**
     * Show assessment range configuration page for a class
     */
    public function configureAssessmentRanges($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('course')
            ->firstOrFail();

        $course = $class->course;

        // Get or create assessment range
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $range) {
            $range = AssessmentRange::create([
                'class_id' => $classId,
                'subject_id' => $course ? $course->id : null,
                'teacher_id' => $teacherId,
                // Default quiz values (5 quizzes at 25 points each)
                'quiz_1_max' => 25,
                'quiz_2_max' => 25,
                'quiz_3_max' => 25,
                'quiz_4_max' => 25,
                'quiz_5_max' => 25,
                // Default exam values (70 for midterm, 80 for final as requested)
                'midterm_exam_max' => 70,
                'final_exam_max' => 80,
                'prelim_exam_max' => 70,
                // Default skills values (midterm)
                'class_participation_midterm' => 15,
                'activities_midterm' => 15,
                'assignments_midterm' => 15,
                'output_midterm' => 30,
                // Default skills values (final)
                'class_participation_final' => 15,
                'activities_final' => 15,
                'assignments_final' => 15,
                'output_final' => 30,
                // Default attitude values (midterm)
                'behavior_midterm' => 5,
                'awareness_midterm' => 5,
                // Default attitude values (final)
                'behavior_final' => 5,
                'awareness_final' => 5,
                // Other defaults
                'attendance_required' => true,
                'attendance_min_percentage' => 80,
            ]);
        }

        return view('teacher.assessment.configure_enhanced', compact('class', 'range'));
    }

    /**
     * Store assessment range configuration
     */
    public function storeAssessmentRanges(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            // Knowledge - Quizzes (5 separate quizzes, each max 50 points)
            'quiz_1_max' => 'required|integer|min:5|max:100',
            'quiz_2_max' => 'required|integer|min:5|max:100',
            'quiz_3_max' => 'required|integer|min:5|max:100',
            'quiz_4_max' => 'required|integer|min:5|max:100',
            'quiz_5_max' => 'required|integer|min:5|max:100',

            // Knowledge - Exams (Midterm & Final only)
            'midterm_exam_max' => 'required|integer|min:20|max:200',
            'final_exam_max' => 'required|integer|min:20|max:200',

            // Skills - Class Participation
            'class_participation_midterm' => 'required|integer|min:0|max:100',
            'class_participation_final' => 'required|integer|min:0|max:100',

            // Skills - Activities
            'activities_midterm' => 'required|integer|min:0|max:100',
            'activities_final' => 'required|integer|min:0|max:100',

            // Skills - Assignments
            'assignments_midterm' => 'required|integer|min:0|max:100',
            'assignments_final' => 'required|integer|min:0|max:100',

            // Skills - Output/Project
            'output_midterm' => 'required|integer|min:0|max:100',
            'output_final' => 'required|integer|min:0|max:100',

            // Attitude - Behavior
            'behavior_midterm' => 'required|integer|min:0|max:100',
            'behavior_final' => 'required|integer|min:0|max:100',

            // Attitude - Awareness
            'awareness_midterm' => 'required|integer|min:0|max:100',
            'awareness_final' => 'required|integer|min:0|max:100',

            // Attendance
            'attendance_required' => 'boolean',
            'attendance_min_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['teacher_id'] = $teacherId;
        $validated['class_id'] = $classId;
        $validated['subject_id'] = $class->course_id ?? null;

        AssessmentRange::updateOrCreate(
            [
                'class_id' => $classId,
                'teacher_id' => $teacherId,
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Assessment ranges configured successfully!');
    }

    /**
     * Show enhanced CHED grade entry form with configurable ranges
     */
    public function showGradeEntryEnhanced($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store enhanced CHED grades with attendance and assignments
     */
    public function storeGradesEnhanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return redirect()->back()->with('error', 'Invalid term selected');
        }

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.q1' => 'nullable|numeric|min:0',
            'grades.*.q2' => 'nullable|numeric|min:0',
            'grades.*.q3' => 'nullable|numeric|min:0',
            'grades.*.q4' => 'nullable|numeric|min:0',
            'grades.*.q5' => 'nullable|numeric|min:0',
            'grades.*.midterm_exam' => 'nullable|numeric|min:0',
            'grades.*.final_exam' => 'nullable|numeric|min:0',
            'grades.*.output_score' => 'nullable|numeric|min:0',
            'grades.*.class_participation_score' => 'nullable|numeric|min:0',
            'grades.*.activities_score' => 'nullable|numeric|min:0',
            'grades.*.assignments_score' => 'nullable|numeric|min:0',
            'grades.*.behavior_score' => 'nullable|numeric|min:0',
            'grades.*.awareness_score' => 'nullable|numeric|min:0',
            'grades.*.attendance_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        $gradesCreated = 0;
        $gradesUpdated = 0;
        $attendanceUpdated = 0;
        $errors = [];
        foreach ($validated['grades'] as $gradeData) {
            try {
                $studentId = $gradeData['student_id'];

                // Prepare quiz data - support 1-10 quizzes dynamically
                $quizzes = [];
                for ($q = 1; $q <= 10; $q++) {
                    $quizValue = floatval($gradeData["q$q"] ?? 0);
                    if ($quizValue > 0) {
                        $quizzes[] = $quizValue;
                    }
                }

                // If no quizzes provided, use empty array (will be handled by calculation)
                if (empty($quizzes)) {
                    $quizzes = array_fill(0, max(1, $range->num_quizzes ?? 5), 0);
                }

                // Prepare exam data
                $exams = [
                    'midterm' => floatval($gradeData['midterm_exam'] ?? 0),
                    'final' => floatval($gradeData['final_exam'] ?? 0),
                ];

                // Calculate scores using configurable ranges
                $knowledgeScore = Grade::calculateKnowledge($quizzes, $exams, $range, $term);

                $skillsScore = Grade::calculateSkills(
                    floatval($gradeData['output_score'] ?? 0),
                    floatval($gradeData['class_participation_score'] ?? 0),
                    floatval($gradeData['activities_score'] ?? 0),
                    floatval($gradeData['assignments_score'] ?? 0),
                    $range
                );

                $attitudeScore = Grade::calculateAttitude(
                    floatval($gradeData['behavior_score'] ?? 0),
                    floatval($gradeData['awareness_score'] ?? 0),
                    $range
                );

                $finalGrade = Grade::calculateFinalGrade($knowledgeScore, $skillsScore, $attitudeScore);
                $gradePoint = Grade::getGradePoint($finalGrade);

                // Store or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'subject_id' => $class->course_id,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    [
                        'q1' => $quizzes[0],
                        'q2' => $quizzes[1],
                        'q3' => $quizzes[2],
                        'q4' => $quizzes[3],
                        'q5' => $quizzes[4],
                        'midterm_exam' => $exams['midterm'],
                        'final_exam' => $exams['final'],
                        'knowledge_score' => $knowledgeScore,
                        'output_score' => floatval($gradeData['output_score'] ?? 0),
                        'class_participation_score' => floatval($gradeData['class_participation_score'] ?? 0),
                        'activities_score' => floatval($gradeData['activities_score'] ?? 0),
                        'assignments_score' => floatval($gradeData['assignments_score'] ?? 0),
                        'skills_score' => $skillsScore,
                        'behavior_score' => floatval($gradeData['behavior_score'] ?? 0),
                        'awareness_score' => floatval($gradeData['awareness_score'] ?? 0),
                        'attitude_score' => $attitudeScore,
                        'final_grade' => $finalGrade,
                        'grade_point' => $gradePoint,
                        'remarks' => $gradeData['remarks'] ?? null,
                        'grading_period' => $term,
                    ]
                );

                if ($grade->wasRecentlyCreated) {
                    $gradesCreated++;
                } else {
                    $gradesUpdated++;
                }

                // Update attendance if provided
                if (isset($gradeData['attendance_score']) && $gradeData['attendance_score'] !== null) {
                    StudentAttendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'class_id' => $classId,
                            'subject_id' => $class->course_id,
                            'term' => $term,
                        ],
                        [
                            'teacher_id' => $teacherId,
                            'attendance_score' => floatval($gradeData['attendance_score']),
                        ]
                    );
                    $attendanceUpdated++;
                }
            } catch (\Exception $e) {
                $errors[] = "Student {$studentId}: ".$e->getMessage();
            }
        }

        $message = "✓ Grades saved! Created: {$gradesCreated}, Updated: {$gradesUpdated}, Attendance: {$attendanceUpdated}";

        if (! empty($errors)) {
            $message .= ' ('.count($errors).' errors)';
        }

        return redirect()->route('teacher.grades')->with('success', $message);
    }

    /**
     * Show inline grade entry form (NEW ENHANCED GRADING)
     */
    public function showGradeEntryInline($classId)
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store grades via inline entry (NEW ENHANCED GRADING)
     */
    public function storeGradesInline(Request $request, $classId)
    {
        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.component' => 'required|string',
            'grades.*.score' => 'required|numeric|min:0',
        ]);

        foreach ($validated['grades'] as $gradeData) {
            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'class_id' => $classId,
                    'component' => $gradeData['component'],
                ],
                [
                    'teacher_id' => $teacherId,
                    'score' => $gradeData['score'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Grades saved successfully',
        ]);
    }

    /**
     * Show grade analytics dashboard (NEW ENHANCED GRADING)
     */
    public function showGradeAnalytics($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user', 'course')
            ->firstOrFail();

        $grades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('student')
            ->whereNotNull('final_grade')
            ->get();

        $totalStudents = $this->getStudentsForClass($class)->count();
        $gradesWithFinal = $grades->where('final_grade', '!=', null);

        // Calculate comprehensive analytics
        $analytics = [];

        if ($gradesWithFinal->count() > 0) {
            $finalGrades = $gradesWithFinal->pluck('final_grade');
            $analytics = [
                'total_students' => $totalStudents,
                'graded_count' => $gradesWithFinal->count(),
                'ungraded_count' => $totalStudents - $gradesWithFinal->count(),
                'avg_grade' => round($finalGrades->avg(), 2),
                'highest_grade' => round($finalGrades->max(), 2),
                'lowest_grade' => round($finalGrades->min(), 2),
                'median_grade' => round($finalGrades->median(), 2),
                'std_dev' => $this->calculateStandardDeviation($finalGrades->toArray()),
                'passed_count' => $gradesWithFinal->where('final_grade', '>=', 60)->count(),
                'failed_count' => $gradesWithFinal->where('final_grade', '<', 60)->count(),
                'pass_percentage' => round(($gradesWithFinal->where('final_grade', '>=', 60)->count() / max(1, $gradesWithFinal->count())) * 100, 1),
                'fail_percentage' => round(($gradesWithFinal->where('final_grade', '<', 60)->count() / max(1, $gradesWithFinal->count())) * 100, 1),
                'grade_a_count' => $gradesWithFinal->where('final_grade', '>=', 90)->count(),
                'grade_b_count' => $gradesWithFinal->whereBetween('final_grade', [80, 89.99])->count(),
                'grade_c_count' => $gradesWithFinal->whereBetween('final_grade', [70, 79.99])->count(),
                'grade_d_count' => $gradesWithFinal->whereBetween('final_grade', [60, 69.99])->count(),
                'grade_f_count' => $gradesWithFinal->where('final_grade', '<', 60)->count(),
                'knowledge_avg' => round($gradesWithFinal->avg('knowledge_score'), 2),
                'skills_avg' => round($gradesWithFinal->avg('skills_score'), 2),
                'attitude_avg' => round($gradesWithFinal->avg('attitude_score'), 2),
                'quiz_avg' => round($gradesWithFinal->avg(function ($g) {
                    $quizzes = array_filter([$g->q1, $g->q2, $g->q3, $g->q4, $g->q5]);

                    return ! empty($quizzes) ? array_sum($quizzes) / count($quizzes) : 0;
                }), 2),
                'exam_avg' => round($gradesWithFinal->avg(function ($g) {
                    $exams = array_filter([$g->midterm_exam, $g->final_exam]);

                    return ! empty($exams) ? array_sum($exams) / count($exams) : 0;
                }), 2),
            ];
        } else {
            $analytics = [
                'total_students' => $totalStudents,
                'graded_count' => 0,
                'ungraded_count' => $totalStudents,
                'avg_grade' => 0,
                'highest_grade' => 0,
                'lowest_grade' => 0,
                'median_grade' => 0,
                'std_dev' => 0,
                'passed_count' => 0,
                'failed_count' => 0,
                'pass_percentage' => 0,
                'fail_percentage' => 0,
                'grade_a_count' => 0,
                'grade_b_count' => 0,
                'grade_c_count' => 0,
                'grade_d_count' => 0,
                'grade_f_count' => 0,
                'knowledge_avg' => 0,
                'skills_avg' => 0,
                'attitude_avg' => 0,
                'quiz_avg' => 0,
                'exam_avg' => 0,
            ];
        }

        return view('teacher.grades.analytics_dashboard', compact('class', 'grades', 'analytics', 'totalStudents'));
    }

    /**
     * Calculate standard deviation for an array of numbers
     */
    private function calculateStandardDeviation($values)
    {
        if (empty($values)) {
            return 0;
        }

        $mean = array_sum($values) / count($values);
        $deviationSquared = array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values);
        $variance = array_sum($deviationSquared) / count($values);

        return round(sqrt($variance), 2);
    }

    /**
     * Get students for a class — uses course_id so non-primary classes show all course students.
     */
    private function getStudentsForClass(ClassModel $class)
    {
        if ($class->course_id) {
            return Student::where('course_id', $class->course_id)
                ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                ->orderBy('last_name')->orderBy('first_name')
                ->get();
        }
        return $class->students()
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name')
            ->get();
    }

    /**
     * Show create class form
     */
    public function createClass()
    {
        $teacherId = Auth::id();
        $user = Auth::user();
        $teacherCampus = $user->campus;
        $teacherSchoolId = $user->school_id;

        // Get all subjects assigned to this teacher (active assignments)
        // and independent subjects created by this teacher.
        $assignedSubjects = Subject::with('course')
            ->where(function ($query) use ($teacherId) {
                $query->whereHas('teachers', function ($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId)
                      ->where('teacher_subject.status', 'active');
                })
                ->orWhere(function ($q) use ($teacherId) {
                    $q->whereNull('program_id')
                      ->whereHas('teachers', function ($subQ) use ($teacherId) {
                          $subQ->where('teacher_id', $teacherId);
                      });
                });
            })
            ->when($teacherCampus, fn($q) => $q->where(function ($q2) use ($teacherCampus, $teacherSchoolId) {
                $q2->where('campus', $teacherCampus)
                   ->orWhereNull('campus');
                if ($teacherSchoolId) {
                    $q2->orWhere('school_id', $teacherSchoolId);
                }
            }))
            ->orderBy('subject_name')
            ->get();

        // Get courses based on teacher's campus
        $courses = Course::query()
            ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
            ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
            ->orderBy('program_name')
            ->get();
        
        // Get students from teacher's campus for assignment
        $students = Student::with(['course'])
            ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
            ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get departments
        $departments = $courses->pluck('department')->filter()->unique()->values()->toArray();

        return view('teacher.classes.create', compact(
            'assignedSubjects', 
            'courses', 
            'students',
            'departments'
        ));
    }

    /**
     * Store a new class created by teacher
     */
    public function storeClass(Request $request)
    {
        // Normalize the special keys so manual create works with dropdown values
        $subjectIdInput = $request->input('subject_id');
        $courseIdInput = $request->input('course_id');

        $subjectId = $subjectIdInput === 'new-subject' ? null : $subjectIdInput;
        $courseId = $courseIdInput === 'new-course' ? null : $courseIdInput;

        if ($subjectIdInput === 'new-subject') {
            $request->merge(['subject_id' => null]);
        }

        if ($courseIdInput === 'new-course') {
            $request->merge(['course_id' => null]);
        }

        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'course_id' => 'nullable|exists:courses,id', // Made nullable for independent subjects
            'subject_id' => 'nullable|exists:subjects,id',
            'new_subject_name' => 'nullable|string|max:255',
            'new_subject_code' => 'nullable|string|max:50|unique:subjects,subject_code',
            'new_course_name' => 'nullable|string|max:255',
            'new_course_code' => 'nullable|string|max:50|unique:courses,program_code',
            'section' => 'required|string|in:A,B,C,D,E',
            'year_level' => 'required|integer|in:1,2,3,4',
            'academic_year' => 'required|string',
            'description' => 'nullable|string|max:500',
            'semester' => 'required|string|in:First,Second,Summer',
            'auto_assign' => 'boolean',
            'create_assignment' => 'boolean',
            'additional_teachers' => 'nullable|array',
            'additional_teachers.*' => 'exists:users,id',
        ]);

        if (!$subjectId && !$request->filled('new_subject_name')) {
            return back()->withInput()->withErrors(['subject_id' => 'Please select an existing subject or enter a new one.']);
        }

        if ($subjectId) {
            $selectedSubject = Subject::find($subjectId);
            if ($selectedSubject) {
                $assigned = $selectedSubject->teachers()
                    ->where('teacher_id', Auth::id())
                    ->where('teacher_subject.status', 'active')
                    ->exists();

                if (! $assigned) {
                    return back()->withInput()->withErrors(['subject_id' => 'This subject is not assigned to you. Ask admin for assignment.']);
                }
            }
        }

        if (!$courseId && !$request->filled('new_course_name') && !$request->filled('new_subject_name')) {
            return back()->withInput()->withErrors(['course_id' => 'Please select an existing course or create a new one.']);
        }

        // If teacher chose to create a new course, do it now
        if (!$courseId && $request->filled('new_course_name')) {
            $newCourse = Course::firstOrCreate(
                ['program_code' => $request->input('new_course_code')],
                [
                    'program_name' => $request->input('new_course_name'),
                    'description' => $request->input('description') ?? 'Course created by teacher',
                    'total_years' => 4,
                    'status' => 'Active',
                ]
            );
            $courseId = $newCourse->id;
        }

        // If teacher chose to create a new subject, do it now and assign to teacher
        if (!$subjectId && $request->filled('new_subject_name')) {
            $subject = Subject::create([
                'subject_name' => $request->input('new_subject_name'),
                'subject_code' => $request->input('new_subject_code'),
                'credit_hours' => (int)($request->input('credit_hours', 3)),
                'category' => $request->input('category', 'General'),
                'description' => $request->input('description'),
                'year_level' => (int)$request->input('year_level'),
                'semester' => $request->input('semester'),
                'program_id' => $courseId,
            ]);

            $subject->teachers()->syncWithoutDetaching([
                Auth::id() => [
                    'status' => 'active',
                    'assigned_at' => now(),
                ],
            ]);

            // Add admin-approval flag notifier (optional): subject created by teacher
            // may still require administrative review in workflow.
            // This is a business rule pointer; admin should manually approve schema/assignment.

            $subjectId = $subject->id;
        }

        // Get the subject now after new creation or selection
        $subject = Subject::find($subjectId);
        $units = $subject ? $subject->credit_hours : 3;

        // If no course_id provided, try to get it from the subject's program_id
        $courseId = $courseId ?? $subject?->program_id ?? null;
        
        // If still no course, create a default "Independent" course or use existing one
        if (!$courseId) {
            $independentCourse = Course::firstOrCreate(
                ['program_code' => 'IND'],
                [
                    'program_name' => 'Independent Studies',
                    'program_code' => 'IND',
                    'description' => 'Independent teacher-created courses',
                    'total_years' => 4,
                    'status' => 'Active'
                ]
            );
            $courseId = $independentCourse->id;
        }

        // Convert year integer to enum format for year column
        $yearMapping = [
            1 => '1st',
            2 => '2nd', 
            3 => '3rd',
            4 => '4th'
        ];
        
        // Create the class
        $class = ClassModel::create([
            'class_name'    => $validated['class_name'],
            'course_id'     => $courseId,
            'subject_id'    => $subjectId,
            'teacher_id'    => Auth::id(),
            'section'       => $validated['section'],
            'year'          => (int) $validated['year_level'],
            'class_level'   => (int) $validated['year_level'], // mirrors year
            'academic_year' => $validated['academic_year'] ?? null,
            'total_students'=> 1,
            'status'        => 'Active',
            'description'   => $validated['description'] ?? null,
            'units'         => $units,
            'campus'        => Auth::user()->campus,
            'school_id'     => Auth::user()->school_id,
        ]);

        // Create teacher assignments if requested
        $teacherId = Auth::id();
        $course = Course::find($courseId);

        $assignmentIds = [];

        // Auto-assign the creating teacher to the class
        if ($request->boolean('auto_assign')) {
            $assignment = TeacherAssignment::create([
                'teacher_id' => $teacherId,
                'class_id' => $class->id,
                'subject_id' => $validated['subject_id'],
                'course_id' => $courseId,
                'department' => $course->department ?? 'Independent',
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
                'status' => 'active',
                'notes' => 'Auto-assigned when creating class',
            ]);

            $assignmentIds[] = $assignment->id;
        }

        // Create additional assignments if requested
        if ($request->boolean('create_assignment') && $request->filled('additional_teachers')) {
            foreach ($request->additional_teachers as $additionalTeacherId) {
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $additionalTeacherId,
                    'class_id' => $class->id,
                    'subject_id' => $validated['subject_id'],
                    'course_id' => $courseId,
                    'department' => $course->department ?? 'Independent',
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                    'status' => 'active',
                    'notes' => 'Assigned by '.Auth::user()->name,
                ]);

                $assignmentIds[] = $assignment->id;
            }
        }

        // Assign students to class if provided
        if ($request->has('assigned_students')) {
            $submittedStudentIds = array_filter(array_map('trim', explode(',', $request->input('assigned_students', ''))));
            $validStudentIds = Student::whereIn('id', $submittedStudentIds)->pluck('id')->toArray();

            // Ensure there's at least one assignment for student assignment tracking
            if (empty($assignmentIds)) {
                $assignment = TeacherAssignment::firstOrCreate([
                    'teacher_id' => $teacherId,
                    'class_id' => $class->id,
                ], [
                    'subject_id' => $validated['subject_id'],
                    'course_id' => $validated['course_id'],
                    'department' => $course->department ?? null,
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                    'status' => 'active',
                    'notes' => 'Auto-assigned when creating class',
                ]);
                $assignmentIds[] = $assignment->id;
            }

            foreach ($assignmentIds as $assignmentId) {
                $assignment = TeacherAssignment::find($assignmentId);
                if ($assignment) {
                    $assignment->assignStudents($validStudentIds);
                }
            }

            // Update students' class assignment and related school/department information
            if (! empty($validStudentIds)) {
                $studentUpdate = ['class_id' => $class->id];
                if (! empty($course->college)) {
                    $studentUpdate['school'] = $course->college;
                }
                if (! empty($course->department)) {
                    $studentUpdate['department'] = $course->department;
                }

                Student::whereIn('id', $validStudentIds)->update($studentUpdate);
            }
        }

        // Ensure total_students matches the total number of students enrolled in the class
        $class->refresh();
        $class->total_students = max(1, $class->students()
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->count());
        $class->save();

        $message = "Class '{$class->class_name}' created successfully!";
        if ($request->boolean('auto_assign') || $request->boolean('create_assignment')) {
            $message .= ' Teacher assignments have been created.';
        }
        if ($request->filled('assigned_students')) {
            $studentCount = count($validStudentIds ?? []);
            $message .= " {$studentCount} students have been assigned to the class.";
        }

        return redirect()->route('teacher.classes.show', $class->id)
            ->with('success', $message);
    }

    /**
     * Show form to edit a class
     */
    public function editClass($classId)
    {
        $user = Auth::user();
        $teacherId = $user->id;
        $teacherCampus = $user->campus;
        $teacherSchoolId = $user->school_id;

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with(['course', 'subject', 'students'])
            ->firstOrFail();

        $courses = Course::query()
            ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
            ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
            ->orderBy('program_name')
            ->get();

        $assignedSubjects = Subject::whereHas('teachers', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                  ->where('teacher_subject.status', 'active');
            })
            ->when($teacherCampus, fn($q) => $q->where(function ($q2) use ($teacherCampus, $teacherSchoolId) {
                $q2->where('campus', $teacherCampus)->orWhereNull('campus');
                if ($teacherSchoolId) $q2->orWhere('school_id', $teacherSchoolId);
            }))
            ->orderBy('subject_name')
            ->get();

        return view('teacher.classes.edit', compact('class', 'courses', 'assignedSubjects'));
    }

    /**
     * Update class information
     */
    public function updateClass(Request $request, $classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'section' => 'required|string|in:A,B,C,D,E',
            'year_level' => 'required|integer|in:1,2,3,4',
            'academic_year' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        $class->update([
            'class_name' => $validated['class_name'],
            'course_id' => $validated['course_id'],
            'section' => $validated['section'],
            'year' => (int) $validated['year_level'], // actual DB column is 'year'
            'academic_year' => $validated['academic_year'],
            'description' => $validated['description'] ?? null,
        ]);

        // Sync class student assignments if provided (supports adding/removing students)
        if ($request->has('assigned_students')) {
            $submittedStudentIds = array_filter(array_map('trim', explode(',', $request->input('assigned_students', ''))));
            $validStudentIds = Student::whereIn('id', $submittedStudentIds)->pluck('id')->toArray();

            $currentStudentIds = $class->students()->pluck('id')->toArray();
            $toAdd = array_diff($validStudentIds, $currentStudentIds);
            $toRemove = array_diff($currentStudentIds, $validStudentIds);

            // Ensure there is a teacher assignment record for this class
            $assignment = TeacherAssignment::firstOrCreate(
                [
                    'teacher_id' => Auth::id(),
                    'class_id' => $class->id,
                ],
                [
                    'subject_id' => 1, // Default subject
                    'status' => 'active',
                    'notes' => 'Auto-created for student assignment',
                ]
            );

            // Sync the pivot table for assignment_students
            $assignment->syncStudents($validStudentIds);

            // Update students' class assignment and school/department when added
            if (! empty($toAdd)) {
                $studentUpdate = ['class_id' => $class->id];
                if (! empty($class->course->college)) {
                    $studentUpdate['school'] = $class->course->college;
                }
                if (! empty($class->course->department)) {
                    $studentUpdate['department'] = $class->course->department;
                }
                Student::whereIn('id', $toAdd)->update($studentUpdate);
            }
            if (! empty($toRemove)) {
                Student::whereIn('id', $toRemove)->update(['class_id' => null]);
            }
        }

        // Ensure total_students stays consistent with the total number of students in the class
        $class->refresh();
        $class->total_students = max(1, $class->students()
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->count());
        $class->save();

        $message = "Class '{$class->class_name}' updated successfully!";
        if ($request->filled('assigned_students')) {
            $studentCount = count($validStudentIds ?? []);
            $message .= " {$studentCount} students have been assigned to the class.";
        }

        return redirect()->route('teacher.classes.show', $class->id)
            ->with('success', $message);
    }

    /**
     * Ajax helper: Get filtered students for assignment modals
     */
    public function getStudents(Request $request)
    {
        $teacher = Auth::user();
        $teacherCampus = $teacher->campus;
        $teacherSchoolId = $teacher->school_id;

        // Base query with campus isolation
        $query = Student::with(['course']);

        // Apply campus isolation to students
        if ($teacherCampus) {
            $query->where('campus', $teacherCampus);
        }
        if ($teacherSchoolId) {
            $query->where('school_id', $teacherSchoolId);
        }

        // Filter by course if provided
        if ($request->course_id && $request->course_id != 'new-course') {
            $query->where('course_id', $request->course_id);
        }

        // Filter by year if provided
        if ($request->year) {
            $query->where('year', $request->year);
        }

        // Filter by section if provided
        if ($request->section) {
            $query->where('section', $request->section);
        }

        // Search filter
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->get();

        $studentData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name, // Uses getNameAttribute from model
                'full_name' => $student->full_name, // Uses getFullNameAttribute from model
                'student_id' => $student->student_id,
                'email' => $student->email,
                'course_id' => $student->course_id,
                'program_name' => $student->course->program_name ?? 'Unknown',
                'department' => $student->course->department ?? 'Unknown',
                'year' => $student->year,
                'section' => $student->section,
                'class_id' => $student->class_id ?? null,
                'class_name' => $student->class->class_name ?? 'Unassigned',
                'campus' => $student->campus,
                'school_id' => $student->school_id,
            ];
        });

        return response()->json(['students' => $studentData]);
    }

    /**
     * Delete/destroy a class
     */
    public function destroyClass($classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $className = $class->class_name;

        // Delete all related grades first
        Grade::where('class_id', $classId)->delete();

        // Delete all related attendance records
        StudentAttendance::whereIn('student_id', $class->students->pluck('id'))->delete();

        // Delete the class
        $class->delete();

        return redirect()->route('teacher.classes')
            ->with('success', "Class '{$className}' has been deleted successfully!");
    }

    /**
     * Remove a student from a class (unassign without deleting)
     */
    public function removeStudentFromClass($classId, $studentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $student = Student::where('id', $studentId)
            ->where('class_id', $classId)
            ->firstOrFail();

        // Unassign student from class
        $student->class_id = null;
        $student->save();

        // Remove student from any teacher assignment for this class
        $assignments = TeacherAssignment::where('class_id', $classId)->get();
        foreach ($assignments as $assignment) {
            $assignment->students()->detach($studentId);
        }

        return response()->json(['success' => true]);
    }

    /**
     * List all students in a class
     */
    public function indexStudents($classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $students = $this->getStudentsForClass($class)
            ->sortBy(fn($s) => $s->last_name . ' ' . $s->first_name)->values();

        return view('teacher.students.index', compact('class', 'students'));
    }

    /**
     * Show form to edit a student
     */
    public function editStudent($studentId)
    {
        $student = Student::findOrFail($studentId);

        // Verify teacher owns the class this student belongs to
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teacher.students.edit', compact('student', 'class'));
    }

    /**
     * Update student information
     */
    public function updateStudent(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        // Verify teacher owns the class
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,'.$student->id,
            'year_level' => 'required|integer|in:1,2,3,4',
            'section' => 'required|string|in:A,B,C,D,E',
        ]);

        // Update student information directly
        $student->update([
            'first_name' => explode(' ', $validated['name'])[0],
            'last_name' => explode(' ', $validated['name'], 2)[1] ?? '',
            'email' => $validated['email'],
        ]);

        // Update student academic info
        $student->update([
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
        ]);

        return redirect()->route('teacher.students.index', $class->id)
            ->with('success', "Student '{$student->name}' updated successfully!");
    }

    /**
     * Delete/destroy a student
     */
    public function destroyStudent($studentId)
    {
        $student = Student::findOrFail($studentId);

        // Verify teacher owns the class
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $studentName = $student->name ?? 'Unknown';
        $classId = $class->id;

        // Delete all related grades
        Grade::where('student_id', $studentId)->delete();

        // Delete all related attendance records
        StudentAttendance::where('student_id', $studentId)->delete();

        // Delete the student
        $student->delete();

        return redirect()->route('teacher.students.index', $classId)
            ->with('success', "Student '$studentName' has been removed successfully!");
    }

    /**
     * AJAX: Save individual grade field
     */
    public function saveGradeField(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'class_id' => 'required|integer',
            'term' => 'required|in:midterm,final',
            'field' => 'required|string',
            'value' => 'nullable|numeric',
        ]);

        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get or create grade record
        $grade = Grade::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'term' => $request->term,
                'teacher_id' => $teacherId,
            ],
            [
                'subject_id' => $class->course_id,
            ]
        );

        // Update the field
        $grade->update([
            $request->field => $request->value,
        ]);

        // Calculate scores if this is a component input
        $this->calculateGradeScores($grade);

        return response()->json([
            'success' => true,
            'message' => 'Grade saved successfully',
            'final_grade' => $grade->final_grade,
        ]);
    }

    /**
     * AJAX: Save a single GradeEntry cell (auto-save on blur)
     */
    public function saveGradeEntryCell(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term' => 'required|in:midterm,final',
            'field' => 'required|string',
            'value' => 'nullable|numeric|min:0|max:100',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $allowedFields = [
            'exam_pr', 'exam_md', 'exam_fn',
            'quiz_1', 'quiz_2', 'quiz_3', 'quiz_4', 'quiz_5',
            'output_1', 'output_2', 'output_3',
            'classpart_1', 'classpart_2', 'classpart_3',
            'activity_1', 'activity_2', 'activity_3',
            'assignment_1', 'assignment_2', 'assignment_3',
            'behavior_1', 'behavior_2', 'behavior_3',
            'attendance_1', 'attendance_2', 'attendance_3',
            'awareness_1', 'awareness_2', 'awareness_3',
        ];

        if (! in_array($request->field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 422);
        }

        $entry = GradeEntry::updateOrCreate(
            ['student_id' => $request->student_id, 'class_id' => $classId, 'teacher_id' => $teacherId, 'term' => $request->term],
            [$request->field => $request->value ?? 0]
        );

        $computed = $entry->computeAverages(['knowledge' => 40, 'skills' => 50, 'attitude' => 10]);
        $entry->update($computed);

        return response()->json(['success' => true, 'computed' => $computed]);
    }

    /**
     * AJAX: Clear a single GradeEntry cell (set to 0)
     */
    public function clearGradeEntryCell(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term' => 'required|in:midterm,final',
            'field' => 'required|string',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $entry = GradeEntry::where('student_id', $request->student_id)
            ->where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $request->term)
            ->first();

        if ($entry) {
            $entry->update([$request->field => 0]);
            $computed = $entry->computeAverages(['knowledge' => 40, 'skills' => 50, 'attitude' => 10]);
            $entry->update($computed);
        }

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Clear all grade entry data for a student in a term
     */
    public function clearStudentGradeEntry(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term' => 'required|in:midterm,final',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        GradeEntry::where('student_id', $request->student_id)
            ->where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $request->term)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Student grades cleared']);
    }

    /**
     * Calculate all component scores and final grade from individual entries
     */
    private function calculateGradeScores($grade)
    {
        // Knowledge Score (40%)
        $quizzes = array_filter([
            $grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5,
            $grade->q6, $grade->q7, $grade->q8, $grade->q9, $grade->q10,
        ]);

        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $examAvg = (($grade->midterm_exam ?? 0) + ($grade->final_exam ?? 0)) / 2;

        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills Score (50%) - Calculate from individual entries
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : 0;

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : 0;

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : 0;

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : 0;

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude Score (10%) - Calculate from individual entries
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : 0;

        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : 0;

        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final Grade (K=40%, S=50%, A=10%)
        $grade->final_grade = (
            ($grade->knowledge_score * 0.40) +
            ($grade->skills_score * 0.50) +
            ($grade->attitude_score * 0.10)
        );

        $grade->save();
    }

    /**
     * AJAX: Get all scores for a student as JSON (supports live score display)
     */
    public function getStudentScores($studentId, $classId, $term)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get the grade record
        $grade = Grade::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->first();

        if (! $grade) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No grades found for this student',
            ]);
        }

        // Extract all entered scores
        $scores = [
            'knowledge' => [
                'q1' => $grade->q1,
                'q2' => $grade->q2,
                'q3' => $grade->q3,
                'q4' => $grade->q4,
                'q5' => $grade->q5,
                'midterm_exam' => $grade->midterm_exam,
                'final_exam' => $grade->final_exam,
                'knowledge_score' => $grade->knowledge_score,
            ],
            'skills' => [
                'output_1' => $grade->output_1,
                'output_2' => $grade->output_2,
                'output_3' => $grade->output_3,
                'class_participation_1' => $grade->class_participation_1,
                'class_participation_2' => $grade->class_participation_2,
                'class_participation_3' => $grade->class_participation_3,
                'activities_1' => $grade->activities_1,
                'activities_2' => $grade->activities_2,
                'activities_3' => $grade->activities_3,
                'assignments_1' => $grade->assignments_1,
                'assignments_2' => $grade->assignments_2,
                'assignments_3' => $grade->assignments_3,
                'skills_score' => $grade->skills_score,
            ],
            'attitude' => [
                'behavior_1' => $grade->behavior_1,
                'behavior_2' => $grade->behavior_2,
                'behavior_3' => $grade->behavior_3,
                'awareness_1' => $grade->awareness_1,
                'awareness_2' => $grade->awareness_2,
                'awareness_3' => $grade->awareness_3,
                'attitude_score' => $grade->attitude_score,
            ],
            'final' => [
                'final_grade' => $grade->final_grade,
                'grade_point' => Grade::getGradePoint($grade->final_grade),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $scores,
            'message' => 'Scores retrieved successfully',
        ]);
    }

    /**
     * Show NEW KSA Grade Entry Form
     */
    public function showGradeEntryNew($classId)
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store NEW KSA Grades using Midterm/Final structure
     */
    public function storeGradesNew(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        $grades = $request->input('grades', []);

        $successCount = 0;
        $errors = [];

        foreach ($grades as $studentId => $gradeData) {
            try {
                $student = Student::findOrFail($studentId);

                // Prepare update data
                $updateData = [
                    'subject_id' => $class->course_id,
                    'teacher_id' => $teacherId,
                ];

                // Knowledge Components - Exams
                $updateData['exam_prelim'] = floatval($gradeData['exam_prelim'] ?? 0);
                $updateData['exam_midterm'] = floatval($gradeData['exam_midterm'] ?? 0);
                $updateData['exam_final'] = floatval($gradeData['exam_final'] ?? 0);

                // Knowledge Components - Quizzes
                $updateData['quiz_1'] = floatval($gradeData['quiz_1'] ?? 0);
                $updateData['quiz_2'] = floatval($gradeData['quiz_2'] ?? 0);
                $updateData['quiz_3'] = floatval($gradeData['quiz_3'] ?? 0);
                $updateData['quiz_4'] = floatval($gradeData['quiz_4'] ?? 0);
                $updateData['quiz_5'] = floatval($gradeData['quiz_5'] ?? 0);

                // Skills Components - Output
                $updateData['output_1'] = floatval($gradeData['output_1'] ?? 0);
                $updateData['output_2'] = floatval($gradeData['output_2'] ?? 0);
                $updateData['output_3'] = floatval($gradeData['output_3'] ?? 0);

                // Skills Components - Class Participation
                $updateData['class_participation_1'] = floatval($gradeData['class_participation_1'] ?? 0);
                $updateData['class_participation_2'] = floatval($gradeData['class_participation_2'] ?? 0);
                $updateData['class_participation_3'] = floatval($gradeData['class_participation_3'] ?? 0);

                // Skills Components - Activities
                $updateData['activities_1'] = floatval($gradeData['activities_1'] ?? 0);
                $updateData['activities_2'] = floatval($gradeData['activities_2'] ?? 0);
                $updateData['activities_3'] = floatval($gradeData['activities_3'] ?? 0);

                // Skills Components - Assignments
                $updateData['assignments_1'] = floatval($gradeData['assignments_1'] ?? 0);
                $updateData['assignments_2'] = floatval($gradeData['assignments_2'] ?? 0);
                $updateData['assignments_3'] = floatval($gradeData['assignments_3'] ?? 0);

                // Attitude Components - Behavior
                $updateData['behavior_1'] = floatval($gradeData['behavior_1'] ?? 0);
                $updateData['behavior_2'] = floatval($gradeData['behavior_2'] ?? 0);
                $updateData['behavior_3'] = floatval($gradeData['behavior_3'] ?? 0);

                // Attitude Components - Awareness
                $updateData['awareness_1'] = floatval($gradeData['awareness_1'] ?? 0);
                $updateData['awareness_2'] = floatval($gradeData['awareness_2'] ?? 0);
                $updateData['awareness_3'] = floatval($gradeData['awareness_3'] ?? 0);

                // Create or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                    ],
                    $updateData
                );

                // Calculate component averages
                $this->recalculateNewGradeScores($grade);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Student {$studentId}: ".$e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "✓ {$successCount} grades saved successfully!";
            if (! empty($errors)) {
                $message .= ' ('.count($errors).' errors encountered)';
            }

            return redirect()->route('teacher.grades', $classId)->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No grades were saved. Please check for errors.');
        }
    }

    /**
     * Recalculate all grade scores using NEW system (Midterm/Final)
     */
    private function recalculateNewGradeScores($grade)
    {
        // Prepare arrays for calculation methods
        $quizzes = [
            'quiz_1' => $grade->quiz_1,
            'quiz_2' => $grade->quiz_2,
            'quiz_3' => $grade->quiz_3,
            'quiz_4' => $grade->quiz_4,
            'quiz_5' => $grade->quiz_5,
        ];

        $exams = [
            'exam_prelim' => $grade->exam_prelim,
            'exam_midterm' => $grade->exam_midterm,
            'exam_final' => $grade->exam_final,
        ];

        // Calculate Knowledge Average
        $grade->knowledge_average = Grade::calculateKnowledgeAverage($quizzes, $exams);

        // Calculate Skills Average (now returns array with totals)
        $output = [$grade->output_1, $grade->output_2, $grade->output_3];
        $cp = [$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3];
        $activities = [$grade->activities_1, $grade->activities_2, $grade->activities_3];
        $assignments = [$grade->assignments_1, $grade->assignments_2, $grade->assignments_3];

        $skillsResult = Grade::calculateSkillsAverage($output, $cp, $activities, $assignments);
        $grade->skills_average = $skillsResult['average'];
        $grade->output_total = $skillsResult['totals']['output_total'];
        $grade->class_participation_total = $skillsResult['totals']['class_participation_total'];
        $grade->activities_total = $skillsResult['totals']['activities_total'];
        $grade->assignments_total = $skillsResult['totals']['assignments_total'];

        // Calculate Attitude Average (now returns array with totals)
        $behavior = [$grade->behavior_1, $grade->behavior_2, $grade->behavior_3];
        $awareness = [$grade->awareness_1, $grade->awareness_2, $grade->awareness_3];

        $attitudeResult = Grade::calculateAttitudeAverage($behavior, $awareness);
        $grade->attitude_average = $attitudeResult['average'];
        $grade->behavior_total = $attitudeResult['totals']['behavior_total'];
        $grade->awareness_total = $attitudeResult['totals']['awareness_total'];

        // Calculate Midterm Grade (Knowledge 40% + Skills 50% + Attitude 10%)
        $grade->midterm_grade =
            ($grade->knowledge_average * 0.40) +
            ($grade->skills_average * 0.50) +
            ($grade->attitude_average * 0.10);

        // For now, set final_grade_value equal to midterm_grade
        // In a full implementation, this would be based on final exam scores
        $grade->final_grade_value = $grade->midterm_grade;

        // Calculate Overall Grade (Midterm 40% + Final 60%)
        $grade->overall_grade = Grade::calculateOverallGrade($grade->midterm_grade, $grade->final_grade_value);

        // Set decimal grade (same as overall grade)
        $grade->decimal_grade = $grade->overall_grade;

        // Get grade point and letter grade
        $grade->grade_point = Grade::getGradePoint($grade->overall_grade);
        $decimalScale = \App\Helpers\GradeHelper::convertToDecimalScale($grade->overall_grade);
        $gradeLabel = \App\Helpers\GradeHelper::getGradeLabel($decimalScale);
        $grade->letter_grade = \App\Helpers\GradeHelper::extractLetterGrade($gradeLabel);

        // Save all changes
        $grade->save();
    }

    /**
     * Upload/Lock grades from grade_entries table to grades table
     * This moves grades from temporary storage (grade_entries) to permanent storage (grades)
     */
    public function uploadGradeEntry(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return back()->with('error', 'Invalid term specified.');
        }

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get all grade entries for this class and term
        $entries = GradeEntry::where('class_id', $classId)
            ->where('term', $term)
            ->get();

        if ($entries->isEmpty()) {
            return back()->with('error', 'No grade entries found for '.ucfirst($term).' term. Please enter grades first.');
        }

        $successCount = 0;
        $errors = [];

        try {
            foreach ($entries as $entry) {
                // Find or create grade record
                $grade = Grade::firstOrCreate(
                    [
                        'student_id' => $entry->student_id,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                    ],
                    [
                        'subject_id' => $class->course_id,
                        'academic_year' => date('Y'),
                    ]
                );

                // Prefix for the term (mid_ or final_)
                $prefix = ($term === 'midterm') ? 'mid_' : 'final_';

                // Map grade_entries columns to grades table columns
                $updateData = [
                    // Knowledge Component
                    $prefix.'exam_pr' => $entry->exam_pr,
                    $prefix.'exam_md' => $entry->exam_md,
                    $prefix.'quiz_1' => $entry->quiz_1,
                    $prefix.'quiz_2' => $entry->quiz_2,
                    $prefix.'quiz_3' => $entry->quiz_3,
                    $prefix.'quiz_4' => $entry->quiz_4,
                    $prefix.'quiz_5' => $entry->quiz_5,
                    // Skills Component
                    $prefix.'output_1' => $entry->output_1,
                    $prefix.'output_2' => $entry->output_2,
                    $prefix.'output_3' => $entry->output_3,
                    $prefix.'classpart_1' => $entry->classpart_1,
                    $prefix.'classpart_2' => $entry->classpart_2,
                    $prefix.'classpart_3' => $entry->classpart_3,
                    $prefix.'activity_1' => $entry->activity_1,
                    $prefix.'activity_2' => $entry->activity_2,
                    $prefix.'activity_3' => $entry->activity_3,
                    $prefix.'assignment_1' => $entry->assignment_1,
                    $prefix.'assignment_2' => $entry->assignment_2,
                    $prefix.'assignment_3' => $entry->assignment_3,
                    // Attitude Component
                    $prefix.'behavior_1' => $entry->behavior_1,
                    $prefix.'behavior_2' => $entry->behavior_2,
                    $prefix.'behavior_3' => $entry->behavior_3,
                    $prefix.'awareness_1' => $entry->awareness_1,
                    $prefix.'awareness_2' => $entry->awareness_2,
                    $prefix.'awareness_3' => $entry->awareness_3,
                    // Computed Averages
                    $prefix.'knowledge_average' => $entry->knowledge_average,
                    $prefix.'skills_average' => $entry->skills_average,
                    $prefix.'attitude_average' => $entry->attitude_average,
                    $prefix.'final_grade' => $entry->term_grade,
                ];

                // Update grade record
                $grade->update($updateData);
                $successCount++;
            }

            // If all uploads successful, show success message
            return redirect()->route('teacher.grades', $classId)
                ->with('success', "🎉 Successfully uploaded {$successCount} grades for ".ucfirst($term).' term to permanent storage!');

        } catch (\Exception $e) {
            Log::error('Grade upload error: '.$e->getMessage());

            return back()->with('error', 'Error uploading grades: '.$e->getMessage());
        }
    }

    /**
     * Show grade results and summary by class with calculated decimal grades
     * Organized by class groups with pass/fail status and verification from backend
     */
    public function gradeResults()
    {
        $teacherId = Auth::id();
        $selectedClassId = request()->query('class_id');

        // Get teacher's classes with grades
        $classesQuery = ClassModel::where('teacher_id', $teacherId)
            ->with(['course']);

        if ($selectedClassId) {
            $classesQuery->where('id', $selectedClassId);
        }

        $classes = $classesQuery->get();
        $selectedClass = $selectedClassId ? $classes->first() : null;

        $classGradeResults = [];

        foreach ($classes as $class) {
            // Get all grades for this class
            $grades = Grade::where('class_id', $class->id)
                ->where('teacher_id', $teacherId)
                ->with('student')
                ->get();

            // Calculate results for each student
            $studentResults = [];
            $classStats = [
                'total_students' => \App\Models\Student::when($class->course_id, fn($q) => $q->where('course_id', $class->course_id), fn($q) => $q->where('class_id', $class->id))
                    ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                    ->count(),
                'graded_students' => 0,
                'passed_count' => 0,
                'failed_count' => 0,
                'average_grade' => 0,
                'pass_percentage' => 0,
            ];

            $totalGrade = 0;
            $graderCount = 0;

            foreach ($grades as $grade) {
                // Use the GradeHelper to calculate complete summary
                $summary = \App\Helpers\GradeHelper::getCompleteGradeSummary(
                    $grade->mid_knowledge_average ?? 0,
                    $grade->mid_skills_average ?? 0,
                    $grade->mid_attitude_average ?? 0,
                    $grade->final_knowledge_average ?? 0,
                    $grade->final_skills_average ?? 0,
                    $grade->final_attitude_average ?? 0
                );

                // Only include if has grades
                if ($summary['overall']['term_grade'] > 0) {
                    // Update the grade record with calculated values
                    $grade->update([
                        'mid_final_grade' => $summary['midterm']['term_grade'],
                        'final_final_grade' => $summary['final']['term_grade'],
                        'overall_grade' => $summary['overall']['term_grade'],
                        'grade_5pt_scale' => $summary['overall']['decimal_grade'],
                        'letter_grade' => \App\Helpers\GradeHelper::extractLetterGrade($summary['overall']['grade_label']),
                        'remarks' => $summary['overall']['remarks'],
                    ]);

                    $studentResults[] = [
                        'student' => $grade->student,
                        'student_id' => $grade->student->student_id,
                        'student_name' => trim(($grade->student->first_name ?? '') . ' ' . ($grade->student->last_name ?? '')) ?: 'N/A',
                        'midterm_grade' => $summary['midterm']['term_grade'],
                        'midterm_decimal' => $summary['midterm']['decimal_grade'],
                        'midterm_status' => $summary['midterm']['status'],
                        'final_grade' => $summary['final']['term_grade'],
                        'final_decimal' => $summary['final']['decimal_grade'],
                        'final_status' => $summary['final']['status'],
                        'overall_grade' => $summary['overall']['term_grade'],
                        'decimal_grade' => $summary['overall']['decimal_grade'],
                        'letter_grade' => \App\Helpers\GradeHelper::extractLetterGrade($summary['overall']['grade_label']),
                        'status' => $summary['overall']['status'],
                        'remarks' => $summary['overall']['remarks'],
                    ];

                    $totalGrade += $summary['overall']['term_grade'];
                    $graderCount++;

                    if ($summary['overall']['status'] === 'Passed') {
                        $classStats['passed_count']++;
                    } else {
                        $classStats['failed_count']++;
                    }
                }
            }

            // Calculate class statistics
            $classStats['graded_students'] = $graderCount;
            $classStats['average_grade'] = $graderCount > 0 ? round($totalGrade / $graderCount, 2) : 0;
            $classStats['pass_percentage'] = $classStats['total_students'] > 0
                ? round(($classStats['passed_count'] / $classStats['total_students']) * 100, 1)
                : 0;

            // Always include the class so the teacher can see the summary even if grades are not yet entered
            $classGradeResults[] = [
                'class' => $class,
                'course' => $class->course,
                'students' => $studentResults,
                'stats' => $classStats,
            ];
        }

        return view('teacher.grades.grade_results', compact('classGradeResults', 'selectedClass'));
    }

    /**
     * Get attendance grades for a class and term
     */
    public function getAttendanceGrades($classId, $term = 'Midterm')
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with(['students', 'program'])
            ->firstOrFail();

        // Get attendance records for the term
        $attendanceRecords = Attendance::where('class_id', $classId)
            ->where('term', $term)
            ->with('student.user')
            ->get()
            ->groupBy('student_id');

        $attendanceGrades = [];

        foreach ($attendanceRecords as $studentId => $records) {
            $student = $records->first()->student;
            $totalDays = $records->count();
            $presentDays = $records->where('status', 'Present')->count();
            $lateDays = $records->where('status', 'Late')->count();

            // Calculate attendance percentage (Present + Late = Attended)
            $attendedDays = $presentDays + $lateDays;
            $attendancePercentage = $totalDays > 0 ? ($attendedDays / $totalDays) * 100 : 0;

            $attendanceGrades[] = [
                'student_id' => $studentId,
                'student_name' => $student->name ?? 'Unknown',
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'late_days' => $lateDays,
                'attendance_percentage' => round($attendancePercentage, 2),
                'term' => $term,
                'grade_score' => round($attendancePercentage, 1), // Convert to grade score
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $attendanceGrades,
            'class' => $class,
            'term' => $term,
        ]);
    }

    /**
     * Sync attendance data to grades
     */
    public function syncAttendanceToGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'term' => 'required|in:Midterm,Final',
            'attendance_weight' => 'required|numeric|min:0|max:100',
        ]);

        $term = $validated['term'];
        $attendanceWeight = $validated['attendance_weight'] / 100;

        // Get attendance records
        $attendanceRecords = Attendance::where('class_id', $classId)
            ->where('term', $term)
            ->get()
            ->groupBy('student_id');

        $updated = 0;

        foreach ($attendanceRecords as $studentId => $records) {
            $totalDays = $records->count();
            $presentDays = $records->where('status', 'Present')->count();
            $lateDays = $records->where('status', 'Late')->count();

            // Calculate attendance percentage
            $attendedDays = $presentDays + $lateDays;
            $attendancePercentage = $totalDays > 0 ? ($attendedDays / $totalDays) * 100 : 0;
            $attendanceScore = round($attendancePercentage, 1);

            // Update grade record with attendance data
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => $term,
                ],
                [
                    'attendance_score' => $attendanceScore,
                    'attendance_percentage' => round($attendancePercentage, 2),
                    'total_attendance_days' => $totalDays,
                    'present_days' => $presentDays,
                    'late_days' => $lateDays,
                ]
            );

            $updated++;
        }

        return response()->json([
            'success' => true,
            'message' => "Attendance data synced to grades for {$updated} students",
            'term' => $term,
            'attendance_weight' => $validated['attendance_weight'],
        ]);
    }

    /**
            'knowledge' => [
                (object)['name' => 'Exam', 'weight' => 60, 'max_score' => 100],
                (object)['name' => 'Quiz 1', 'weight' => 20, 'max_score' => 100],
                (object)['name' => 'Quiz 2', 'weight' => 20, 'max_score' => 100]
            ],
            'skills' => [
                (object)['name' => 'Output', 'weight' => 40, 'max_score' => 100],
                (object)['name' => 'Class Participation', 'weight' => 30, 'max_score' => 100],
                (object)['name' => 'Activities', 'weight' => 30, 'max_score' => 100]
            ],
            'attitude' => [
                (object)['name' => 'Behavior', 'weight' => 50, 'max_score' => 100],
                (object)['name' => 'Awareness', 'weight' => 50, 'max_score' => 100]
            ]
        ]
    ];

    // Get existing grades for this class and term
    $existingGrades = Grade::where('class_id', $classId)
        ->where('term', $term)
        ->get()
        ->keyBy('student_id');

    return view('teacher.grades.advanced_grade_entry', compact(
        'class',
        'students',
        'ksaSettings',
        'term',
        'existingGrades'
    ));
    }

    /**
     * Show grade content management page
     */
    public function showGradeContent($classId, $term = 'midterm')
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with(['program'])
            ->firstOrFail();

        // Get students for the class
        $students = $this->getStudentsForClass($class);
        $class->setRelation('students', $students);

        // Get existing grades for this class and term
        $entries = Grade::where('class_id', $classId)
            ->where('term', strtolower($term))
            ->get()
            ->keyBy('student_id');

        // Get assessment range
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Check if components exist, if not, initialize default components
        $totalComponents = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->count();

        if ($totalComponents === 0) {
            // Auto-initialize standard KSA components
            $this->initializeStandardComponents($classId, $teacherId);
        }

        // Load components from database
        $knowledgeComponents = AssessmentComponent::where('class_id', $classId)
            ->where('category', 'Knowledge')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $skillsComponents = AssessmentComponent::where('class_id', $classId)
            ->where('category', 'Skills')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $attitudeComponents = AssessmentComponent::where('class_id', $classId)
            ->where('category', 'Attitude')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Load existing component entries for this class and term
        $componentEntries = ComponentEntry::where('class_id', $classId)
            ->where('term', strtolower($term))
            ->get()
            ->groupBy('student_id')
            ->map(function ($entries) {
                return $entries->keyBy('component_id');
            });

        // Load attendance settings from KSA settings
        $ksaSettingsModel = KsaSetting::where('class_id', $classId)
            ->where('term', strtolower($term))
            ->first();

        // Calculate attendance scores for all students using AttendanceCalculationService
        $attendanceService = new \App\Services\AttendanceCalculationService();
        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = $attendanceService->calculateAttendanceScore(
                $student->id,
                $classId,
                ucfirst($term) // Convert to 'Midterm' or 'Final'
            );
        }

        // Build KSA settings from DB (use actual teacher-configured weights)
        $ksaSettings = (object) [
            'knowledge_percentage' => (float) ($ksaSettingsModel->knowledge_weight ?? 40),
            'skills_percentage'    => (float) ($ksaSettingsModel->skills_weight    ?? 50),
            'attitude_percentage'  => (float) ($ksaSettingsModel->attitude_weight  ?? 10),
            'knowledge_weight'     => (float) ($ksaSettingsModel->knowledge_weight ?? 40) / 100,
            'skills_weight'        => (float) ($ksaSettingsModel->skills_weight    ?? 50) / 100,
            'attitude_weight'      => (float) ($ksaSettingsModel->attitude_weight  ?? 10) / 100,
            'total_meetings'       => (int)   ($ksaSettingsModel->total_meetings   ?? 0),
            'attendance_weight'    => (float) ($ksaSettingsModel->attendance_weight ?? 0),
            'attendance_category'  => $ksaSettingsModel->attendance_category ?? 'attitude',
            'passing_grade'        => (float) ($ksaSettingsModel->passing_grade    ?? 74),
        ];

        return view('teacher.grades.grade_content', compact(
            'class',
            'students',
            'term',
            'entries',
            'range',
            'ksaSettings',
            'knowledgeComponents',
            'skillsComponents',
            'attitudeComponents',
            'componentEntries',
            'attendanceData'
        ));
    }

    /**
     * Initialize standard KSA components for a class
     */
    private function initializeStandardComponents($classId, $teacherId)
    {
        // Determine if this is for midterm or final based on existing data
        // For now, we'll create components that work for both terms
        
        // Standard Knowledge Components (40% of grade)
        $knowledgeComponents = [
            // EXAM (60% of Knowledge)
            ['name' => 'Midterm Exam', 'subcategory' => 'Exam', 'weight' => 60, 'max_score' => 100, 'passing_score' => 75, 'order' => 1],
            
            // QUIZZES (40% of Knowledge) - 3 quizzes
            ['name' => 'Quiz 1', 'subcategory' => 'Quiz', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75, 'order' => 2],
            ['name' => 'Quiz 2', 'subcategory' => 'Quiz', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75, 'order' => 3],
            ['name' => 'Quiz 3', 'subcategory' => 'Quiz', 'weight' => 13.34, 'max_score' => 100, 'passing_score' => 75, 'order' => 4],
        ];

        // Standard Skills Components (50% of grade)
        $skillsComponents = [
            // OUTPUT (40% of Skills) - 3 outputs
            ['name' => 'Output 1', 'subcategory' => 'Output', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75, 'order' => 1],
            ['name' => 'Output 2', 'subcategory' => 'Output', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75, 'order' => 2],
            ['name' => 'Output 3', 'subcategory' => 'Output', 'weight' => 13.34, 'max_score' => 100, 'passing_score' => 75, 'order' => 3],
            
            // CLASS PARTICIPATION (30% of Skills) - 3 participations
            ['name' => 'Class Participation 1', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75, 'order' => 4],
            ['name' => 'Class Participation 2', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75, 'order' => 5],
            ['name' => 'Class Participation 3', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75, 'order' => 6],
            
            // ACTIVITIES (15% of Skills) - 3 activities
            ['name' => 'Activity 1', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 7],
            ['name' => 'Activity 2', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 8],
            ['name' => 'Activity 3', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 9],
            
            // ASSIGNMENTS (15% of Skills) - 3 assignments
            ['name' => 'Assignment 1', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 10],
            ['name' => 'Assignment 2', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 11],
            ['name' => 'Assignment 3', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75, 'order' => 12],
        ];

        // Standard Attitude Components (10% of grade)
        $attitudeComponents = [
            // BEHAVIOR (50% of Attitude) - 3 behaviors
            ['name' => 'Behavior 1', 'subcategory' => 'Behavior', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75, 'order' => 1],
            ['name' => 'Behavior 2', 'subcategory' => 'Behavior', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75, 'order' => 2],
            ['name' => 'Behavior 3', 'subcategory' => 'Behavior', 'weight' => 16.66, 'max_score' => 100, 'passing_score' => 75, 'order' => 3],
            
            // AWARENESS (50% of Attitude) - 3 awareness
            ['name' => 'Awareness 1', 'subcategory' => 'Awareness', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75, 'order' => 4],
            ['name' => 'Awareness 2', 'subcategory' => 'Awareness', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75, 'order' => 5],
            ['name' => 'Awareness 3', 'subcategory' => 'Awareness', 'weight' => 16.66, 'max_score' => 100, 'passing_score' => 75, 'order' => 6],
        ];

        // Insert Knowledge components
        foreach ($knowledgeComponents as $comp) {
            AssessmentComponent::create([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Knowledge',
                'subcategory' => $comp['subcategory'],
                'name' => $comp['name'],
                'max_score' => $comp['max_score'],
                'weight' => $comp['weight'],
                'passing_score' => $comp['passing_score'],
                'order' => $comp['order'],
                'is_active' => true,
            ]);
        }

        // Insert Skills components
        foreach ($skillsComponents as $comp) {
            AssessmentComponent::create([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Skills',
                'subcategory' => $comp['subcategory'],
                'name' => $comp['name'],
                'max_score' => $comp['max_score'],
                'weight' => $comp['weight'],
                'passing_score' => $comp['passing_score'],
                'order' => $comp['order'],
                'is_active' => true,
            ]);
        }

        // Insert Attitude components
        foreach ($attitudeComponents as $comp) {
            AssessmentComponent::create([
                'class_id' => $classId,
                'teacher_id' => $teacherId,
                'category' => 'Attitude',
                'subcategory' => $comp['subcategory'],
                'name' => $comp['name'],
                'max_score' => $comp['max_score'],
                'weight' => $comp['weight'],
                'passing_score' => $comp['passing_score'],
                'order' => $comp['order'],
                'is_active' => true,
            ]);
        }
    }

    /**
     * Get grade statistics
        $stats = [
            'total_students' => $students->count(),
            'graded_students' => $entries->count(),
            'average_grade' => $entries->avg('grade') ?? 0,
            'passing_students' => $entries->where('grade', '>=', 75)->count(),
            'failing_students' => $entries->where('grade', '<', 75)->count(),
        ];

        return view('teacher.grades.grade_content', compact(
            'class',
            'students',
            'term',
            'entries',
            'range',
            'ksaSettings',
            'stats'
        ));
    }

    /**
     * Show attendance-grade integration view
     */
    public function showGradeIntegration($classId, $term = 'Midterm')
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with(['students', 'program'])
            ->firstOrFail();

        return view('teacher.attendance.grade_integration', compact('class', 'term'));
    }

    /**
     * Save advanced grades from frontend
     */
    public function saveAdvancedGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        try {
            $grades = $request->input('grades', []);
            $term = $request->input('term', 'midterm');
            $saved = 0;

            foreach ($grades as $gradeData) {
                $studentId = $gradeData['student_id'];
                $components = $gradeData['components'] ?? [];
                $midtermGrade = $gradeData['midterm_grade'] ?? 0;
                $finalGrade = $gradeData['final_grade'] ?? 0;
                $finalScore = $gradeData['final_score'] ?? 0;

                // Validate student belongs to this class and teacher's campus
                $student = Student::where('id', $studentId)
                    ->where('class_id', $classId)
                    ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                    ->first();

                if (! $student) {
                    continue;
                }

                // Create or update grade record
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => strtolower($term),
                    ],
                    [
                        'knowledge' => $components['knowledge_0'] ?? 0,
                        'skills' => $components['skills_0'] ?? 0,
                        'attitude' => $components['attitude_0'] ?? 0,
                        'midterm_grade' => $midtermGrade,
                        'final_grade' => $finalGrade,
                        'grade' => $finalScore,
                        'components' => json_encode($components),
                        'graded_at' => now(),
                    ]
                );

                $saved++;
            }

            return response()->json([
                'success' => true,
                'message' => "Grades saved successfully for {$saved} students",
                'saved_count' => $saved,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving grades: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save grade configuration
     */
    public function saveGradeConfig(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        try {
            $config = $request->input('config', []);

            // Save configuration to database or cache
            $configKey = "grade_config_{$classId}_{$teacherId}";
            Cache::put($configKey, $config, 60 * 24 * 7); // Cache for 7 days

            return response()->json([
                'success' => true,
                'message' => 'Grade configuration saved successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving configuration: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get grade statistics for frontend
     */
    public function getGradeStatistics($classId, $term = 'midterm')
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $grades = Grade::where('class_id', $classId)
            ->where('term', strtolower($term))
            ->get();

        $totalStudents = $this->getStudentsForClass($class)->count();
        $gradedStudents = $grades->count();
        $averageGrade = $grades->avg('grade') ?? 0;
        $passingStudents = $grades->where('grade', '>=', 75)->count();
        $passingRate = $gradedStudents > 0 ? ($passingStudents / $gradedStudents) * 100 : 0;
        $completionRate = $totalStudents > 0 ? ($gradedStudents / $totalStudents) * 100 : 0;

        // Grade distribution
        $distribution = [
            'excellent' => $grades->where('grade', '>=', 90)->count(),
            'good' => $grades->whereBetween('grade', [80, 89])->count(),
            'fair' => $grades->whereBetween('grade', [70, 79])->count(),
            'poor' => $grades->where('grade', '<', 70)->count(),
        ];

        return response()->json([
            'total_students' => $totalStudents,
            'graded_students' => $gradedStudents,
            'average_grade' => round($averageGrade, 1),
            'passing_rate' => round($passingRate, 0),
            'completion_rate' => round($completionRate, 0),
            'distribution' => $distribution,
        ]);
    }

    /**
     * Export grades to CSV
     */
    public function exportGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with(['students.user', 'program'])
            ->firstOrFail();

        $term = $request->input('term', 'midterm');

        $grades = Grade::where('class_id', $classId)
            ->where('term', strtolower($term))
            ->with('student.user')
            ->get();

        $filename = "grades_{$class->name}_{$term}_".date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($grades, $class, $term) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Student ID',
                'Student Name',
                'Class',
                'Program',
                'Term',
                'Knowledge',
                'Skills',
                'Attitude',
                'Midterm Grade',
                'Final Grade',
                'Final Score',
                'Status',
                'Graded At',
            ]);

            // CSV Data
            foreach ($grades as $grade) {
                $status = 'Pass';
                if ($grade->grade < 75) {
                    $status = 'Fail';
                }
                if ($grade->grade < 70) {
                    $status = 'Poor';
                }
                if ($grade->grade >= 90) {
                    $status = 'Excellent';
                }

                fputcsv($file, [
                    $grade->student->student_id ?? '',
                    $grade->student->name ?? 'Unknown',
                    $class->name ?? '',
                    $class->program->program_name ?? '',
                    ucfirst($term),
                    $grade->knowledge ?? 0,
                    $grade->skills ?? 0,
                    $grade->attitude ?? 0,
                    $grade->midterm_grade ?? 0,
                    $grade->final_grade ?? 0,
                    $grade->grade ?? 0,
                    $status,
                    $grade->graded_at ? $grade->graded_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import grades from CSV
     */
    public function importGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        try {
            $file = $request->file('csv_file');
            if (! $file) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded',
                ], 400);
            }

            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));

            if (empty($data) || count($data) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'CSV file is empty or invalid',
                ], 400);
            }

            // Remove header
            $headers = array_shift($data);
            $imported = 0;
            $errors = [];

            foreach ($data as $row) {
                if (count($row) < 6) {
                    continue;
                }

                $studentId = $row[0] ?? null;
                $knowledge = floatval($row[1] ?? 0);
                $skills = floatval($row[2] ?? 0);
                $attitude = floatval($row[3] ?? 0);
                $midtermGrade = floatval($row[4] ?? 0);
                $finalGrade = floatval($row[5] ?? 0);

                // Validate student
                $student = Student::where('id', $studentId)
                    ->where('class_id', $classId)
                    ->first();

                if (! $student) {
                    $errors[] = "Student ID {$studentId} not found in class";

                    continue;
                }

                // Calculate final score
                $finalScore = ($midtermGrade * 0.4) + ($finalGrade * 0.6);

                // Save grade
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => 'midterm',
                    ],
                    [
                        'knowledge' => $knowledge,
                        'skills' => $skills,
                        'attitude' => $attitude,
                        'midterm_grade' => $midtermGrade,
                        'final_grade' => $finalGrade,
                        'grade' => $finalScore,
                        'graded_at' => now(),
                    ]
                );

                $imported++;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$imported} grades",
                'imported' => $imported,
                'errors' => $errors,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing grades: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save component-based grades
     */
    public function saveComponentGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->get('term', 'midterm');

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|integer',
            'grades.*.components' => 'required|array',
            'grades.*.components.*.component_id' => 'required|integer',
            'grades.*.components.*.score' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['grades'] as $studentGrade) {
                $studentId = $studentGrade['student_id'];

                foreach ($studentGrade['components'] as $componentScore) {
                    $componentId = $componentScore['component_id'];
                    $score = $componentScore['score'] ?? null;

                    if ($score !== null && $score >= 0) {
                        ComponentEntry::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'component_id' => $componentId,
                                'class_id' => $classId,
                                'term' => $term,
                            ],
                            [
                                'raw_score' => $score,
                                // normalized_score is auto-calculated in ComponentEntry::boot()
                            ]
                        );
                    }
                }

                // Recalculate ComponentAverage for this student/class/term
                \App\Models\ComponentAverage::calculateAndUpdate($studentId, $classId, $term);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grades saved successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving component grades: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving grades: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show teacher's courses and course access requests
     */
    public function coursesIndex()
    {
        $teacherId = Auth::id();
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        $isApproved = empty($user->campus) || $user->campus_status === 'approved';

        // Get approved course access requests with campus isolation
        $approvedRequests = CourseAccessRequest::where('teacher_id', $teacherId)
            ->where('status', 'approved')
            ->when(!empty($campus), fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->with('course')
            ->get();

        $approvedCourseIds = $approvedRequests->pluck('course.id')->filter()->unique();

        // Also include courses the teacher already has classes in (direct assignment)
        $directCourseIds = ClassModel::where('teacher_id', $teacherId)
            ->when(!empty($campus), fn($q) => $q->where('campus', $campus))
            ->distinct()
            ->pluck('course_id')
            ->filter();

        // Merge both sources — courses from requests + courses from direct class assignments
        $allCourseIds = $approvedCourseIds->merge($directCourseIds)->unique()->values();

        $approvedCourses = Course::whereIn('id', $allCourseIds)
            ->orderBy('program_name')
            ->get()
            ->map(function ($course) use ($teacherId, $campus, $schoolId) {
                $course->classes_count = ClassModel::where('teacher_id', $teacherId)
                    ->where('course_id', $course->id)
                    ->when(!empty($campus), fn($q) => $q->where('campus', $campus))
                    ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
                    ->count();
                return $course;
            });

        // Get pending requests with campus isolation
        $pendingRequests = CourseAccessRequest::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->when(!empty($campus), fn($q) => $q->where('campus', $campus))
            ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
            ->with('course')
            ->get();

        // Get available courses - ONLY for approved teachers with campus isolation
        $availableCourses = collect();
        if ($isApproved) {
            $requestedCourseIds = CourseAccessRequest::where('teacher_id', $teacherId)
                ->whereIn('status', ['pending', 'approved'])
                ->pluck('course_id');

            // Exclude courses already shown (approved requests + direct assignments)
            $excludeIds = $requestedCourseIds->merge($allCourseIds)->unique();

            $availableCourses = Course::whereNotIn('id', $excludeIds)
                ->when(!empty($campus), fn($q) => $q->where('campus', $campus))
                ->when(!empty($schoolId), fn($q) => $q->where('school_id', $schoolId))
                ->orderBy('program_name')
                ->get();
        }

        return view('teacher.courses.index', compact(
            'approvedCourses',
            'pendingRequests',
            'availableCourses',
            'isApproved'
        ));
    }

    /**
     * Request access to a course
     */
    public function requestCourseAccess(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $user = Auth::user();

        // Check if user is approved for campus access
        if (!empty($user->campus) && $user->campus_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Campus approval required before requesting course access.'
            ]);
        }

        // Check if already requested
        $existingRequest = CourseAccessRequest::where('teacher_id', $teacherId)
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You have already requested access to this course.'
            ]);
        }

        // Create request with campus isolation
        CourseAccessRequest::create([
            'teacher_id' => $teacherId,
            'course_id' => $validated['course_id'],
            'campus' => $user->campus,
            'school_id' => $user->school_id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course access request sent successfully.'
        ]);
    }

    /**
     * Cancel a pending course access request
     */
    public function cancelCourseRequest($requestId)
    {
        $teacherId = Auth::id();
        
        $request = CourseAccessRequest::where('id', $requestId)
            ->where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => 'Request not found or cannot be canceled.'
            ]);
        }

        $request->delete();

        return response()->json([
            'success' => true,
            'message' => 'Request canceled successfully.'
        ]);
    }
}
