<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        // Use the new grouped method for better organization
        $subjects = Subject::with('course', 'instructor')->get();

        return view('admin.subjects', compact('subjects'));
    }

    public function create()
    {
        $courses = Course::active()->get();
        $instructors = User::where('role', 'teacher')->orWhere('role', 'instructor')->get();
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

        return view('admin.subjects.create', compact('courses', 'instructors', 'categories', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects|string|max:10',
            'subject_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:6',
            'course_id' => 'nullable|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:Core,Elective,General',
            'program' => 'nullable|string|max:255',
        ]);

        // The model's boot method will handle program sync automatically
        $subject = Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', "Subject '{$subject->subject_name}' created successfully and assigned to '{$subject->program}'");
    }

    public function edit(Subject $subject)
    {
        $courses = Course::active()->get();
        $instructors = User::where('role', 'teacher')->orWhere('role', 'instructor')->get();
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
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code,'.$subject->id.'|string|max:10',
            'subject_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'credit_hours' => 'required|integer|min:1|max:6',
            'course_id' => 'nullable|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:Core,Elective,General',
            'program' => 'nullable|string|max:255',
        ]);

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
        $subjects = Subject::whereNotNull('course_id')->get();
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
