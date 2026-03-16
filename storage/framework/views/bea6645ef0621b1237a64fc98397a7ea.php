

<?php $__env->startSection('content'); ?>
    <style>
        /* Modern Dashboard Styling */
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dashboard Header */
        .dashboard-header {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-text h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .welcome-text p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        .date-time {
            text-align: right;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .current-date {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1.125rem;
            margin-bottom: 0.25rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            opacity: 0;
            transition: var(--transition);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-icon.primary {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.info {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .stat-change.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
            color: var(--primary-color);
            text-decoration: none;
        }

        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .quick-action-content h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .quick-action-content p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* KSA Card */
        .ksa-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .ksa-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .ksa-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ksa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .ksa-item {
            background: var(--light-bg);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid var(--border-color);
        }

        .ksa-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .ksa-item-title {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ksa-percentage {
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--primary-color);
        }

        .ksa-progress {
            height: 6px;
            background: var(--border-color);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .ksa-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .ksa-description {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Data Tables */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .data-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .data-card-header {
            background: var(--light-bg);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-card-body {
            padding: 0;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: var(--light-bg);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
            text-align: left;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modern-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .modern-table tr:hover {
            background: var(--light-bg);
        }

        .badge-modern {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-primary {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .badge-info {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .welcome-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .welcome-text h1 {
                font-size: 1.5rem;
            }

            .date-time {
                text-align: left;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .ksa-grid {
                grid-template-columns: 1fr;
            }

            .data-grid {
                grid-template-columns: 1fr;
            }

            .modern-table {
                font-size: 0.75rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-header {
                padding: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .quick-action-btn {
                padding: 1rem;
            }

            .ksa-card {
                padding: 1rem;
            }
        }
    </style>

    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome back, <?php echo e(auth()->user()->name ?? 'Teacher'); ?>!</h1>
                <p>Here's what's happening with your classes today.</p>
            </div>
            <div class="date-time">
                <div class="current-date" id="currentDate"></div>
                <div class="current-time" id="currentTime"></div>
            </div>
        </div>

        
        <?php if(isset($latestSchoolRequest) && $latestSchoolRequest): ?>
            <div class="alert alert-<?php echo e($latestSchoolRequest->status === 'approved' ? 'success' : 'danger'); ?> mt-4 mb-0"
                role="alert">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="alert-heading mb-1">
                            School request <?php echo e(ucfirst($latestSchoolRequest->status)); ?>

                        </h5>
                        <p class="mb-1">
                            Your request to connect to <strong><?php echo e($latestSchoolRequest->school_name); ?></strong> has been
                            <strong><?php echo e($latestSchoolRequest->status); ?></strong>.
                        </p>
                        <?php if($latestSchoolRequest->admin_note): ?>
                            <p class="mb-0"><strong>Admin note:</strong> <?php echo e($latestSchoolRequest->admin_note); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-end">
                        <a href="<?php echo e(route('teacher.school-requests.history')); ?>" class="btn btn-sm btn-outline-light">
                            View request history
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid slide-up">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo e($myClasses->count() ?? 0); ?></div>
                    <div class="stat-label">My Classes</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 12% from last month
                    </span>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo e($myCourses->count() ?? 0); ?></div>
                    <div class="stat-label">My Courses</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 8% from last month
                    </span>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo e($totalStudents ?? 0); ?></div>
                    <div class="stat-label">Total Students</div>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 15% from last month
                    </span>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?php echo e($gradesPosted ?? 0); ?></div>
                    <div class="stat-label">Grades Posted</div>
                    <span class="stat-change negative">
                        <i class="fas fa-arrow-down"></i> 3% from last month
                    </span>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions slide-up">
        <a href="<?php echo e(route('teacher.classes.create')); ?>" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div class="quick-action-content">
                <h3>Create Class</h3>
                <p>Add a new class to your schedule</p>
            </div>
        </a>

        <a href="<?php echo e(route('teacher.classes')); ?>" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="quick-action-content">
                <h3>Enter Grades</h3>
                <p>Record student grades and assessments</p>
            </div>
        </a>

        <a href="<?php echo e(route('teacher.attendance')); ?>" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-check-square"></i>
            </div>
            <div class="quick-action-content">
                <h3>Take Attendance</h3>
                <p>Mark student attendance for today</p>
            </div>
        </a>

        <a href="<?php echo e(route('teacher.grades.results')); ?>" class="quick-action-btn">
            <div class="quick-action-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="quick-action-content">
                <h3>View Reports</h3>
                <p>Generate and view performance reports</p>
            </div>
        </a>
    </div>

    <!-- KSA Grading System -->
    <div class="ksa-card fade-in">
        <div class="ksa-header">
            <div class="ksa-title">
                <i class="fas fa-chart-line text-primary"></i>
                KSA Grading System Overview
            </div>
            <button class="btn btn-sm btn-outline-primary">
                <i class="fas fa-info-circle"></i> Learn More
            </button>
        </div>

        <div class="ksa-grid">
            <div class="ksa-item">
                <div class="ksa-item-header">
                    <div class="ksa-item-title">
                        <i class="fas fa-brain text-primary"></i>
                        Knowledge
                    </div>
                    <div class="ksa-percentage">40%</div>
                </div>
                <div class="ksa-progress">
                    <div class="ksa-progress-fill" style="width: 40%;"></div>
                </div>
                <div class="ksa-description">
                    Tests, quizzes, exams, and theoretical assessments
                </div>
            </div>

            <div class="ksa-item">
                <div class="ksa-item-header">
                    <div class="ksa-item-title">
                        <i class="fas fa-cogs text-success"></i>
                        Skills
                    </div>
                    <div class="ksa-percentage">35%</div>
                </div>
                <div class="ksa-progress">
                    <div class="ksa-progress-fill"
                        style="width: 35%; background: linear-gradient(90deg, var(--success-color), var(--info-color));">
                    </div>
                </div>
                <div class="ksa-description">
                    Practical applications, projects, and hands-on activities
                </div>
            </div>

            <div class="ksa-item">
                <div class="ksa-item-header">
                    <div class="ksa-item-title">
                        <i class="fas fa-heart text-warning"></i>
                        Attitude
                    </div>
                    <div class="ksa-percentage">25%</div>
                </div>
                <div class="ksa-progress">
                    <div class="ksa-progress-fill"
                        style="width: 25%; background: linear-gradient(90deg, var(--warning-color), var(--info-color));">
                    </div>
                </div>
                <div class="ksa-description">
                    Participation, behavior, and professional conduct
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="data-grid slide-up">
        <!-- My Courses -->
        <div class="data-card">
            <div class="data-card-header">
                <div class="data-card-title">
                    <i class="fas fa-book text-primary"></i>
                    My Courses
                </div>
                <a href="<?php echo e(route('teacher.subjects')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="data-card-body">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Classes</th>
                            <th>Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $myCourses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($course): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e(optional($course)->course_name ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo e(optional($course)->course_code ?? 'N/A'); ?></small>
                                    </td>
                                    <td><span class="badge-modern badge-info">0</span></td>
                                    <td><span class="badge-modern badge-success">0</span></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox me-2"></i>No courses assigned yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My Classes -->
        <div class="data-card">
            <div class="data-card-header">
                <div class="data-card-title">
                    <i class="fas fa-door-open text-success"></i>
                    My Classes
                </div>
                <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="data-card-body">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Section</th>
                            <th>Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $myClasses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($class): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e(optional($class)->class_name ?? 'N/A'); ?></div>
                                        <small
                                            class="text-muted"><?php echo e(optional(optional($class)->course)->course_name ?? 'N/A'); ?></small>
                                    </td>
                                    <td><span class="badge-modern badge-primary"><?php echo e($class->section ?? 'N/A'); ?></span>
                                    </td>
                                    <td><?php echo e($class->students ? $class->students->count() : 0); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox me-2"></i>No classes assigned yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="data-card fade-in">
        <div class="data-card-header">
            <div class="data-card-title">
                <i class="fas fa-clock text-info"></i>
                Recent Grading Activity
            </div>
            <a href="<?php echo e(route('teacher.grades.results')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="data-card-body">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Course</th>
                        <th>Graded Students</th>
                        <th>Avg Knowledge</th>
                        <th>Avg Skills</th>
                        <th>Avg Attitude</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentGrades ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if($grade): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($grade->class_name ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo e($grade->course_name ?? 'N/A'); ?></td>
                                <td><span
                                        class="badge-modern badge-primary"><?php echo e(optional($grade)->student_count ?? 0); ?></span>
                                </td>
                                <td><span
                                        class="badge-modern badge-info"><?php echo e(optional($grade)->avg_knowledge !== null ? number_format(optional($grade)->avg_knowledge, 1) : 'N/A'); ?></span>
                                </td>
                                <td><span
                                        class="badge-modern badge-success"><?php echo e(optional($grade)->avg_skills !== null ? number_format(optional($grade)->avg_skills, 1) : 'N/A'); ?></span>
                                </td>
                                <td><span
                                        class="badge-modern badge-warning"><?php echo e(optional($grade)->avg_attitude !== null ? number_format(optional($grade)->avg_attitude, 1) : 'N/A'); ?></span>
                                </td>
                                <td>
                                    <?php if(optional($grade)->updated_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse(optional($grade)->updated_at)->format('M d, Y')); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                <i class="fas fa-inbox me-2"></i>No recent grading activity
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Date and Time Display
        function updateDateTime() {
            const now = new Date();

            // Format date
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const dateString = now.toLocaleDateString('en-US', options);

            // Format time
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            const dateElement = document.getElementById('currentDate');
            const timeElement = document.getElementById('currentTime');

            if (dateElement) dateElement.textContent = dateString;
            if (timeElement) timeElement.textContent = timeString;
        }

        // Initialize date/time and update every second
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Animate stats on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all cards
            document.querySelectorAll('.stat-card, .quick-action-btn, .ksa-card, .data-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>