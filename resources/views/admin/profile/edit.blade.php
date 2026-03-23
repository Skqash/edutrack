@extends('layouts.admin')

@section('content')
    <style>
        .edit-header {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .form-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }

        .form-section {
            border-bottom: 1px solid #e9ecef;
            padding: 2rem 0;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.75rem;
            color: #28a745;
            font-size: 1.25rem;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }

        .form-control,
        .form-select {
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-note {
            background: #f8f9fa;
            border-left: 3px solid #28a745;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
    </style>

    <!-- Edit Header -->
    <div class="edit-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h2 fw-bold mb-0">Edit Profile</h1>
                </div>
                <div class="col-auto ms-auto">
                    <p class="mb-0 text-white-50 small">Update your personal and administrative information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card form-card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-user"></i> Personal Information
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="+1 (555) 000-0000">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Administrative Information Section -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-briefcase"></i> Administrative Information
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                                        id="department" name="department"
                                        value="{{ old('department', $user->department ?? ($admin->department ?? '')) }}"
                                        placeholder="e.g., Academic Affairs, Administration">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Biography</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4"
                                    placeholder="Tell about your role and experience...">{{ old('bio', $user->bio ?? ($admin->bio ?? '')) }}</textarea>
                                <small class="text-muted d-block mt-1">Maximum 500 characters</small>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-lock"></i> Change Password
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Leave empty to keep current password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>

                            <div class="password-note">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Password Requirements:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Minimum 8 characters</li>
                                        <li>Must match in both fields</li>
                                        <li>Leave blank to keep current password</li>
                                    </ul>
                                </small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card form-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-lightbulb me-2"></i> Helpful Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-circle text-success small"></i> Keep It Professional
                        </h6>
                        <small class="text-muted">Your profile information is visible across the system.</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-circle text-success small"></i> Required Fields
                        </h6>
                        <small class="text-muted">Full Name and Email Address are required for account operation.</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-circle text-success small"></i> Password Security
                        </h6>
                        <small class="text-muted">Use a strong password with mix of letters, numbers, and symbols.</small>
                    </div>

                    <hr>

                    <div>
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="fas fa-circle text-success small"></i> Updates Saved Immediately
                        </h6>
                        <small class="text-muted">Your changes are applied to your account right away.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
