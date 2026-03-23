<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\CourseAccessRequest;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Get teachers with campus isolation
        $teachers = User::where('role', 'teacher')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->paginate(20);
        
        $totalStudents = Student::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();
        $totalTeachers = User::where('role', 'teacher')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();
        $totalClasses = ClassModel::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();
        $totalSubjects = Subject::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();

        return view('admin.teachers.index', compact('teachers', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function campusApprovals()
    {
        $pendingTeachers = User::where('role', 'teacher')
            ->where('campus_status', 'pending')
            ->whereNotNull('campus')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTeachers = User::where('role', 'teacher')
            ->where('campus_status', 'approved')
            ->whereNotNull('campus')
            ->with('approvedBy')
            ->orderBy('campus_approved_at', 'desc')
            ->get();

        $rejectedTeachers = User::where('role', 'teacher')
            ->where('campus_status', 'rejected')
            ->whereNotNull('campus')
            ->orderBy('campus_approved_at', 'desc')
            ->get();

        $pendingCount = $pendingTeachers->count();
        $approvedCount = $approvedTeachers->count();
        $rejectedCount = $rejectedTeachers->count();

        return view('admin.teachers.campus_approvals', compact(
            'pendingTeachers',
            'approvedTeachers', 
            'rejectedTeachers',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function approveCampus($teacherId)
    {
        try {
            $teacher = User::findOrFail($teacherId);
            
            $teacher->update([
                'campus_status' => 'approved',
                'campus_approved_at' => now(),
                'campus_approved_by' => auth()->id(),
                'status' => 'Active' // Also activate the teacher account
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher campus affiliation approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving campus affiliation: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectCampus($teacherId)
    {
        try {
            $teacher = User::findOrFail($teacherId);
            
            $teacher->update([
                'campus_status' => 'rejected',
                'campus_approved_at' => now(),
                'campus_approved_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher campus affiliation rejected.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting campus affiliation: ' . $e->getMessage()
            ]);
        }
    }

    public function revokeCampus($teacherId)
    {
        try {
            $teacher = User::findOrFail($teacherId);
            
            $teacher->update([
                'campus_status' => 'pending',
                'campus_approved_at' => null,
                'campus_approved_by' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher campus affiliation revoked.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error revoking campus affiliation: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Get available schools/campuses for dropdown
        $schools = \App\Models\School::orderBy('school_name')->get();

        return view('admin.teachers.create', compact('schools', 'adminCampus', 'adminSchoolId'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'teacher';
        $validated['status'] = 'Active';
        
        // Inherit admin's campus and school
        if ($adminCampus) {
            $validated['campus'] = $adminCampus;
            $validated['campus_status'] = 'approved';
            $validated['campus_approved_at'] = now();
            $validated['campus_approved_by'] = $admin->id;
        }
        if ($adminSchoolId) {
            $validated['school_id'] = $adminSchoolId;
        }

        User::create($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully');
    }

    public function edit(User $teacher)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Ensure only teachers can be edited and campus isolation
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        // Check campus isolation
        if ($adminCampus && $teacher->campus !== $adminCampus) {
            abort(403, 'You can only edit teachers from your campus');
        }
        if ($adminSchoolId && $teacher->school_id !== $adminSchoolId) {
            abort(403, 'You can only edit teachers from your school');
        }

        $schools = \App\Models\School::orderBy('school_name')->get();

        return view('admin.teachers.edit', compact('teacher', 'schools', 'adminCampus', 'adminSchoolId'));
    }

    public function update(Request $request, User $teacher)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Ensure only teachers can be updated and campus isolation
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        // Check campus isolation
        if ($adminCampus && $teacher->campus !== $adminCampus) {
            abort(403, 'You can only edit teachers from your campus');
        }
        if ($adminSchoolId && $teacher->school_id !== $adminSchoolId) {
            abort(403, 'You can only edit teachers from your school');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$teacher->id,
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,'.$teacher->id,
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:100',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(User $teacher)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus ?? null;
        $adminSchoolId = $admin->school_id ?? null;

        // Ensure only teachers can be deleted and campus isolation
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        // Check campus isolation
        if ($adminCampus && $teacher->campus !== $adminCampus) {
            abort(403, 'You can only delete teachers from your campus');
        }
        if ($adminSchoolId && $teacher->school_id !== $adminSchoolId) {
            abort(403, 'You can only delete teachers from your school');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully');
    }

    /**
     * Show subjects assigned to a teacher
     */
    public function subjects(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $assignedSubjects = $teacher->subjects()->wherePivot('status', 'active')->with('course')->get();

        $pendingSubjects = $teacher->subjects()->wherePivot('status', 'pending')->with('course')->get();

        $availableSubjects = Subject::whereDoesntHave('teachers', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('course')->get();

        return view('admin.teachers.subjects', compact('teacher', 'assignedSubjects', 'availableSubjects', 'pendingSubjects'));
    }

    /**
     * Assign subjects to a teacher
     */
    public function assignSubjects(Request $request, User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        // Assign new subjects
        foreach ($validated['subject_ids'] as $subjectId) {
            $teacher->subjects()->attach($subjectId, [
                'status' => 'active',
                'assigned_at' => now(),
            ]);
        }

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subjects assigned successfully');
    }

    /**
     * Remove subject assignment from teacher
     */
    public function removeSubject(User $teacher, Subject $subject)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $teacher->subjects()->detach($subject->id);

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subject removed successfully');
    }

    /**
     * Approve pending subject request for teacher
     */
    public function approveSubject(User $teacher, Subject $subject)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $teacher->subjects()->updateExistingPivot($subject->id, [
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        \App\Models\Notification::create([
            'user_id' => $teacher->id,
            'title' => 'Subject Request Approved',
            'message' => "Your request to handle {$subject->subject_name} has been approved.",
            'type' => 'success',
            'icon' => 'check-circle',
            'action_url' => route('teacher.subjects'),
        ]);

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subject request approved and assigned');
    }

    /**
     * Reject pending subject request for teacher
     */
    public function rejectSubject(User $teacher, Subject $subject)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $teacher->subjects()->updateExistingPivot($subject->id, [
            'status' => 'inactive',
        ]);

        \App\Models\Notification::create([
            'user_id' => $teacher->id,
            'title' => 'Subject Request Rejected',
            'message' => "Your request to handle {$subject->subject_name} has been rejected. Please contact admin for details.",
            'type' => 'danger',
            'icon' => 'times-circle',
            'action_url' => route('teacher.subjects'),
        ]);

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subject request rejected');
    }

    /**
     * Show course access requests
     */
    public function courseAccessRequests()
    {
        $pendingRequests = CourseAccessRequest::where('status', 'pending')
            ->with(['teacher', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = CourseAccessRequest::where('status', 'approved')
            ->with(['teacher', 'course', 'approvedBy'])
            ->orderBy('approved_at', 'desc')
            ->get();

        $rejectedRequests = CourseAccessRequest::where('status', 'rejected')
            ->with(['teacher', 'course', 'approvedBy'])
            ->orderBy('approved_at', 'desc')
            ->get();

        $pendingCount = $pendingRequests->count();
        $approvedCount = $approvedRequests->count();
        $rejectedCount = $rejectedRequests->count();

        return view('admin.teachers.course_access_requests', compact(
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Approve course access request
     */
    public function approveCourseAccess($requestId)
    {
        try {
            $request = CourseAccessRequest::findOrFail($requestId);
            
            $request->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Notify teacher
            \App\Models\Notification::create([
                'user_id' => $request->teacher_id,
                'title' => 'Course Access Approved',
                'message' => "Your request for access to {$request->course->program_name} has been approved.",
                'type' => 'success',
                'icon' => 'check-circle',
                'action_url' => route('teacher.courses'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course access request approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving course access: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reject course access request
     */
    public function rejectCourseAccess(Request $request, $requestId)
    {
        try {
            $courseRequest = CourseAccessRequest::findOrFail($requestId);
            
            $courseRequest->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_note' => $request->input('admin_note'),
            ]);

            // Notify teacher
            \App\Models\Notification::create([
                'user_id' => $courseRequest->teacher_id,
                'title' => 'Course Access Rejected',
                'message' => "Your request for access to {$courseRequest->course->program_name} has been rejected." . 
                           ($request->input('admin_note') ? " Reason: " . $request->input('admin_note') : ''),
                'type' => 'danger',
                'icon' => 'times-circle',
                'action_url' => route('teacher.courses'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course access request rejected.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting course access: ' . $e->getMessage()
            ]);
        }
    }
}
