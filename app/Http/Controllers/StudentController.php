<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;

class StudentController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        $attendance = $student->attendance()
            ->with('class')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $grades = $student->grades()
            ->with('class')
            ->latest()
            ->limit(10)
            ->get();

        // Calculate attendance statistics
        $totalAttendance = $student->attendance()->count();
        $presentDays = $student->attendance()->where('status', 'present')->count();
        $attendancePercentage = $totalAttendance > 0 ? round(($presentDays / $totalAttendance) * 100, 2) : 0;

        // Calculate average grade (not GPA - Philippines uses grades)
        $allGrades = $student->grades()->pluck('grade');
        $averageGrade = $allGrades->count() > 0 ? round($allGrades->sum() / $allGrades->count(), 2) : 0;

        return view('student.dashboard', compact('student', 'attendance', 'grades', 'attendancePercentage', 'averageGrade'));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
        ]);

        $student->update($request->only(['phone', 'address', 'gender']));

        /** @var User $user */
        $user = Auth::user();
        if ($request->filled('phone') && $user->phone !== $request->phone) {
            $user->update(['phone' => $request->phone]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update e-signature
     */
    public function updateESignature(Request $request)
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        try {
            // The signature comes as a data URL (e.g., data:image/png;base64,...)
            // Store it as-is or process if needed
            $signature = $request->signature;
            
            $student->update(['e_signature' => $signature]);

            return response()->json([
                'success' => true,
                'message' => 'E-signature saved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save e-signature: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get student attendance with detailed statistics
     */
    public function getAttendance()
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        // Paginated attendance records
        $records = $student->attendance()
            ->with(['class', 'teacher'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        // Calculate statistics
        $total = $student->attendance()->count();
        $present = $student->attendance()->where('status', 'present')->count();
        $absent = $student->attendance()->where('status', 'absent')->count();
        $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

        // Attendance by class (grouped statistics)
        $attendanceByClass = null;
        // This would require a relationship or custom query based on your schema

        return view('student.attendance', compact('records', 'total', 'present', 'absent', 'percentage', 'attendanceByClass'));
    }

    /**
     * Get student grades with filtering
     */
    public function getGrades()
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        // Paginated grade records
        $records = $student->grades()
            ->with(['class'])
            ->latest()
            ->paginate(20);

        // Calculate statistics
        $totalGrades = $student->grades()->count();
        $passing = $student->grades()->where('grade', '>=', 75)->count();
        $belowAverage = $student->grades()->where('grade', '<', 75)->count();
        
        // Calculate average grade (not GPA - Philippines uses grades)
        $allGrades = $student->grades()->pluck('grade');
        $averageGrade = $allGrades->count() > 0 ? round($allGrades->sum() / $allGrades->count(), 2) : 0;

        // Get unique classes for filter
        $classes = $student->grades()
            ->with('class')
            ->distinct('class_id')
            ->pluck('class_id')
            ->unique();

        // Grades by class (grouped statistics)
        $gradesByClass = null;

        return view('student.grades', compact('records', 'averageGrade', 'totalGrades', 'passing', 'belowAverage', 'classes', 'gradesByClass'));
    }

    /**
     * Show e-signature form
     */
    public function showSignatureForm()
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return redirect('/login');
        }

        return view('student.signature-form', compact('student'));
    }

    /**
     * Update student e-signature (AJAX)
     */
    public function updateSignature(Request $request)
    {
        $student = $this->getStudentProfile();

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'signature_data' => 'required|string',
        ]);

        try {
            // The signature comes as a data URL (e.g., data:image/png;base64,...)
            $student->update(['e_signature' => $request->signature_data]);

            return response()->json([
                'success' => true,
                'message' => 'E-signature saved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save e-signature: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Change student password
     */
    public function changePassword(Request $request)
    {
        $student = $this->getStudentProfile();
        /** @var User $user */
        $user = Auth::user();

        if (! $student || ! $user || $user->role !== 'student') {
            return redirect('/login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        try {
            $hashedPassword = Hash::make($request->new_password);
            $user->update(['password' => $hashedPassword]);

            if ($student) {
                $student->update(['password' => $hashedPassword]);
            }

            return back()->with('success', 'Password changed successfully. Please log in again.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    /**
     * Get the authenticated student's profile record.
     *
     * @return Student|null
     */
    private function getStudentProfile()
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'student') {
            return null;
        }

        $student = $user->student;

        if (! $student) {
            [$firstName, $lastName] = $this->splitName($user->name);
            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => 'STU' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'school_id' => $user->school_id,
                    'status' => $user->status ?? 'Active',
                ]
            );
        }

        return $student;
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name));
        $firstName = $parts[0] ?? '';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : $firstName;

        return [$firstName, $lastName];
    }
}
