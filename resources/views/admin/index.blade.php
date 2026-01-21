@extends('layouts.admin')

@section('content')

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-1">Dashboard Overview</h2>
            <p class="text-muted">Welcome back! Here's your system performance.</p>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-user-graduate icon"></i>
                    <div class="stat-label">Total Students</div>
                    <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Students in system</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-chalkboard-user icon"></i>
                    <div class="stat-label">Total Teachers</div>
                    <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Teachers in system</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-book icon"></i>
                    <div class="stat-label">Total Subjects</div>
                    <div class="stat-value">{{ $totalSubjects ?? 0 }}</div>
                    <small class="text-muted"><i class="fas fa-minus"></i> Subjects available</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-school icon"></i>
                    <div class="stat-label">Total Classes</div>
                    <div class="stat-value">{{ $totalClasses ?? 0 }}</div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Classes available</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Student Enrollment Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2" style="color: #3498db;"></i> Student Enrollment Trend
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Class Distribution Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-pie-chart me-2" style="color: #27ae60;"></i> Students per Class
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="classDistributionChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Row -->
    <div class="row mb-4">
        <!-- Subject Performance -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-star me-2" style="color: #f39c12;"></i> Subject Performance
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subjectPerformanceChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-check-circle me-2" style="color: #27ae60;"></i> Attendance Rate
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2" style="color: #3498db;"></i> Recent Activities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-item d-flex mb-3">
                        <div class="activity-marker"
                            style="background-color: #3498db; width: 4px; border-radius: 2px; margin-right: 15px;"></div>
                        <div style="flex: 1;">
                            <strong>New Student Registered</strong>
                            <p class="text-muted mb-0">John Doe registered in class 10-A</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex mb-3">
                        <div class="activity-marker"
                            style="background-color: #27ae60; width: 4px; border-radius: 2px; margin-right: 15px;"></div>
                        <div style="flex: 1;">
                            <strong>Grade Submitted</strong>
                            <p class="text-muted mb-0">Mr. Smith submitted quarterly grades</p>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex">
                        <div class="activity-marker"
                            style="background-color: #f39c12; width: 4px; border-radius: 2px; margin-right: 15px;"></div>
                        <div style="flex: 1;">
                            <strong>Class Schedule Updated</strong>
                            <p class="text-muted mb-0">Class 12-B schedule has been updated</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2" style="color: #e74c3c;"></i> Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Attendance Rate</span>
                            <span class="badge bg-success">92%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 92%;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Pass Rate</span>
                            <span class="badge bg-info">87%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 87%;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Course Completion</span>
                            <span class="badge bg-warning">78%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 78%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">Faculty Utilization</span>
                            <span class="badge bg-primary">95%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 95%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .activity-item {
            padding-bottom: 12px;
        }

        .activity-marker {
            min-height: 100%;
        }

        .stat-card small {
            display: block;
            margin-top: 8px;
            font-size: 12px;
        }
    </style>

    <!-- Chart.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // Student Enrollment Trend Chart
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(enrollmentCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Student Enrollment',
                    data: [850, 920, 980, 1050, 1120, 1150, 1180, 1220, 1250, 1280, 1285, 1285],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 12, weight: 'bold' } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Class Distribution Chart
        const classCtx = document.getElementById('classDistributionChart').getContext('2d');
        new Chart(classCtx, {
            type: 'doughnut',
            data: {
                labels: ['Class 10-A', 'Class 10-B', 'Class 12-A', 'Class 12-B', 'Class 11-A', 'Class 11-B'],
                datasets: [{
                    data: [65, 70, 72, 68, 60, 55],
                    backgroundColor: [
                        '#3498db',
                        '#27ae60',
                        '#f39c12',
                        '#e74c3c',
                        '#9b59b6',
                        '#1abc9c'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 15 }
                    }
                }
            }
        });

        // Subject Performance Chart
        const subjectCtx = document.getElementById('subjectPerformanceChart').getContext('2d');
        new Chart(subjectCtx, {
            type: 'bar',
            data: {
                labels: ['Math', 'Science', 'English', 'History', 'Geography', 'Computer'],
                datasets: [{
                    label: 'Average Score',
                    data: [85, 78, 82, 80, 79, 88],
                    backgroundColor: [
                        '#3498db',
                        '#27ae60',
                        '#f39c12',
                        '#e74c3c',
                        '#9b59b6',
                        '#1abc9c'
                    ],
                    borderRadius: 5,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });

        // Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'radar',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                datasets: [{
                    label: 'Attendance Rate (%)',
                    data: [92, 89, 94, 91, 88],
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#27ae60'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, labels: { font: { size: 12 } } }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });
    </script>

@endsection