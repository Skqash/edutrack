<?php $__env->startSection('content'); ?>
    <style>
        /* Modern Classes Page Styling */
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

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Page Header */
        .page-header {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            padding-left: 2.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        /* Class Cards */
        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .class-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
            height: fit-content;
        }

        .class-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .class-card::before {
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

        .class-card:hover::before {
            opacity: 1;
        }

        .class-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--light-bg);
        }

        .class-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .class-meta {
            color: var(--text-secondary);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .class-body {
            padding: 1rem;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .progress-section {
            margin-bottom: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .progress-bar-custom {
            height: 8px;
            background: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .class-footer {
            padding: 0.75rem 1rem;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn-modern {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
        }

        .btn-primary-modern:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: var(--white);
            text-decoration: none;
        }

        .btn-outline-modern {
            background: var(--white);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline-modern:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: var(--white);
        }

        .btn-success-modern:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: var(--white);
            text-decoration: none;
        }

        /* Badges */
        .badge-modern {
            padding: 0.375rem 0.75rem;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-description {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .filter-section {
                padding: 1rem;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            .classes-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .class-header {
                padding: 1rem;
            }

            .class-body {
                padding: 1rem;
            }

            .class-footer {
                padding: 1rem;
                flex-direction: column;
            }

            .stat-row {
                gap: 1rem;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
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

        .slide-up {
            animation: slideUp 0.6s ease-out;
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
    </style>

    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="page-title">My Classes</h1>
                <p class="page-subtitle">Manage your classes and track student progress</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn-modern btn-primary-modern">
                    <i class="fas fa-plus"></i>
                    Create New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section slide-up">
        <div class="filter-group">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchClasses"
                    placeholder="Search classes by name or course...">
            </div>
            <select class="form-select" id="filterCourse" style="min-width: 200px;">
                <option value="">All Courses</option>
                <?php if(isset($courses)): ?>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->course_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
            <select class="form-select" id="filterYear" style="min-width: 150px;">
                <option value="">All Years</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
            <select class="form-select" id="filterSection" style="min-width: 150px;">
                <option value="">All Sections</option>
                <option value="A">Section A</option>
                <option value="B">Section B</option>
                <option value="C">Section C</option>
                <option value="D">Section D</option>
                <option value="E">Section E</option>
            </select>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="classes-grid">
        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="class-card fade-in" data-class-name="<?php echo e($class->class_name); ?>"
                data-course-id="<?php echo e($class->course->id ?? ''); ?>" data-course="<?php echo e($class->course->course_name ?? ''); ?>"
                data-year="<?php echo e($class->year ?? ''); ?>" data-section="<?php echo e($class->section ?? ''); ?>">
                <div class="class-header">
                    <div class="class-title">
                        <i class="fas fa-chalkboard text-primary"></i>
                        <?php echo e($class->class_name); ?>

                    </div>
                    <div class="class-meta">
                        <span class="badge-modern badge-primary"><?php echo e($class->section ?? 'N/A'); ?></span>
                        <span><i class="fas fa-calendar me-1"></i>Year <?php echo e($class->year ?? 'N/A'); ?></span>
                        <span><i class="fas fa-school me-1"></i>S.Y. <?php echo e($class->academic_year ?? 'N/A'); ?></span>
                    </div>
                </div>

                <div class="class-body">
                    <div class="stat-row">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($class->students->count()); ?></div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($class->capacity ?? 0); ?></div>
                            <div class="stat-label">Capacity</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($class->year ?? 'N/A'); ?></div>
                            <div class="stat-label">Year</div>
                        </div>
                    </div>

                    <?php if($class->course): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Course</small>
                            <div class="fw-semibold"><?php echo e($class->course->course_name); ?></div>
                            <small class="text-muted"><?php echo e($class->course->course_code ?? 'N/A'); ?></small>
                        </div>
                    <?php endif; ?>

                    <div class="progress-section">
                        <div class="progress-label">
                            <span>Enrollment</span>
                            <span><?php echo e($class->students->count()); ?>/<?php echo e($class->capacity ?? 0); ?></span>
                        </div>
                        <div class="progress-bar-custom">
                            <?php
                                $percentage = ($class->students->count() / ($class->capacity ?? 1)) * 100;
                                $progressColor =
                                    $percentage >= 90
                                        ? 'var(--danger-color)'
                                        : ($percentage >= 75
                                            ? 'var(--warning-color)'
                                            : 'var(--success-color)');
                            ?>
                            <div class="progress-fill"
                                style="width: <?php echo e($percentage); ?>%; background: <?php echo e($progressColor); ?>;"></div>
                        </div>
                    </div>
                </div>

                <div class="class-footer">
                    <a href="<?php echo e(route('teacher.classes.show', $class->id)); ?>"
                        class="btn-modern btn-outline-modern flex-grow-1">
                        <i class="fas fa-eye"></i>
                        View Details
                    </a>
                    <div class="dropdown">
                        <button class="btn-modern btn-primary-modern dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-graduation-cap"></i>
                            Grades
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=midterm">
                                    <i class="fas fa-edit text-primary"></i> Midterm Grades
                                </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=final">
                                    <i class="fas fa-edit text-success"></i> Final Grades
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item"
                                    href="<?php echo e(route('teacher.grades.results')); ?>?class_id=<?php echo e($class->id); ?>">
                                    <i class="fas fa-chart-bar text-info"></i> View All Grades
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <h3 class="empty-title">No Classes Yet</h3>
                <p class="empty-description">Start by creating your first class to begin managing your students and grades.
                </p>
                <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn-modern btn-primary-modern">
                    <i class="fas fa-plus me-2"></i>
                    Create Your First Class
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($classes->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($classes->links()); ?>

        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchClasses');
            const courseFilter = document.getElementById('filterCourse');
            const yearFilter = document.getElementById('filterYear');
            const sectionFilter = document.getElementById('filterSection');
            const classCards = document.querySelectorAll('.class-card');

            function filterClasses() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCourse = courseFilter.value;
                const selectedYear = yearFilter.value;
                const selectedSection = sectionFilter.value.toLowerCase();

                classCards.forEach(card => {
                    const className = card.dataset.className.toLowerCase();
                    const courseName = card.dataset.course.toLowerCase();
                    const courseId = String(card.dataset.courseId || '');
                    const year = card.dataset.year;
                    const section = card.dataset.section.toLowerCase();

                    const matchesSearch = className.includes(searchTerm) || courseName.includes(searchTerm);
                    const matchesCourse = !selectedCourse || courseId === selectedCourse;
                    const matchesYear = !selectedYear || year === selectedYear;
                    const matchesSection = !selectedSection || section.includes(selectedSection);

                    if (matchesSearch && matchesCourse && matchesYear && matchesSection) {
                        card.style.display = 'block';
                        card.classList.add('fade-in');
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Add event listeners
            searchInput.addEventListener('input', filterClasses);
            courseFilter.addEventListener('change', filterClasses);
            yearFilter.addEventListener('change', filterClasses);
            sectionFilter.addEventListener('change', filterClasses);
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/index.blade.php ENDPATH**/ ?>