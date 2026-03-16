<?php $__env->startSection('content'); ?>
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
                                <h3 class="text-primary mb-0"><?php echo e($totalUsers ?? 0); ?></h3>
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
                                <h3 class="text-info mb-0"><?php echo e($totalAdmins ?? 0); ?></h3>
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
                                <h3 class="text-success mb-0"><?php echo e($totalTeachers ?? 0); ?></h3>
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
                                <h3 class="text-danger mb-0"><?php echo e($totalStudents ?? 0); ?></h3>
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
                        <h3 class="text-warning mb-0"><?php echo e($totalCourses ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Active Courses</p>
                        <h3 class="text-success mb-0"><?php echo e($activeCourses ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Inactive Courses</p>
                        <h3 class="text-secondary mb-0"><?php echo e($inactiveCourses ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Total Subjects</p>
                        <h3 class="text-primary mb-0"><?php echo e($totalSubjects ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Total Classes</p>
                        <h3 class="text-info mb-0"><?php echo e($totalClasses ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Active Classes</p>
                        <h3 class="text-success mb-0"><?php echo e($activeClasses ?? 0); ?></h3>
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
                                <strong><?php echo e($totalCapacity ?? 0); ?> seats</strong>
                            </div>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-primary" style="width: 100%">
                                    <?php echo e($totalCapacity ?? 0); ?> seats
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Enrolled</span>
                                <strong><?php echo e($totalEnrolled ?? 0); ?> students</strong>
                            </div>
                            <div class="progress" style="height: 30px;">
                                <?php
                                    $occupancyPercent =
                                        $totalCapacity > 0 ? round(($totalEnrolled / $totalCapacity) * 100) : 0;
                                    $progressColor =
                                        $occupancyPercent > 80
                                            ? 'danger'
                                            : ($occupancyPercent > 60
                                                ? 'warning'
                                                : 'success');
                                ?>
                                <div class="progress-bar bg-<?php echo e($progressColor); ?>" style="width: <?php echo e($occupancyPercent); ?>%">
                                    <?php echo e($occupancyPercent); ?>%
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-muted small">
                            <p class="mb-0">Occupancy Rate: <strong><?php echo e($occupancyPercent ?? 0); ?>%</strong></p>
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                        style="width: 32px; height: 32px;">
                                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                    </div>
                                                    <span><?php echo e($user->name); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td>
                                                <?php if($user->role === 'admin'): ?>
                                                    <span class="badge bg-danger"><?php echo e(ucfirst($user->role)); ?></span>
                                                <?php elseif($user->role === 'teacher'): ?>
                                                    <span class="badge bg-success"><?php echo e(ucfirst($user->role)); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-info"><?php echo e(ucfirst($user->role)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small"><?php echo e($user->created_at->diffForHumans()); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox"></i> No users yet
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($course->course_name); ?></td>
                                            <td><strong><?php echo e($course->course_code); ?></strong></td>
                                            <td><?php echo e($course->instructor->name ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if($course->status === 'Active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox"></i> No courses yet
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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
                        <h5 class="mb-0"><i class="fas fa-tools"></i> System Management & Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <!-- User Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.users.index')); ?>" class="btn btn-primary w-100 py-3">
                                    <i class="fas fa-users"></i><br><small>Manage Users</small>
                                </a>
                            </div>

                            <!-- Course Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.courses.index')); ?>" class="btn btn-success w-100 py-3">
                                    <i class="fas fa-book"></i><br><small>Manage Courses</small>
                                </a>
                            </div>

                            <!-- Class Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.classes.index')); ?>" class="btn btn-info w-100 py-3">
                                    <i class="fas fa-building"></i><br><small>Manage Classes</small>
                                </a>
                            </div>

                            <!-- Student Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.students.index')); ?>" class="btn btn-warning w-100 py-3">
                                    <i class="fas fa-graduation-cap"></i><br><small>Manage Students</small>
                                </a>
                            </div>

                            <!-- Grade Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.grades.index')); ?>" class="btn btn-danger w-100 py-3">
                                    <i class="fas fa-star"></i><br><small>Manage Grades</small>
                                </a>
                            </div>

                            <!-- Attendance Management -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.attendance.index')); ?>" class="btn btn-secondary w-100 py-3">
                                    <i class="fas fa-clipboard-list"></i><br><small>Attendance</small>
                                </a>
                            </div>

                            <!-- Query Database -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.tools.query')); ?>" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-database"></i><br><small>Query DB</small>
                                </a>
                            </div>

                            <!-- System Health -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.health')); ?>" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-heartbeat"></i><br><small>System Health</small>
                                </a>
                            </div>

                            <!-- View Logs -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.logs.view')); ?>" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-file-alt"></i><br><small>View Logs</small>
                                </a>
                            </div>

                            <!-- Database Backup -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.tools.backup')); ?>" class="btn btn-outline-danger w-100 py-3"
                                    onclick="return confirm('Download database backup?')">
                                    <i class="fas fa-save"></i><br><small>Backup DB</small>
                                </a>
                            </div>

                            <!-- Database Stats -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.tools.database')); ?>" class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-chart-bar"></i><br><small>DB Stats</small>
                                </a>
                            </div>

                            <!-- Database Cleanup -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="<?php echo e(route('super.cleanup')); ?>" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-trash"></i><br><small>Cleanup Data</small>
                                </a>
                            </div>

                            <!-- Clear Caches -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <form action="<?php echo e(route('super.system.cache-clear')); ?>" method="POST"
                                    style="display:inline; width: 100%;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-dark w-100 py-3"
                                        onclick="return confirm('Clear all caches?')">
                                        <i class="fas fa-broom"></i><br><small>Clear Caches</small>
                                    </button>
                                </form>
                            </div>

                            <!-- Run Migrations -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <form action="<?php echo e(route('super.system.migrate')); ?>" method="POST"
                                    style="display:inline; width: 100%;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-primary w-100 py-3"
                                        onclick="return confirm('Run database migrations?')">
                                        <i class="fas fa-cog"></i><br><small>Migrate DB</small>
                                    </button>
                                </form>
                            </div>

                            <!-- Run Seeders -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <form action="<?php echo e(route('super.system.seed')); ?>" method="POST"
                                    style="display:inline; width: 100%;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-success w-100 py-3"
                                        onclick="return confirm('Reseed database with test data?')">
                                        <i class="fas fa-leaf"></i><br><small>Seed DB</small>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <?php if(isset($dbStats)): ?>
                            <hr class="my-3">
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p class="text-muted mb-2"><strong>Database Status:</strong></p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Status</small>
                                    <?php if($dbStats['status'] === 'Connected'): ?>
                                        <p class="fw-bold"><i class="fas fa-check-circle text-success"></i> Connected</p>
                                    <?php else: ?>
                                        <p class="fw-bold"><i class="fas fa-times-circle text-danger"></i> Error</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Database</small>
                                    <p class="fw-bold"><?php echo e($dbStats['database'] ?? 'N/A'); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Tables</small>
                                    <p class="fw-bold"><?php echo e($dbStats['tableCount'] ?? 0); ?> tables</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Users</small>
                                    <p class="fw-bold"><?php echo e($totalUsers ?? 0); ?> total</p>
                                </div>
                            </div>
                        <?php endif; ?>
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
                    data: [<?php echo e($totalAdmins); ?>, <?php echo e($totalTeachers); ?>, <?php echo e($totalStudents); ?>],
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\super\dashboard.blade.php ENDPATH**/ ?>