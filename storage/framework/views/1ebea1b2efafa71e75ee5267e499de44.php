

<?php $__env->startSection('content'); ?>
    <style>
        :root {
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --knowledge-color: #3b82f6;
            --skills-color: #10b981;
            --attitude-color: #8b5cf6;
        }

        .summary-container {
            max-width: 100%;
            padding: 1.5rem;
        }

        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1rem;
        }

        .class-summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .class-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
        }

        .class-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .class-info {
            display: flex;
            gap: 2rem;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        /* KSA Component Table */
        .ksa-table-wrapper {
            overflow-x: auto;
            padding: 1.5rem;
        }

        .ksa-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .ksa-table th {
            padding: 0.75rem 0.5rem;
            text-align: center;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            white-space: nowrap;
        }

        .ksa-table td {
            padding: 0.6rem 0.5rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .ksa-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Header Colors */
        .header-knowledge {
            background-color: #dbeafe !important;
            color: #1e40af;
        }

        .header-skills {
            background-color: #d1fae5 !important;
            color: #065f46;
        }

        .header-attitude {
            background-color: #e9d5ff !important;
            color: #6b21a8;
        }

        .header-midterm {
            background-color: #fef3c7 !important;
            color: #92400e;
        }

        .header-final {
            background-color: #fed7aa !important;
            color: #9a3412;
        }

        /* Student Name Column */
        .student-name-cell {
            text-align: left !important;
            font-weight: 500;
            color: #1e293b;
            min-width: 200px;
            position: sticky;
            left: 0;
            background: white;
            z-index: 10;
        }

        .student-name-cell:hover {
            background-color: #f8fafc !important;
        }

        /* Grade Cells */
        .grade-value {
            font-weight: 600;
        }

        .grade-excellent {
            color: #059669;
        }

        .grade-good {
            color: #0891b2;
        }

        .grade-average {
            color: #d97706;
        }

        .grade-poor {
            color: #dc2626;
        }

        /* Final Grade Highlight */
        .final-grade-cell {
            background-color: #fef3c7 !important;
            font-weight: 700;
            font-size: 0.95rem;
        }

        /* KSA Section Styling */
        .ksa-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .ksa-section h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Component Breakdown */
        .component-breakdown {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Statistics Summary */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-box {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Legend */
        .legend-box {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.85rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .legend-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: 600;
        }

        /* Calculation Formula Display */
        .formula-box {
            background: #f1f5f9;
            border-left: 4px solid var(--info-color);
            padding: 1rem;
            margin: 1rem 1.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .formula-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .formula-text {
            color: #475569;
            font-family: 'Courier New', monospace;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        /* Class Filter Styling */
        #classFilter {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        #classFilter:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .filter-label {
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Summary Info Badge */
        .summary-info-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .ksa-table {
                font-size: 0.7rem;
            }

            .student-name-cell {
                position: static;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .ksa-table {
                font-size: 0.75rem;
            }

            .ksa-table th,
            .ksa-table td {
                padding: 0.4rem 0.3rem;
            }
            
            /* Mobile Filter Styling */
            .page-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }
            
            .page-header .ms-auto {
                margin-left: 0 !important;
                margin-top: 1rem;
                flex-direction: column;
                align-items: stretch !important;
            }
            
            #classFilter {
                width: 100% !important;
                min-width: 100% !important;
            }
            
            .filter-label {
                margin-bottom: 0.5rem;
            }
            
            .summary-info-badge {
                justify-content: center;
                width: 100%;
            }
        }
    </style>

    <div class="summary-container">
        <!-- Page Header -->
        <div class="page-header no-print">
            <h1 class="page-title">
                <i class="fas fa-chart-bar"></i> Comprehensive Grade Summary
            </h1>
            <p class="page-subtitle">
                Detailed KSA (Knowledge, Skills, Attitude) component breakdown with midterm and final term calculations
            </p>

            <div class="d-flex gap-2 mt-3 flex-wrap align-items-center">
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Back to Grades
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="fas fa-print"></i> Print Summary
                </button>
                
                <!-- Class Filter -->
                <div class="ms-auto d-flex align-items-center gap-2">
                    <label for="classFilter" class="mb-0 fw-semibold">
                        <i class="fas fa-filter"></i> Filter by Class:
                    </label>
                    <select id="classFilter" class="form-select" style="width: auto; min-width: 250px;" onchange="filterByClass(this.value)">
                        <option value="">All Classes</option>
                        <?php
                            $teacherId = Auth::id();
                            $allClasses = \App\Models\ClassModel::where('teacher_id', $teacherId)
                                ->with('course')
                                ->orderBy('class_name')
                                ->get();
                            $selectedClassId = request()->query('class_id');
                        ?>
                        <?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClassId == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->class_name); ?>

                                <?php if($class->course): ?>
                                    - <?php echo e($class->course->program_name); ?>

                                <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
        
        <script>
            function filterByClass(classId) {
                const url = new URL(window.location.href);
                if (classId) {
                    url.searchParams.set('class_id', classId);
                } else {
                    url.searchParams.delete('class_id');
                }
                window.location.href = url.toString();
            }
        </script>

        <!-- Calculation Formula -->
        <div class="formula-box no-print">
            <div class="formula-title">📐 Grade Calculation Formula</div>
            <div class="formula-text">
                <strong>Midterm Grade (40%):</strong> Knowledge (40%) + Skills (50%) + Attitude (10%)<br>
                <strong>Final Grade (60%):</strong> Knowledge (40%) + Skills (50%) + Attitude (10%)<br>
                <strong>Overall Grade:</strong> (Midterm × 40%) + (Final × 60%)<br>
                <br>
                <strong>Knowledge:</strong> Exam (60%) + Quizzes (40%)<br>
                <strong>Skills:</strong> Output (40%) + Class Participation (30%) + Activities (15%) + Assignments (15%)<br>
                <strong>Attitude:</strong> Behavior (50%) + Class Participation/Awareness (50%)
            </div>
        </div>

        <!-- Legend -->
        <div class="legend-box no-print">
            <div class="legend-item">
                <span class="legend-badge" style="background: #dbeafe; color: #1e40af;">K</span>
                <span>Knowledge (40%)</span>
            </div>
            <div class="legend-item">
                <span class="legend-badge" style="background: #d1fae5; color: #065f46;">S</span>
                <span>Skills (50%)</span>
            </div>
            <div class="legend-item">
                <span class="legend-badge" style="background: #e9d5ff; color: #6b21a8;">A</span>
                <span>Attitude (10%)</span>
            </div>
            <div class="legend-item">
                <span class="legend-badge" style="background: #fef3c7; color: #92400e;">Midterm</span>
                <span>40% of Final Grade</span>
            </div>
            <div class="legend-item">
                <span class="legend-badge" style="background: #fed7aa; color: #9a3412;">Final</span>
                <span>60% of Final Grade</span>
            </div>
        </div>

        <?php if(empty($classGradeSummaries)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <h3>No Grade Data Available</h3>
                <?php if($selectedClassId): ?>
                    <p class="text-muted">No grades have been entered for the selected class yet.</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Go to Grade Entry
                        </a>
                        <button onclick="filterByClass('')" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> View All Classes
                        </button>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Please enter grades for your classes to view the summary.</p>
                    <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-edit"></i> Go to Grade Entry
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Summary Info -->
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <div class="summary-info-badge">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Showing <?php echo e(count($classGradeSummaries)); ?> <?php echo e(count($classGradeSummaries) === 1 ? 'Class' : 'Classes'); ?></span>
                </div>
                <?php if($selectedClassId): ?>
                    <button onclick="filterByClass('')" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list"></i> View All Classes
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Class Summaries -->
            <?php $__currentLoopData = $classGradeSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="class-summary-card">
                    <!-- Class Header -->
                    <div class="class-header">
                        <div class="class-title"><?php echo e($summary['class']->class_name); ?></div>
                        <div class="class-info">
                            <span><i class="fas fa-book"></i> <?php echo e($summary['course']->program_name ?? 'N/A'); ?></span>
                            <span><i class="fas fa-users"></i> <?php echo e($summary['stats']['total_students']); ?> Students</span>
                            <span><i class="fas fa-check-circle"></i> <?php echo e($summary['stats']['graded_students']); ?> Graded</span>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-label">Average Midterm</div>
                            <div class="stat-value" style="color: var(--warning-color);">
                                <?php echo e(number_format($summary['stats']['avg_midterm'], 2)); ?>

                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Average Final</div>
                            <div class="stat-value" style="color: var(--danger-color);">
                                <?php echo e(number_format($summary['stats']['avg_final'], 2)); ?>

                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Overall Average</div>
                            <div class="stat-value" style="color: var(--primary-color);">
                                <?php echo e(number_format($summary['stats']['avg_overall'], 2)); ?>

                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Pass Rate</div>
                            <div class="stat-value" style="color: var(--success-color);">
                                <?php echo e(number_format($summary['stats']['pass_rate'], 1)); ?>%
                            </div>
                        </div>
                    </div>

                    <!-- Midterm KSA Table -->
                    <div class="ksa-section" style="margin-bottom: 2rem;">
                        <h4 style="padding: 1rem 1.5rem; background: #fef3c7; color: #92400e; margin: 0; font-weight: 700; border-bottom: 2px solid #f59e0b;">
                            📊 MIDTERM GRADES (40% of Final Grade)
                        </h4>
                        <div class="ksa-table-wrapper">
                            <table class="ksa-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="student-name-cell">Student Name</th>
                                        <th colspan="3" style="background: #f8fafc; color: #1e293b;">KSA Components</th>
                                        <th rowspan="2" class="header-midterm">Midterm<br>Grade</th>
                                        <th rowspan="2" class="header-midterm">Decimal<br>Grade</th>
                                    </tr>
                                    <tr>
                                        <th class="header-knowledge">Knowledge<br>(40%)</th>
                                        <th class="header-skills">Skills<br>(50%)</th>
                                        <th class="header-attitude">Attitude<br>(10%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $summary['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <!-- Student Name -->
                                            <td class="student-name-cell">
                                                <div style="font-weight: 600;"><?php echo e($student['name']); ?></div>
                                                <div style="font-size: 0.75rem; color: #64748b;">ID: <?php echo e($student['student_id']); ?></div>
                                            </td>

                                            <!-- Midterm KSA Components -->
                                            <td class="grade-value <?php echo e($student['midterm_k_class']); ?>">
                                                <?php echo e(number_format($student['midterm_knowledge'], 2)); ?>

                                                <div class="component-breakdown">Exam + Quizzes</div>
                                            </td>
                                            <td class="grade-value <?php echo e($student['midterm_s_class']); ?>">
                                                <?php echo e(number_format($student['midterm_skills'], 2)); ?>

                                                <div class="component-breakdown">Output + Activities</div>
                                            </td>
                                            <td class="grade-value <?php echo e($student['midterm_a_class']); ?>">
                                                <?php echo e(number_format($student['midterm_attitude'], 2)); ?>

                                                <div class="component-breakdown">Behavior + Awareness</div>
                                            </td>

                                            <!-- Midterm Grade -->
                                            <td class="grade-value <?php echo e($student['midterm_grade_class']); ?>" style="background: #fef9e7; font-weight: 700;">
                                                <?php echo e(number_format($student['midterm_grade'], 2)); ?>

                                            </td>

                                            <!-- Midterm Decimal Grade -->
                                            <td style="text-align: center; font-weight: 600;">
                                                <?php echo e(number_format(\App\Helpers\GradeHelper::convertToDecimalScale($student['midterm_grade']), 2)); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No midterm grades entered yet
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Final KSA Table -->
                    <div class="ksa-section" style="margin-bottom: 2rem;">
                        <h4 style="padding: 1rem 1.5rem; background: #fed7aa; color: #9a3412; margin: 0; font-weight: 700; border-bottom: 2px solid #f97316;">
                            📊 FINAL GRADES (60% of Final Grade)
                        </h4>
                        <div class="ksa-table-wrapper">
                            <table class="ksa-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="student-name-cell">Student Name</th>
                                        <th colspan="3" style="background: #f8fafc; color: #1e293b;">KSA Components</th>
                                        <th rowspan="2" class="header-final">Final<br>Grade</th>
                                        <th rowspan="2" class="header-final">Decimal<br>Grade</th>
                                    </tr>
                                    <tr>
                                        <th class="header-knowledge">Knowledge<br>(40%)</th>
                                        <th class="header-skills">Skills<br>(50%)</th>
                                        <th class="header-attitude">Attitude<br>(10%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $summary['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <!-- Student Name -->
                                            <td class="student-name-cell">
                                                <div style="font-weight: 600;"><?php echo e($student['name']); ?></div>
                                                <div style="font-size: 0.75rem; color: #64748b;">ID: <?php echo e($student['student_id']); ?></div>
                                            </td>

                                            <!-- Final KSA Components -->
                                            <td class="grade-value <?php echo e($student['final_k_class']); ?>">
                                                <?php echo e(number_format($student['final_knowledge'], 2)); ?>

                                                <div class="component-breakdown">Exam + Quizzes</div>
                                            </td>
                                            <td class="grade-value <?php echo e($student['final_s_class']); ?>">
                                                <?php echo e(number_format($student['final_skills'], 2)); ?>

                                                <div class="component-breakdown">Output + Activities</div>
                                            </td>
                                            <td class="grade-value <?php echo e($student['final_a_class']); ?>">
                                                <?php echo e(number_format($student['final_attitude'], 2)); ?>

                                                <div class="component-breakdown">Behavior + Awareness</div>
                                            </td>

                                            <!-- Final Grade -->
                                            <td class="grade-value <?php echo e($student['final_grade_class']); ?>" style="background: #fff5e6; font-weight: 700;">
                                                <?php echo e(number_format($student['final_grade'], 2)); ?>

                                            </td>

                                            <!-- Final Decimal Grade -->
                                            <td style="text-align: center; font-weight: 600;">
                                                <?php echo e(number_format(\App\Helpers\GradeHelper::convertToDecimalScale($student['final_grade']), 2)); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No final grades entered yet
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Overall Final Grade Summary -->
                    <div class="ksa-section">
                        <h4 style="padding: 1rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin: 0; font-weight: 700; border-bottom: 2px solid #4f46e5;">
                            🎯 OVERALL FINAL GRADE (Midterm 40% + Final 60%)
                        </h4>
                        <div class="ksa-table-wrapper">
                            <table class="ksa-table">
                                <thead>
                                    <tr>
                                        <th class="student-name-cell">Student Name</th>
                                        <th style="background: #fef3c7; color: #92400e;">Midterm<br>Grade</th>
                                        <th style="background: #fef3c7; color: #92400e;">Midterm<br>Contribution<br>(40%)</th>
                                        <th style="background: #fed7aa; color: #9a3412;">Final<br>Grade</th>
                                        <th style="background: #fed7aa; color: #9a3412;">Final<br>Contribution<br>(60%)</th>
                                        <th style="background: #e9d5ff; color: #6b21a8;">Overall<br>Grade</th>
                                        <th style="background: #e9d5ff; color: #6b21a8;">Decimal<br>Grade</th>
                                        <th style="background: #e9d5ff; color: #6b21a8;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $summary['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <!-- Student Name -->
                                            <td class="student-name-cell">
                                                <div style="font-weight: 600;"><?php echo e($student['name']); ?></div>
                                                <div style="font-size: 0.75rem; color: #64748b;">ID: <?php echo e($student['student_id']); ?></div>
                                            </td>

                                            <!-- Midterm Grade -->
                                            <td class="grade-value <?php echo e($student['midterm_grade_class']); ?>" style="text-align: center;">
                                                <?php echo e(number_format($student['midterm_grade'], 2)); ?>

                                            </td>

                                            <!-- Midterm Contribution -->
                                            <td style="text-align: center; font-weight: 600; color: #92400e;">
                                                <?php echo e(number_format($student['midterm_grade'] * 0.40, 2)); ?>

                                            </td>

                                            <!-- Final Grade -->
                                            <td class="grade-value <?php echo e($student['final_grade_class']); ?>" style="text-align: center;">
                                                <?php echo e(number_format($student['final_grade'], 2)); ?>

                                            </td>

                                            <!-- Final Contribution -->
                                            <td style="text-align: center; font-weight: 600; color: #9a3412;">
                                                <?php echo e(number_format($student['final_grade'] * 0.60, 2)); ?>

                                            </td>

                                            <!-- Overall Grade -->
                                            <td class="final-grade-cell <?php echo e($student['overall_grade_class']); ?>" style="font-size: 1.1rem;">
                                                <?php echo e(number_format($student['overall_grade'], 2)); ?>

                                            </td>

                                            <!-- Decimal Grade -->
                                            <td style="text-align: center; font-weight: 700; font-size: 1rem;">
                                                <?php echo e($student['decimal_grade']); ?>

                                            </td>

                                            <!-- Status -->
                                            <td style="text-align: center;">
                                                <?php if($student['status'] === 'Passed'): ?>
                                                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600;">
                                                        ✅ Passed
                                                    </span>
                                                <?php else: ?>
                                                    <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600;">
                                                        ❌ Failed
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                                                No grades available
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/grade_summary_detailed.blade.php ENDPATH**/ ?>