<?php $__env->startSection('content'); ?>
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
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-user-graduate icon" style="color: #3498db;"></i>
                    <div class="stat-label text-muted">Total Students</div>
                    <div class="stat-value fw-bold"><?php echo e($totalStudents ?? 0); ?></div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Students in system</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-chalkboard-user icon" style="color: #27ae60;"></i>
                    <div class="stat-label text-muted">Total Teachers</div>
                    <div class="stat-value fw-bold"><?php echo e($totalTeachers ?? 0); ?></div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Teachers in system</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-book icon" style="color: #f39c12;"></i>
                    <div class="stat-label text-muted">Total Subjects</div>
                    <div class="stat-value fw-bold"><?php echo e($totalSubjects ?? 0); ?></div>
                    <small class="text-muted"><i class="fas fa-minus"></i> Subjects available</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-school icon" style="color: #e74c3c;"></i>
                    <div class="stat-label text-muted">Total Classes</div>
                    <div class="stat-value fw-bold"><?php echo e($totalClasses ?? 0); ?></div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> Classes available</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Widget -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-envelope-open-text icon" style="color: #8e44ad;"></i>
                    <div class="stat-label text-muted">Pending School Requests</div>
                    <div class="stat-value fw-bold"><?php echo e($pendingSchoolRequests ?? 0); ?></div>
                    <small class="text-muted">
                        <a href="<?php echo e(route('admin.school-requests.index')); ?>" class="text-decoration-none">
                            View requests <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
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

    <!-- Main Tabs Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="grades-tab" data-bs-toggle="tab"
                                data-bs-target="#grades-tab-pane" type="button" role="tab">
                                <i class="fas fa-award me-2"></i> Grades by Class
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="attendance-tab" data-bs-toggle="tab"
                                data-bs-target="#attendance-tab-pane" type="button" role="tab">
                                <i class="fas fa-clipboard-list me-2"></i> Attendance by Class
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- GRADES TAB -->
                        <div class="tab-pane fade show active" id="grades-tab-pane" role="tabpanel" tabindex="0">
                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Select Class</label>
                                    <select class="form-select" id="gradeClassSelect" onchange="loadGradesData()">
                                        <option value="">All Classes</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->class_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Select Teacher</label>
                                    <select class="form-select" id="gradeTeacherSelect" onchange="loadGradesData()">
                                        <option value="">All Teachers</option>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Grading Period</label>
                                    <select class="form-select" id="gradePeriodSelect" onchange="loadGradesData()">
                                        <option value="">All Periods</option>
                                        <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($period); ?>"><?php echo e(ucfirst($period)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Search Student</label>
                                    <input type="text" class="form-control" id="gradeSearchInput"
                                        placeholder="Student name..." onkeyup="loadGradesData()">
                                </div>
                            </div>

                            <div id="gradesContainer" style="min-height: 300px;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Loading grades data...</p>
                                </div>
                            </div>
                        </div>

                        <!-- ATTENDANCE TAB -->
                        <div class="tab-pane fade" id="attendance-tab-pane" role="tabpanel" tabindex="0">
                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Select Class</label>
                                    <select class="form-select" id="attendanceClassSelect"
                                        onchange="loadAttendanceData()">
                                        <option value="">All Classes</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->class_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select" id="attendanceStatusSelect"
                                        onchange="loadAttendanceData()">
                                        <option value="">All Status</option>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Late">Late</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">From Date</label>
                                    <input type="date" class="form-control" id="attendanceDateFrom"
                                        onchange="loadAttendanceData()">
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label class="form-label fw-bold">To Date</label>
                                    <input type="date" class="form-control" id="attendanceDateTo"
                                        onchange="loadAttendanceData()">
                                </div>
                            </div>

                            <div id="attendanceContainer" style="min-height: 300px;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p class="mt-2">Loading attendance data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.8;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2rem;
            margin: 10px 0;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e7f3ff;
            color: #3498db;
        }

        .accordion-button:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        .badge {
            margin-right: 5px;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: #3498db;
            border-bottom-color: #3498db;
        }

        .nav-tabs .nav-link:hover {
            color: #3498db;
        }
    </style>

    <!-- Chart.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // Initialize charts
        function initializeCharts() {
            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart')?.getContext('2d');
            if (enrollmentCtx) {
                new Chart(enrollmentCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                            'Dec'],
                        datasets: [{
                            label: 'Student Enrollment',
                            data: [850, 920, 980, 1050, 1120, 1150, 1180, 1220, 1250, 1280, 1285, 1285],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Class Distribution Chart
            const classCtx = document.getElementById('classDistributionChart')?.getContext('2d');
            if (classCtx) {
                const classLabels = <?php echo json_encode($classesWithStudents->pluck('class_name')->toArray(), 15, 512) ?>;
                const classData = <?php echo json_encode($classesWithStudents->pluck('students_count')->toArray(), 15, 512) ?>;

                new Chart(classCtx, {
                    type: 'doughnut',
                    data: {
                        labels: classLabels,
                        datasets: [{
                            data: classData,
                            backgroundColor: [
                                '#3498db', '#27ae60', '#f39c12', '#e74c3c', '#9b59b6',
                                '#1abc9c', '#34495e', '#16a085', '#c0392b', '#8e44ad'
                            ]
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
            }
        }

        // Load Grades Data
        function loadGradesData() {
            const classId = document.getElementById('gradeClassSelect').value;
            const teacherId = document.getElementById('gradeTeacherSelect').value;
            const period = document.getElementById('gradePeriodSelect').value;
            const search = document.getElementById('gradeSearchInput').value;

            const params = new URLSearchParams({
                class_id: classId,
                teacher_id: teacherId,
                period: period,
                search: search
            });

            fetch(`<?php echo e(route('admin.grades.by-class')); ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.data.grades.data || data.data.grades.data.length === 0) {
                        document.getElementById('gradesContainer').innerHTML =
                            '<div class="alert alert-info text-center"><i class="fas fa-info-circle me-2"></i> No grades found</div>';
                        return;
                    }

                    const gradesByClass = {};
                    data.data.grades.data.forEach(grade => {
                        const className = grade.class.class_name;
                        if (!gradesByClass[className]) {
                            gradesByClass[className] = {
                                items: [],
                                passed: 0,
                                failed: 0,
                                total: 0,
                                average: 0
                            };
                        }
                        gradesByClass[className].items.push(grade);
                        gradesByClass[className].total++;
                        gradesByClass[className].average += grade.final_grade;

                        if (grade.final_grade >= 75) {
                            gradesByClass[className].passed++;
                        } else {
                            gradesByClass[className].failed++;
                        }
                    });

                    // Calculate averages
                    Object.keys(gradesByClass).forEach(className => {
                        gradesByClass[className].average /= gradesByClass[className].total;
                    });

                    let html = '<div class="accordion" id="gradesAccordion">';
                    let index = 0;

                    Object.keys(gradesByClass).forEach(className => {
                        const classData = gradesByClass[className];
                        html += `
                            <div class="accordion-item border-0 shadow-sm mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#gradesCollapse${index}">
                                        <i class="fas fa-book me-2" style="color: #3498db;"></i>
                                        <strong>${className}</strong>
                                        <span class="badge bg-info ms-2">${classData.total} Students</span>
                                        <span class="badge bg-success ms-1">Pass: ${classData.passed}</span>
                                        <span class="badge bg-danger ms-1">Fail: ${classData.failed}</span>
                                        <span class="badge bg-primary ms-auto">Avg: ${classData.average.toFixed(2)}</span>
                                    </button>
                                </h2>
                                <div id="gradesCollapse${index}" class="accordion-collapse collapse show" data-bs-parent="#gradesAccordion">
                                    <div class="accordion-body p-0">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Teacher</th>
                                                    <th>Final Grade</th>
                                                    <th>Period</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        `;

                        classData.items.forEach(grade => {
                            const statusBadge = grade.final_grade >= 75 ?
                                '<span class="badge bg-success">Passed</span>' :
                                '<span class="badge bg-danger">Failed</span>';

                            html += `
                                <tr>
                                    <td><strong>${grade.student.user.name}</strong></td>
                                    <td>${grade.teacher.name}</td>
                                    <td><strong>${grade.final_grade}</strong></td>
                                    <td>${grade.grading_period || 'N/A'}</td>
                                    <td>${statusBadge}</td>
                                </tr>
                            `;
                        });

                        html += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                        index++;
                    });

                    html += '</div>';
                    document.getElementById('gradesContainer').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('gradesContainer').innerHTML =
                        '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i> Error loading grades</div>';
                });
        }

        // Load Attendance Data
        function loadAttendanceData() {
            const classId = document.getElementById('attendanceClassSelect').value;
            const status = document.getElementById('attendanceStatusSelect').value;
            const dateFrom = document.getElementById('attendanceDateFrom').value;
            const dateTo = document.getElementById('attendanceDateTo').value;

            const params = new URLSearchParams({
                class_id: classId,
                status: status,
                date_from: dateFrom,
                date_to: dateTo
            });

            fetch(`<?php echo e(route('admin.attendance.by-class')); ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.data.attendance.data || data.data.attendance.data.length === 0) {
                        document.getElementById('attendanceContainer').innerHTML =
                            '<div class="alert alert-info text-center"><i class="fas fa-info-circle me-2"></i> No attendance records found</div>';
                        return;
                    }

                    const attendanceByClass = {};
                    data.data.attendance.data.forEach(record => {
                        const className = record.class.class_name;
                        if (!attendanceByClass[className]) {
                            attendanceByClass[className] = {
                                items: [],
                                present: 0,
                                absent: 0,
                                late: 0,
                                total: 0
                            };
                        }
                        attendanceByClass[className].items.push(record);
                        attendanceByClass[className].total++;

                        if (record.status === 'Present') {
                            attendanceByClass[className].present++;
                        } else if (record.status === 'Absent') {
                            attendanceByClass[className].absent++;
                        } else if (record.status === 'Late') {
                            attendanceByClass[className].late++;
                        }
                    });

                    let html = '<div class="accordion" id="attendanceAccordion">';
                    let index = 0;

                    Object.keys(attendanceByClass).forEach(className => {
                        const classData = attendanceByClass[className];
                        const rate = ((classData.present / classData.total) * 100).toFixed(1);

                        html += `
                            <div class="accordion-item border-0 shadow-sm mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#attendanceCollapse${index}">
                                        <i class="fas fa-clipboard-list me-2" style="color: #27ae60;"></i>
                                        <strong>${className}</strong>
                                        <span class="badge bg-success ms-2">Present: ${classData.present}</span>
                                        <span class="badge bg-danger ms-1">Absent: ${classData.absent}</span>
                                        <span class="badge bg-warning ms-1">Late: ${classData.late}</span>
                                        <span class="badge bg-primary ms-auto">Rate: ${rate}%</span>
                                    </button>
                                </h2>
                                <div id="attendanceCollapse${index}" class="accordion-collapse collapse show" data-bs-parent="#attendanceAccordion">
                                    <div class="accordion-body p-0">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        `;

                        classData.items.forEach(record => {
                            const statusBadge = `<span class="badge ${
                                record.status === 'Present' ? 'bg-success' : 
                                record.status === 'Absent' ? 'bg-danger' : 'bg-warning'
                            }">${record.status}</span>`;

                            const recordDate = new Date(record.date).toLocaleDateString();

                            html += `
                                <tr>
                                    <td><strong>${record.student.user.name}</strong></td>
                                    <td>${recordDate}</td>
                                    <td>${statusBadge}</td>
                                    <td>${record.notes || '-'}</td>
                                </tr>
                            `;
                        });

                        html += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                        index++;
                    });

                    html += '</div>';
                    document.getElementById('attendanceContainer').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('attendanceContainer').innerHTML =
                        '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i> Error loading attendance</div>';
                });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            loadGradesData();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\index.blade.php ENDPATH**/ ?>