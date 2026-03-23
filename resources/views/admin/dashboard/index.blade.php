@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <p class="mb-0 text-muted">
                @if($adminCampus)
                    Campus: <span class="badge bg-primary">{{ $adminCampus }}</span>
                @else
                    System-wide Overview
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportData('dashboard', 'csv')">CSV Report</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportData('dashboard', 'pdf')">PDF Report</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Teachers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teachers']['total'] ?? 0 }}</div>
                            <div class="text-xs text-muted">
                                <span class="text-success">{{ $stats['total_teachers']['approved'] ?? 0 }} approved</span> |
                                <span class="text-warning">{{ $stats['total_teachers']['pending'] ?? 0 }} pending</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Classes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_classes'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Courses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_courses'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approvals Alert -->
    @if(($stats['pending_approvals']['campus_approvals'] ?? 0) > 0 || ($stats['pending_approvals']['course_requests'] ?? 0) > 0)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Pending Approvals:</strong>
        @if(($stats['pending_approvals']['campus_approvals'] ?? 0) > 0)
            <a href="{{ route('admin.teachers.campus-approvals') }}" class="alert-link">
                {{ $stats['pending_approvals']['campus_approvals'] }} campus approval{{ $stats['pending_approvals']['campus_approvals'] > 1 ? 's' : '' }}
            </a>
        @endif
        @if(($stats['pending_approvals']['course_requests'] ?? 0) > 0)
            @if(($stats['pending_approvals']['campus_approvals'] ?? 0) > 0) and @endif
            <a href="{{ route('admin.teachers.course-access-requests') }}" class="alert-link">
                {{ $stats['pending_approvals']['course_requests'] }} course request{{ $stats['pending_approvals']['course_requests'] > 1 ? 's' : '' }}
            </a>
        @endif
        need your attention.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadRecentActivities()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="recentActivitiesContainer">
                        @forelse($recentActivities as $activity)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @php
                                    $iconClass = match($activity['type']) {
                                        'user_registered' => 'fas fa-user-plus text-success',
                                        'class_created' => 'fas fa-school text-info',
                                        default => 'fas fa-info-circle text-primary'
                                    };
                                @endphp
                                <i class="{{ $iconClass }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-800">{{ $activity['message'] }}</div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p class="mb-0">No recent activities</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Add Teacher
                        </a>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-2"></i>Add Student
                        </a>
                        <a href="{{ route('admin.classes.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-plus me-2"></i>Create Class
                        </a>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-plus me-2"></i>Add Course
                        </a>
                        <hr>
                        <a href="{{ route('admin.teachers.campus-approvals') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-building me-2"></i>Campus Approvals
                            @if(($stats['pending_approvals']['campus_approvals'] ?? 0) > 0)
                                <span class="badge bg-warning">{{ $stats['pending_approvals']['campus_approvals'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.teachers.course-access-requests') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-graduation-cap me-2"></i>Course Requests
                            @if(($stats['pending_approvals']['course_requests'] ?? 0) > 0)
                                <span class="badge bg-info">{{ $stats['pending_approvals']['course_requests'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="card shadow mt-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">System Health</h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="checkSystemHealth()">
                        <i class="fas fa-heartbeat"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="systemHealthContainer">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p class="mb-0 small">Loading health status...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Enrollment Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Enrollment by Course</h6>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Grade Distribution Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grade Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="gradeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Dashboard functionality
let enrollmentChart, gradeChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    checkSystemHealth();
});

function initializeCharts() {
    // Enrollment Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    const enrollmentData = @json($chartData['enrollment'] ?? []);
    
    enrollmentChart = new Chart(enrollmentCtx, {
        type: 'bar',
        data: {
            labels: enrollmentData.map(item => item.course),
            datasets: [{
                label: 'Students',
                data: enrollmentData.map(item => item.students),
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grade Distribution Chart
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    const gradeData = @json($chartData['grades'] ?? []);
    
    gradeChart = new Chart(gradeCtx, {
        type: 'doughnut',
        data: {
            labels: gradeData.map(item => item.grade_letter),
            datasets: [{
                data: gradeData.map(item => item.count),
                backgroundColor: [
                    '#1cc88a', // A - Green
                    '#36b9cc', // B - Blue
                    '#f6c23e', // C - Yellow
                    '#fd7e14', // D - Orange
                    '#e74a3b'  // F - Red
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function refreshDashboard() {
    location.reload();
}

function loadRecentActivities() {
    // Implementation for loading recent activities via AJAX
    console.log('Loading recent activities...');
}

function checkSystemHealth() {
    fetch('{{ route("admin.dashboard") }}/system-health')
        .then(response => response.json())
        .then(data => {
            updateSystemHealthDisplay(data);
        })
        .catch(error => {
            console.error('Error checking system health:', error);
            document.getElementById('systemHealthContainer').innerHTML = `
                <div class="text-center text-danger py-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="mb-0 small">Error checking health</p>
                </div>
            `;
        });
}

function updateSystemHealthDisplay(health) {
    const container = document.getElementById('systemHealthContainer');
    const overallStatus = health.overall.status;
    
    let statusClass = 'success';
    let statusIcon = 'check-circle';
    
    if (overallStatus === 'warning') {
        statusClass = 'warning';
        statusIcon = 'exclamation-triangle';
    } else if (overallStatus === 'error') {
        statusClass = 'danger';
        statusIcon = 'times-circle';
    }
    
    container.innerHTML = `
        <div class="text-center">
            <i class="fas fa-${statusIcon} fa-2x text-${statusClass} mb-2"></i>
            <p class="mb-0 small text-${statusClass}">${health.overall.message}</p>
        </div>
        <hr>
        <div class="small">
            <div class="d-flex justify-content-between mb-1">
                <span>Database:</span>
                <span class="text-${health.database.status === 'healthy' ? 'success' : 'danger'}">
                    ${health.database.status}
                </span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Cache:</span>
                <span class="text-${health.cache.status === 'healthy' ? 'success' : 'danger'}">
                    ${health.cache.status}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Storage:</span>
                <span class="text-${health.storage.status === 'healthy' ? 'success' : 'warning'}">
                    ${health.storage.used_percentage}% used
                </span>
            </div>
        </div>
    `;
}

function exportData(type, format) {
    const url = `{{ route('admin.dashboard') }}/export?type=${type}&format=${format}`;
    window.open(url, '_blank');
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endsection