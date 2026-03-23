@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 fw-bold mb-1">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Courses & Programs
                    </h2>
                    <p class="text-muted mb-0">Manage your course access and view available programs</p>
                </div>
                @php
                    $user = auth()->user();
                    $isApproved = empty($user->campus) || $user->campus_status === 'approved';
                @endphp
                @if ($isApproved)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestCourseModal">
                        <i class="fas fa-plus me-1"></i> Request Course Access
                    </button>
                @endif
            </div>

            <!-- Campus Status Alert -->
            @if (!$isApproved)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Limited Access:</strong> Course access requests are not available until your campus affiliation is approved.
                </div>
            @endif

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="coursesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="my-courses-tab" data-bs-toggle="tab" data-bs-target="#my-courses" type="button" role="tab">
                        <i class="fas fa-check-circle me-1"></i>
                        My Courses ({{ $approvedCourses->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        <i class="fas fa-clock me-1"></i>
                        Pending Requests ({{ $pendingRequests->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab">
                        <i class="fas fa-list me-1"></i>
                        Available Courses ({{ $availableCourses->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="coursesTabsContent">
                <!-- My Approved Courses -->
                <div class="tab-pane fade show active" id="my-courses" role="tabpanel">
                    @if($approvedCourses->count() > 0)
                        <div class="row g-3">
                            @foreach($approvedCourses as $course)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title fw-bold mb-0">{{ $course->program_name }}</h6>
                                                <span class="badge bg-success">Approved</span>
                                            </div>
                                            
                                            @if($course->program_code)
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-code me-1"></i> {{ $course->program_code }}
                                                </p>
                                            @endif
                                            
                                            @if($course->description)
                                                <p class="text-muted small mb-3">{{ Str::limit($course->description, 100) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-chalkboard me-1"></i>
                                                    {{ $course->classes_count ?? 0 }} classes
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('teacher.classes.create') }}?course_id={{ $course->id }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-plus"></i> Create Class
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5>No Approved Courses</h5>
                            <p class="text-muted">You don't have access to any courses yet. Request access to get started.</p>
                            @if ($isApproved)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestCourseModal">
                                    <i class="fas fa-plus me-1"></i> Request Course Access
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Pending Requests -->
                <div class="tab-pane fade" id="pending" role="tabpanel">
                    @if($pendingRequests->count() > 0)
                        <div class="row g-3">
                            @foreach($pendingRequests as $request)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title fw-bold mb-0">{{ $request->course->program_name }}</h6>
                                                <span class="badge bg-warning">Pending</span>
                                            </div>
                                            
                                            @if($request->course->program_code)
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-code me-1"></i> {{ $request->course->program_code }}
                                                </p>
                                            @endif
                                            
                                            @if($request->reason)
                                                <p class="text-muted small mb-3">
                                                    <strong>Reason:</strong> {{ Str::limit($request->reason, 100) }}
                                                </p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $request->created_at->format('M d, Y') }}
                                                </small>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="cancelRequest({{ $request->id }})">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h5>No Pending Requests</h5>
                            <p class="text-muted">You don't have any pending course access requests.</p>
                        </div>
                    @endif
                </div>

                <!-- Available Courses -->
                <div class="tab-pane fade" id="available" role="tabpanel">
                    @if($availableCourses->count() > 0)
                        <div class="row g-3">
                            @foreach($availableCourses as $course)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title fw-bold mb-0">{{ $course->program_name }}</h6>
                                                <span class="badge bg-secondary">Available</span>
                                            </div>
                                            
                                            @if($course->program_code)
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-code me-1"></i> {{ $course->program_code }}
                                                </p>
                                            @endif
                                            
                                            @if($course->description)
                                                <p class="text-muted small mb-3">{{ Str::limit($course->description, 100) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $course->students_count ?? 0 }} students
                                                </small>
                                                @if ($isApproved)
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="requestCourse({{ $course->id }}, '{{ $course->program_name }}')">
                                                        <i class="fas fa-paper-plane"></i> Request
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <h5>No Available Courses</h5>
                            <p class="text-muted">All courses have been requested or approved.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Course Modal -->
<div class="modal fade" id="requestCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Course Access</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="requestCourseForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Select Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Choose a course...</option>
                            @foreach($availableCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->program_name }} ({{ $course->program_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Request</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Explain why you need access to this course..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Request course access
document.getElementById('requestCourseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("teacher.courses.request") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the request.');
    });
});

// Quick request from available courses
function requestCourse(courseId, courseName) {
    document.getElementById('course_id').value = courseId;
    document.getElementById('reason').value = `I would like to teach ${courseName} and need access to manage classes and students for this program.`;
    new bootstrap.Modal(document.getElementById('requestCourseModal')).show();
}

// Cancel pending request
function cancelRequest(requestId) {
    if (confirm('Are you sure you want to cancel this request?')) {
        fetch(`/teacher/courses/requests/${requestId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling the request.');
        });
    }
}
</script>
@endsection