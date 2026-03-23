@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Profile Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1">{{ $teacher->name }}</h2>
                            <p class="text-muted mb-2">{{ $teacher->email }}</p>
                            <div class="d-flex gap-2">
                                @if($teacher->campus)
                                    <span class="badge bg-primary">{{ $teacher->campus }}</span>
                                @else
                                    <span class="badge bg-secondary">Independent Teacher</span>
                                @endif
                                @if($teacher->campus_status)
                                    <span class="badge bg-{{ $teacher->campus_status === 'approved' ? 'success' : ($teacher->campus_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($teacher->campus_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('teacher.profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Profile Information -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p class="mb-0">{{ $teacher->name ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <p class="mb-0">{{ $teacher->email ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <p class="mb-0">{{ $teacher->phone ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Employee ID</label>
                                    <p class="mb-0">{{ $teacher->employee_id ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Qualification</label>
                                    <p class="mb-0">{{ $teacher->qualification ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Specialization</label>
                                    <p class="mb-0">{{ $teacher->specialization ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Department</label>
                                    <p class="mb-0">{{ $teacher->department ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $teacher->status === 'Active' ? 'success' : 'danger' }}">
                                            {{ $teacher->status ?? 'Active' }}
                                        </span>
                                    </p>
                                </div>
                                @if($teacher->bio)
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Bio</label>
                                        <p class="mb-0">{{ $teacher->bio }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security Policies -->
                    @if(isset($securityPolicies) && count($securityPolicies) > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shield-alt me-2"></i>Security Policies
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($securityPolicies as $policy)
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-{{ $policy['type'] }} bg-opacity-10 p-2">
                                                <i class="{{ $policy['icon'] }} text-{{ $policy['type'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $policy['title'] }}</h6>
                                            <p class="mb-1 text-muted">{{ $policy['description'] }}</p>
                                            @if($policy['enforced'])
                                                <span class="badge bg-success bg-opacity-25 text-success">
                                                    <i class="fas fa-check me-1"></i>Enforced
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Profile Management Sidebar -->
                <div class="col-lg-4">
                    <!-- Profile Completion -->
                    @if(isset($profileData['profile_completion']))
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Profile Completion</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Progress</span>
                                    <span class="fw-bold">{{ $profileData['profile_completion']['percentage'] }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ $profileData['profile_completion']['percentage'] }}%"></div>
                                </div>
                                <small class="text-muted">
                                    {{ $profileData['profile_completion']['completed_fields'] }}/{{ $profileData['profile_completion']['total_fields'] }} fields completed
                                </small>
                                @if(count($profileData['profile_completion']['missing_fields']) > 0)
                                    <div class="mt-2">
                                        <small class="text-muted d-block">Missing fields:</small>
                                        @foreach($profileData['profile_completion']['missing_fields'] as $field)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Campus Connections -->
                    @if(isset($profileData['campus_connections']) && count($profileData['campus_connections']) > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Campus Connections</h6>
                            </div>
                            <div class="card-body">
                                @foreach($profileData['campus_connections'] as $connection)
                                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2">
                                        <div>
                                            <div class="fw-bold">{{ $connection['short_name'] }}</div>
                                            <small class="text-muted">{{ $connection['role'] }} since {{ $connection['since']->format('M Y') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $connection['status'] === 'approved' ? 'success' : 'warning' }}">
                                            {{ ucfirst($connection['status']) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.profile.edit') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i>Edit Profile
                                </a>
                                <a href="{{ route('teacher.profile.change-password') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-key me-1"></i>Change Password
                                </a>
                                <a href="{{ route('teacher.settings.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-cog me-1"></i>Account Settings
                                </a>
                                @if(empty($teacher->campus))
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#campusRequestModal">
                                        <i class="fas fa-university me-1"></i>Request Campus Affiliation
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#campusChangeModal">
                                        <i class="fas fa-exchange-alt me-1"></i>Request Campus Change
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Campus Request Modal -->
<div class="modal fade" id="campusRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-university me-2"></i>Request Campus Affiliation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.request.campus-change') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="requested_campus" class="form-label">Requested Campus *</label>
                        <select class="form-select" id="requested_campus" name="requested_campus" required>
                            <option value="">Select Campus</option>
                            <option value="Kabankalan">CPSU Main Campus - Kabankalan City</option>
                            <option value="Victorias">CPSU Victorias Campus</option>
                            <option value="Sipalay">CPSU Sipalay Campus - Brgy. Gil Montilla</option>
                            <option value="Cauayan">CPSU Cauayan Campus</option>
                            <option value="Candoni">CPSU Candoni Campus</option>
                            <option value="Hinoba-an">CPSU Hinoba-an Campus</option>
                            <option value="Ilog">CPSU Ilog Campus</option>
                            <option value="Hinigaran">CPSU Hinigaran Campus</option>
                            <option value="Moises Padilla">CPSU Moises Padilla Campus</option>
                            <option value="San Carlos">CPSU San Carlos Campus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Request *</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required placeholder="Please explain why you want to be affiliated with this campus..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your request will be reviewed by the campus administrator. You will receive a notification once your request is processed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Campus Change Modal -->
<div class="modal fade" id="campusChangeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>Request Campus Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.request.campus-change') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Current Campus:</strong> {{ $teacher->campus ?? 'None' }}
                    </div>
                    <div class="mb-3">
                        <label for="requested_campus_change" class="form-label">New Campus *</label>
                        <select class="form-select" id="requested_campus_change" name="requested_campus" required>
                            <option value="">Select New Campus</option>
                            <option value="Kabankalan">CPSU Main Campus - Kabankalan City</option>
                            <option value="Victorias">CPSU Victorias Campus</option>
                            <option value="Sipalay">CPSU Sipalay Campus - Brgy. Gil Montilla</option>
                            <option value="Cauayan">CPSU Cauayan Campus</option>
                            <option value="Candoni">CPSU Candoni Campus</option>
                            <option value="Hinoba-an">CPSU Hinoba-an Campus</option>
                            <option value="Ilog">CPSU Ilog Campus</option>
                            <option value="Hinigaran">CPSU Hinigaran Campus</option>
                            <option value="Moises Padilla">CPSU Moises Padilla Campus</option>
                            <option value="San Carlos">CPSU San Carlos Campus</option>
                            <option value="">Independent Teacher (No Campus)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="change_reason" class="form-label">Reason for Change *</label>
                        <textarea class="form-control" id="change_reason" name="reason" rows="4" required placeholder="Please explain why you want to change your campus affiliation..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Changing campus will affect your access to current classes and data. Your request will be reviewed by administrators.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exchange-alt me-1"></i>Request Change
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection