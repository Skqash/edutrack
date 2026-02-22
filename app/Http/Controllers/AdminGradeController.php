<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminGradeController extends Controller
{
    public function index()
    {
        // Get all classes with their grade records
        $classes = \App\Models\ClassModel::with(['grades' => function($query) {
            $query->with('student.user', 'subject', 'teacher')->orderBy('created_at', 'desc');
        }, 'students' => function($query) {
            $query->with('user');
        }])->get();

        // Group grades by class and calculate statistics
        $gradesByClass = [];
        foreach($classes as $class) {
            $grades = $class->grades;
            $stats = [
                'total_records' => $grades->count(),
                'average_grade' => $grades->count() > 0 ? round($grades->avg('grade_point'), 2) : 0,
                'highest_grade' => $grades->count() > 0 ? $grades->max('grade_point') : 0,
                'lowest_grade' => $grades->count() > 0 ? $grades->min('grade_point') : 0,
            ];
            $gradesByClass[$class->id] = [
                'class' => $class,
                'grades' => $grades,
                'stats' => $stats
            ];
        }

        // Get total counts
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = Subject::count();

        return view('admin.grades.index', compact('gradesByClass', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $subjects = Subject::where('status', 'Active')->get();
        return view('admin.grades.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'required|in:A,B,C,D,F',
            'semester' => 'required|in:1,2,3,4,5,6,7,8',
            'academic_year' => 'required|string'
        ]);

        Grade::create($validated);
        return redirect()->route('admin.grades.index')->with('success', 'Grade recorded successfully');
    }

    public function edit(Grade $grade)
    {
        $students = Student::with('user')->get();
        $subjects = Subject::where('status', 'Active')->get();
        return view('admin.grades.edit', compact('grade', 'students', 'subjects'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'required|numeric|min:0|max:100',
            'semester' => 'required|in:1,2,3,4,5,6,7,8',
            'academic_year' => 'required|string'
        ]);

        $grade->update($validated);
        return redirect()->route('admin.grades.index')->with('success', 'Grade updated successfully');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('admin.grades.index')->with('success', 'Grade deleted successfully');
    }

    /**
     * Export all grades for a specific class as CSV
     */
    public function exportClass($classId)
    {
        $class = ClassModel::with(['students.user', 'grades' => function($query) {
            $query->with('subject', 'teacher');
        }])->findOrFail($classId);

        // Get all students in this class
        $students = $class->students;
        
        // Generate CSV header
        $csv = "Student Name,Subject,Midterm,Finals,Total Average\n";
        
        // Group grades by student
        foreach($students as $student) {
            $studentGrades = $class->grades->where('student_id', $student->id);
            
            if($studentGrades->count() > 0) {
                foreach($studentGrades as $grade) {
                    $midterm = $grade->midterm_exam ?? 'N/A';
                    $finals = $grade->final_exam ?? 'N/A';
                    $average = $grade->grade_point ?? 'N/A';
                    
                    $csv .= '"' . ($student->user->name ?? 'N/A') . '",' 
                         . '"' . ($grade->subject->subject_name ?? 'N/A') . '",'
                         . '"' . $midterm . '",'
                         . '"' . $finals . '",'
                         . '"' . $average . '"' . "\n";
                }
            } else {
                // Add student with N/A if no grades
                $csv .= '"' . ($student->user->name ?? 'N/A') . '",N/A,N/A,N/A,N/A' . "\n";
            }
        }

        $filename = 'Grades_' . $class->class_name . '_' . date('Y-m-d') . '.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Print student grades as PDF (CRC format)
     */
    public function printStudent($classId, $studentId)
    {
        $student = Student::with('user', 'class')->findOrFail($studentId);
        $class = ClassModel::findOrFail($classId);
        
        // Get all grades for this student in this class
        $grades = Grade::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->with('subject', 'teacher')
            ->get();

        // Calculate totals
        $totalAverage = $grades->count() > 0 ? round($grades->avg('grade_point'), 2) : 0;
        $midtermAvg = $grades->count() > 0 ? round($grades->avg('midterm_exam'), 2) : 0;
        $finalAvg = $grades->count() > 0 ? round($grades->avg('final_exam'), 2) : 0;

        $data = [
            'student' => $student,
            'class' => $class,
            'grades' => $grades,
            'totalAverage' => $totalAverage,
            'midtermAvg' => $midtermAvg,
            'finalAvg' => $finalAvg,
        ];

        $pdf = Pdf::loadView('admin.grades.pdf-student', $data);
        
        return $pdf->download($student->user->name . '_Grades.pdf');
    }
}
