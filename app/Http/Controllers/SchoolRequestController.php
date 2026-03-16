<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\SchoolRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\NewSchoolConnectionRequest;
use App\Notifications\SchoolRequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class SchoolRequestController extends Controller
{
    /**
     * Show form for teacher to request school connection.
     */
    public function create()
    {
        $user = Auth::user();
        $existing = SchoolRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('teacher.school_request.create', [
            'existing' => $existing,
        ]);
    }

    /**
     * Teacher: view the full history of their school requests.
     */
    public function history()
    {
        $user = Auth::user();
        $requests = SchoolRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.school_request.history', compact('requests'));
    }

    /**
     * Store a new school connection request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_email' => 'nullable|email|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_address' => 'nullable|string|max:500',
            'note' => 'nullable|string|max:1000',
        ]);

        $schoolRequest = SchoolRequest::create([
            'user_id' => $user->id,
            'school_name' => $validated['school_name'],
            'school_email' => $validated['school_email'] ?? null,
            'school_phone' => $validated['school_phone'] ?? null,
            'school_address' => $validated['school_address'] ?? null,
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

        // Notify all admins about the new school connection request
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewSchoolConnectionRequest($schoolRequest));
        }

        // Keep admin dashboard stats accurate
        DashboardController::clearCache();

        return redirect()->route('teacher.school-request.create')
            ->with('success', 'School connection request submitted. Admin will review it shortly.');
    }

    /**
     * Admin: list all school requests.
     */
    public function index()
    {
        $requests = SchoolRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.school_requests.index', compact('requests'));
    }

    /**
     * Admin: view a single request.
     */
    public function show(SchoolRequest $schoolRequest)
    {
        $schoolRequest->load('user');
        return view('admin.school_requests.show', compact('schoolRequest'));
    }

    /**
     * Admin: update request status.
     */
    public function update(Request $request, SchoolRequest $schoolRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:1000',
            'connected_school' => 'nullable|string|max:255',
        ]);

        // Store previous status to determine if we should notify the teacher
        $previousStatus = $schoolRequest->status;

        $schoolRequest->update($validated);

        // If approved, update the teacher's connected school (admin can choose which school to assign)
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $schoolRequest->user_id],
            [
                'employee_id' => 'EMP' . $schoolRequest->user_id,
                'status' => 'Active',
            ]
        );

        if ($validated['status'] === 'approved') {
            $teacher->update(['connected_school' => $validated['connected_school'] ?? $schoolRequest->school_name]);
        } elseif ($validated['status'] === 'rejected') {
            $teacher->update(['connected_school' => null]);
        }

        // Notify teacher when request status changes
        if ($previousStatus !== $validated['status']) {
            $schoolRequest->user->notify(new SchoolRequestStatusUpdated($schoolRequest));
        }

        // Keep admin dashboard stats accurate
        DashboardController::clearCache();

        return redirect()->route('admin.school-requests.show', $schoolRequest)
            ->with('success', 'Request updated successfully.');
    }
}

