<?php $__env->startSection('content'); ?>
    <?php
        /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\ClassModel[] $classes */
    ?>

    <style>
        /* Reuse styling from My Classes page for a consistent look */
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

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
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
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }

        .class-footer {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .class-footer .btn-modern {
            flex: 1;
            min-width: 120px;
            margin-bottom: 0.5rem;
        }

        .btn-modern {
            border-radius: 12px;
            padding: 0.6rem 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: var(--white);
            border-color: transparent;
        }

        .btn-outline-modern {
            background: transparent;
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-modern:hover {
            background: rgba(79, 70, 229, 0.08);
        }

        .btn-success-modern {
            background: var(--success-color);
            color: var(--white);
            border-color: transparent;
        }

        .btn-info-modern {
            background: var(--info-color);
            color: var(--white);
            border-color: transparent;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="grade-dashboard" style="width: 100%; padding: 0 1.5rem; margin: 0 auto;">
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-graduation-cap"></i> Grades
                    </h1>
                    <p class="page-subtitle">Enter or review grades for your classes (Midterm / Final + Summary).</p>
                </div>

                <div class="action-buttons">
                    <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="<?php echo e(route('teacher.grades.results')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-chart-line"></i> View Grade Summary
                    </a>
                </div>
            </div>
        </div>

        <div class="filter-section slide-up">
            <div class="filter-group">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="searchClasses"
                        placeholder="Search classes by name or course...">
                </div>
                <select class="form-select" id="filterCourse" style="min-width: 200px;">
                    <option value="">All Programs</option>
                    <?php if(isset($courses)): ?>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($course->id); ?>"><?php echo e($course->program_name); ?></option>
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

        <div class="classes-grid">
            <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="class-card fade-in" data-class-name="<?php echo e($class->class_name); ?>"
                    data-course-id="<?php echo e($class->course->id ?? ''); ?>" data-course="<?php echo e($class->course->program_name ?? ''); ?>"
                    data-year="<?php echo e($class->year ?? ''); ?>" data-section="<?php echo e($class->section ?? ''); ?>">
                    <div class="class-header">
                        <div class="class-title">
                            <i class="fas fa-chalkboard text-primary"></i>
                            <?php echo e($class->class_name); ?>

                        </div>
                        <div class="class-meta">
                            <span class="badge-modern badge-primary"><?php echo e($class->section ?? 'N/A'); ?></span>
                            <span><i class="fas fa-calendar me-1"></i>Year <?php echo e($class->year ?? 'N/A'); ?></span>
                        </div>
                    </div>

                    <div class="class-body">
                        <div class="stat-row">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo e($class->student_count ?? 0); ?></div>
                                <div class="stat-label">Total Students</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo e($class->units ?? 'N/A'); ?></div>
                                <div class="stat-label">Units</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo e($class->year ?? 'N/A'); ?></div>
                                <div class="stat-label">Year</div>
                            </div>
                        </div>

                        <?php if($class->course): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Program</small>
                                <div class="fw-semibold"><?php echo e($class->course->program_name); ?></div>
                                <small class="text-muted"><?php echo e($class->course->program_code ?? 'N/A'); ?></small>
                            </div>
                        <?php endif; ?>

                        <div class="progress-section">
                            <?php
                                $totalStudents = $class->student_count ?? 0;
                                $maxDisplay = max($totalStudents, 1);
                                $percentage = min(($totalStudents / $maxDisplay) * 100, 100);
                                $progressColor = $totalStudents > 0 ? 'var(--success-color)' : 'var(--secondary-color)';
                            ?>
                            <div class="progress-label">
                                <span>Enrolled Students</span>
                                <span><?php echo e($totalStudents); ?></span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill"
                                    style="width: <?php echo e($percentage); ?>%; background: <?php echo e($progressColor); ?>;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="class-footer">
                        <a href="<?php echo e(route('teacher.grades.content', $class->id)); ?>?term=midterm"
                            class="btn-modern btn-primary-modern">
                            <i class="fas fa-edit"></i>
                            Midterm
                        </a>
                        <a href="<?php echo e(route('teacher.grades.content', $class->id)); ?>?term=final"
                            class="btn-modern btn-success-modern">
                            <i class="fas fa-flag-checkered"></i>
                            Final
                        </a>
                        <a href="<?php echo e(route('teacher.grades.results')); ?>?class_id=<?php echo e($class->id); ?>"
                            class="btn-modern btn-info-modern">
                            <i class="fas fa-chart-bar"></i>
                            Summary
                        </a>
                        <a href="<?php echo e(route('teacher.grades.content', $class->id)); ?>" class="btn-modern btn-outline-modern"
                            style="width: 100%;">
                            <i class="fas fa-sliders-h"></i>
                            Configure Assessment
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state" style="width: 100%;">
                    <div class="empty-icon" style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
                    <h3 class="empty-title">No Classes Found</h3>
                    <p class="empty-description">You currently have no classes assigned. Create a class to start entering
                        grades.</p>
                    <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Create Your First Class
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if($classes->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($classes->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            searchInput.addEventListener('input', filterClasses);
            courseFilter.addEventListener('change', filterClasses);
            yearFilter.addEventListener('change', filterClasses);
            sectionFilter.addEventListener('change', filterClasses);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/index.blade.php ENDPATH**/ ?>