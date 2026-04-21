<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;
        
        // Apply campus filtering for campus admins
        $query = Subject::with(['program', 'school', 'campusSchool']);
        
        if ($adminCampus) {
            $query->where('campus', $adminCampus);
        }
        if ($adminSchoolId) {
            $query->where('school_id', $adminSchoolId);
        }
        
        $subjects = $query->get();

        return view('admin.subjects', compact('subjects', 'adminCampus'));
    }

    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        // Filter courses by campus for campus admins
        $courses = Course::active()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->get();
            
        $instructors = Teacher::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->get();
            
        $categories = [
            'Programming' => 'Programming',
            'Mathematics' => 'Mathematics',
            'Science' => 'Science',
            'Languages' => 'Languages',
            'Social Studies' => 'Social Studies',
            'Technical' => 'Technical',
            'Education' => 'Education',
            'Agriculture' => 'Agriculture',
            'Business' => 'Business',
            'Arts' => 'Arts',
            'Health' => 'Health',
            'General Education' => 'General Education',
        ];
        $types = [
            'Core' => 'Core - Required Subject',
            'Elective' => 'Elective - Optional Subject',
            'General' => 'General - Common Subject',
        ];

        return view('admin.subjects.create', compact('courses', 'instructors', 'categories', 'types', 'adminCampus'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;
        
        $validated = $request->validate([
            'subject_code' => 'required|string|max:10',
            'subject_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:6',
            'course_id' => 'nullable|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:Core,Elective,General',
            'program' => 'nullable|string|max:255',
            'year_level' => 'nullable|integer|min:1|max:4',
            'semester' => 'nullable|in:1,2',
        ]);

        // Set defaults for nullable fields
        if (!isset($validated['year_level'])) {
            $validated['year_level'] = 1; // Default to year 1
        }
        if (!isset($validated['semester'])) {
            $validated['semester'] = 1; // Default to semester 1
        }

        // Add campus isolation
        if ($adminCampus) {
            $validated['campus'] = $adminCampus;
        }
        if ($adminSchoolId) {
            $validated['school_id'] = $adminSchoolId;
            
            // Set campus_code from admin's school
            $school = \App\Models\School::find($adminSchoolId);
            if ($school) {
                $validated['campus_code'] = $school->school_code;
            }
        }

        // Ensure subject_code uniqueness within campus
        $existingSubject = Subject::where('subject_code', $validated['subject_code'])
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->first();

        if ($existingSubject) {
            return back()->withErrors(['subject_code' => 'Subject code already exists in this campus.'])->withInput();
        }

        // The model's boot method will handle program sync automatically
        $subject = Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', "Subject '{$subject->subject_name}' created successfully and assigned to '{$subject->program}'");
    }

    public function edit(Subject $subject)
    {
        $courses = Course::active()->get();
        $instructors = Teacher::get();
        $categories = [
            'Programming' => 'Programming',
            'Mathematics' => 'Mathematics',
            'Science' => 'Science',
            'Languages' => 'Languages',
            'Social Studies' => 'Social Studies',
            'Technical' => 'Technical',
            'Education' => 'Education',
            'Agriculture' => 'Agriculture',
            'Business' => 'Business',
            'Arts' => 'Arts',
            'Health' => 'Health',
            'General Education' => 'General Education',
        ];
        $types = [
            'Core' => 'Core - Required Subject',
            'Elective' => 'Elective - Optional Subject',
            'General' => 'General - Common Subject',
        ];

        return view('admin.subjects.edit', compact('subject', 'courses', 'instructors', 'categories', 'types'));
    }

    public function update(Request $request, Subject $subject)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Check campus isolation
        if ($adminCampus && $subject->campus !== $adminCampus) {
            abort(403, 'You can only edit subjects from your campus');
        }
        if ($adminSchoolId && $subject->school_id !== $adminSchoolId) {
            abort(403, 'You can only edit subjects from your school');
        }

        $validated = $request->validate([
            'subject_code' => 'required|string|max:10',
            'subject_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:6',
            'course_id' => 'nullable|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:Core,Elective,General',
            'program' => 'nullable|string|max:255',
            'year_level' => 'nullable|integer|min:1|max:4',
            'semester' => 'nullable|in:1,2',
        ]);

        // Ensure subject_code uniqueness within campus (excluding current subject)
        $existingSubject = Subject::where('subject_code', $validated['subject_code'])
            ->where('id', '!=', $subject->id)
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->first();

        if ($existingSubject) {
            return back()->withErrors(['subject_code' => 'Subject code already exists in this campus.'])->withInput();
        }

        $oldProgram = $subject->program;

        // The model's boot method will handle program sync automatically
        $subject->update($validated);

        $message = "Subject '{$subject->subject_name}' updated successfully";
        if ($oldProgram !== $subject->program) {
            $message .= " and moved from '{$oldProgram}' to '{$subject->program}'";
        }

        return redirect()->route('admin.subjects.index')->with('success', $message);
    }

    public function destroy(Subject $subject)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Check campus isolation
        if ($adminCampus && $subject->campus !== $adminCampus) {
            abort(403, 'You can only delete subjects from your campus');
        }
        if ($adminSchoolId && $subject->school_id !== $adminSchoolId) {
            abort(403, 'You can only delete subjects from your school');
        }

        $subjectName = $subject->subject_name;
        $programName = $subject->program;

        // Check if subject is used in any grades or classes
        // Add additional checks as needed

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', "Subject '{$subjectName}' from '{$programName}' deleted successfully");
    }

    public function show(Subject $subject)
    {
        $subject->load('course', 'instructor');

        return view('admin.subjects.show', compact('subject'));
    }

    public function byCategory($category)
    {
        $subjects = Subject::byCategory($category)->with('course', 'instructor')->get();

        return view('admin.subjects.category', compact('subjects', 'category'));
    }

    public function byProgram($program)
    {
        $subjects = Subject::byProgram($program)->with('course', 'instructor')->get();

        return view('admin.subjects.program', compact('subjects', 'program'));
    }

    // New method to sync all subjects with their courses
    public function syncAll()
    {
        $subjects = Subject::whereNotNull('program_id')->get();
        $updated = 0;

        foreach ($subjects as $subject) {
            $oldProgram = $subject->program;
            $subject->syncWithCourse();

            if ($oldProgram !== $subject->program) {
                $updated++;
            }
        }

        // Check if request is AJAX
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Synced {$updated} subjects with their respective courses",
                'updated' => $updated,
            ]);
        }

        return redirect()->route('admin.subjects.index')->with('success', "Synced {$updated} subjects with their respective courses");
    }

    // API method for AJAX requests
    public function getSubjectsByCourse($courseId)
    {
        $subjects = Subject::byCourse($courseId)->with('instructor')->get();

        return response()->json([
            'subjects' => $subjects,
            'count' => $subjects->count(),
        ]);
    }
}
