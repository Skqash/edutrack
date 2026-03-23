<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceCalculationService
{
    /**
     * Calculate attendance score using formula: (attendance_count / total_meetings) × 50 + 50
     * 
     * @param int $studentId
     * @param int $classId
     * @param string $term 'Midterm' or 'Final'
     * @return array
     */
    public function calculateAttendanceScore($studentId, $classId, $term = 'Midterm')
    {
        $class = ClassModel::findOrFail($classId);
        
        // Get total meetings for the term
        $totalMeetings = $term === 'Midterm' 
            ? $class->total_meetings_midterm 
            : $class->total_meetings_final;
        
        // Count attendance records for this student in this class and term
        $attendanceRecords = Attendance::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->get();
        
        // Count different statuses
        $presentCount = $attendanceRecords->where('status', 'Present')->count();
        $lateCount = $attendanceRecords->where('status', 'Late')->count();
        $absentCount = $attendanceRecords->where('status', 'Absent')->count();
        $leaveCount = $attendanceRecords->where('status', 'Leave')->count();
        
        // Calculate attendance count (Present + Late count as attended)
        $attendanceCount = $presentCount + $lateCount;
        
        // Calculate attendance score using formula: (attendance_count / total_meetings) × 50 + 50
        $attendanceScore = 0;
        if ($totalMeetings > 0) {
            $attendanceScore = ($attendanceCount / $totalMeetings) * 50 + 50;
            // Cap at 100
            $attendanceScore = min(100, $attendanceScore);
        }
        
        // Calculate attendance percentage (for display)
        $attendancePercentage = $totalMeetings > 0 
            ? ($attendanceCount / $totalMeetings) * 100 
            : 0;
        
        return [
            'attendance_score' => round($attendanceScore, 2),
            'attendance_percentage' => round($attendancePercentage, 2),
            'attendance_count' => $attendanceCount,
            'total_meetings' => $totalMeetings,
            'present_count' => $presentCount,
            'late_count' => $lateCount,
            'absent_count' => $absentCount,
            'leave_count' => $leaveCount,
            'total_recorded' => $attendanceRecords->count(),
        ];
    }
    
    /**
     * Calculate weighted attendance contribution to final grade
     * 
     * @param int $studentId
     * @param int $classId
     * @param string $term
     * @return float
     */
    public function calculateAttendanceGradeContribution($studentId, $classId, $term = 'Midterm')
    {
        $class = ClassModel::findOrFail($classId);
        $attendanceData = $this->calculateAttendanceScore($studentId, $classId, $term);
        
        // Get attendance percentage weight (e.g., 10%)
        $attendanceWeight = $class->attendance_percentage / 100;
        
        // Calculate contribution: attendance_score × attendance_weight
        $contribution = $attendanceData['attendance_score'] * $attendanceWeight;
        
        return round($contribution, 2);
    }
    
    /**
     * Get attendance summary for all students in a class
     * 
     * @param int $classId
     * @param string $term
     * @return array
     */
    public function getClassAttendanceSummary($classId, $term = 'Midterm')
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $summary = [];
        
        foreach ($class->students as $student) {
            $attendanceData = $this->calculateAttendanceScore($student->id, $classId, $term);
            $summary[] = [
                'student_id' => $student->id,
                'student_name' => $student->user->name ?? 'N/A',
                'student_number' => $student->student_id,
                'attendance_data' => $attendanceData,
            ];
        }
        
        return $summary;
    }
    
    /**
     * Update student_attendance table with calculated scores
     * 
     * @param int $studentId
     * @param int $classId
     * @param string $term
     * @return void
     */
    public function updateStudentAttendanceRecord($studentId, $classId, $term = 'Midterm')
    {
        $attendanceData = $this->calculateAttendanceScore($studentId, $classId, $term);
        $class = ClassModel::find($classId);
        
        // Use subject_id from class, or default to 1 if not set
        $subjectId = $class->subject_id ?? 1;
        
        DB::table('student_attendance')->updateOrInsert(
            [
                'student_id' => $studentId,
                'class_id' => $classId,
                'term' => $term,
            ],
            [
                'subject_id' => $subjectId,
                'attendance_score' => $attendanceData['attendance_score'],
                'total_classes' => $attendanceData['total_meetings'],
                'present_classes' => $attendanceData['present_count'] + $attendanceData['late_count'],
                'absent_classes' => $attendanceData['absent_count'],
                'updated_at' => now(),
            ]
        );
    }
    
    /**
     * Bulk update attendance records for all students in a class
     * 
     * @param int $classId
     * @param string $term
     * @return int Number of records updated
     */
    public function bulkUpdateClassAttendance($classId, $term = 'Midterm')
    {
        $class = ClassModel::with('students')->findOrFail($classId);
        $updated = 0;
        
        foreach ($class->students as $student) {
            $this->updateStudentAttendanceRecord($student->id, $classId, $term);
            $updated++;
        }
        
        return $updated;
    }
}
