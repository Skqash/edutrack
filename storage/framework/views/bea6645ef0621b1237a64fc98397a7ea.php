<?php $__env->startSection('content'); ?>
    <style>
        /* Enhanced Professional Dashboard Styling */
        body {
            background-color: #f8f9fa;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
        }

        .stat-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
        }

        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .campus-status-card {
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .campus-status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: currentColor;
        }

        .campus-approved {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .campus-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        .campus-rejected {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .campus-independent {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .performance-metric {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        .trend-neutral { color: #6c757d; }

        .activity-item {
            padding: 1rem;
            border-left: 4px solid #e9ecef;
            margin-bottom: 1rem;
            background: white;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            border-left-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .quick-action-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            cursor: pointer;
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
        }

        .quick-action-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .quick-action-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .campus-status-card {
                padding: 1rem;
                margin-bottom: 1.5rem;
            }
        }
    </style>

    <!-- Enhanced Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-2">
                    <div class="me-3">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <div>
                        <h1 class="h2 fw-bold mb-1">Welcome back, <?php echo e(auth()->user()->name); ?>! 👋</h1>
                        <p class="mb-0 opacity-90"><?php echo e(now()->format('l, F j, Y')); ?></p>
                    </div>
                </div>
                
                <?php if(isset($campusInfo)): ?>
                    <div class="d-flex align-items-center">
                        <i class="<?php echo e($campusInfo['icon']); ?> me-2"></i>
                        <span><?php echo e($campusInfo['name']); ?></span>
                        <?php if($campusInfo['status'] === 'approved'): ?>
                            <span class="badge bg-success bg-opacity-25 ms-2">✓ Approved</span>
                        <?php elseif($campusInfo['status'] === 'pending'): ?>
                            <span class="badge bg-warning bg-opacity-25 ms-2">⏳ Pending</span>
                        <?php elseif($campusInfo['status'] === 'rejected'): ?>
                            <span class="badge bg-danger bg-opacity-25 ms-2">✗ Rejected</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end">
                <?php if($isApproved && $myClasses && method_exists($myClasses, 'count') && $myClasses->count() > 0): ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light btn-lg dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-keyboard me-2"></i>Quick Grade
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="dropdown-header"><?php echo e($class->class_name); ?></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=midterm">
                                    <i class="fas fa-star me-2"></i>Midterm Grades
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=final">
                                    <i class="fas fa-trophy me-2"></i>Final Grades
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <button class="btn btn-light btn-lg" disabled>
                        <i class="fas fa-lock me-2"></i>
                        <?php echo e($isApproved ? 'No Classes' : 'Approval Required'); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enhanced Campus Status Card -->
    <?php if(isset($campusInfo)): ?>
        <div class="campus-status-card campus-<?php echo e($campusInfo['status']); ?>">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="<?php echo e($campusInfo['icon']); ?> fa-3x"></i>
                </div>
                <div class="col">
                    <h5 class="fw-bold mb-1"><?php echo e($campusInfo['name']); ?></h5>
                    <p class="mb-2 opacity-90"><?php echo e($campusInfo['description']); ?></p>
                    <?php if(isset($campusInfo['school'])): ?>
                        <small class="opacity-75">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?php echo e($campusInfo['school']->city ?? ''); ?>, <?php echo e($campusInfo['school']->province ?? ''); ?>

                        </small>
                    <?php endif; ?>
                </div>
                <?php if(isset($performanceMetrics)): ?>
                    <div class="col-md-auto d-none d-md-block">
                        <div class="row g-3">
                            <div class="col">
                                <div class="performance-metric">
                                    <div class="h4 fw-bold mb-1"><?php echo e($performanceMetrics['current']['grades']); ?></div>
                                    <small>Grades This Month</small>
                                    <?php if($performanceMetrics['trends']['grades']['direction'] !== 'neutral'): ?>
                                        <div class="trend-<?php echo e($performanceMetrics['trends']['grades']['direction']); ?>">
                                            <i class="fas fa-arrow-<?php echo e($performanceMetrics['trends']['grades']['direction'] === 'up' ? 'up' : 'down'); ?>"></i>
                                            <?php echo e($performanceMetrics['trends']['grades']['percentage']); ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="performance-metric">
                                    <div class="h4 fw-bold mb-1"><?php echo e($performanceMetrics['current']['attendance']); ?></div>
                                    <small>Attendance Records</small>
                                    <?php if($performanceMetrics['trends']['attendance']['direction'] !== 'neutral'): ?>
                                        <div class="trend-<?php echo e($performanceMetrics['trends']['attendance']['direction']); ?>">
                                            <i class="fas fa-arrow-<?php echo e($performanceMetrics['trends']['attendance']['direction'] === 'up' ? 'up' : 'down'); ?>"></i>
                                            <?php echo e($performanceMetrics['trends']['attendance']['percentage']); ?>%
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-lg-3">
            <div class="stat-card h-100" onclick="window.location.href='<?php echo e(route('teacher.classes')); ?>'">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-primary"><?php echo e($statistics['totalClasses'] ?? 0); ?></h2>
                            <small class="text-muted">Classes</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo e(min(($statistics['totalClasses'] ?? 0) * 10, 100)); ?>%"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-arrow-right me-1"></i>Manage Classes
                    </small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100" onclick="window.location.href='<?php echo e(route('teacher.classes')); ?>'">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-success"><?php echo e($statistics['totalStudents'] ?? 0); ?></h2>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: <?php echo e(min(($statistics['totalStudents'] ?? 0) * 2, 100)); ?>%"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-arrow-right me-1"></i>View Students
                    </small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100" onclick="window.location.href='<?php echo e(route('teacher.grades')); ?>'">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-warning"><?php echo e($statistics['gradesPosted'] ?? 0); ?></h2>
                            <small class="text-muted">Grades Posted</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: <?php echo e(min(($statistics['gradesPosted'] ?? 0) * 1, 100)); ?>%"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-arrow-right me-1"></i>Grade Management
                    </small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100" onclick="window.location.href='<?php echo e(route('teacher.grades')); ?>'">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="fas fa-clock fa-2x text-danger"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-danger"><?php echo e($statistics['pendingGrades'] ?? 0); ?></h2>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-danger" style="width: <?php echo e(min(($statistics['pendingGrades'] ?? 0) * 10, 100)); ?>%"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-arrow-right me-1"></i>Complete Grades
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced KSA Grading System & Recent Activities -->
    <div class="row mb-4 g-3">
        <!-- KSA System Overview -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-star me-2"></i>KSA Grading System Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-muted mb-3">Comprehensive assessment based on three key components with performance insights:</p>

                            <!-- Knowledge Component -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="fas fa-brain me-2"></i>Knowledge (40%)
                                    </h6>
                                    <span class="badge bg-primary">Avg: <?php echo e($statistics['averages']['knowledge'] ?? 0); ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: <?php echo e($statistics['averages']['knowledge'] ?? 0); ?>%"></div>
                                </div>
                                <small class="text-muted">Quizzes (40%) + Exams (60%)</small>
                            </div>

                            <!-- Skills Component -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-success">
                                        <i class="fas fa-tools me-2"></i>Skills (50%)
                                    </h6>
                                    <span class="badge bg-success">Avg: <?php echo e($statistics['averages']['skills'] ?? 0); ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo e($statistics['averages']['skills'] ?? 0); ?>%"></div>
                                </div>
                                <small class="text-muted">Output (40%) + Class Part (30%) + Activities (15%) + Assignments (15%)</small>
                            </div>

                            <!-- Attitude Component -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-info">
                                        <i class="fas fa-handshake me-2"></i>Attitude (10%)
                                    </h6>
                                    <span class="badge bg-info">Avg: <?php echo e($statistics['averages']['attitude'] ?? 0); ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: <?php echo e($statistics['averages']['attitude'] ?? 0); ?>%"></div>
                                </div>
                                <small class="text-muted">Behavior (50%) + Awareness (50%)</small>
                            </div>

                            <div class="alert alert-light border-start border-4 border-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Final Grade Formula:</strong>
                                        <code>(K × 0.40) + (S × 0.50) + (A × 0.10)</code>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-primary"><?php echo e($statistics['averages']['final_grade'] ?? 0); ?>%</div>
                                        <small class="text-muted">Overall Average</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-4x text-primary mb-3"></i>
                                <h5 class="fw-bold">Balanced Assessment</h5>
                                <p class="text-muted small">Comprehensive evaluation system for holistic student development</p>
                            </div>
                            
                            <?php if($isApproved): ?>
                                <div class="d-grid gap-2">
                                    <a href="<?php echo e(route('teacher.grades.settings', ['classId' => $myClasses->first()->id ?? 1])); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-cog me-1"></i>Grade Settings
                                    </a>
                                    <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-chart-line me-1"></i>View Analytics
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-clock me-2"></i>Recent Activities
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if(isset($recentActivities) && is_array($recentActivities) && count($recentActivities) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = array_slice($recentActivities, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-<?php echo e($activity['color']); ?> bg-opacity-10 p-2">
                                                <i class="<?php echo e($activity['icon']); ?> text-<?php echo e($activity['color']); ?>"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold"><?php echo e($activity['title']); ?></h6>
                                            <p class="mb-1 small text-muted"><?php echo e($activity['description']); ?></p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo e($activity['time']->diffForHumans()); ?>

                                            </small>
                                        </div>
                                        <?php if(isset($activity['link'])): ?>
                                            <a href="<?php echo e($activity['link']); ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent activities</p>
                            <small class="text-muted">Start grading or managing attendance to see activities here</small>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if(isset($recentActivities) && is_array($recentActivities) && count($recentActivities) > 5): ?>
                    <div class="card-footer bg-light text-center">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All Activities</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enhanced My Classes Section -->
    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-chalkboard-teacher me-2"></i>My Classes
                            <?php if(isset($campusInfo) && $campusInfo['type'] === 'campus'): ?>
                                <span class="badge bg-info ms-2"><?php echo e($campusInfo['short_name']); ?></span>
                            <?php endif; ?>
                        </h5>
                        <?php if($isApproved): ?>
                            <div class="btn-group">
                                <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Add Class
                                </a>
                                <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo e(route('teacher.classes')); ?>">
                                        <i class="fas fa-list me-2"></i>Manage All Classes
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('teacher.subjects')); ?>">
                                        <i class="fas fa-book me-2"></i>My Subjects
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades')); ?>">
                                        <i class="fas fa-chart-line me-2"></i>Grade Analytics
                                    </a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <span class="badge bg-warning">
                                <i class="fas fa-lock me-1"></i>Campus Approval Required
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($isApproved && $myClasses && method_exists($myClasses, 'count') && $myClasses->count() > 0): ?>
                        <div class="row g-3">
                            <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card quick-action-card h-100" onclick="window.location.href='<?php echo e(route('teacher.classes.show', $class->id)); ?>'">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title mb-0 fw-bold"><?php echo e($class->class_name); ?></h6>
                                                <span class="badge bg-primary"><?php echo e($class->students ? $class->students->count() : 0); ?> students</span>
                                            </div>
                                            
                                            <?php if($class->subject): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-book me-1"></i><?php echo e($class->subject->subject_name); ?>

                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if($class->course): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-graduation-cap me-1"></i><?php echo e($class->course->program_name); ?>

                                                    </small>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($class->school): ?>
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-university me-1"></i><?php echo e($class->school->short_name); ?>

                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i><?php echo e($class->academic_year ?? 'Current'); ?>

                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-success btn-sm" 
                                                            onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?php echo e(route('teacher.grades.entry', $class->id)); ?>'"
                                                            title="Grade Entry">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info btn-sm" 
                                                            onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?php echo e(route('teacher.attendance.manage', $class->id)); ?>'"
                                                            title="Attendance">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                            onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?php echo e(route('teacher.classes.edit', $class->id)); ?>'"
                                                            title="Edit Class">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php elseif(!$isApproved): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-lock fa-4x text-warning mb-3"></i>
                            <h5 class="text-muted">Campus Approval Required</h5>
                            <p class="text-muted">Your campus affiliation is <?php echo e(auth()->user()->campus_status ?? 'pending'); ?>. Contact your campus admin for approval to access classes and grading features.</p>
                            <div class="alert alert-warning d-inline-block">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status:</strong> <?php echo e(ucfirst(auth()->user()->campus_status ?? 'pending')); ?>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Classes Yet</h5>
                            <p class="text-muted">Create your first class to start managing students, grades, and attendance.</p>
                            <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create First Class
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Quick Actions -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card quick-action-card h-100 <?php echo e(!$isApproved ? 'disabled' : ''); ?>" 
                 onclick="<?php echo e($isApproved ? "window.location.href='" . route('teacher.subjects') . "'" : ''); ?>">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-<?php echo e($isApproved ? 'book' : 'lock'); ?> fa-3x text-<?php echo e($isApproved ? 'primary' : 'muted'); ?>"></i>
                    </div>
                    <h6 class="fw-bold <?php echo e(!$isApproved ? 'text-muted' : ''); ?>">My Subjects</h6>
                    <p class="text-muted small mb-3">
                        <?php echo e($isApproved ? 'Manage your teaching subjects and curriculum' : 'Campus approval required'); ?>

                    </p>
                    <?php if($isApproved && isset($assignedSubjects)): ?>
                        <span class="badge bg-primary"><?php echo e($assignedSubjects ? $assignedSubjects->count() : 0); ?> subjects</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card quick-action-card h-100 <?php echo e(!$isApproved ? 'disabled' : ''); ?>" 
                 onclick="<?php echo e($isApproved ? "window.location.href='" . route('teacher.grades') . "'" : ''); ?>">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-<?php echo e($isApproved ? 'chart-line' : 'lock'); ?> fa-3x text-<?php echo e($isApproved ? 'success' : 'muted'); ?>"></i>
                    </div>
                    <h6 class="fw-bold <?php echo e(!$isApproved ? 'text-muted' : ''); ?>">Grade Analytics</h6>
                    <p class="text-muted small mb-3">
                        <?php echo e($isApproved ? 'View comprehensive grading statistics and reports' : 'Campus approval required'); ?>

                    </p>
                    <?php if($isApproved && isset($statistics)): ?>
                        <span class="badge bg-success"><?php echo e($statistics['averages']['final_grade']); ?>% avg</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card quick-action-card h-100 <?php echo e(!$isApproved ? 'disabled' : ''); ?>" 
                 onclick="<?php echo e($isApproved ? "window.location.href='" . route('teacher.attendance') . "'" : ''); ?>">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-<?php echo e($isApproved ? 'calendar-check' : 'lock'); ?> fa-3x text-<?php echo e($isApproved ? 'warning' : 'muted'); ?>"></i>
                    </div>
                    <h6 class="fw-bold <?php echo e(!$isApproved ? 'text-muted' : ''); ?>">Attendance Management</h6>
                    <p class="text-muted small mb-3">
                        <?php echo e($isApproved ? 'Track and manage student attendance records' : 'Campus approval required'); ?>

                    </p>
                    <?php if($isApproved && isset($statistics)): ?>
                        <span class="badge bg-warning"><?php echo e($statistics['attendanceRecords']); ?> records</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Tasks Section -->
    <?php if($isApproved && isset($pendingTasks) && is_array($pendingTasks) && count($pendingTasks) > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h6 class="mb-0 fw-bold text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Pending Tasks (<?php echo e(is_array($pendingTasks) ? count($pendingTasks) : 0); ?>)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php $__currentLoopData = array_slice($pendingTasks, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <div class="alert alert-<?php echo e($task['priority'] === 'high' ? 'danger' : ($task['priority'] === 'medium' ? 'warning' : 'info')); ?> mb-0">
                                        <h6 class="alert-heading fw-bold"><?php echo e($task['title']); ?></h6>
                                        <p class="mb-2 small"><?php echo e($task['description']); ?></p>
                                        <?php if(isset($task['link'])): ?>
                                            <a href="<?php echo e($task['link']); ?>" class="btn btn-sm btn-outline-<?php echo e($task['priority'] === 'high' ? 'danger' : ($task['priority'] === 'medium' ? 'warning' : 'info')); ?>">
                                                Take Action
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Security Policies & Profile Management Section -->
    <?php if($isApproved): ?>
        <div class="row mb-4 g-3">
            <!-- Security Policies -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-shield-alt me-2"></i>Security & Policies
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if(isset($securityPolicies) && is_array($securityPolicies) && count($securityPolicies) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php $__currentLoopData = $securityPolicies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-group-item border-0 px-0 py-2">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="rounded-circle bg-<?php echo e($policy['type']); ?> bg-opacity-10 p-2">
                                                    <i class="<?php echo e($policy['icon']); ?> text-<?php echo e($policy['type']); ?>"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold"><?php echo e($policy['title']); ?></h6>
                                                <p class="mb-1 small text-muted"><?php echo e($policy['description']); ?></p>
                                                <?php if($policy['enforced']): ?>
                                                    <span class="badge bg-success bg-opacity-25 text-success">
                                                        <i class="fas fa-check me-1"></i>Enforced
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-shield-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No security policies configured</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Profile Management -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-user-cog me-2"></i>Profile Management
                            </h6>
                            <a href="<?php echo e(route('teacher.profile.edit')); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(isset($profileManagement)): ?>
                            <!-- Profile Completion -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Profile Completion</span>
                                    <span class="badge bg-primary"><?php echo e($profileManagement['profile_completion']['percentage']); ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: <?php echo e($profileManagement['profile_completion']['percentage']); ?>%"></div>
                                </div>
                                <small class="text-muted">
                                    <?php echo e($profileManagement['profile_completion']['completed_fields']); ?>/<?php echo e($profileManagement['profile_completion']['total_fields']); ?> fields completed
                                </small>
                            </div>

                            <!-- Campus Connections -->
                            <?php if(isset($profileManagement['campus_connections']) && is_array($profileManagement['campus_connections']) && count($profileManagement['campus_connections']) > 0): ?>
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-2">Campus Connections</h6>
                                    <?php $__currentLoopData = $profileManagement['campus_connections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $connection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2">
                                            <div>
                                                <div class="fw-bold"><?php echo e($connection['short_name']); ?></div>
                                                <small class="text-muted"><?php echo e($connection['role']); ?> since <?php echo e($connection['since']->format('M Y')); ?></small>
                                            </div>
                                            <span class="badge bg-<?php echo e($connection['status'] === 'approved' ? 'success' : 'warning'); ?>">
                                                <?php echo e(ucfirst($connection['status'])); ?>

                                            </span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Quick Actions -->
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('teacher.profile.change-password')); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-key me-1"></i>Change Password
                                </a>
                                <a href="<?php echo e(route('teacher.settings.index')); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-cog me-1"></i>Account Settings
                                </a>
                                <?php if(empty(auth()->user()->campus)): ?>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#campusRequestModal">
                                        <i class="fas fa-university me-1"></i>Request Campus Affiliation
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#campusChangeModal">
                                        <i class="fas fa-exchange-alt me-1"></i>Request Campus Change
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enhanced Teacher CRUD Modals -->
    
    <!-- Subject Creation Modal -->
    <div class="modal fade" id="createSubjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-book me-2"></i>Create New Subject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('teacher.subjects.create')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="subject_name" class="form-label">Subject Name *</label>
                                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="subject_code" class="form-label">Subject Code *</label>
                                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                                <?php if(isset($campusInfo) && $campusInfo['type'] === 'campus'): ?>
                                    <small class="text-muted">Campus code will be automatically added</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="credit_hours" class="form-label">Credit Hours *</label>
                                <select class="form-select" id="credit_hours" name="credit_hours" required>
                                    <option value="">Select Credit Hours</option>
                                    <option value="1">1 Credit Hour</option>
                                    <option value="2">2 Credit Hours</option>
                                    <option value="3" selected>3 Credit Hours</option>
                                    <option value="4">4 Credit Hours</option>
                                    <option value="5">5 Credit Hours</option>
                                    <option value="6">6 Credit Hours</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="semester" class="form-label">Semester *</label>
                                <select class="form-select" id="semester" name="semester" required>
                                    <option value="">Select Semester</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="year_level" class="form-label">Year Level</label>
                                <select class="form-select" id="year_level" name="year_level">
                                    <option value="">Select Year Level</option>
                                    <option value="1" selected>1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Core">Core Subject</option>
                                    <option value="Major">Major Subject</option>
                                    <option value="Minor">Minor Subject</option>
                                    <option value="Elective">Elective</option>
                                    <option value="General">General Education</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the subject..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Subject
                        </button>
                    </div>
                </form>
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
                <form action="<?php echo e(route('teacher.request.campus-change')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                <form action="<?php echo e(route('teacher.request.campus-change')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Current Campus:</strong> <?php echo e(auth()->user()->campus ?? 'None'); ?>

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

    <!-- Quick Actions Floating Button -->
    <?php if($isApproved): ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div class="btn-group-vertical">
                <button type="button" class="btn btn-primary btn-lg rounded-circle mb-2" data-bs-toggle="modal" data-bs-target="#createSubjectModal" title="Create Subject">
                    <i class="fas fa-book"></i>
                </button>
                <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-success btn-lg rounded-circle mb-2" title="Create Class">
                    <i class="fas fa-plus"></i>
                </a>
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-warning btn-lg rounded-circle" title="Grade Entry">
                    <i class="fas fa-star"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>