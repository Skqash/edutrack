

<?php $__env->startSection('content'); ?>
    <style>
        .grade-result-container {
            max-width: 100%;
            overflow: hidden;
            background: #f8f9fa;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #2196F3;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1a237e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin: 0.25rem 0 0 0;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .class-card {
            background: white;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }

        .class-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: box-shadow 0.3s ease;
        }

        .class-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .class-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a237e;
            margin: 0;
        }

        .class-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0.25rem 0 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-bottom: 1px solid #e3e6f0;
        }

        .stat-box {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.375rem;
            border: 1px solid #e3e6f0;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2196F3;
            margin: 0;
        }

        .stat-value.passed {
            color: #28a745;
        }

        .stat-value.failed {
            color: #dc3545;
        }

        .stat-value.percentage {
            color: #fd7e14;
        }

        .grades-table-wrapper {
            overflow-x: auto;
            background: white;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .grades-table th {
            background: #495057;
            color: white;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .grades-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e3e6f0;
            vertical-align: middle;
        }

        .grades-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .student-name-cell {
            font-weight: 500;
            color: #1a237e;
        }

        .student-id-cell {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .grade-cell {
            text-align: center;
            font-weight: 600;
        }

        .decimal-grade {
            display: inline-block;
            min-width: 40px;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            background-color: #fff3cd;
            color: #856404;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.8rem;
            text-align: center;
            min-width: 70px;
        }

        .status-passed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .grade-knowledge {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .grade-skills {
            background-color: #e8f5e9;
            color: #1b5e20;
        }

        .grade-attitude {
            background-color: #f3e5f5;
            color: #4a148c;
        }

        .letter-grade-badge {
            display: inline-block;
            min-width: 32px;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 700;
            text-align: center;
            font-size: 0.8rem;
        }

        .grade-a {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .grade-b {
            background-color: #e8f5e9;
            color: #1b5e20;
        }

        .grade-c {
            background-color: #f3e5f5;
            color: #4a148c;
        }

        .grade-d {
            background-color: #fff3e0;
            color: #e65100;
        }

        .grade-f {
            background-color: #ffebee;
            color: #b71c1c;
        }

        .remarks-text {
            font-size: 0.8rem;
            color: #6c757d;
            font-style: italic;
        }

        .legend-box {
            display: flex;
            gap: 1.5rem;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
            color: #6c757d;
        }

        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .no-data {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            border: 1px dashed #dee2e6;
        }

        .no-data-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .grades-table {
                font-size: 0.8rem;
            }
            
            .grades-table th,
            .grades-table td {
                padding: 0.5rem;
            }
        }
    </style>

    <div class="grade-result-container p-3">
        <!-- Professional Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i> Grade Results
            </h1>
            <p class="page-subtitle">Comprehensive grade analysis with pass/fail status and performance metrics</p>

            <div class="action-buttons">
                <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i> Edit Grades
                </a>
                <button class="btn btn-outline-info" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <!-- Professional Legend -->
        <div class="legend-box">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #d4edda; border-color: #28a745;"></div>
                <span><strong>Passed</strong> - Decimal Grade ≤ 3.0</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #f8d7da; border-color: #dc3545;"></div>
                <span><strong>Failed</strong> - Decimal Grade > 3.0</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fff3cd; border-color: #ffc107;"></div>
                <span><strong>Decimal Grade</strong> - 1.0 (Best) to 5.0 (Worst)</span>
            </div>
        </div>

        <?php if(empty($classGradeResults)): ?>
            <!-- Professional No Data State -->
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <div class="empty-state-title">No Grade Results Available</div>
                <div class="empty-state-text">
                    No students have grades calculated yet. Please enter grades first using the Grade Entry Form.
                </div>
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Go to Grade Entry
                </a>
            </div>
        <?php else: ?>
            <!-- Professional Grade Results by Class -->
            <?php $__currentLoopData = $classGradeResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classResult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="class-card">
                    <!-- Professional Class Header -->
                    <div class="class-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="class-title"><?php echo e($classResult['class']->name); ?></h3>
                                <div class="class-subtitle">
                                    <i class="fas fa-book"></i> <?php echo e($classResult['course']->name ?? 'Course'); ?>

                                </div>
                            </div>
                            <span class="badge bg-primary">
                                <?php echo e($classResult['stats']['graded_students']); ?>/<?php echo e($classResult['stats']['total_students']); ?> Graded
                            </span>
                        </div>
                    </div>

                    <!-- Professional Statistics -->
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-label">Total Students</div>
                            <div class="stat-value"><?php echo e($classResult['stats']['total_students']); ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Graded</div>
                            <div class="stat-value"><?php echo e($classResult['stats']['graded_students']); ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Passed</div>
                            <div class="stat-value passed"><?php echo e($classResult['stats']['passed_count']); ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Failed</div>
                            <div class="stat-value failed"><?php echo e($classResult['stats']['failed_count']); ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Pass Rate</div>
                            <div class="stat-value percentage"><?php echo e($classResult['stats']['pass_percentage']); ?>%</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Average Grade</div>
                            <div class="stat-value"><?php echo e($classResult['stats']['average_grade']); ?></div>
                        </div>
                    </div>

                    <!-- Professional Grades Table -->
                    <?php if(empty($classResult['students'])): ?>
                        <div style="padding: 1.5rem;">
                            <div class="no-data">
                                <div class="no-data-icon">📋</div>
                                <p>No grades entered for this class yet</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="grades-table-wrapper">
                            <table class="grades-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 200px;">Student Name</th>
                                        <th class="grade-knowledge">Midterm<br>Grade / Decimal</th>
                                        <th class="grade-skills">Final<br>Grade / Decimal</th>
                                        <th class="grade-attitude">Overall<br>Grade / Decimal</th>
                                        <th>Letter<br>Grade</th>
                                        <th style="min-width: 100px;">Status</th>
                                        <th style="min-width: 200px;">Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $classResult['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-student-id="<?php echo e($result['student_id']); ?>">
                                            <!-- Student Name -->
                                            <td>
                                                <div class="student-name-cell"><?php echo e($result['student_name']); ?></div>
                                                <div class="student-id-cell">ID: <?php echo e($result['student_id']); ?></div>
                                            </td>

                                            <!-- Midterm Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.25rem;"><?php echo e($result['midterm_grade']); ?></div>
                                                <span class="decimal-grade"><?php echo e($result['midterm_decimal']); ?></span>
                                            </td>

                                            <!-- Final Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.25rem;"><?php echo e($result['final_grade']); ?></div>
                                                <span class="decimal-grade"><?php echo e($result['final_decimal']); ?></span>
                                            </td>

                                            <!-- Overall Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.25rem; font-weight: 700;">
                                                    <?php echo e($result['overall_grade']); ?>

                                                </div>
                                                <span class="decimal-grade"><?php echo e($result['decimal_grade']); ?></span>
                                            </td>

                                            <!-- Letter Grade -->
                                            <td class="grade-cell">
                                                <?php
                                                    $letterGrade = substr($result['letter_grade'], 0, 1);
                                                    $gradeClass = match ($letterGrade) {
                                                        'A' => 'grade-a',
                                                        'B' => 'grade-b',
                                                        'C' => 'grade-c',
                                                        'D' => 'grade-d',
                                                        'F' => 'grade-f',
                                                        default => 'grade-f',
                                                    };
                                                ?>
                                                <span class="letter-grade-badge <?php echo e($gradeClass); ?>">
                                                    <?php echo e($letterGrade); ?>

                                                </span>
                                            </td>

                                            <!-- Status -->
                                            <td>
                                                <span class="status-badge <?php if($result['status'] === 'Passed'): ?> status-passed <?php else: ?> status-failed <?php endif; ?>">
                                                    <?php if($result['status'] === 'Passed'): ?>
                                                        ✓ Passed
                                                    <?php else: ?>
                                                        ✗ Failed
                                                    <?php endif; ?>
                                                </span>
                                            </td>

                                            <!-- Performance Remarks -->
                                            <td>
                                                <p class="remarks-text"><?php echo e($result['remarks']); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

    <script>
        // Simple row highlighting
        document.querySelectorAll('.grades-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f1f3f5';
            });

            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        console.log('📊 Professional Grade Results Page Loaded');
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/grade_results.blade.php ENDPATH**/ ?>