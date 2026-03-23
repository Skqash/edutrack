@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 fw-bold mb-1">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Course Access Requests
                    </h2>
                    <p class="text-muted mb-0">Manage teacher requests for course access</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-warning fs-6">{{ $pendingCount }} Pending</span>
                    <span class="badge bg-success fs-6">{{ $approvedCount }} Approved</span>
                    <span class="badge bg-danger fs-6">{{ $rejectedCount }} Rejected</span>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="requestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        <i class="fas fa-clock me-1"></i>
                        Pending ({{ $pendingCount }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                        <i class="fas fa-check-circle me-1"></i>
                        Approved ({{ $approvedCount }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
                        <i class="fas fa-times-circle me-1"></i>
                        Rejected ({{ $rejectedCount }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="requestTabsContent">
                <!-- Pending Requests -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    @if($pendingRequests->count() > 0)
                        <div class="row g-3">
                            @foreach($pendingRequests as $request)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title fw-bold mb-0">{{ $request->course->program_name }}</h6>
                                                <span class="badge bg-warning">Pending</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-user me-1"></i>
                                                    <strong>Teacher:</strong> {{ $request->teacher->name }}
                                                </p>
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $request->teacher->email }}
                                                </p>
                                                @if($request->teacher->campus)
                                                    <p class="text-muted small mb-1">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $request->teacher->campus }}
                                                    </p>
                                                @endif
                                            </div>

                                            @if($request->course->program_code)
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-code me-1"></i> {{ $request->course->program_code }}
                                                </p>
                                            @endif
                                            
                                            @if($request->reason)
                                                <div class="mb-3">
                                                    <strong class="small">Reason:</strong>
                                                    <p class="text-muted small mb-0">{{ Str::limit($request->reason, 100) }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $request->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-success btn-sm flex-fill" onclick="approveRequest({{ $request->id }})">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm flex-fill" onclick="rejectRequest({{ $request->id }}, '{{ $request->course->program_name }}')">
                                                    <i class="fas fa-times"></i> Reject
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
                            <p class="text-muted">All course access requests have been processed.</p>
                        </div>
                    @endif
                </div>

                <!-- Approved Requests -->
                <div class="tab-pane fade" id="approved" role="tabpanel">
                    @if($approvedRequests->count() > 0)
                        <div class="row g-3">
                            @foreach($approvedRequests as $request)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title fw-bold mb-0">{{ $request->course->program_name }}</h6>
                                                <span class="badge bg-success">Approved</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-user me-1"></i>
                                                    <strong>Teacher:</strong> {{ $request->teacher->name }}
                                                </p>
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $request->teacher->email }}
                                                </p>
                                            </div>

                                            @if($request->course->program_code)
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-code me-1"></i> {{ $request->course->program_code }}
                                                </p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-check me-1"></i>
                                                    Approved {{ $request->approved_at->format('M d, Y') }}
                                                </small>
                                                @if($request->approvedBy)
                                                    <small class="text-muted">
                                                        by {{ $request->approvedBy->name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <h5>No Approved Requests</h5>
                            <p class="text-muted">No course access requests have been approved yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Rejected Requests -->
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    @if($rejectedRequests->count() > 0)
                        <div class="row g-3">
                            @foreach($rejectedRequests as $request)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title fw-bold mb-0">{{ $request->course->program_name }}</h6>
                                                <span class="badge bg-danger">Rejected</span>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-user me-1"></i>
                                                    <strong>Teacher:</strong> {{ $request->teacher->name }}
                                                </p>
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    {{ $request->teacher->email }}
                                                </p>
                                            </div>

                                            @if($request->admin_note)
                                                <div class="mb-3">
                                                    <strong class="small">Admin Note:</strong>
                                                    <p class="text-muted small mb-0">{{ $request->admin_note }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-times me-1"></i>
                                                    Rejected {{ $request->approved_at->format('M d, Y') }}
                                                </small>
                                                @if($request->approvedBy)
                                                    <small class="text-muted">
                                                        by {{ $request->approvedBy->name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                            <h5>No Rejected Requests</h5>
                            <p class="text-muted">No course access requests have been rejected.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Course Access Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject the course access request for <strong id="courseName"></strong>?</p>
                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Reason for rejection (optional)</label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="3" placeholder="Provide a reason for the rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRequestId = null;

function approveRequest(requestId) {
    if (confirm('Are you sure you want to approve this course access request?')) {
        fetch(`/admin/course-access-requests/${requestId}/approve`, {
            method: 'POST',
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
            alert('An error occurred while approving the request.');
        });
    }
}

function rejectRequest(requestId, courseName) {
    currentRequestId = requestId;
    document.getElementById('courseName').textContent = courseName;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/course-access-requests/${currentRequestId}/reject`, {
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
        alert('An error occurred while rejecting the request.');
    });
});
</script>
@endsection