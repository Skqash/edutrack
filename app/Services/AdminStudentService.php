<?php

namespace App\Services;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminStudentService
{
    /**
     * Get filtered students with campus restrictions
     */
    public function getFilteredStudents(array $filters): LengthAwarePaginator
    {
        $query = Student::with(['course', 'school']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['campus'])) {
            $query->where('campus', $filters['campus']);
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    /**
     * Get student statistics for admin's campus
     */
    public function getStudentStatistics(?string $adminCampus): array
    {
        $baseQuery = Student::query();
        
        if ($adminCampus) {
            $baseQuery->where('campus', $adminCampus);
        }

        $totalStudents = $baseQuery->count();
        
        $activeStudents = (clone $baseQuery)->where('status', 'Active')->count();
        $inactiveStudents = (clone $baseQuery)->where('status', 'Inactive')->count();

        // Year level distribution
        $yearLevels = (clone $baseQuery)->selectRaw('year, COUNT(*) as count')
            ->groupBy('year')
            ->pluck('count', 'year')
            ->toArray();

        // Students with/without classes
        $withClasses = (clone $baseQuery)->whereNotNull('class_id')->count();
        $withoutClasses = (clone $baseQuery)->whereNull('class_id')->count();

        return [
            'total' => $totalStudents,
            'active' => $activeStudents,
            'inactive' => $inactiveStudents,
            'year_levels' => $yearLevels,
            'with_classes' => $withClasses,
            'without_classes' => $withoutClasses,
        ];
    }

    /**
     * Create new student
     */
    public function createStudent(array $data, ?string $adminCampus): Student
    {
        return DB::transaction(function () use ($data, $adminCampus) {
            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student',
                'phone' => $data['phone'] ?? null,
                'status' => 'Active',
                'campus' => $adminCampus, // Inherit admin's campus
            ]);

            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id'],
                'class_id' => $data['class_id'] ?? null,
                'year_level' => $data['year_level'],
                'address' => $data['address'] ?? null,
            ]);

            return $student;
        });
    }

    /**
     * Update student
     */
    public function updateStudent(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            // Update student record directly (no user relationship)
            $student->update([
                'student_id' => $data['student_id'],
                'first_name' => $data['first_name'] ?? $student->first_name,
                'middle_name' => $data['middle_name'] ?? $student->middle_name,
                'last_name' => $data['last_name'] ?? $student->last_name,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'course_id' => $data['course_id'],
                'class_id' => $data['class_id'] ?? null,
                'year' => $data['year'] ?? $data['year_level'] ?? $student->year,
                'section' => $data['section'] ?? $student->section,
                'address' => $data['address'] ?? null,
                'status' => $data['status'],
            ]);

            if (!empty($data['password'])) {
                $student->update(['password' => Hash::make($data['password'])]);
            }

            return $student->fresh();
        });
    }

    /**
     * Delete student
     */
    public function deleteStudent(Student $student): bool
    {
        return DB::transaction(function () use ($student) {
            // Delete related records
            Grade::where('student_id', $student->id)->delete();
            
            // Delete student record
            $student->delete();
            
            return true;
        });
    }

    /**
     * Get student detail statistics
     */
    public function getStudentDetailStatistics(Student $student): array
    {
        $grades = Grade::where('student_id', $student->id)->get();
        
        return [
            'total_grades' => $grades->count(),
            'average_grade' => $grades->where('final_grade', '>', 0)->avg('final_grade'),
            'highest_grade' => $grades->where('final_grade', '>', 0)->max('final_grade'),
            'lowest_grade' => $grades->where('final_grade', '>', 0)->min('final_grade'),
            'subjects_enrolled' => $grades->unique('subject_id')->count(),
            'attendance_rate' => $this->calculateAttendanceRate($student),
        ];
    }

    /**
     * Calculate attendance rate for student
     */
    private function calculateAttendanceRate(Student $student): float
    {
        $totalDays = DB::table('attendance')
            ->where('student_id', $student->id)
            ->count();

        if ($totalDays === 0) {
            return 0;
        }

        $presentDays = DB::table('attendance')
            ->where('student_id', $student->id)
            ->where('status', 'Present')
            ->count();

        return round(($presentDays / $totalDays) * 100, 2);
    }

    /**
     * Perform bulk actions on students
     */
    public function performBulkAction(string $action, array $studentIds, User $admin, ?int $classId = null): array
    {
        try {
            $adminCampus = $admin->campus;
            
            // Get students with campus restriction
            $students = Student::whereIn('id', $studentIds)
                ->when($adminCampus, function ($q) use ($adminCampus) {
                    $q->whereHas('course', fn($cq) => $cq->where('campus', $adminCampus));
                })
                ->get();

            if ($students->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No valid students found for this action.'
                ];
            }

            $count = 0;
            foreach ($students as $student) {
                switch ($action) {
                    case 'activate':
                        if ($student->status !== 'Active') {
                            $student->update(['status' => 'Active']);
                            $count++;
                        }
                        break;
                    
                    case 'deactivate':
                        if ($student->status !== 'Inactive') {
                            $student->update(['status' => 'Inactive']);
                            $count++;
                        }
                        break;
                    
                    case 'transfer_class':
                        if ($classId && $student->class_id !== $classId) {
                            // Verify class belongs to admin's campus
                            $class = ClassModel::find($classId);
                            if (!$adminCampus || $class->campus === $adminCampus) {
                                $student->update(['class_id' => $classId]);
                                $count++;
                            }
                        }
                        break;
                    
                    case 'delete':
                        $this->deleteStudent($student);
                        $count++;
                        break;
                }
            }

            return [
                'success' => true,
                'message' => "Successfully processed {$count} students."
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Transfer student to different class
     */
    public function transferStudent(Student $student, int $classId, int $adminId, ?string $reason = null): array
    {
        try {
            $oldClassId = $student->class_id;
            
            $student->update([
                'class_id' => $classId,
                'transfer_reason' => $reason,
                'transferred_by' => $adminId,
                'transferred_at' => now(),
            ]);

            // Log the transfer
            DB::table('student_transfers')->insert([
                'student_id' => $student->id,
                'from_class_id' => $oldClassId,
                'to_class_id' => $classId,
                'reason' => $reason,
                'transferred_by' => $adminId,
                'transferred_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Student transferred successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error transferring student: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Export students data
     */
    public function exportStudents(string $format, array $filters)
    {
        // This would implement CSV/Excel export functionality
        // For now, returning a placeholder response
        return response()->json([
            'success' => true,
            'message' => 'Export functionality to be implemented',
            'format' => $format,
            'filters' => $filters
        ]);
    }

    /**
     * Import students from file
     */
    public function importStudents($file, User $admin, int $courseId, ?int $classId = null): array
    {
        // This would implement CSV/Excel import functionality
        // For now, returning a placeholder response
        return [
            'success' => true,
            'message' => 'Import functionality to be implemented'
        ];
    }
}