<?php $__env->startSection('content'); ?>
    <style>
        /* Professional dashboard styling with minimal colors */
        body {
            background-color: #f8f9fa;
        }

        .stat-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .stat-card h2 {
            font-size: 2rem;
            letter-spacing: -1px;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .feature-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .feature-card .card-body {
            padding: 1.75rem 1.5rem;
        }

        .feature-card .card-title {
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }

        /* Remove excessive border colors from components */
        .alert-light {
            border: none !important;
            background-color: #f8f9fa !important;
        }

        .card {
            border: none !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef !important;
            font-weight: 600;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Clean table styling */
        .table {
            color: #333 !important;
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f8f9fa !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #212529 !important;
        }

        .btn-group-sm .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.875rem;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Header enhancement */
        .header-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        /* Quick banner enhancement */
        .quick-banner {
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-banner i {
            opacity: 0.3;
        }

        .quick-banner h6,
        .quick-banner small {
            color: white !important;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #0066cc, transparent);
            border-radius: 2px;
        }

        /* 5-column stat cards layout */
        .col-lg-2-4 {
            flex: 0 0 calc(20% - 0.6rem);
            max-width: calc(20% - 0.6rem);
        }

        @media (max-width: 1199.98px) {
            .col-lg-2-4 {
                flex: 0 0 calc(50% - 0.75rem);
                max-width: calc(50% - 0.75rem);
            }
        }

        @media (max-width: 767.98px) {
            .col-lg-2-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .stat-card .card-body {
            padding: 1rem;
        }

        .stat-card h2 {
            font-size: 1.75rem;
        }

        .stat-card i {
            font-size: 2.5rem !important;
        }
    </style>

    <!-- Header Section -->
    <div class="header-section">
        <div class="row mb-0">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="h2 fw-bold mb-1">Welcome, <?php echo e(auth()->user()->name ?? 'Teacher'); ?>! 👋</h1>
                        <small class="text-muted d-block"><?php echo e(now()->format('l, F j, Y')); ?></small>
                    </div>
                    <?php if($myClasses && $myClasses->count() > 0): ?>
                        <div class="btn-group d-none d-md-inline-block">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-keyboard me-2"></i> Start Grading
                            </button>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="dropdown-header"><?php echo e($c->class_name ?? 'Class'); ?></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $c->id)); ?>?term=midterm">Midterm — <?php echo e($c->class_name); ?></a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $c->id)); ?>?term=final">Final — <?php echo e($c->class_name); ?></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-primary d-none d-md-inline-block" disabled>
                            <i class="fas fa-keyboard me-2"></i> Start Grading
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="quick-banner">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <i class="fas fa-rocket fa-2x"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1 fw-bold">Enhanced Grading System Ready</h6>
                        <small>Your dashboard is fully configured • All systems operational • Ready for grading</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Info Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="quick-banner">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <i class="fas fa-rocket fa-2x"></i>
                </div>
                <div class="col">
                    <h6 class="mb-1 fw-bold">Enhanced Grading System Ready</h6>
                    <small>Your dashboard is fully configured • All systems operational • Ready for grading</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Enhanced with gradients and improved spacing -->
<div class="row mb-4 g-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card h-100" style="border-left: 4px solid #0066cc;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2 small fw-semibold">Classes</h6>
                        <h2 class="mb-0 text-primary fw-bold"><?php echo e($myClasses ? $myClasses->count() : 0); ?></h2>
                    </div>
                    <i class="fas fa-door-open fa-3x text-primary opacity-15"></i>
                </div>
                <small class="text-muted d-block mt-2">Total classes</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card h-100" style="border-left: 4px solid #6f42c1;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2 small fw-semibold">Courses</h6>
                        <h2 class="mb-0 fw-bold" style="color: #6f42c1;"><?php echo e($myCourses ? $myCourses->count() : 0); ?></h2>
                    </div>
                    <i class="fas fa-building fa-3x opacity-15" style="color: #6f42c1;"></i>
                </div>
                <small class="text-muted d-block mt-2">Academic courses</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card h-100" style="border-left: 4px solid #00a86b;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2 small fw-semibold">Students</h6>
                        <h2 class="mb-0 fw-bold" style="color: #00a86b;"><?php echo e($totalStudents ?? 0); ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-15" style="color: #00a86b;"></i>
                </div>
                <small class="text-muted d-block mt-2">Total enrolled</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stat-card h-100" style="border-left: 4px solid #ff8c00;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-2 small fw-semibold">Grades Posted</h6>
                        <h2 class="mb-0 fw-bold" style="color: #ff8c00;"><?php echo e($gradesPosted ?? 0); ?></h2>
                    </div>
                    <i class="fas fa-star fa-3x opacity-15" style="color: #ff8c00;"></i>
                </div>
                <small class="text-muted d-block mt-2">Recently added</small>
            </div>
        </div>
    </div>
</div>

<!-- KSA Grading System Info -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fas fa-star me-2"></i> KSA Grading System
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="text-muted small mb-3">Comprehensive grading based on three key components:</p>

                        <div class="row">
                            <!-- Knowledge -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-dark small fw-bold">
                                        <i class="fas fa-brain me-2"></i> Knowledge
                                    </h6>
                                    <small class="text-muted"><strong>40%</strong></small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 40%; background-color: #0066cc;"></div>
                                </div>
                                <small class="text-muted d-block mt-1">Quizzes (40%) + Exams (60%)</small>
                            </div>

                            <!-- Skills -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold small text-dark">
                                        <i class="fas fa-tools me-2"></i> Skills
                                    </h6>
                                    <small class="text-muted"><strong>50%</strong></small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 50%; background-color: #28a745;"></div>
                                </div>
                                <small class="text-muted d-block mt-1">Output (40%) + Class Part (30%) + Activities (15%) +
                                    Assignments (15%)</small>
                            </div>

                            <!-- Attitude -->
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold small text-dark">
                                        <i class="fas fa-handshake me-2"></i> Attitude
                                    </h6>
                                    <small class="text-muted"><strong>10%</strong></small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 10%; background-color: #6c757d;"></div>
                                </div>
                                <small class="text-muted d-block mt-1">Behavior (50%) + Awareness (50%)</small>
                            </div>
                        </div>

                        <hr class="my-3">
                        <div class="alert alert-light mb-0 p-3 rounded">
                            <small class="fw-bold text-dark">Final Grade = (K × 0.40) + (S × 0.50) + (A × 0.10)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                            </div>
                            <h5 class="fw-bold text-primary">KSA System</h5>
                            <p class="text-muted small">Balanced assessment approach for comprehensive student evaluation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Two Column Section: Courses & Subjects -->
    <div class="row mb-4 g-3">
        <!-- Left: My Courses -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="fas fa-building me-2"></i> My Courses
                        </h5>
                        <span class="badge bg-secondary">
                            <i class="fas fa-info-circle me-1"></i> Admin Assigned
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Course</th>
                                    <th>Department</th>
                                    <th class="text-center">Classes</th>
                                    <th class="text-center">Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($myCourses && $myCourses->count() > 0): ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $myCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $courseClasses = $myClasses->where('course_id', $course->id);
                                            $courseStudentCount = $courseClasses->sum(function($class) {
                                                return $class->students->count();
                                            });
                                        ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong class="text-primary d-block"><?php echo e($course->course_name ?? 'N/A'); ?></strong>
                                                <small class="text-muted"><?php echo e($course->course_code ?? 'N/A'); ?></small>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo e($course->college ?? 'General Education'); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?php echo e($courseClasses->count()); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?php echo e($courseStudentCount); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                <small>No courses assigned</small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            <small>No courses available</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: My Classes -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="fas fa-chalkboard me-2"></i> My Classes - UPDATED!
                        </h5>
                        <div>
                            <button class="btn btn-sm fw-bold text-white me-2" 
                                    href="<?php echo e(route('teacher.classes.create')); ?>"
                                    style="background-color: #667eea; border: none;">
                                <i class="fas fa-plus me-1"></i> Create Class
                            </button>
                            <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-sm fw-bold text-white"
                                style="background-color: #0066cc; border: none;">View All</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Class Name</th>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th class="text-center">Students</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($myClasses && $myClasses->count() > 0): ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong class="text-primary d-block"><?php echo e($class->class_name ?? 'N/A'); ?></strong>
                                                <small class="text-muted"><?php echo e($class->section ?? 'Year 1'); ?> - <?php echo e($class->year ?? 'N/A'); ?> Year</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($class->course->subject_name ?? $class->course->course_name ?? 'N/A'); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($class->course->course_code ?? 'N/A'); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo e(route('teacher.classes.show', $class->id)); ?>" class="btn fw-bold text-white"
                                                        style="background-color: #00a86b; border: none;" title="View Class">  
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn fw-bold text-white"
                                                        data-bs-toggle="modal" data-bs-target="#addStudentModal"
                                                        onclick="selectClassForStudent(<?php echo e($class->id); ?>)"
                                                        style="background-color: #667eea; border: none;" title="Add Students">  
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-chalkboard fa-2x mb-2"></i><br>
                                                <small>No classes created yet</small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-chalkboard fa-2x mb-2"></i><br>
                                            <small>No classes available</small>
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


    <!-- Recent Grades Posted Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="section-title mb-3">
                <i class="fas fa-history me-2"></i> Recent Classes Graded
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if($recentGrades && $recentGrades->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 fw-bold">Class</th>
                                        <th class="fw-bold">Department</th>
                                        <th class="text-center fw-bold">Students</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Knowledge</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Skills</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Attitude</th>
                                        <th class="text-center fw-bold">Avg Grade</th>
                                        <th class="text-center fw-bold">Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold">
                                                <a href="<?php echo e(route('teacher.classes.show', $classData->class_id)); ?>" class="text-decoration-none text-dark">
                                                    <?php echo e($classData->class_name ?? 'N/A'); ?>

                                                </a>
                                            </td>
                                            <td><small class="text-muted"><?php echo e($classData->course_name ?? $classData->department_name ?? 'N/A'); ?></small></td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?php echo e($classData->student_count); ?></span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                <?php
                                                    $kPoint = \App\Models\Grade::getGradePoint($classData->avg_knowledge);
                                                    $kColor = \App\Models\Grade::getGradeColor($classData->avg_knowledge);
                                                ?>
                                                <span class="badge bg-<?php echo e($kColor); ?>">
                                                    <?php echo e(round($classData->avg_knowledge, 1)); ?>

                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                <?php
                                                    $sPoint = \App\Models\Grade::getGradePoint($classData->avg_skills);
                                                    $sColor = \App\Models\Grade::getGradeColor($classData->avg_skills);
                                                ?>
                                                <span class="badge bg-<?php echo e($sColor); ?>">
                                                    <?php echo e(round($classData->avg_skills, 1)); ?>

                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                <?php
                                                    $aPoint = \App\Models\Grade::getGradePoint($classData->avg_attitude);
                                                    $aColor = \App\Models\Grade::getGradeColor($classData->avg_attitude);
                                                ?>
                                                <span class="badge bg-<?php echo e($aColor); ?>">
                                                    <?php echo e(round($classData->avg_attitude, 1)); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    $fPoint = \App\Models\Grade::getGradePoint($classData->avg_final_grade);
                                                    $fColor = \App\Models\Grade::getGradeColor($classData->avg_final_grade);
                                                ?>
                                                <span class="badge bg-<?php echo e($fColor); ?> fw-bold">
                                                    <?php echo e(round($classData->avg_final_grade, 2)); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted d-block">
                                                    <?php if($classData->updated_at): ?>
                                                        <?php echo e($classData->updated_at->diffForHumans()); ?>

                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i><br>
                            <small>No grades posted yet • Start by creating a class and adding students</small>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($recentGrades && $recentGrades->count() > 0): ?>
                    <div class="card-footer bg-light text-center">
                        <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-sm btn-outline-primary fw-bold">
                            <i class="fas fa-list me-1"></i> View All Grades
                        </a>
                    </div>
                <?php endif; ?>
    </div>

    <!-- Add Student Modal -->
    <?php echo $__env->make('teacher.components.add-student-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
                option.style.display = '';
            });
        });

        // Function to select class for adding students
        window.selectClassForStudent = function(classId) {
            // Set the class ID in all class selects
            const classSelects = document.querySelectorAll('[id$="_class_id"]');
            classSelects.forEach(select => {
                select.value = classId;
            });
        };
    });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>