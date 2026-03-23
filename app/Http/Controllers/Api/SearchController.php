<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Get subjects for the authenticated teacher
     */
    public function subjects(Request $request)
    {
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        
        if ($user->role === 'teacher') {
            $subjects = Subject::whereHas('teachers', function ($query) use ($user) {
                $query->where('teacher_id', $user->id)
                      ->where('teacher_subject.status', 'active');
            })
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->with(['program'])
            ->get();
        } else {
            $subjects = Subject::query()
                ->when($campus, fn($q) => $q->where('campus', $campus))
                ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                ->with(['program'])
                ->get();
        }

        return response()->json($subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => ($subject->subject_code ? $subject->subject_code . ' - ' : '') . $subject->subject_name,
                'description' => $subject->program ? 'Program: ' . $subject->program->program_name : 'Independent Subject',
                'subject_code' => $subject->subject_code,
                'subject_name' => $subject->subject_name,
                'credit_hours' => $subject->credit_hours,
                'category' => $subject->category,
                'program_id' => $subject->program_id,
                'program_name' => $subject->program ? $subject->program->program_name : null,
                'campus' => $subject->campus,
                'school_id' => $subject->school_id
            ];
        }));
    }

    /**
     * Search subjects
     */
    public function searchSubjects(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        
        $subjectsQuery = Subject::query();
        
        // Apply campus isolation
        if ($campus) {
            $subjectsQuery->where('campus', $campus);
        }
        if ($schoolId) {
            $subjectsQuery->where('school_id', $schoolId);
        }
        
        if ($user->role === 'teacher') {
            $subjectsQuery->whereHas('teachers', function ($q) use ($user) {
                $q->where('teacher_id', $user->id)
                  ->where('teacher_subject.status', 'active');
            });
        }
        
        if ($query) {
            $subjectsQuery->where(function ($q) use ($query) {
                $q->where('subject_name', 'LIKE', "%{$query}%")
                  ->orWhere('subject_code', 'LIKE', "%{$query}%");
            });
        }
        
        $subjects = $subjectsQuery->with(['program'])->limit(20)->get();

        return response()->json($subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => ($subject->subject_code ? $subject->subject_code . ' - ' : '') . $subject->subject_name,
                'description' => $subject->program ? 'Program: ' . $subject->program->program_name : 'Independent Subject',
                'subject_code' => $subject->subject_code,
                'subject_name' => $subject->subject_name,
                'credit_hours' => $subject->credit_hours,
                'category' => $subject->category,
                'campus' => $subject->campus,
                'school_id' => $subject->school_id
            ];
        }));
    }

    /**
     * Get courses
     */
    public function courses(Request $request)
    {
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;

        $courses = Course::query()
            ->when($campus, fn($q) => $q->where('campus', $campus))
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->orderBy('program_name')
            ->get();

        return response()->json($courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->program_name . ' (' . ($course->program_code ?? 'N/A') . ')',
                'description' => $course->description ?? 'No description available',
                'program_name' => $course->program_name,
                'program_code' => $course->program_code,
                'college' => $course->college,
                'department' => $course->department,
                'campus' => $course->campus,
                'school_id' => $course->school_id
            ];
        }));
    }

    /**
     * Search courses
     */
    public function searchCourses(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        
        $coursesQuery = Course::query();
        
        // Apply campus isolation
        if ($campus) {
            $coursesQuery->where('campus', $campus);
        }
        if ($schoolId) {
            $coursesQuery->where('school_id', $schoolId);
        }
        
        if ($query) {
            $coursesQuery->where(function ($q) use ($query) {
                $q->where('program_name', 'LIKE', "%{$query}%")
                  ->orWhere('program_code', 'LIKE', "%{$query}%")
                  ->orWhere('college', 'LIKE', "%{$query}%")
                  ->orWhere('department', 'LIKE', "%{$query}%");
            });
        }
        
        $courses = $coursesQuery->orderBy('program_name')->limit(20)->get();

        return response()->json($courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->program_name . ' (' . ($course->program_code ?? 'N/A') . ')',
                'description' => $course->description ?? 'No description available',
                'program_name' => $course->program_name,
                'program_code' => $course->program_code,
                'college' => $course->college,
                'department' => $course->department,
                'campus' => $course->campus,
                'school_id' => $course->school_id
            ];
        }));
    }

    /**
     * Get students
     */
    public function students(Request $request)
    {
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        
        $studentsQuery = Student::query();
        
        // Apply campus isolation
        if ($campus) {
            $studentsQuery->where('campus', $campus);
        }
        if ($schoolId) {
            $studentsQuery->where('school_id', $schoolId);
        }
        
        // If teacher, only show students from their classes
        if ($user->role === 'teacher') {
            $classIds = ClassModel::where('teacher_id', $user->id)
                ->when($campus, fn($q) => $q->where('campus', $campus))
                ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                ->pluck('id');
            
            $studentsQuery->whereIn('class_id', $classIds);
        }
        
        $students = $studentsQuery->orderBy('first_name')->orderBy('last_name')->get();

        return response()->json($students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'description' => $student->email,
                'email' => $student->email,
                'student_id' => $student->student_id ?? 'N/A',
                'year_level' => $student->year_level,
                'section' => $student->section,
                'campus' => $student->campus,
                'school_id' => $student->school_id
            ];
        }));
    }

    /**
     * Search students
     */
    public function searchStudents(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        $campus = $user->campus;
        $schoolId = $user->school_id;
        
        $studentsQuery = Student::query();
        
        // Apply campus isolation
        if ($campus) {
            $studentsQuery->where('campus', $campus);
        }
        if ($schoolId) {
            $studentsQuery->where('school_id', $schoolId);
        }
        
        // If teacher, only show students from their classes
        if ($user->role === 'teacher') {
            $classIds = ClassModel::where('teacher_id', $user->id)
                ->when($campus, fn($q) => $q->where('campus', $campus))
                ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                ->pluck('id');
            
            $studentsQuery->whereIn('class_id', $classIds);
        }
        
        if ($query) {
            $studentsQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('student_id', 'LIKE', "%{$query}%");
            });
        }
        
        $students = $studentsQuery->orderBy('first_name')->orderBy('last_name')->limit(20)->get();

        return response()->json($students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'description' => $student->email,
                'email' => $student->email,
                'student_id' => $student->student_id ?? 'N/A',
                'year_level' => $student->year_level,
                'section' => $student->section,
                'campus' => $student->campus,
                'school_id' => $student->school_id
            ];
        }));
    }

    /**
     * Get teachers
     */
    public function teachers(Request $request)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return response()->json($teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'description' => $teacher->email,
                'email' => $teacher->email,
                'department' => $teacher->department ?? 'N/A',
                'specialization' => $teacher->specialization ?? 'N/A'
            ];
        }));
    }

    /**
     * Search teachers
     */
    public function searchTeachers(Request $request)
    {
        $query = $request->get('q', '');
        
        $teachersQuery = User::where('role', 'teacher');
        
        if ($query) {
            $teachersQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('department', 'LIKE', "%{$query}%")
                  ->orWhere('specialization', 'LIKE', "%{$query}%");
            });
        }
        
        $teachers = $teachersQuery->orderBy('name')->limit(20)->get();

        return response()->json($teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'description' => $teacher->email,
                'email' => $teacher->email,
                'department' => $teacher->department ?? 'N/A',
                'specialization' => $teacher->specialization ?? 'N/A'
            ];
        }));
    }

    /**
     * Get classes
     */
    public function classes(Request $request)
    {
        $user = Auth::user();
        $classesQuery = ClassModel::query();
        
        // If teacher, only show their classes
        if ($user->role === 'teacher') {
            $classesQuery->where('teacher_id', $user->id);
        }
        
        $classes = $classesQuery->with(['course', 'teacher'])->orderBy('class_name')->get();

        return response()->json($classes->map(function ($class) {
            return [
                'id' => $class->id,
                'name' => $class->class_name,
                'description' => ($class->course ? $class->course->program_name : 'No Course') . 
                               ' - ' . ($class->teacher ? $class->teacher->name : 'No Teacher'),
                'class_name' => $class->class_name,
                'course_name' => $class->course ? $class->course->program_name : null,
                'teacher_name' => $class->teacher ? $class->teacher->name : null,
                'year_level' => $class->year_level,
                'section' => $class->section,
                'semester' => $class->semester,
                'academic_year' => $class->academic_year
            ];
        }));
    }

    /**
     * Search classes
     */
    public function searchClasses(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        
        $classesQuery = ClassModel::query();
        
        // If teacher, only show their classes
        if ($user->role === 'teacher') {
            $classesQuery->where('teacher_id', $user->id);
        }
        
        if ($query) {
            $classesQuery->where(function ($q) use ($query) {
                $q->where('class_name', 'LIKE', "%{$query}%")
                  ->orWhere('section', 'LIKE', "%{$query}%")
                  ->orWhere('semester', 'LIKE', "%{$query}%")
                  ->orWhere('academic_year', 'LIKE', "%{$query}%");
            });
        }
        
        $classes = $classesQuery->with(['course', 'teacher'])->orderBy('class_name')->limit(20)->get();

        return response()->json($classes->map(function ($class) {
            return [
                'id' => $class->id,
                'name' => $class->class_name,
                'description' => ($class->course ? $class->course->program_name : 'No Course') . 
                               ' - ' . ($class->teacher ? $class->teacher->name : 'No Teacher'),
                'class_name' => $class->class_name,
                'course_name' => $class->course ? $class->course->program_name : null,
                'teacher_name' => $class->teacher ? $class->teacher->name : null,
                'year_level' => $class->year_level,
                'section' => $class->section,
                'semester' => $class->semester,
                'academic_year' => $class->academic_year
            ];
        }));
    }

    /**
     * Get departments for dropdown
     */
    public function departments(Request $request)
    {
        $query = $request->get('q', '');
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Get unique departments from courses
        $departmentsQuery = Course::select('department')
            ->whereNotNull('department')
            ->where('department', '!=', '');

        // Apply campus filtering
        if ($adminCampus) {
            $departmentsQuery->where('campus', $adminCampus);
        }
        if ($adminSchoolId) {
            $departmentsQuery->where('school_id', $adminSchoolId);
        }

        // Apply search filter
        if ($query) {
            $departmentsQuery->where('department', 'LIKE', "%{$query}%");
        }

        $departments = $departmentsQuery->distinct()
            ->orderBy('department')
            ->pluck('department')
            ->map(function ($department, $index) {
                return [
                    'id' => $index + 1,
                    'name' => $department,
                    'description' => 'Department'
                ];
            });

        return response()->json($departments->values());
    }

    /**
     * Search departments
     */
    public function searchDepartments(Request $request)
    {
        return $this->departments($request);
    }
}