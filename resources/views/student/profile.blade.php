@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">My Profile</h1>
                <p class="text-muted mb-0">View and manage your personal & academic information</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            Personal Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Display Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small"><strong>Student ID</strong></label>
                                    <p class="form-control-plaintext fw-bold">{{ $student->student_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small"><strong>Email</strong></label>
                                    <p class="form-control-plaintext">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small"><strong>First Name</strong></label>
                                    <p class="form-control-plaintext">{{ $student->first_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small"><strong>Middle Name</strong></label>
                                    <p class="form-control-plaintext">{{ $student->middle_name ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted small"><strong>Last Name</strong></label>
                                    <p class="form-control-plaintext">{{ $student->last_name }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Edit Information -->
                        <h6 class="mb-3"><i class="fas fa-edit me-1"></i> Edit Personal Information</h6>
                        <form action="{{ route('student.update-profile') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ $student->phone ?? '' }}"
                                            placeholder="e.g., +63-9XX-XXX-XXXX">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                            name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" @selected($student->gender === 'male')>Male</option>
                                            <option value="female" @selected($student->gender === 'female')>Female</option>
                                            <option value="other" @selected($student->gender === 'other')>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date" value="{{ $student->birth_date ?? '' }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="enrollment_date" class="form-label">Enrollment Date</label>
                                        <input type="date" class="form-control"
                                            value="{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '' }}"
                                            readonly disabled>
                                        <small class="text-muted">System managed field</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    placeholder="Enter your complete address">{{ $student->address ?? '' }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h6 class="mb-3"><i class="fas fa-graduation-cap me-1"></i> Academic Information</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Department / Course</label>
                                        <input type="text" class="form-control"
                                            value="{{ $student->department ?? '—' }}" readonly disabled>
                                        <small class="text-muted">System managed</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Year Level</label>
                                        <input type="text" class="form-control"
                                            value="Year {{ $student->year ?? '—' }}" readonly disabled>
                                        <small class="text-muted">System managed</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Academic Year</label>
                                        <input type="text" class="form-control"
                                            value="{{ $student->academic_year ?? 'Not Set' }}" readonly disabled>
                                        <small class="text-muted">System managed</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Campus</label>
                                        <input type="text" class="form-control"
                                            value="{{ $student->campus ?? 'Not Assigned' }}" readonly disabled>
                                        <small class="text-muted">System managed</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Enrollment Status</label>
                                        <div>
                                            <span class="badge" style="background-color: {{ $student->status === 'active' ? '#28a745' : '#dc3545' }}; font-size: 0.95rem; padding: 0.5rem 0.8rem;">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">System managed</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4 mb-4">
                <!-- Account Summary -->
                <div class="card border-0 mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2 text-info"></i>
                            Account Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Account Status</small>
                            <span class="badge bg-success p-2">Active</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Account Created</small>
                            <p class="mb-0 small">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block">Last Updated</small>
                            <p class="mb-0 small">{{ auth()->user()->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- E-Signature Section -->
                <div class="card border-0 mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-pen me-2 text-warning"></i>
                            E-Signature Status
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        @if (auth()->user()->hasESignature())
                            <div class="mb-3">
                                <span class="badge bg-success" style="font-size: 0.95rem;">✓ Registered</span>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted small mb-2">Current Signature:</p>
                                <div class="border rounded p-2 bg-light" style="max-width: 100%;">
                                    <img src="{{ $student->e_signature }}" alt="Your E-Signature"
                                        style="max-height: 80px; max-width: 100%; border-radius: 0.25rem;">
                                </div>
                            </div>
                            <a href="{{ route('student.signature.form') }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-edit me-1"></i> Update Signature
                            </a>
                        @else
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark" style="font-size: 0.95rem;">⚠ Not Set</span>
                            </div>
                            <p class="text-muted small mb-3">You need to create a digital signature for signature-based attendance.</p>
                            <a href="{{ route('student.signature.form') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i> Create Signature
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Security -->
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-shield-alt me-2 text-danger"></i>
                            Security
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Keep your account secure by protecting your password.</p>
                        <a href="#" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="fas fa-lock me-1"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-lock me-2"></i> Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('student.change-password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
