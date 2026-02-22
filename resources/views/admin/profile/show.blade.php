@extends('layouts.admin')

@section('content')
    <style>
        .profile-header {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0;
        }

        .profile-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }

        .profile-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .info-group {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 0;
        }

        .info-group:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #212529;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        .avatar-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #28a745;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            margin: 0 auto 1rem;
        }

        .badge-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #e6f9ed;
            color: #28a745;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
    </style>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 fw-bold mb-2">{{ $user->name }}</h1>
                    <span class="badge-status">Active Administrator</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-light me-2">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </a>
                    <a href="{{ route('admin.profile.change-password') }}" class="btn btn-outline-light">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-shield me-2"></i> Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">
                            @if ($user->phone)
                                <a href="tel:{{ $user->phone }}" class="text-decoration-none">{{ $user->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>

                    @if ($admin)
                        <div class="info-group">
                            <div class="info-label">Department</div>
                            <div class="info-value">
                                {{ $admin->department ?? 'Not specified' }}
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Biography</div>
                            <div class="info-value">
                                @if ($admin->bio)
                                    <p>{{ $admin->bio }}</p>
                                @else
                                    <span class="text-muted">No biography provided</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="info-group">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">
                            {{ $user->created_at->format('F j, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Avatar & Quick Actions -->
            <div class="card profile-card">
                <div class="card-body">
                    <div class="avatar-section">
                        <div class="avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">Administrator Account</p>
                        <span class="badge bg-success">Active</span>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i> Account Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="info-label">Role</div>
                        <div class="info-value">
                            <span class="badge bg-success">Administrator</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Account Status</div>
                        <div class="info-value">
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value small text-muted">
                            {{ $user->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-link me-2"></i> Quick Links
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2 text-success"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2 text-success"></i> Users
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-door-open me-2 text-success"></i> Classes
                    </a>
                    <a href="{{ route('admin.profile.change-password') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2 text-success"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
