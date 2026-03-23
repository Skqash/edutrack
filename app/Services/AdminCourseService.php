<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminCourseService
{
    /**
     * Get filtered courses with campus restrictions
     */
    public function getFilteredCourses(array $filters): LengthAwarePaginator
    {
        $query = Course::with(['head', 'department']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('program_name', 'like', "%{$search}%")
                  ->orWhere('program_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['campus'])) {
            $query->where('campus', $filters['campus']);
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('program_name')->paginate(20);
    }

    /**
     * Get course statistics for admin's campus
     */
    public function getCourseStatistics(?string $adminCampus): array
    {
        $baseQuery = Course::query();
        
        if ($adminCampus) {
            $baseQuery->where('campus', $adminCampus);
        }

        $totalCourses = $baseQuery->count();
        
        $activeCourses = (clone $baseQuery)->where('status', 'Active')->count();
        $inactiveCourses = (clone $baseQuery)->where('status', 'Inactive')->count();

        // Courses with/without heads
        $withHeads = (clone $baseQuery)->whereNotNull('program_head_id')->count();
        $withoutHeads = (clone $baseQuery)->whereNull('program_head_id')->count();

        // Department distribution
        $departmentDistribution = (clone $baseQuery)
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->selectRaw('departments.department_name, COUNT(*) as count')
            ->groupBy('departments.id', 'departments.department_name')
            ->pluck('count', 'department_name')
            ->toArray();

        return [
            'total' => $totalCourses,
            'active' => $activeCourses,
            'inactive' => $inactiveCourses,
            'with_heads' => $withHeads,
            'without_heads' => $withoutHeads,
            'department_distribution' => $departmentDistribution,
        ];
    }

    /**
     * Create new course
     */
    public function createCourse(array $data, ?string $adminCampus): Course
    {
        $courseData = [
            'program_code' => $data['program_code'],
            'program_name' => $data['program_name'],
            'department' => $data['department_id'], // Store department name
            'program_head_id' => $data['program_head_id'] ?? null,
            'total_years' => $data['total_years'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'campus' => $adminCampus, // Inherit admin's campus
        ];

        return Course::create($courseData);
    }

    /**
     * Update course
     */
    public function updateCourse(Course $course, array $data): Course
    {
        $course->update([
            'program_code' => $data['program_code'],
            'program_name' => $data['program_name'],
            'department' => $data['department_id'], // Store department name
            'program_head_id' => $data['program_head_id'] ?? null,
            'total_years' => $data['total_years'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return $course->fresh();
    }

    /**
     * Delete course
     */
    public function deleteCourse(Course $course): array
    {
        // Check if course has subjects
        if ($course->subjects()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Cannot delete course with existing subjects. Please remove subjects first.'
            ];
        }

        // Check if course has students
        if ($course->students()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Cannot delete course with enrolled students. Please transfer students first.'
            ];
        }

        // Check if course has classes
        if ($course->classes()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Cannot delete course with existing classes. Please remove classes first.'
            ];
        }

        return DB::transaction(function () use ($course) {
            $course->delete();
            
            return [
                'success' => true,
                'message' => 'Course deleted successfully.'
            ];
        });
    }

    /**
     * Get course detail statistics
     */
    public function getCourseDetailStatistics(Course $course): array
    {
        return [
            'total_subjects' => $course->subjects()->count(),
            'total_classes' => $course->classes()->count(),
            'total_students' => $course->students()->count(),
            'active_classes' => $course->classes()->where('status', 'Active')->count(),
            'subjects_by_year' => $course->subjects()
                ->selectRaw('year_level, COUNT(*) as count')
                ->groupBy('year_level')
                ->pluck('count', 'year_level')
                ->toArray(),
        ];
    }

    /**
     * Get course subjects with campus filtering
     */
    public function getCourseSubjects(Course $course, ?string $adminCampus): array
    {
        $assignedSubjects = $course->subjects()
            ->with('instructor')
            ->orderBy('year_level')
            ->orderBy('subject_name')
            ->get();

        $availableSubjects = Subject::whereNull('program_id')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('subject_name')
            ->get();

        return [
            'assigned' => $assignedSubjects,
            'available' => $availableSubjects,
        ];
    }

    /**
     * Perform bulk actions on courses
     */
    public function performBulkAction(string $action, array $courseIds, User $admin): array
    {
        try {
            $adminCampus = $admin->campus;
            
            // Get courses with campus restriction
            $courses = Course::whereIn('id', $courseIds)
                ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
                ->get();

            if ($courses->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No valid courses found for this action.'
                ];
            }

            $count = 0;
            $errors = [];

            foreach ($courses as $course) {
                try {
                    switch ($action) {
                        case 'activate':
                            if ($course->status !== 'Active') {
                                $course->update(['status' => 'Active']);
                                $count++;
                            }
                            break;
                        
                        case 'deactivate':
                            if ($course->status !== 'Inactive') {
                                $course->update(['status' => 'Inactive']);
                                $count++;
                            }
                            break;
                        
                        case 'delete':
                            $result = $this->deleteCourse($course);
                            if ($result['success']) {
                                $count++;
                            } else {
                                $errors[] = "Course '{$course->program_name}': {$result['message']}";
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Course '{$course->program_name}': {$e->getMessage()}";
                }
            }

            $message = "Successfully processed {$count} courses.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return [
                'success' => true,
                'message' => $message,
                'processed' => $count,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Export courses data
     */
    public function exportCourses(string $format, array $filters)
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
}