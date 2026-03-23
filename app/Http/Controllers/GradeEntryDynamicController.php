<?php

namespace App\Http\Controllers;

use App\Models\AssessmentComponent;
use App\Models\ClassModel;
use App\Models\ComponentEntry;
use App\Models\ComponentAverage;
use App\Models\GradeEntry;
use App\Models\KsaSetting;
use App\Models\AssessmentRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GradeEntryDynamicController extends Controller
{
    /**
     * Show dynamic grade entry form
     */
    public function show($classId, $term = 'midterm')
    {
        $teacherId = Auth::id();
        
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->firstOrFail();

        $students = $class->students()->get();

        // Fetch assessment components grouped by category
        $componentsCollection = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();
        $components = $componentsCollection->groupBy('category');

        // Fetch KSA settings for this class and term
        $ksaSettings = KsaSetting::where('class_id', $classId)
            ->where('term', $term)
            ->first();

        // Create default KSA settings if none exist
        if (!$ksaSettings) {
            $ksaSettings = (object)[
                'knowledge_percentage' => 40,
                'skills_percentage' => 50,
                'attitude_percentage' => 10,
            ];
        }

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

        return view('teacher.grades.grade_entry', compact('class', 'students', 'term', 'components', 'ksaSettings', 'range', 'entries'));
    }

    /**
     * Get students for a class as JSON
     */
    public function getStudents($classId)
    {
        $teacherId = Auth::id();

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $students = $class->students()
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'student_no' => $student->student_id,
                    'full_name' => $student->name,
                    'name' => $student->name,
                ];
            });

        return response()->json($students);
    }

    /**
     * Get entries for a class/term
     */
    public function getEntries($classId)
    {
        $teacherId = Auth::id();
        $term = request('term', 'midterm');

        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $entries = ComponentEntry::where('class_id', $classId)
            ->where('term', $term)
            ->get()
            ->groupBy('student_id');

        return response()->json($entries);
    }

    /**
     * Save multiple component entries for a student
     */
    public function saveEntries(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->input('term', 'midterm');

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'entries' => 'required|array',
            'entries.*' => 'array', // Each student's entries
        ]);

        $saved = 0;
        $errors = [];

        foreach ($validated['entries'] as $studentId => $components) {
            try {
                foreach ($components as $componentId => $rawScore) {
                    ComponentEntry::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'class_id' => $classId,
                            'component_id' => $componentId,
                            'term' => $term,
                        ],
                        [
                            'raw_score' => floatval($rawScore),
                        ]
                    );
                    $saved++;
                }

                // Recalculate averages for this student
                ComponentAverage::calculateAndUpdate($studentId, $classId, $term);
            } catch (\Exception $e) {
                Log::error("Error saving entries for student {$studentId}: {$e->getMessage()}");
                $errors[] = "Student {$studentId}: {$e->getMessage()}";
            }
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
            'errors' => $errors,
            'message' => "✅ Saved {$saved} component entries"
        ]);
    }

    /**
     * Get calculated averages for all students in a class
     */
    public function getAverages($classId)
    {
        $teacherId = Auth::id();
        $term = request('term', 'midterm');

        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $averages = ComponentAverage::where('class_id', $classId)
            ->where('term', $term)
            ->with('student')
            ->get();

        return response()->json([
            'success' => true,
            'averages' => $averages,
        ]);
    }

    /**
     * Get a single student's entry details
     */
    public function getStudentEntries($classId, $studentId)
    {
        $teacherId = Auth::id();
        $term = request('term', 'midterm');

        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $entries = ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->with('component')
            ->get();

        $average = ComponentAverage::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->first();

        return response()->json([
            'success' => true,
            'entries' => $entries,
            'average' => $average,
        ]);
    }

    /**
     * Delete a component entry
     */
    public function deleteEntry($classId, $entryId)
    {
        $teacherId = Auth::id();

        $entry = ComponentEntry::with('component')
            ->findOrFail($entryId);

        // Verify the entry belongs to a class owned by this teacher
        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $studentId = $entry->student_id;
        $term = $entry->term;

        $entry->delete();

        // Recalculate averages
        ComponentAverage::calculateAndUpdate($studentId, $classId, $term);

        return response()->json([
            'success' => true,
            'message' => 'Entry deleted successfully',
        ]);
    }

    /**
     * Bulk delete all entries for a student
     */
    public function deleteStudentEntries($classId, $studentId)
    {
        $teacherId = Auth::id();
        $term = request('term', 'midterm');

        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        ComponentEntry::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->delete();

        // Clear averages
        ComponentAverage::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All entries deleted for this student',
        ]);
    }
}
