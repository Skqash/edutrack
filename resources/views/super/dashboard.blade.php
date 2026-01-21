@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="mb-4">
        <h2 class="h3 d-inline-block"><i class="fas fa-crown text-warning"></i> Super Admin Dashboard</h2>
        <span class="badge bg-danger ms-2">System Administrator</span>
    </div>

    <!-- KPI Statistics Row 1 -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Users</p>
                            <h3 class="text-primary mb-0">{{ $totalUsers ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-users fa-3x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Admin Users</p>
                            <h3 class="text-info mb-0">{{ $totalAdmins ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-user-tie fa-3x text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Teachers</p>
                            <h3 class="text-success mb-0">{{ $totalTeachers ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-chalkboard-user fa-3x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Students</p>
                            <h3 class="text-danger mb-0">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-graduation-cap fa-3x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Statistics Row 2 -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Courses</p>
                    <h3 class="text-warning mb-0">{{ $totalCourses ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Active Courses</p>
                    <h3 class="text-success mb-0">{{ $activeCourses ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Inactive Courses</p>
                    <h3 class="text-secondary mb-0">{{ $inactiveCourses ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Subjects</p>
                    <h3 class="text-primary mb-0">{{ $totalSubjects ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Classes</p>
                    <h3 class="text-info mb-0">{{ $totalClasses ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small mb-1">Active Classes</p>
                    <h3 class="text-success mb-0">{{ $activeClasses ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Overview -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chair"></i> Class Capacity Overview</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Capacity</span>
                            <strong>{{ $totalCapacity ?? 0 }} seats</strong>
                        </div>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-primary" style="width: 100%">
                                {{ $totalCapacity ?? 0 }} seats
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Enrolled</span>
                            <strong>{{ $totalEnrolled ?? 0 }} students</strong>
                        </div>
                        <div class="progress" style="height: 30px;">
                            @php
                                $occupancyPercent = $totalCapacity > 0 ? round(($totalEnrolled / $totalCapacity) * 100) : 0;
                                $progressColor = $occupancyPercent > 80 ? 'danger' : ($occupancyPercent > 60 ? 'warning' : 'success');
                            @endphp
                            <div class="progress-bar bg-{{ $progressColor }}" style="width: {{ $occupancyPercent }}%">
                                {{ $occupancyPercent }}%
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-muted small">
                        <p class="mb-0">Occupancy Rate: <strong>{{ $occupancyPercent ?? 0 }}%</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> User Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="userDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Users</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">{{ ucfirst($user->role) }}</span>
                                            @elseif($user->role === 'teacher')
                                                <span class="badge bg-success">{{ ucfirst($user->role) }}</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox"></i> No users yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Recent Courses</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCourses as $course)
                                    <tr>
                                        <td>{{ $course->course_name }}</td>
                                        <td><strong>{{ $course->course_code }}</strong></td>
                                        <td>{{ $course->instructor->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($course->status === 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox"></i> No courses yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Management Options -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> System Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-users-cog fa-2x text-primary mb-3"></i>
                                <h6>Manage All Users</h6>
                                <p class="text-muted small mb-3">View and manage all system users</p>
                                <a href="#" class="btn btn-sm btn-primary">Go to Users</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-book-open fa-2x text-success mb-3"></i>
                                <h6>Manage Courses</h6>
                                <p class="text-muted small mb-3">View and manage all courses</p>
                                <a href="#" class="btn btn-sm btn-success">Go to Courses</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-database fa-2x text-warning mb-3"></i>
                                <h6>Database Backup</h6>
                                <p class="text-muted small mb-3">Backup and restore database</p>
                                <a href="#" class="btn btn-sm btn-warning">Backup Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
<script>
    // User Distribution Chart
    const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Teachers', 'Students'],
            datasets: [{
                data: [{{ $totalAdmins }}, {{ $totalTeachers }}, {{ $totalStudents }}],
                backgroundColor: ['#dc3545', '#28a745', '#17a2b8'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

