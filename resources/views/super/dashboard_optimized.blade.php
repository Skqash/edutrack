@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <!-- Header with Logout -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-0"><i class="fas fa-crown text-warning"></i> Super Admin Dashboard</h2>
                <small class="text-muted">System Administrator Panel</small>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;"></form>
            </div>
        </div>

        <!-- Quick Stats Row 1 -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Users</p>
                                <h4 class="text-primary mb-0">{{ $totalUsers ?? 0 }}</h4>
                                <small class="text-success">{{ $totalAdmins + $totalTeachers + $totalStudents }}
                                    active</small>
                            </div>
                            <i class="fas fa-users fa-2x text-primary opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Admins</p>
                                <h4 class="text-danger mb-0">{{ $totalAdmins ?? 0 }}</h4>
                                <small class="text-info"><i class="fas fa-arrow-up"></i> System Operators</small>
                            </div>
                            <i class="fas fa-user-tie fa-2x text-danger opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Teachers</p>
                                <h4 class="text-success mb-0">{{ $totalTeachers ?? 0 }}</h4>
                                <small class="text-info"><i class="fas fa-arrow-up"></i> Instructors</small>
                            </div>
                            <i class="fas fa-chalkboard-user fa-2x text-success opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Students</p>
                                <h4 class="text-info mb-0">{{ $totalStudents ?? 0 }}</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up"></i> Enrolled</small>
                            </div>
                            <i class="fas fa-graduation-cap fa-2x text-info opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row 2 -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Courses</p>
                        <h4 class="text-warning mb-0">{{ $totalCourses ?? 0 }}</h4>
                        <small class="text-success">{{ $activeCourses ?? 0 }} Active</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Classes</p>
                        <h4 class="text-purple mb-0">{{ $totalClasses ?? 0 }}</h4>
                        <small class="text-success">{{ $activeClasses ?? 0 }} Active</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Subjects</p>
                        <h4 class="text-secondary mb-0">{{ $totalSubjects ?? 0 }}</h4>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Capacity</p>
                        <h4 class="text-info mb-0">{{ $totalCapacity ?? 0 }}</h4>
                        <small class="text-warning">Total Seats</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Enrolled</p>
                        <h4 class="text-success mb-0">{{ $totalEnrolled ?? 0 }}</h4>
                        <small class="text-info">Students Assigned</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Utilization</p>
                        <h4 class="text-warning mb-0">
                            {{ $totalCapacity > 0 ? round(($totalEnrolled / $totalCapacity) * 100) : 0 }}%</h4>
                        <small class="text-muted">Capacity Used</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row - Optimized -->
        <div class="row mb-4">
            <!-- User Distribution - Compact -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0"><i class="fas fa-chart-pie"></i> User Distribution</h6>
                    </div>
                    <div class="card-body" style="position: relative; height: 250px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Course Status - Compact -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Course Status</h6>
                    </div>
                    <div class="card-body" style="position: relative; height: 250px;">
                        <canvas id="courseStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('super.users.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-user-plus"></i> New User
                            </a>
                            <a href="{{ route('super.courses.create') }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-book"></i> New Course
                            </a>
                            <a href="{{ route('super.students.create') }}" class="btn btn-sm btn-info">
                                <i class="fas fa-graduation-cap"></i> Add Student
                            </a>
                            <a href="{{ route('super.health') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-heartbeat"></i> System Health
                            </a>
                            <a href="{{ route('super.tools.backup') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-download"></i> Backup DB
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Buttons Grid - Optimized -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0"><i class="fas fa-cog"></i> Management Tools</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <!-- Users Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.users.index') }}"
                                    class="btn btn-outline-primary btn-sm w-100 py-2">
                                    <i class="fas fa-users"></i> Users
                                    <span class="badge bg-primary ms-2">{{ $totalUsers ?? 0 }}</span>
                                </a>
                            </div>

                            <!-- Students Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.students.index') }}"
                                    class="btn btn-outline-info btn-sm w-100 py-2">
                                    <i class="fas fa-graduation-cap"></i> Students
                                    <span class="badge bg-info ms-2">{{ $totalStudents ?? 0 }}</span>
                                </a>
                            </div>

                            <!-- Courses Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.courses.index') }}"
                                    class="btn btn-outline-warning btn-sm w-100 py-2">
                                    <i class="fas fa-book"></i> Courses
                                    <span class="badge bg-warning ms-2">{{ $totalCourses ?? 0 }}</span>
                                </a>
                            </div>

                            <!-- Classes Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.classes.index') }}"
                                    class="btn btn-outline-purple btn-sm w-100 py-2">
                                    <i class="fas fa-chalkboard"></i> Classes
                                    <span class="badge bg-purple ms-2">{{ $totalClasses ?? 0 }}</span>
                                </a>
                            </div>

                            <!-- Grades Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.grades.index') }}"
                                    class="btn btn-outline-danger btn-sm w-100 py-2">
                                    <i class="fas fa-chart-line"></i> Grades
                                </a>
                            </div>

                            <!-- Attendance Management -->
                            <div class="col-md-3">
                                <a href="{{ route('super.attendance.index') }}"
                                    class="btn btn-outline-success btn-sm w-100 py-2">
                                    <i class="fas fa-calendar-check"></i> Attendance
                                </a>
                            </div>

                            <!-- Database Tools -->
                            <div class="col-md-3">
                                <a href="{{ route('super.tools.database') }}"
                                    class="btn btn-outline-secondary btn-sm w-100 py-2">
                                    <i class="fas fa-database"></i> Database
                                </a>
                            </div>

                            <!-- System Tools -->
                            <div class="col-md-3">
                                <a href="{{ route('super.health') }}" class="btn btn-outline-info btn-sm w-100 py-2">
                                    <i class="fas fa-heartbeat"></i> Health Check
                                </a>
                            </div>

                            <!-- Logs -->
                            <div class="col-md-3">
                                <a href="{{ route('super.logs.view') }}" class="btn btn-outline-dark btn-sm w-100 py-2">
                                    <i class="fas fa-file-alt"></i> Logs
                                </a>
                            </div>

                            <!-- Cleanup -->
                            <div class="col-md-3">
                                <a href="{{ route('super.cleanup') }}" class="btn btn-outline-danger btn-sm w-100 py-2">
                                    <i class="fas fa-broom"></i> Cleanup
                                </a>
                            </div>

                            <!-- Cache Management -->
                            <div class="col-md-3">
                                <form action="{{ route('super.system.cache-clear') }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning btn-sm w-100 py-2"
                                        onclick="return confirm('Clear all caches?')">
                                        <i class="fas fa-trash"></i> Clear Cache
                                    </button>
                                </form>
                            </div>

                            <!-- Migrations -->
                            <div class="col-md-3">
                                <form action="{{ route('super.system.migrate') }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100 py-2"
                                        onclick="return confirm('Run migrations?')">
                                        <i class="fas fa-sync"></i> Migrate
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Data Tables -->
        <div class="row">
            <!-- Recent Users -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3 d-flex justify-content-between">
                        <h6 class="mb-0"><i class="fas fa-user-plus"></i> Recent Users</h6>
                        <a href="{{ route('super.users.index') }}" class="btn btn-link btn-sm">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
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
                                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                        style="width: 28px; height: 28px; font-size: 12px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="small">{{ Str::limit($user->name, 15) }}</span>
                                                </div>
                                            </td>
                                            <td class="small">{{ Str::limit($user->email, 20) }}</td>
                                            <td>
                                                @if ($user->role === 'admin')
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
                                            <td colspan="4" class="text-center text-muted py-3">No users yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Courses -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3 d-flex justify-content-between">
                        <h6 class="mb-0"><i class="fas fa-book"></i> Recent Courses</h6>
                        <a href="{{ route('super.courses.index') }}" class="btn btn-link btn-sm">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Code</th>
                                        <th>Instructor</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentCourses as $course)
                                        <tr>
                                            <td class="small">{{ Str::limit($course->course_name, 20) }}</td>
                                            <td class="small"><code>{{ $course->course_code }}</code></td>
                                            <td class="small">
                                                {{ $course->instructor ? Str::limit($course->instructor->name, 15) : '-' }}
                                            </td>
                                            <td class="text-muted small">{{ $course->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No courses yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-purple {
            color: #6f42c1;
        }

        .bg-purple {
            background-color: #6f42c1;
        }

        .btn-outline-purple {
            color: #6f42c1;
            border-color: #6f42c1;
        }

        .btn-outline-purple:hover {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: #fff;
        }

        .avatar {
            font-weight: bold;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
    <script>
        // User Distribution Chart - Compact
        const userDistributionCtx = document.getElementById('userDistributionChart');
        if (userDistributionCtx) {
            new Chart(userDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admins', 'Teachers', 'Students'],
                    datasets: [{
                        data: [{{ $totalAdmins ?? 0 }}, {{ $totalTeachers ?? 0 }},
                            {{ $totalStudents ?? 0 }}
                        ],
                        backgroundColor: ['#dc3545', '#28a745', '#17a2b8'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Course Status Chart - Compact
        const courseStatusCtx = document.getElementById('courseStatusChart');
        if (courseStatusCtx) {
            new Chart(courseStatusCtx, {
                type: 'bar',
                data: {
                    labels: ['Total', 'Active', 'Inactive'],
                    datasets: [{
                        label: 'Courses',
                        data: [{{ $totalCourses ?? 0 }}, {{ $activeCourses ?? 0 }},
                            {{ $totalCourses - $activeCourses ?? 0 }}
                        ],
                        backgroundColor: ['#007bff', '#28a745', '#6c757d'],
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: Math.max({{ $totalCourses ?? 0 }} + 5, 10)
                        }
                    }
                }
            });
        }
    </script>
@endsection
