<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSignature;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceSignatureController extends Controller
{
    /**
     * Display signatures for a class/term
     */
    public function index(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = $request->input('term', 'midterm');
        $status = $request->input('status', null);

        $query = AttendanceSignature::where('class_id', $classId)
            ->where('term', $term);

        if ($status) {
            $query->where('status', $status);
        }

        $signatures = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->count(),
            'pending' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'pending')->count(),
            'approved' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'approved')->count(),
            'rejected' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'rejected')->count(),
        ];

        return view('teacher.grades.attendance-signatures.index', compact('class', 'signatures', 'term', 'stats'));
    }

    /**
     * Show upload form for e-signature
     */
    public function create(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = $request->input('term', 'midterm');

        // Get students without signatures for this term
        $studentsWithoutSignatures = Student::where('class_id', $classId)
            ->whereDoesntHave('attendanceSignatures', function ($query) use ($classId, $term) {
                $query->where('class_id', $classId)
                    ->where('term', $term)
                    ->where('status', 'approved');
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('teacher.grades.attendance-signatures.upload', compact('class', 'term', 'studentsWithoutSignatures'));
    }

    /**
     * Store uploaded e-signature
     */
    public function store(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|in:midterm,final,general',
            'signature_type' => 'required|in:digital,upload,pen-based',
            'signature_data' => 'required_if:signature_type,digital|nullable|string',
            'signature_file' => 'required_if:signature_type,upload|nullable|file|mimes:png,jpg,jpeg,pdf|max:5000',
            'signed_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        if ($student->class_id != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $signatureData = null;
        $mimeType = null;
        $filename = null;
        $size = null;

        if ($validated['signature_type'] === 'digital' && $validated['signature_data']) {
            // Store base64 encoded digital signature
            $signatureData = $validated['signature_data'];
            $mimeType = 'image/png';
        } elseif ($validated['signature_type'] === 'upload' && $request->hasFile('signature_file')) {
            // Store uploaded file
            $file = $request->file('signature_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->store('attendance-signatures/' . $classId, 'public');
            
            $signatureData = $path;
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
        }

        if (!$signatureData) {
            return response()->json(['error' => 'No signature data provided'], 400);
        }

        // Check for existing signature
        $existing = AttendanceSignature::where('student_id', $student->id)
            ->where('class_id', $classId)
            ->where('term', $validated['term'])
            ->first();

        if ($existing && $existing->status === 'approved') {
            return response()->json([
                'error' => 'Approved signature already exists for this term',
                'code' => 'signature_exists'
            ], 409);
        }

        $signature = AttendanceSignature::updateOrCreate(
            [
                'student_id' => $student->getAttribute('id'),
                'class_id' => $classId,
                'term' => $validated['term'],
            ],
            [
                'teacher_id' => auth()->user()->getAttribute('id'),
                'signature_type' => $validated['signature_type'],
                'signature_data' => $signatureData,
                'signature_filename' => $filename,
                'signature_mime_type' => $mimeType,
                'signature_size' => $size,
                'signed_date' => $validated['signed_date'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'pending',
                'is_verified' => false,
            ]
        );

        // For admin-uploaded signatures, auto-approve
        if (auth()->user()->role === 'admin') {
            $signature->approve(auth()->id(), 'Auto-approved by admin');
        }

        return response()->json([
            'success' => true,
            'message' => 'Signature uploaded successfully',
            'signature' => [
                'id' => $signature->id,
                'student_name' => $student->full_name,
                'status' => $signature->status,
            ]
        ]);
    }

    /**
     * Display single signature
     */
    public function show($classId, $signatureId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $signature = AttendanceSignature::findOrFail($signatureId);
        if ($signature->class_id != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return view('teacher.grades.attendance-signatures.show', compact('class', 'signature'));
    }

    /**
     * Approve signature (admin only)
     */
    public function approve(Request $request, $classId, $signatureId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $signature = AttendanceSignature::findOrFail($signatureId);
        if ($signature->getAttribute('class_id') != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        $signature->approve(auth()->user()->getAttribute('id'), $validated['remarks'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Signature approved successfully',
        ]);
    }

    /**
     * Reject signature (admin only)
     */
    public function reject(Request $request, $classId, $signatureId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $signature = AttendanceSignature::findOrFail($signatureId);
        if ($signature->getAttribute('class_id') != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'remarks' => 'required|string|max:500',
        ]);

        $signature->reject(auth()->user()->getAttribute('id'), $validated['remarks']);

        return response()->json([
            'success' => true,
            'message' => 'Signature rejected',
        ]);
    }

    /**
     * Delete signature
     */
    public function destroy($classId, $signatureId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $signature = AttendanceSignature::findOrFail($signatureId);
        if ($signature->class_id != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete file if stored
        if ($signature->signature_type === 'upload' && $signature->signature_data) {
            Storage::disk('public')->delete($signature->signature_data);
        }

        $signature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Signature deleted successfully',
        ]);
    }

    /**
     * Get signatures statistics
     */
    public function getStatistics($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = request()->input('term', 'midterm');

        $stats = [
            'total' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->count(),
            'pending' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'pending')->count(),
            'approved' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'approved')->count(),
            'rejected' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'rejected')->count(),
            'archived' => AttendanceSignature::where('class_id', $classId)->where('term', $term)->where('status', 'archived')->count(),
        ];

        $totalStudents = Student::where('class_id', $classId)->count();
        $stats['coverage_percentage'] = $totalStudents > 0 ? round(($stats['approved'] / $totalStudents) * 100, 2) : 0;

        return response()->json($stats);
    }
}
