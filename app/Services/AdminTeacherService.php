<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminTeacherService
{
    /**
     * Get filtered teachers with campus restrictions
     */
    public function getFilteredTeachers(array $filters): LengthAwarePaginator
    {
        $query = Teacher::with(['subjects', 'classes', 'user']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['campus'])) {
            // Query through user relationship for campus
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('campus', $filters['campus']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['approval_status'])) {
            // Query through user relationship for campus_status
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('campus_status', $filters['approval_status']);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    /**
     * Get teacher statistics for admin's campus
     */
    public function getTeacherStatistics(?string $adminCampus): array
    {
        // Query users with role='teacher' since campus_status is in users table
        $baseQuery = User::where('role', 'teacher');
        
        if ($adminCampus) {
            $baseQuery->where('campus', $adminCampus);
        }

        return [
            'total' => $baseQuery->count(),
            'active' => (clone $baseQuery)->where('status', 'Active')->count(),
            'inactive' => (clone $baseQuery)->where('status', 'Inactive')->count(),
            'approved' => (clone $baseQuery)->where('campus_status', 'approved')->count(),
            'pending' => (clone $baseQuery)->where('campus_status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('campus_status', 'rejected')->count(),
            'independent' => User::where('role', 'teacher')->whereNull('campus')->count(),
        ];
    }

    /**
     * Get available campuses for admin
     */
    public function getAvailableCampuses(?string $adminCampus): array
    {
        if ($adminCampus) {
            return [$adminCampus];
        }

        // Super admin can see all campuses
        return [
            'CPSU - Bayambang Campus',
            'CPSU - Binalonan Campus', 
            'CPSU - Infanta Campus',
            'CPSU - San Carlos Campus',
            'CPSU - San Quintin Campus',
            'CPSU - Sta. Maria Campus',
            'CPSU - Tarlac Campus',
            'CPSU - Urdaneta Campus'
        ];
    }

    /**
     * Create new teacher
     */
    public function createTeacher(array $data, int $adminId): Teacher
    {
        // Split name into first and last
        $nameParts = explode(' ', $data['name'] ?? '', 2);
        $teacherData = [
            'first_name' => $nameParts[0] ?? '',
            'last_name' => $nameParts[1] ?? '',
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'school_id' => $data['school_id'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'department' => $data['department'] ?? null,
            'status' => 'Active',
        ];

        return Teacher::create($teacherData);
    }

    /**
     * Update teacher
     */
    public function updateTeacher(Teacher $teacher, array $data): Teacher
    {
        // Split name into first and last if provided
        $updateData = [];
        if (isset($data['name'])) {
            $nameParts = explode(' ', $data['name'], 2);
            $updateData['first_name'] = $nameParts[0] ?? '';
            $updateData['last_name'] = $nameParts[1] ?? '';
        }
        
        $updateData = array_merge($updateData, [
            'email' => $data['email'],
            'school_id' => $data['school_id'] ?? null,
            'phone' => $data['phone'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'department' => $data['department'] ?? null,
            'status' => $data['status'],
        ]);

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $teacher->update($updateData);
        return $teacher->fresh();
    }

    /**
     * Delete teacher
     */
    public function deleteTeacher(Teacher $teacher): bool
    {
        return DB::transaction(function () use ($teacher) {
            // Remove subject assignments
            $teacher->subjects()->detach();
            
            // Remove course access requests
            CourseAccessRequest::where('teacher_id', $teacher->id)->delete();
            
            // Delete teacher
            return $teacher->delete();
        });
    }

    /**
     * Get campus approvals for admin's campus
     */
    public function getCampusApprovals(?string $adminCampus): array
    {
        // Query through user relationship since campus_status is in users table
        $baseQuery = Teacher::with('user')->whereHas('user', function($q) {
            $q->whereNotNull('campus');
        });

        if ($adminCampus) {
            $baseQuery->whereHas('user', function($q) use ($adminCampus) {
                $q->where('campus', $adminCampus);
            });
        }

        $pendingTeachers = (clone $baseQuery)
            ->whereHas('user', function($q) {
                $q->where('campus_status', 'pending');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTeachers = (clone $baseQuery)
            ->whereHas('user', function($q) {
                $q->where('campus_status', 'approved');
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $rejectedTeachers = (clone $baseQuery)
            ->whereHas('user', function($q) {
                $q->where('campus_status', 'rejected');
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'pendingTeachers' => $pendingTeachers,
            'approvedTeachers' => $approvedTeachers,
            'rejectedTeachers' => $rejectedTeachers,
            'pendingCount' => $pendingTeachers->count(),
            'approvedCount' => $approvedTeachers->count(),
            'rejectedCount' => $rejectedTeachers->count(),
        ];
    }

    /**
     * Approve campus affiliation
     */
    public function approveCampusAffiliation(Teacher $teacher, int $adminId): array
    {
        try {
            // Update the user record, not the teacher record
            if ($teacher->user) {
                $teacher->user->update([
                    'campus_status' => 'approved',
                    'campus_approved_at' => now(),
                    'campus_approved_by' => $adminId,
                    'status' => 'Active'
                ]);
            }
            
            // Also update teacher status
            $teacher->update([
                'status' => 'Active'
            ]);

            return [
                'success' => true,
                'message' => 'Teacher campus affiliation approved successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error approving campus affiliation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reject campus affiliation
     */
    public function rejectCampusAffiliation(Teacher $teacher, int $adminId, ?string $reason = null): array
    {
        try {
            // Update the user record, not the teacher record
            if ($teacher->user) {
                $teacher->user->update([
                    'campus_status' => 'rejected',
                    'campus_approved_at' => now(),
                    'campus_approved_by' => $adminId,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Teacher campus affiliation rejected.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error rejecting campus affiliation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Revoke campus affiliation
     */
    public function revokeCampusAffiliation(Teacher $teacher): array
    {
        try {
            // Update the user record, not the teacher record
            if ($teacher->user) {
                $teacher->user->update([
                    'campus_status' => 'pending',
                    'campus_approved_at' => null,
                    'campus_approved_by' => null,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Teacher campus affiliation revoked.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error revoking campus affiliation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get course access requests for admin's campus
     */
    public function getCourseAccessRequests(?string $adminCampus): array
    {
        $baseQuery = CourseAccessRequest::with(['teacher', 'course', 'approvedBy']);

        if ($adminCampus) {
            $baseQuery->whereHas('teacher', function ($q) use ($adminCampus) {
                $q->where('campus', $adminCampus);
            });
        }

        $pendingRequests = (clone $baseQuery)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = (clone $baseQuery)
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->limit(20)
            ->get();

        $rejectedRequests = (clone $baseQuery)
            ->where('status', 'rejected')
            ->orderBy('approved_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'pendingCount' => $pendingRequests->count(),
            'approvedCount' => $approvedRequests->count(),
            'rejectedCount' => $rejectedRequests->count(),
        ];
    }

    /**
     * Approve course access request
     */
    public function approveCourseAccess(CourseAccessRequest $request, int $adminId): array
    {
        try {
            $request->update([
                'status' => 'approved',
                'approved_by' => $adminId,
                'approved_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Course access request approved successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error approving course access: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reject course access request
     */
    public function rejectCourseAccess(CourseAccessRequest $request, int $adminId, ?string $adminNote = null): array
    {
        try {
            $request->update([
                'status' => 'rejected',
                'approved_by' => $adminId,
                'approved_at' => now(),
                'admin_note' => $adminNote,
            ]);

            return [
                'success' => true,
                'message' => 'Course access request rejected.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error rejecting course access: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get teacher detail statistics
     */
    public function getTeacherDetailStatistics(Teacher $teacher): array
    {
        return [
            'total_classes' => $teacher->classes()->count(),
            'total_students' => $teacher->classes()->withCount('students')->get()->sum('students_count'),
            'total_subjects' => $teacher->subjects()->count(),
            'pending_course_requests' => CourseAccessRequest::where('teacher_id', $teacher->id)
                ->where('status', 'pending')
                ->count(),
            'approved_course_requests' => CourseAccessRequest::where('teacher_id', $teacher->id)
                ->where('status', 'approved')
                ->count(),
        ];
    }

    /**
     * Get teacher subjects with campus filtering
     */
    public function getTeacherSubjects(Teacher $teacher, ?string $adminCampus): array
    {
        $assignedSubjects = $teacher->subjects()
            ->wherePivot('status', 'active')
            ->with('course')
            ->when($adminCampus, function ($q) use ($adminCampus) {
                $q->where('campus', $adminCampus);
            })
            ->get();

        $pendingSubjects = $teacher->subjects()
            ->wherePivot('status', 'pending')
            ->with('course')
            ->when($adminCampus, function ($q) use ($adminCampus) {
                $q->where('campus', $adminCampus);
            })
            ->get();

        $availableSubjects = Subject::whereDoesntHave('teachers', function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with('course')
            ->when($adminCampus, function ($q) use ($adminCampus) {
                $q->where('campus', $adminCampus);
            })
            ->get();

        return [
            'assignedSubjects' => $assignedSubjects,
            'pendingSubjects' => $pendingSubjects,
            'availableSubjects' => $availableSubjects,
        ];
    }

    /**
     * Assign subjects to teacher
     */
    public function assignSubjects(Teacher $teacher, array $subjectIds, ?string $adminCampus): array
    {
        try {
            // Verify subjects belong to admin's campus
            if ($adminCampus) {
                $validSubjects = Subject::whereIn('id', $subjectIds)
                    ->where('campus', $adminCampus)
                    ->pluck('id')
                    ->toArray();
                
                if (count($validSubjects) !== count($subjectIds)) {
                    return [
                        'success' => false,
                        'message' => 'Some subjects do not belong to your campus.'
                    ];
                }
            }

            // Assign subjects
            foreach ($subjectIds as $subjectId) {
                $teacher->subjects()->attach($subjectId, [
                    'status' => 'active',
                    'assigned_at' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Subjects assigned successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error assigning subjects: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove subject from teacher
     */
    public function removeSubject(Teacher $teacher, Subject $subject): array
    {
        try {
            $teacher->subjects()->detach($subject->id);

            return [
                'success' => true,
                'message' => 'Subject removed successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error removing subject: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Perform bulk actions on teachers
     */
    public function performBulkAction(string $action, array $teacherIds, ?string $adminCampus = null, ?string $reason = null): array
    {
        try {
            // Get teachers with campus restriction - load user relationship
            $teachers = Teacher::with('user')
                ->whereIn('id', $teacherIds)
                ->when($adminCampus, function ($q) use ($adminCampus) {
                    $q->whereHas('user', function($uq) use ($adminCampus) {
                        $uq->where('campus', $adminCampus);
                    });
                })
                ->get();

            if ($teachers->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No valid teachers found for this action.'
                ];
            }

            $count = 0;
            foreach ($teachers as $teacher) {
                switch ($action) {
                    case 'approve_campus':
                        if ($teacher->user && $teacher->user->campus_status === 'pending') {
                            $teacher->user->update([
                                'campus_status' => 'approved',
                                'campus_approved_at' => now(),
                                'campus_approved_by' => auth()->id(),
                                'status' => 'Active'
                            ]);
                            $teacher->update(['status' => 'Active']);
                            $count++;
                        }
                        break;
                    
                    case 'reject_campus':
                        if ($teacher->user && $teacher->user->campus_status === 'pending') {
                            $teacher->user->update([
                                'campus_status' => 'rejected',
                                'campus_approved_at' => now(),
                                'campus_approved_by' => auth()->id(),
                            ]);
                            $count++;
                        }
                        break;
                    
                    case 'activate':
                        if ($teacher->status !== 'Active') {
                            $teacher->update(['status' => 'Active']);
                            if ($teacher->user) {
                                $teacher->user->update(['status' => 'Active']);
                            }
                            $count++;
                        }
                        break;
                    
                    case 'deactivate':
                        if ($teacher->status !== 'Inactive') {
                            $teacher->update(['status' => 'Inactive']);
                            if ($teacher->user) {
                                $teacher->user->update(['status' => 'Inactive']);
                            }
                            $count++;
                        }
                        break;
                    
                    case 'delete':
                        $this->deleteTeacher($teacher);
                        $count++;
                        break;
                }
            }

            return [
                'success' => true,
                'message' => "Successfully processed {$count} teachers."
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Export teachers data
     */
    public function exportTeachers(string $format, array $filters)
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
     * Import teachers from file
     */
    public function importTeachers($file, User $admin, bool $autoApprove = false): array
    {
        // This would implement CSV/Excel import functionality
        // For now, returning a placeholder response
        return [
            'success' => true,
            'message' => 'Import functionality to be implemented'
        ];
    }
}