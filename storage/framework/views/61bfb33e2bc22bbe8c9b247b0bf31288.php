<?php $__env->startSection('content'); ?>
    <style>
        /* Remove number input spinners - high specificity so it always wins */
        #gradeForm input[type="number"]::-webkit-outer-spin-button,
        #gradeForm input[type="number"]::-webkit-inner-spin-button,
        #gradeForm .form-control[type="number"]::-webkit-outer-spin-button,
        #gradeForm .form-control[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            appearance: none !important;
            margin: 0 !important;
            display: none !important;
        }

        #gradeForm input[type="number"],
        #gradeForm .form-control[type="number"] {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }

        /* ====== KSA COLOR CODING ====== */
        /* KNOWLEDGE Section - Professional Blue */
        .exam-md,
        .exam-fn,
        .exam-ave,
        .quiz-1,
        .quiz-2,
        .quiz-3,
        .quiz-4,
        .quiz-5,
        .quiz-total,
        .knowledge-ave {
            background-color: #E3F2FD !important;
            border-color: #64B5F6 !important;
        }

        .exam-ave,
        .quiz-total,
        .knowledge-ave {
            background-color: #BBDEFB !important;
            border-color: #2196F3 !important;
            color: #0D47A1 !important;
        }

        /* SKILLS Section - Professional Green */
        .output-1,
        .output-2,
        .output-3,
        .output-total,
        .classpart-1,
        .classpart-2,
        .classpart-3,
        .classpart-total,
        .activity-1,
        .activity-2,
        .activity-3,
        .activity-total,
        .assignment-1,
        .assignment-2,
        .assignment-3,
        .assignment-total,
        .skills-ave {
            background-color: #E8F5E9 !important;
            border-color: #81C784 !important;
        }

        .output-total,
        .classpart-total,
        .activity-total,
        .assignment-total,
        .skills-ave {
            background-color: #C8E6C9 !important;
            border-color: #4CAF50 !important;
            color: #1B5E20 !important;
        }

        /* ATTITUDE Section - Professional Purple */
        .behavior-1,
        .behavior-2,
        .behavior-3,
        .behavior-total,
        .awareness-1,
        .awareness-2,
        .awareness-3,
        .awareness-total,
        .attitude-ave {
            background-color: #F3E5F5 !important;
            border-color: #CE93D8 !important;
        }

        .behavior-total,
        .awareness-total,
        .attitude-ave {
            background-color: #E1BEE7 !important;
            border-color: #9C27B0 !important;
            color: #4A148C !important;
        }

        /* FINAL - Professional Gold/Amber - No KSA Colors */
        .final-grade {
            background-color: #FFF9E6 !important;
            border: 1px solid #FFD54F !important;
            color: #F57F17 !important;
            font-weight: 700 !important;
        }

        /* Decimal grade cell styling */
        .decimal-grade-cell {
            background-color: #FFF9E6 !important;
            border: 1px solid #FFD54F !important;
            color: #F57F17 !important;
            font-weight: 700 !important;
        }

        /* Input focus states with group colors */
        .exam-md:focus,
        .exam-fn:focus,
        .quiz-1:focus,
        .quiz-2:focus,
        .quiz-3:focus,
        .quiz-4:focus,
        .quiz-5:focus {
            border-color: #1976D2 !important;
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25) !important;
            background-color: #E3F2FD !important;
        }

        .output-1:focus,
        .output-2:focus,
        .output-3:focus,
        .classpart-1:focus,
        .classpart-2:focus,
        .classpart-3:focus,
        .activity-1:focus,
        .activity-2:focus,
        .activity-3:focus,
        .assignment-1:focus,
        .assignment-2:focus,
        .assignment-3:focus {
            border-color: #388E3C !important;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25) !important;
            background-color: #E8F5E9 !important;
        }

        .behavior-1:focus,
        .behavior-2:focus,
        .behavior-3:focus,
        .awareness-1:focus,
        .awareness-2:focus,
        .awareness-3:focus {
            border-color: #7B1FA2 !important;
            box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25) !important;
            background-color: #F3E5F5 !important;
        }

        /* Column group styling - subtle background for visual grouping */
        .table tbody tr td:nth-child(n+2):nth-child(-n+9) {
            /* Knowledge section: Exam + Average + Quizzes */
        }

        /* Header styling for better group distinction */
        .table-primary {
            background-color: #F5F5F5 !important;
            border-bottom: 2px solid #CCCCCC !important;
        }

        /* Computed cells (totals and averages) styling */
        .computed-cell {
            font-weight: 700 !important;
            text-align: center !important;
        }

        /* Legend/Info styling */
        .ksa-legend {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .ksa-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .legend-box {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 2px solid;
        }

        .legend-knowledge {
            background-color: #BBDEFB;
            border-color: #2196F3;
        }

        .legend-skills {
            background-color: #C8E6C9;
            border-color: #4CAF50;
        }

        .legend-attitude {
            background-color: #E1BEE7;
            border-color: #9C27B0;
        }

        .legend-final {
            background-color: #FFF9E6;
            border-color: #FFD54F;
        }

        /* ====== HEADER STYLING BY KSA GROUP ====== */
        /* Knowledge header - Blue */
        th.header-knowledge {
            background-color: #2196F3 !important;
            color: white !important;
            font-weight: 600;
            border-color: #1976D2 !important;
        }

        /* Skills header - Green */
        th.header-skills {
            background-color: #4CAF50 !important;
            color: white !important;
            font-weight: 600;
            border-color: #388E3C !important;
        }

        /* Attitude header - Purple */
        th.header-attitude {
            background-color: #9C27B0 !important;
            color: white !important;
            font-weight: 600;
            border-color: #7B1FA2 !important;
        }

        /* Final header - Gold */
        th.header-final {
            background-color: #F57F17 !important;
            color: white !important;
            font-weight: 600;
            border-color: #E65100 !important;
        }

        /* Student header - neutral */
        th.header-student {
            background-color: #616161 !important;
            color: white !important;
            font-weight: 600;
            border-color: #424242 !important;
        }

        /* ====== SECONDARY HEADER ROW STYLING ====== */
        /* Style the second header row with lighter shades of the KSA colors */
        thead tr:nth-child(2) th {
            background-color: #F5F5F5 !important;
            border-color: #BDBDBD !important;
            font-weight: 600;
            color: #424242;
        }

        /* Knowledge columns in secondary header */
        thead tr:nth-child(2) th:nth-child(2),
        thead tr:nth-child(2) th:nth-child(3),
        thead tr:nth-child(2) th:nth-child(4),
        thead tr:nth-child(2) th:nth-child(5),
        thead tr:nth-child(2) th:nth-child(6),
        thead tr:nth-child(2) th:nth-child(7),
        thead tr:nth-child(2) th:nth-child(8),
        thead tr:nth-child(2) th:nth-child(9),
        thead tr:nth-child(2) th:nth-child(10),
        thead tr:nth-child(2) th:nth-child(11) {
            background-color: #E3F2FD !important;
            border-color: #90CAF9 !important;
            color: #0D47A1;
        }

        /* Skills columns in secondary header */
        thead tr:nth-child(2) th:nth-child(12),
        thead tr:nth-child(2) th:nth-child(13),
        thead tr:nth-child(2) th:nth-child(14),
        thead tr:nth-child(2) th:nth-child(15),
        thead tr:nth-child(2) th:nth-child(16),
        thead tr:nth-child(2) th:nth-child(17),
        thead tr:nth-child(2) th:nth-child(18),
        thead tr:nth-child(2) th:nth-child(19),
        thead tr:nth-child(2) th:nth-child(20),
        thead tr:nth-child(2) th:nth-child(21),
        thead tr:nth-child(2) th:nth-child(22),
        thead tr:nth-child(2) th:nth-child(23),
        thead tr:nth-child(2) th:nth-child(24),
        thead tr:nth-child(2) th:nth-child(25),
        thead tr:nth-child(2) th:nth-child(26),
        thead tr:nth-child(2) th:nth-child(27),
        thead tr:nth-child(2) th:nth-child(28) {
            background-color: #E8F5E9 !important;
            border-color: #A5D6A7 !important;
            color: #1B5E20;
        }

        /* Attitude columns in secondary header - BEHAVIOR (4 cols), ATTENDANCE (3 cols), AWARENESS (3 cols), empty, A AVE */
        thead tr:nth-child(2) th:nth-child(29),
        thead tr:nth-child(2) th:nth-child(30),
        thead tr:nth-child(2) th:nth-child(31),
        thead tr:nth-child(2) th:nth-child(32),
        thead tr:nth-child(2) th:nth-child(33),
        thead tr:nth-child(2) th:nth-child(34),
        thead tr:nth-child(2) th:nth-child(35),
        thead tr:nth-child(2) th:nth-child(36),
        thead tr:nth-child(2) th:nth-child(37),
        thead tr:nth-child(2) th:nth-child(38),
        thead tr:nth-child(2) th:nth-child(39),
        thead tr:nth-child(2) th:nth-child(40) {
            background-color: #F3E5F5 !important;
            border-color: #E1BEE7 !important;
            color: #4A148C;
        }

        /* Final columns in secondary header - GRADE and DECIMAL GRADE only */
        thead tr:nth-child(2) th:nth-child(42),
        thead tr:nth-child(2) th:nth-child(43) {
            background-color: #FFF9E6 !important;
            border-color: #FFE082 !important;
            color: #F57F17;
        }

        /* Table scrolling and overflow improvements */
        .table-responsive {
            margin-top: 0;
        }

        thead {
            position: sticky;
            top: calc(var(--topbar-height) + 1rem);
            z-index: 5;
            background: white;
        }

        /* Remove sticky from grade entry to prevent overlap */
        .grade-entry-wrapper {
            padding-top: calc(var(--topbar-height) + 1rem);
        }

        .grade-entry-wrapper thead {
            position: relative;
            top: auto;
            z-index: auto;
        }

        /* ====== STUDENT INFO COLUMNS STYLING ====== */
        tbody tr[data-student-id] td:nth-child(1),
        tbody tr[data-student-id] td:nth-child(2) {
            vertical-align: middle;
            height: 42px;
        }

        tbody tr[data-student-id] td:nth-child(1) {
            background-color: #FAFAFA;
            border-right: 2px solid #E0E0E0;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tbody tr[data-student-id] td:nth-child(2) {
            background-color: #FFFFFF;
            padding-left: 14px !important;
            border-left: none;
        }

        .student-col {
            min-width: 100px;
            max-width: 200px;
            word-break: break-word;
        }



        /* Ensure card respects container width */
        .container-fluid .card {
            width: 100%;
            margin: 0;
            overflow: hidden;
        }

        .card-body {
            overflow: visible;
        }
    </style>
    <style>
        /* Grade entry should fill available width within the main content area */
        .grade-entry-container {
            width: 100%;
            margin: 0;
            /* Prevent the grade entry table from sliding under the fixed sidebar */
            padding-left: calc(var(--sidebar-width) + 1rem);
        }

        body.sidebar-collapsed .grade-entry-container {
            padding-left: calc(var(--sidebar-collapsed-width) + 1rem);
        }

        @media (max-width: 768px) {
            .grade-entry-container {
                padding-left: 1rem;
            }
        }
    </style>

    <div class="grade-entry-container">
        <div class="container-fluid px-0 pb-4">
            <div class="grade-entry-wrapper" style="padding-top: calc(var(--topbar-height) + 1rem);">
                <div class="card shadow-sm w-100" style="max-width: 100%; margin: 0;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-0">📝 Grade Entry Form</h4>
                            <small class="text-muted"><?php echo e($class->name); ?> - <?php echo e(ucfirst($term)); ?> Term</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('teacher.assessment.configure', $class->id)); ?>"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-sliders-h me-1"></i>Configure Assessment
                            </a>
                            <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-sm btn-outline-secondary">← Back</a>
                        </div>
                    </div>

                    <form method="POST"
                        action="<?php echo e(route('teacher.grades.store', ['classId' => $class->id])); ?>?term=<?php echo e($term); ?>"
                        id="gradeForm">
                        <?php echo csrf_field(); ?>

                        <div class="card-body" style="padding-top: 1.5rem; overflow: visible;">
                            <div class="alert alert-info mb-3">
                                <strong>💡 Instructions:</strong> Enter all grades for the
                                <strong><?php echo e(ucfirst($term)); ?></strong>
                                term. All averages will compute automatically.
                            </div>

                            <!-- KSA Color Legend -->
                            <div class="ksa-legend mb-4 p-3 bg-white border rounded"
                                style="margin-left: 0; margin-right: 0;">
                                <div style="width: 100%; margin-bottom: 12px;">
                                    <strong style="font-size: 0.95rem; color: #333;">📋 Color Guide:</strong>
                                </div>
                                <div class="ksa-legend-item">
                                    <div class="legend-box legend-knowledge"></div>
                                    <span><strong>Knowledge (K):</strong> Exams & Quizzes</span>
                                </div>
                                <div class="ksa-legend-item">
                                    <div class="legend-box legend-skills"></div>
                                    <span><strong>Skills (S):</strong> Output, Participation, Activities &
                                        Assignments</span>
                                </div>
                                <div class="ksa-legend-item">
                                    <div class="legend-box legend-attitude"></div>
                                    <span><strong>Attitude (A):</strong> Behavior & Awareness</span>
                                </div>
                                <div class="ksa-legend-item">
                                    <div class="legend-box legend-final"></div>
                                    <span><strong>Final Grade:</strong> Overall Assessment</span>
                                </div>
                            </div>

                            <?php if($range): ?>
                                <div class="alert alert-success">
                                    <strong>📊 Current Assessment Ranges for <?php echo e(ucfirst($term)); ?> Term:</strong>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small><strong>Knowledge:</strong></small><br>
                                            <small>• Quizzes: <?php echo e($range->quiz_1_max ?? 25); ?> pts each</small><br>
                                            <small>• Midterm Exam: <?php echo e($range->midterm_exam_max ?? 70); ?> pts</small><br>
                                            <small>• Final Exam: <?php echo e($range->final_exam_max ?? 80); ?> pts</small>
                                        </div>
                                        <div class="col-md-6">
                                            <small><strong>Skills & Attitude:</strong></small><br>
                                            <small>• Output:
                                                <?php echo e($term == 'midterm' ? $range->output_midterm ?? 30 : $range->output_final ?? 30); ?>

                                                pts</small><br>
                                            <small>• Class Participation:
                                                <?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 15 : $range->class_participation_final ?? 15); ?>

                                                pts</small><br>
                                            <small>• Activities:
                                                <?php echo e($term == 'midterm' ? $range->activities_midterm ?? 15 : $range->activities_final ?? 15); ?>

                                                pts</small>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        <a href="<?php echo e(route('teacher.assessment.configure', $class->id)); ?>"
                                            class="alert-link">Configure these ranges</a> to match your actual assessment
                                        items.
                                    </small>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <strong>⚠️ No Assessment Ranges Configured:</strong>
                                    Using default values.
                                    <a href="<?php echo e(route('teacher.assessment.configure', $class->id)); ?>"
                                        class="alert-link">Configure
                                        assessment ranges</a>
                                    to set custom maximum scores for your class.
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive overflow-auto">
                                <table class="table table-bordered table-sm text-center" style="min-width: 1400px;">
                                    <thead class="table-light">
                                        <tr class="table-primary text-white">
                                            <th class="text-start header-student" colspan="2">Student Info</th>
                                            <th colspan="3" class="header-knowledge">EXAM (60% of Knowledge)</th>
                                            <th colspan="6" class="header-knowledge">QUIZZES (40% of Knowledge)</th>

                                            <th colspan="4" class="header-skills">OUTPUT (40% of Skills)</th>
                                            <th colspan="4" class="header-skills">CLASS PART (30% of Skills)</th>
                                            <th colspan="4" class="header-skills">ACTIVITIES (15% of Skills)</th>
                                            <th colspan="4" class="header-skills">ASSIGNMENTS (15% of Skills)</th>
                                            <th class="header-skills"></th>
                                            <th colspan="4" class="header-attitude">BEHAVIOR (50% of Attitude)</th>
                                            <th colspan="3" class="header-attitude">ATTENDANCE (30% of Attitude)</th>
                                            <th colspan="3" class="header-attitude">AWARENESS (20% of Attitude)</th>
                                            <th class="header-attitude"></th>
                                            <th class="header-attitude">A AVE</th>
                                            <th colspan="2" class="header-final">FINAL</th>
                                        </tr>
                                        <tr class="table-light">
                                            <th class="text-center" style="width: 90px;">ID</th>
                                            <th class="text-start" style="width: 160px;">Name</th>
                                            <?php if($term == 'midterm'): ?>
                                                <th>MD</th>
                                            <?php else: ?>
                                                <th>FN</th>
                                            <?php endif; ?>
                                            <th>AVE</th>
                                            <th>Q1</th>
                                            <th>Q2</th>
                                            <th>Q3</th>
                                            <th>Q4</th>
                                            <th>Q5</th>
                                            <th>TOTAL</th>
                                            <th>K AVE</th>
                                            <th>O1</th>
                                            <th>O2</th>
                                            <th>O3</th>
                                            <th>TOTAL</th>
                                            <th>C1</th>
                                            <th>C2</th>
                                            <th>C3</th>
                                            <th>TOTAL</th>
                                            <th>A1</th>
                                            <th>A2</th>
                                            <th>A3</th>
                                            <th>TOTAL</th>
                                            <th>As1</th>
                                            <th>As2</th>
                                            <th>As3</th>
                                            <th>TOTAL</th>
                                            <th>S AVE</th>
                                            <th>B1</th>
                                            <th>B2</th>
                                            <th>B3</th>
                                            <th>TOTAL</th>
                                            <th>Att1</th>
                                            <th>Att2</th>
                                            <th>Att3</th>
                                            <th>Aw1</th>
                                            <th>Aw2</th>
                                            <th>Aw3</th>
                                            <th>TOTAL</th>
                                            <th>A AVE</th>
                                            <th>GRADE</th>
                                            <th>DECIMAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-warning fw-semibold">
                                            <td class="text-center fw-bold"
                                                style="background-color: #FFE082; color: #F57F17;"></td>
                                            <td class="text-start border-end bg-white fw-bold"></td>
                                            <?php if($term == 'midterm'): ?>
                                                <td title="Midterm Exam Max"><?php echo e($range->midterm_exam_max ?? 100); ?></td>
                                            <?php else: ?>
                                                <td title="Final Exam Max"><?php echo e($range->final_exam_max ?? 100); ?></td>
                                            <?php endif; ?>
                                            <td class="bg-info bg-opacity-25">-</td>
                                            <td title="Quiz 1 Max"><?php echo e($range->quiz_1_max ?? 25); ?></td>
                                            <td title="Quiz 2 Max"><?php echo e($range->quiz_2_max ?? 25); ?></td>
                                            <td title="Quiz 3 Max"><?php echo e($range->quiz_3_max ?? 25); ?></td>
                                            <td title="Quiz 4 Max"><?php echo e($range->quiz_4_max ?? 25); ?></td>
                                            <td title="Quiz 5 Max"><?php echo e($range->quiz_5_max ?? 25); ?></td>
                                            <td class="bg-success bg-opacity-25 fw-bold">
                                                <?php echo e(($range->quiz_1_max ?? 25) + ($range->quiz_2_max ?? 25) + ($range->quiz_3_max ?? 25) + ($range->quiz_4_max ?? 25) + ($range->quiz_5_max ?? 25)); ?>

                                            </td>
                                            <td class="bg-info bg-opacity-25">-</td>
                                            <td title="Output 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>

                                            </td>
                                            <td title="Output 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>

                                            </td>
                                            <td title="Output 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">100</td>
                                            <td title="Class Part 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>

                                            </td>
                                            <td title="Class Part 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>

                                            </td>
                                            <td title="Class Part 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">90</td>
                                            <td title="Activity 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>

                                            </td>
                                            <td title="Activity 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>

                                            </td>
                                            <td title="Activity 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">100</td>
                                            <td title="Assignment 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>

                                            </td>
                                            <td title="Assignment 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>

                                            </td>
                                            <td title="Assignment 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">50</td>
                                            <td class="bg-info bg-opacity-25">-</td>
                                            <td title="Behavior 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>

                                            </td>
                                            <td title="Behavior 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>

                                            </td>
                                            <td title="Behavior 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">90</td>
                                            <td title="Attendance 1 Max"><?php echo e($range->attendance_max ?? 100); ?></td>
                                            <td title="Attendance 2 Max"><?php echo e($range->attendance_max ?? 100); ?></td>
                                            <td title="Attendance 3 Max"><?php echo e($range->attendance_max ?? 100); ?></td>
                                            <td title="Awareness 1 Max">
                                                <?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>

                                            </td>
                                            <td title="Awareness 2 Max">
                                                <?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>

                                            </td>
                                            <td title="Awareness 3 Max">
                                                <?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>

                                            </td>
                                            <td class="bg-success bg-opacity-25 fw-bold">85</td>
                                            <td class="bg-info bg-opacity-25">-</td>
                                            <td
                                                style="background-color: #FFF9E6; border: 1px solid #FFD54F; color: #F57F17; font-weight: bold;">
                                                100</td>
                                            <td
                                                style="background-color: #FFF9E6; border: 1px solid #FFD54F; color: #F57F17; font-weight: bold;">
                                                5.0</td>
                                        </tr>

                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $entry = $entries[$student->id] ?? null;
                                            ?>
                                            <tr data-student-id="<?php echo e($student->id); ?>">
                                                <td class="text-center fw-semibold"
                                                    style="background-color: #F0F0F0; color: #2c3e50; font-size: 0.9rem;">
                                                    <?php echo e($student->student_id); ?></td>
                                                <td class="text-start bg-white fw-semibold" style="color: #2c3e50;">
                                                    <?php echo e($student->user->name ?? $student->name); ?></td>

                                                <!-- EXAM -->
                                                <?php if($term == 'midterm'): ?>
                                                    <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                            data-min="0"
                                                            data-max="<?php echo e($range->midterm_exam_max ?? 100); ?>"
                                                            class="form-control form-control-sm text-center grade-input exam-md"
                                                            name="grades[<?php echo e($student->id); ?>][exam_md]"
                                                            value="<?php echo e(intval($entry->exam_md ?? 0)); ?>"></td>
                                                <?php else: ?>
                                                    <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                            data-min="0" data-max="<?php echo e($range->final_exam_max ?? 100); ?>"
                                                            class="form-control form-control-sm text-center grade-input exam-fn"
                                                            name="grades[<?php echo e($student->id); ?>][exam_fn]"
                                                            value="<?php echo e(intval($entry->exam_fn ?? 0)); ?>"></td>
                                                <?php endif; ?>
                                                <td class="computed-cell exam-ave table-secondary fw-semibold">
                                                    <?php echo e($entry && $entry->exam_average !== null ? number_format($entry->exam_average, 2) : '-'); ?>

                                                </td>

                                                <!-- QUIZZES -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->quiz_1_max ?? 25); ?>"
                                                        class="form-control form-control-sm text-center grade-input quiz-1"
                                                        name="grades[<?php echo e($student->id); ?>][quiz_1]"
                                                        value="<?php echo e(intval($entry->quiz_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->quiz_2_max ?? 25); ?>"
                                                        class="form-control form-control-sm text-center grade-input quiz-2"
                                                        name="grades[<?php echo e($student->id); ?>][quiz_2]"
                                                        value="<?php echo e(intval($entry->quiz_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->quiz_3_max ?? 25); ?>"
                                                        class="form-control form-control-sm text-center grade-input quiz-3"
                                                        name="grades[<?php echo e($student->id); ?>][quiz_3]"
                                                        value="<?php echo e(intval($entry->quiz_3 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->quiz_4_max ?? 25); ?>"
                                                        class="form-control form-control-sm text-center grade-input quiz-4"
                                                        name="grades[<?php echo e($student->id); ?>][quiz_4]"
                                                        value="<?php echo e(intval($entry->quiz_4 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->quiz_5_max ?? 25); ?>"
                                                        class="form-control form-control-sm text-center grade-input quiz-5"
                                                        name="grades[<?php echo e($student->id); ?>][quiz_5]"
                                                        value="<?php echo e(intval($entry->quiz_5 ?? 0)); ?>"></td>
                                                <td class="computed-cell quiz-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->quiz_1 || $entry->quiz_2 || $entry->quiz_3 || $entry->quiz_4 || $entry->quiz_5) ? '100' : '-'); ?>

                                                </td>

                                                <!-- KNOWLEDGE AVERAGE -->
                                                <td class="computed-cell knowledge-ave table-secondary fw-semibold">
                                                    <?php echo e($entry && $entry->knowledge_average !== null ? number_format($entry->knowledge_average, 2) : '-'); ?>

                                                </td>

                                                <!-- OUTPUT -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input output-1"
                                                        name="grades[<?php echo e($student->id); ?>][output_1]"
                                                        value="<?php echo e(intval($entry->output_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input output-2"
                                                        name="grades[<?php echo e($student->id); ?>][output_2]"
                                                        value="<?php echo e(intval($entry->output_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->output_midterm ?? 100 : $range->output_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input output-3"
                                                        name="grades[<?php echo e($student->id); ?>][output_3]"
                                                        value="<?php echo e(intval($entry->output_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell output-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->output_1 || $entry->output_2 || $entry->output_3) ? '100' : '-'); ?>

                                                </td>

                                                <!-- CLASS PART -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input classpart-1"
                                                        name="grades[<?php echo e($student->id); ?>][classpart_1]"
                                                        value="<?php echo e(intval($entry->classpart_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input classpart-2"
                                                        name="grades[<?php echo e($student->id); ?>][classpart_2]"
                                                        value="<?php echo e(intval($entry->classpart_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->class_participation_midterm ?? 100 : $range->class_participation_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input classpart-3"
                                                        name="grades[<?php echo e($student->id); ?>][classpart_3]"
                                                        value="<?php echo e(intval($entry->classpart_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell classpart-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->classpart_1 || $entry->classpart_2 || $entry->classpart_3) ? '90' : '-'); ?>

                                                </td>

                                                <!-- ACTIVITIES -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input activity-1"
                                                        name="grades[<?php echo e($student->id); ?>][activity_1]"
                                                        value="<?php echo e(intval($entry->activity_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input activity-2"
                                                        name="grades[<?php echo e($student->id); ?>][activity_2]"
                                                        value="<?php echo e(intval($entry->activity_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->activities_midterm ?? 100 : $range->activities_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input activity-3"
                                                        name="grades[<?php echo e($student->id); ?>][activity_3]"
                                                        value="<?php echo e(intval($entry->activity_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell activity-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->activity_1 || $entry->activity_2 || $entry->activity_3) ? '100' : '-'); ?>

                                                </td>

                                                <!-- ASSIGNMENTS -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input assignment-1"
                                                        name="grades[<?php echo e($student->id); ?>][assignment_1]"
                                                        value="<?php echo e(intval($entry->assignment_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input assignment-2"
                                                        name="grades[<?php echo e($student->id); ?>][assignment_2]"
                                                        value="<?php echo e(intval($entry->assignment_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->assignments_midterm ?? 100 : $range->assignments_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input assignment-3"
                                                        name="grades[<?php echo e($student->id); ?>][assignment_3]"
                                                        value="<?php echo e(intval($entry->assignment_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell assignment-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->assignment_1 || $entry->assignment_2 || $entry->assignment_3) ? '50' : '-'); ?>

                                                </td>

                                                <!-- SKILLS AVERAGE -->
                                                <td class="computed-cell skills-ave table-secondary fw-semibold">
                                                    <?php echo e($entry && ($entry->output_1 || $entry->output_2 || $entry->output_3 || $entry->classpart_1 || $entry->classpart_2 || $entry->classpart_3 || $entry->activity_1 || $entry->activity_2 || $entry->activity_3 || $entry->assignment_1 || $entry->assignment_2 || $entry->assignment_3) ? number_format($entry->skills_average ?? 0, 2) : '-'); ?>

                                                </td>

                                                <!-- BEHAVIOR -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input behavior-1"
                                                        name="grades[<?php echo e($student->id); ?>][behavior_1]"
                                                        value="<?php echo e(intval($entry->behavior_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input behavior-2"
                                                        name="grades[<?php echo e($student->id); ?>][behavior_2]"
                                                        value="<?php echo e(intval($entry->behavior_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->behavior_midterm ?? 100 : $range->behavior_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input behavior-3"
                                                        name="grades[<?php echo e($student->id); ?>][behavior_3]"
                                                        value="<?php echo e(intval($entry->behavior_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell behavior-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->behavior_1 || $entry->behavior_2 || $entry->behavior_3) ? '90' : '-'); ?>

                                                </td>

                                                <!-- ATTENDANCE (30% of Attitude engagement) -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->attendance_max ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input attendance-1"
                                                        name="grades[<?php echo e($student->id); ?>][attendance_1]"
                                                        value="<?php echo e(intval($entry->attendance_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->attendance_max ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input attendance-2"
                                                        name="grades[<?php echo e($student->id); ?>][attendance_2]"
                                                        value="<?php echo e(intval($entry->attendance_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0" data-max="<?php echo e($range->attendance_max ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input attendance-3"
                                                        name="grades[<?php echo e($student->id); ?>][attendance_3]"
                                                        value="<?php echo e(intval($entry->attendance_3 ?? 0)); ?>"></td>

                                                <!-- AWARENESS -->
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input awareness-1"
                                                        name="grades[<?php echo e($student->id); ?>][awareness_1]"
                                                        value="<?php echo e(intval($entry->awareness_1 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input awareness-2"
                                                        name="grades[<?php echo e($student->id); ?>][awareness_2]"
                                                        value="<?php echo e(intval($entry->awareness_2 ?? 0)); ?>"></td>
                                                <td><input type="text" inputmode="numeric" pattern="[0-9]*"
                                                        data-min="0"
                                                        data-max="<?php echo e($term == 'midterm' ? $range->awareness_midterm ?? 100 : $range->awareness_final ?? 100); ?>"
                                                        class="form-control form-control-sm text-center grade-input awareness-3"
                                                        name="grades[<?php echo e($student->id); ?>][awareness_3]"
                                                        value="<?php echo e(intval($entry->awareness_3 ?? 0)); ?>"></td>
                                                <td class="computed-cell awareness-total table-success fw-semibold">
                                                    <?php echo e($entry && ($entry->awareness_1 || $entry->awareness_2 || $entry->awareness_3) ? '85' : '-'); ?>

                                                </td>

                                                <!-- ATTITUDE AVERAGE -->
                                                <td class="computed-cell attitude-ave table-secondary fw-semibold">
                                                    <?php echo e($entry && ($entry->behavior_1 || $entry->behavior_2 || $entry->behavior_3 || $entry->attendance_1 || $entry->attendance_2 || $entry->attendance_3 || $entry->awareness_1 || $entry->awareness_2 || $entry->awareness_3) ? number_format($entry->attitude_average ?? 0, 2) : '-'); ?>

                                                </td>

                                                <!-- FINAL: GRADE AND DECIMAL GRADE -->
                                                <td
                                                    style="background-color: #FFF9E6 !important; border: 1px solid #FFD54F !important; color: #F57F17 !important; font-weight: 700; text-align: center;">
                                                    <?php echo e($entry && (($term == 'midterm' ? $entry->exam_md ?? 0 : $entry->exam_fn ?? 0) || ($entry->quiz_1 ?? 0) || ($entry->quiz_2 ?? 0) || ($entry->quiz_3 ?? 0) || ($entry->quiz_4 ?? 0) || ($entry->quiz_5 ?? 0) || ($entry->output_1 || $entry->output_2 || $entry->output_3 || $entry->classpart_1 || $entry->classpart_2 || $entry->classpart_3 || $entry->activity_1 || $entry->activity_2 || $entry->activity_3 || $entry->assignment_1 || $entry->assignment_2 || $entry->assignment_3) || ($entry->behavior_1 || $entry->behavior_2 || $entry->behavior_3 || $entry->awareness_1 || $entry->awareness_2 || $entry->awareness_3)) ? number_format($entry->term_grade ?? 0, 2) : '-'); ?>

                                                </td>
                                                <td style="background-color: #FFF9E6 !important; border: 1px solid #FFD54F !important; color: #F57F17 !important; font-weight: 700; text-align: center;"
                                                    class="decimal-grade-cell">
                                                    -
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer bg-light py-3">
                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                                <small class="text-muted mb-0">All averages compute automatically as you type.</small>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Save <?php echo e(ucfirst($term)); ?> Grades
                                    </button>
                                    <button type="button" class="btn btn-success px-4" data-bs-toggle="modal"
                                        data-bs-target="#uploadModal">
                                        <i class="fas fa-cloud-upload-alt me-1"></i> Upload
                                    </button>
                                    <a href="<?php echo e(route('teacher.grades.results')); ?>" class="btn btn-info px-4">
                                        <i class="fas fa-chart-line me-1"></i> View Results
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upload/Lock Confirmation Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="card border-success shadow-lg">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="uploadModalLabel">🔒 Upload/Lock Grades to Permanent Storage</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>⚠️ Important:</strong> This action will:
                                <ul class="mb-0 mt-2">
                                    <li>Transfer all saved <?php echo e(ucfirst($term)); ?> grades to the permanent
                                        <strong>grades</strong>
                                        table
                                    </li>
                                    <li>Lock these grades for admin to fetch and process</li>
                                    <li>Make them visible in grade reports</li>
                                    <li>This action <strong>CANNOT be undone</strong> without database intervention</li>
                                </ul>
                            </div>
                            <p><strong>Are you sure you want to proceed?</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST"
                                action="<?php echo e(route('teacher.grades.upload', ['classId' => $class->id])); ?>"
                                style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="term" value="<?php echo e($term); ?>">
                                <button type="submit" class="btn btn-success btn-lg">Yes, Upload Grades</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php $__env->startSection('scripts'); ?>
        <script>
            // Component Weights for Calculations
            const WEIGHTS = {
                knowledge: 40,
                skills: 50,
                attitude: 10
            };

            document.addEventListener('DOMContentLoaded', function() {
                // Auto-resize input width based on content
                function autoResizeInput(input) {
                    const minWidth = 40;
                    if (input.value.length === 0) {
                        input.style.width = minWidth + 'px';
                    } else {
                        // Create a temporary span to measure text width
                        const span = document.createElement('span');
                        span.style.position = 'absolute';
                        span.style.visibility = 'hidden';
                        span.style.whiteSpace = 'nowrap';
                        span.style.font = window.getComputedStyle(input).font;
                        span.textContent = input.value;
                        document.body.appendChild(span);
                        const width = Math.max(span.offsetWidth + 12, minWidth); // 12px for padding
                        input.style.width = width + 'px';
                        document.body.removeChild(span);
                    }
                }

                // Add real-time validation to all grade inputs
                document.querySelectorAll('.grade-input').forEach(input => {
                    input.addEventListener('input', function() {
                        const maxValue = parseFloat(this.dataset.max) || 100;
                        const value = parseFloat(this.value) || 0;

                        if (value > maxValue) {
                            this.value = maxValue;
                            // Visual feedback
                            this.style.backgroundColor = '#ffebee';
                            setTimeout(() => {
                                this.style.backgroundColor = '';
                            }, 1000);
                        }
                    });
                });

                // Calculate on input change
                document.querySelectorAll('.grade-input').forEach(input => {
                    autoResizeInput(input);

                    input.addEventListener('input', function() {
                        // Restrict to digits and enforce max (type="text" inputmode="numeric")
                        const max = this.getAttribute('data-max');
                        let val = this.value.replace(/\D/g, '');
                        if (max !== null && val !== '' && parseInt(val, 10) > parseInt(max, 10)) {
                            val = max;
                        }
                        this.value = val;

                        autoResizeInput(this);
                        const row = this.closest('tr');
                        calculateRowAverages(row);
                    });
                });

                // Initial calculation for all rows (will use database values if available)
                document.querySelectorAll('tbody tr[data-student-id]').forEach(row => {
                    calculateRowAverages(row);
                });

                function calculateRowAverages(row) {
                    // Input validation function
                    function validateInput(input, maxValue) {
                        const value = parseFloat(input.value) || 0;
                        if (value > maxValue) {
                            input.value = maxValue;
                            // Visual feedback
                            input.style.backgroundColor = '#ffebee';
                            setTimeout(() => {
                                input.style.backgroundColor = '';
                            }, 1000);
                        }
                    }

                    // Validate all inputs against their max values
                    row.querySelectorAll('.grade-input').forEach(input => {
                        const maxValue = parseFloat(input.dataset.max) || 100;
                        validateInput(input, maxValue);
                    });

                    // Helper to get value or 0
                    const getValue = (selector) => {
                        const input = row.querySelector(selector);
                        return (input && input.value) ? parseFloat(input.value) : 0;
                    };

                    // Check if row has any input (to decide if we show calculations)
                    const inputs = row.querySelectorAll('.grade-input');
                    let hasInput = false;
                    inputs.forEach(inp => {
                        if (inp.value && parseFloat(inp.value) > 0) {
                            hasInput = true;
                        }
                    });

                    // EXAM: Handle conditional exam fields based on term
                    const examMd = getValue('.exam-md');
                    const examFn = getValue('.exam-fn');
                    const examMdElement = row.querySelector('.exam-md');
                    const examFnElement = row.querySelector('.exam-fn');

                    let examValue = 0;
                    let examMax = 100;
                    let examPercent = 0;

                    if (examMdElement) {
                        // Midterm term - use exam-md
                        examValue = examMd;
                        examMax = parseFloat(examMdElement.dataset.max || 100);
                        examPercent = examMax > 0 ? (examValue / examMax) * 100 : 0;
                    } else if (examFnElement) {
                        // Final term - use exam-fn
                        examValue = examFn;
                        examMax = parseFloat(examFnElement.dataset.max || 100);
                        examPercent = examMax > 0 ? (examValue / examMax) * 100 : 0;
                    }

                    const examAveCell = row.querySelector('.exam-ave');
                    if (examAveCell) {
                        examAveCell.textContent = examValue > 0 ? examPercent.toFixed(2) : (examAveCell
                            .textContent === '-' ? '-' : examAveCell.textContent);
                    }

                    // QUIZZES: Total of 5 quizzes
                    const quizzes = [
                        getValue('.quiz-1'),
                        getValue('.quiz-2'),
                        getValue('.quiz-3'),
                        getValue('.quiz-4'),
                        getValue('.quiz-5')
                    ];
                    const quizTotal = quizzes.reduce((a, b) => a + b, 0);

                    // Get individual quiz max values from Configure Assessment Ranges with null checks
                    const quiz1Element = row.querySelector('.quiz-1');
                    const quiz2Element = row.querySelector('.quiz-2');
                    const quiz3Element = row.querySelector('.quiz-3');
                    const quiz4Element = row.querySelector('.quiz-4');
                    const quiz5Element = row.querySelector('.quiz-5');

                    const quiz1Max = quiz1Element ? parseFloat(quiz1Element.dataset.max || 25) : 25;
                    const quiz2Max = quiz2Element ? parseFloat(quiz2Element.dataset.max || 25) : 25;
                    const quiz3Max = quiz3Element ? parseFloat(quiz3Element.dataset.max || 25) : 25;
                    const quiz4Max = quiz4Element ? parseFloat(quiz4Element.dataset.max || 25) : 25;
                    const quiz5Max = quiz5Element ? parseFloat(quiz5Element.dataset.max || 25) : 25;
                    const quizMaxTotal = quiz1Max + quiz2Max + quiz3Max + quiz4Max + quiz5Max;

                    // Calculate quiz percentage (normalization) - ensure 0-100 scale
                    const quizPercent = quizMaxTotal > 0 ? Math.round((quizTotal / quizMaxTotal) * 100) : 0;

                    // Quiz total is now handled by Blade template with static value

                    // KNOWLEDGE = (Exam Percentage × 60%) + (Quiz Percentage × 40%)
                    // Use normalized exam percentage and correct weights
                    const knowledge = (examPercent * 0.60) + (quizPercent * 0.40);

                    const knowledgeCell = row.querySelector('.knowledge-ave');
                    if (knowledgeCell !== null) {
                        // Force update - always override database value
                        const knowledgeText = (examValue > 0 || quizzes.some(q => q > 0)) ? knowledge.toFixed(2) : '-';
                        knowledgeCell.textContent = knowledgeText;
                    }

                    // SKILLS: Calculate from student's actual input percentages
                    const outputs = [
                        getValue('.output-1'),
                        getValue('.output-2'),
                        getValue('.output-3')
                    ];
                    const outputTotal = outputs.reduce((a, b) => a + b, 0);

                    const output1Element = row.querySelector('.output-1');
                    const output2Element = row.querySelector('.output-2');
                    const output3Element = row.querySelector('.output-3');
                    const output1Max = output1Element ? parseFloat(output1Element.dataset.max || 100) : 100;
                    const output2Max = output2Element ? parseFloat(output2Element.dataset.max || 100) : 100;
                    const output3Max = output3Element ? parseFloat(output3Element.dataset.max || 100) : 100;
                    const outputMaxTotal = output1Max + output2Max + output3Max;
                    const outputPercent = outputMaxTotal > 0 ? (outputTotal / outputMaxTotal) * 100 : 0;

                    const classparts = [
                        getValue('.classpart-1'),
                        getValue('.classpart-2'),
                        getValue('.classpart-3')
                    ];
                    const classpartTotal = classparts.reduce((a, b) => a + b, 0);

                    const classpart1Element = row.querySelector('.classpart-1');
                    const classpart2Element = row.querySelector('.classpart-2');
                    const classpart3Element = row.querySelector('.classpart-3');
                    const classpart1Max = classpart1Element ? parseFloat(classpart1Element.dataset.max || 100) : 100;
                    const classpart2Max = classpart2Element ? parseFloat(classpart2Element.dataset.max || 100) : 100;
                    const classpart3Max = classpart3Element ? parseFloat(classpart3Element.dataset.max || 100) : 100;
                    const classpartMaxTotal = classpart1Max + classpart2Max + classpart3Max;
                    const classpartPercent = classpartMaxTotal > 0 ? (classpartTotal / classpartMaxTotal) * 100 : 0;

                    const activities = [
                        getValue('.activity-1'),
                        getValue('.activity-2'),
                        getValue('.activity-3')
                    ];
                    const activityTotal = activities.reduce((a, b) => a + b, 0);

                    const activity1Element = row.querySelector('.activity-1');
                    const activity2Element = row.querySelector('.activity-2');
                    const activity3Element = row.querySelector('.activity-3');
                    const activity1Max = activity1Element ? parseFloat(activity1Element.dataset.max || 100) : 100;
                    const activity2Max = activity2Element ? parseFloat(activity2Element.dataset.max || 100) : 100;
                    const activity3Max = activity3Element ? parseFloat(activity3Element.dataset.max || 100) : 100;
                    const activityMaxTotal = activity1Max + activity2Max + activity3Max;
                    const activityPercent = activityMaxTotal > 0 ? (activityTotal / activityMaxTotal) * 100 : 0;

                    const assignments = [
                        getValue('.assignment-1'),
                        getValue('.assignment-2'),
                        getValue('.assignment-3')
                    ];
                    const assignmentTotal = assignments.reduce((a, b) => a + b, 0);

                    const assignment1Element = row.querySelector('.assignment-1');
                    const assignment2Element = row.querySelector('.assignment-2');
                    const assignment3Element = row.querySelector('.assignment-3');
                    const assignment1Max = assignment1Element ? parseFloat(assignment1Element.dataset.max || 100) : 100;
                    const assignment2Max = assignment2Element ? parseFloat(assignment2Element.dataset.max || 100) : 100;
                    const assignment3Max = assignment3Element ? parseFloat(assignment3Element.dataset.max || 100) : 100;
                    const assignmentMaxTotal = assignment1Max + assignment2Max + assignment3Max;
                    const assignmentPercent = assignmentMaxTotal > 0 ? (assignmentTotal / assignmentMaxTotal) * 100 : 0;

                    const skills =
                        (outputPercent * 0.40) +
                        (classpartPercent * 0.30) +
                        (activityPercent * 0.15) +
                        (assignmentPercent * 0.15);

                    const skillsCell = row.querySelector('.skills-ave');
                    if (skillsCell) {
                        const skillsText = skills.toFixed(2);
                        skillsCell.textContent = skillsText;
                    }

                    // ATTITUDE: Calculate from student's actual input percentages
                    // NEW FORMULA: Behavior (50%) + [Attendance (30%) + Awareness (20%)]
                    // Attendance and Awareness form the "Engagement" component (50% of Attitude)

                    const behaviors = [
                        getValue('.behavior-1'),
                        getValue('.behavior-2'),
                        getValue('.behavior-3')
                    ];
                    const behaviorTotal = behaviors.reduce((a, b) => a + b, 0);

                    const behavior1Element = row.querySelector('.behavior-1');
                    const behavior2Element = row.querySelector('.behavior-2');
                    const behavior3Element = row.querySelector('.behavior-3');
                    const behavior1Max = behavior1Element ? parseFloat(behavior1Element.dataset.max || 100) : 100;
                    const behavior2Max = behavior2Element ? parseFloat(behavior2Element.dataset.max || 100) : 100;
                    const behavior3Max = behavior3Element ? parseFloat(behavior3Element.dataset.max || 100) : 100;
                    const behaviorMaxTotal = behavior1Max + behavior2Max + behavior3Max;
                    const behaviorPercent = behaviorMaxTotal > 0 ? (behaviorTotal / behaviorMaxTotal) * 100 : 0;

                    // ATTENDANCE (NEW - 30% of Attitude when combined with Awareness)
                    const attendances = [
                        getValue('.attendance-1'),
                        getValue('.attendance-2'),
                        getValue('.attendance-3')
                    ];
                    const attendanceTotal = attendances.reduce((a, b) => a + b, 0);

                    const attendance1Element = row.querySelector('.attendance-1');
                    const attendance2Element = row.querySelector('.attendance-2');
                    const attendance3Element = row.querySelector('.attendance-3');
                    const attendance1Max = attendance1Element ? parseFloat(attendance1Element.dataset.max || 100) : 100;
                    const attendance2Max = attendance2Element ? parseFloat(attendance2Element.dataset.max || 100) : 100;
                    const attendance3Max = attendance3Element ? parseFloat(attendance3Element.dataset.max || 100) : 100;
                    const attendanceMaxTotal = attendance1Max + attendance2Max + attendance3Max;
                    const attendancePercent = attendanceMaxTotal > 0 ? (attendanceTotal / attendanceMaxTotal) * 100 : 0;

                    const awareness_arr = [
                        getValue('.awareness-1'),
                        getValue('.awareness-2'),
                        getValue('.awareness-3')
                    ];
                    const awarenessTotal = awareness_arr.reduce((a, b) => a + b, 0);

                    const awareness1Element = row.querySelector('.awareness-1');
                    const awareness2Element = row.querySelector('.awareness-2');
                    const awareness3Element = row.querySelector('.awareness-3');
                    const awareness1Max = awareness1Element ? parseFloat(awareness1Element.dataset.max || 100) : 100;
                    const awareness2Max = awareness2Element ? parseFloat(awareness2Element.dataset.max || 100) : 100;
                    const awareness3Max = awareness3Element ? parseFloat(awareness3Element.dataset.max || 100) : 100;
                    const awarenessMaxTotal = awareness1Max + awareness2Max + awareness3Max;
                    const awarenessPercent = awarenessMaxTotal > 0 ? (awarenessTotal / awarenessMaxTotal) * 100 : 0;

                    // Engagement = Attendance (60%) + Awareness (40%)
                    const engagement = (attendancePercent * 0.60) + (awarenessPercent * 0.40);

                    // Attitude = Behavior (50%) + Engagement (50%)
                    const attitude = (behaviorPercent * 0.50) + (engagement * 0.50);

                    const attitudeCell = row.querySelector('.attitude-ave');
                    if (attitudeCell) {
                        const attitudeText = attitude.toFixed(2);
                        attitudeCell.textContent = attitudeText;
                    }

                    // FINAL GRADE = (K × 40% + S × 50% + A × 10%)
                    const finalGrade = hasInput ? (knowledge * 0.40) + (skills * 0.50) + (attitude * 0.10) : 0;

                    // Convert to decimal scale (1.0-5.0)
                    let decimalGrade = 5.0;
                    if (finalGrade >= 98) decimalGrade = 1.0;
                    else if (finalGrade >= 95) decimalGrade = 1.25;
                    else if (finalGrade >= 92) decimalGrade = 1.50;
                    else if (finalGrade >= 89) decimalGrade = 1.75;
                    else if (finalGrade >= 86) decimalGrade = 2.00;
                    else if (finalGrade >= 83) decimalGrade = 2.25;
                    else if (finalGrade >= 80) decimalGrade = 2.50;
                    else if (finalGrade >= 77) decimalGrade = 2.75;
                    else if (finalGrade >= 74) decimalGrade = 3.00;
                    else if (finalGrade >= 71) decimalGrade = 3.25;
                    else if (finalGrade >= 70) decimalGrade = 3.50;

                    // Update GRADE and DECIMAL GRADE cells
                    // Get all td elements at the end of final section
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 2) {
                        // Last two cells are GRADE and DECIMAL
                        const lastCell = cells[cells.length - 1]; // DECIMAL GRADE
                        const secondLastCell = cells[cells.length - 2]; // GRADE

                        if (secondLastCell) {
                            secondLastCell.textContent = hasInput ? finalGrade.toFixed(2) : (secondLastCell
                                .textContent === '-' ? '-' : secondLastCell.textContent);
                        }
                        if (lastCell && lastCell.classList.contains('decimal-grade-cell')) {
                            lastCell.textContent = hasInput ? decimalGrade.toFixed(2) : (lastCell.textContent === '-' ?
                                '-' : lastCell.textContent);
                        }
                    }
                }
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\grades\grade_entry.blade.php ENDPATH**/ ?>