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
    </style>
    <div class="container-fluid py-4" style="max-width: 100%; min-width: 0;">
        <div class="card shadow-sm" style="max-width: 100%; overflow: hidden;">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">📝 Grade Entry Form</h4>
                    <small class="text-muted"><?php echo e($class->name); ?> - <?php echo e(ucfirst($term)); ?> Term</small>
                </div>
                <div>
                    <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-sm btn-outline-secondary">← Back</a>
                </div>
            </div>

            <form method="POST"
                action="<?php echo e(route('teacher.grades.store', ['classId' => $class->id])); ?>?term=<?php echo e($term); ?>"
                id="gradeForm">
                <?php echo csrf_field(); ?>

                <div class="card-body overflow-hidden">
                    <div class="alert alert-info">
                        <strong>💡 Instructions:</strong> Enter all grades for the <strong><?php echo e(ucfirst($term)); ?></strong>
                        term. All averages will compute automatically.
                    </div>

                    <div class="table-responsive overflow-auto">
                        <table class="table table-bordered table-sm text-center" style="min-width: 1400px;">
                            <thead class="table-light">
                                <tr class="table-primary text-white">
                                    <th class="text-start">Student</th>
                                    <th colspan="3">EXAM (60% of Knowledge)</th>
                                    <th colspan="6">QUIZZES (40% of Knowledge)</th>
                                    <th>K AVE</th>
                                    <th colspan="4">OUTPUT (40% of Skills)</th>
                                    <th colspan="4">CLASS PART (30% of Skills)</th>
                                    <th colspan="4">ACTIVITIES (15% of Skills)</th>
                                    <th colspan="4">ASSIGNMENTS (15% of Skills)</th>
                                    <th>S AVE</th>
                                    <th colspan="4">BEHAVIOR (50% of Attitude)</th>
                                    <th colspan="4">AWARENESS (50% of Attitude)</th>
                                    <th>A AVE</th>
                                    <th colspan="4">FINAL</th>
                                </tr>
                                <tr class="table-light">
                                    <th class="text-start">Name</th>
                                    <th>PR</th>
                                    <th>MD</th>
                                    <th>AVE</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                    <th>Q5</th>
                                    <th>AVE</th>
                                    <th>AVE</th>
                                    <th>O1</th>
                                    <th>O2</th>
                                    <th>O3</th>
                                    <th>AVE</th>
                                    <th>C1</th>
                                    <th>C2</th>
                                    <th>C3</th>
                                    <th>AVE</th>
                                    <th>A1</th>
                                    <th>A2</th>
                                    <th>A3</th>
                                    <th>AVE</th>
                                    <th>As1</th>
                                    <th>As2</th>
                                    <th>As3</th>
                                    <th>AVE</th>
                                    <th>AVE</th>
                                    <th>B1</th>
                                    <th>B2</th>
                                    <th>B3</th>
                                    <th>AVE</th>
                                    <th>Aw1</th>
                                    <th>Aw2</th>
                                    <th>Aw3</th>
                                    <th>AVE</th>
                                    <th>AVE</th>
                                    <th>K</th>
                                    <th>S</th>
                                    <th>A</th>
                                    <th>GRADE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-warning fw-semibold">
                                    <td class="student-col text-start border-end bg-white fw-bold">📊 MAX LIMITS</td>
                                    <td title="Preliminary Exam Max">100</td>
                                    <td title="Midterm Exam Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td title="Quiz 1 Max">25</td>
                                    <td title="Quiz 2 Max">25</td>
                                    <td title="Quiz 3 Max">25</td>
                                    <td title="Quiz 4 Max">25</td>
                                    <td title="Quiz 5 Max">25</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td title="Output 1 Max">100</td>
                                    <td title="Output 2 Max">100</td>
                                    <td title="Output 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td title="Class Part 1 Max">100</td>
                                    <td title="Class Part 2 Max">100</td>
                                    <td title="Class Part 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td title="Activity 1 Max">100</td>
                                    <td title="Activity 2 Max">100</td>
                                    <td title="Activity 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td title="Assignment 1 Max">100</td>
                                    <td title="Assignment 2 Max">100</td>
                                    <td title="Assignment 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td title="Behavior 1 Max">100</td>
                                    <td title="Behavior 2 Max">100</td>
                                    <td title="Behavior 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td title="Awareness 1 Max">100</td>
                                    <td title="Awareness 2 Max">100</td>
                                    <td title="Awareness 3 Max">100</td>
                                    <td class="bg-info bg-opacity-25">-</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                    <td class="bg-info bg-opacity-25">100</td>
                                </tr>

                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $entry = $entries[$student->id] ?? null;
                                    ?>
                                    <tr data-student-id="<?php echo e($student->id); ?>">
                                        <td class="student-col text-start border-end bg-white fw-semibold">
                                            <div><strong><?php echo e($student->user->name ?? $student->name); ?></strong></div>
                                            <small class="text-muted">ID: <?php echo e($student->student_id); ?></small>
                                        </td>

                                        <!-- EXAM -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input exam-pr" name="grades[<?php echo e($student->id); ?>][exam_pr]"
                                                value="<?php echo e(intval($entry->exam_pr ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input exam-md" name="grades[<?php echo e($student->id); ?>][exam_md]"
                                                value="<?php echo e(intval($entry->exam_md ?? 0)); ?>"></td>
                                        <td class="computed-cell exam-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->exam_average !== null ? number_format($entry->exam_average, 2) : '-'); ?>

                                        </td>

                                        <!-- QUIZZES -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="25"
                                                class="form-control form-control-sm text-center grade-input quiz-1" name="grades[<?php echo e($student->id); ?>][quiz_1]"
                                                value="<?php echo e(intval($entry->quiz_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="25"
                                                class="form-control form-control-sm text-center grade-input quiz-2" name="grades[<?php echo e($student->id); ?>][quiz_2]"
                                                value="<?php echo e(intval($entry->quiz_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="25"
                                                class="form-control form-control-sm text-center grade-input quiz-3" name="grades[<?php echo e($student->id); ?>][quiz_3]"
                                                value="<?php echo e(intval($entry->quiz_3 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="25"
                                                class="form-control form-control-sm text-center grade-input quiz-4" name="grades[<?php echo e($student->id); ?>][quiz_4]"
                                                value="<?php echo e(intval($entry->quiz_4 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="25"
                                                class="form-control form-control-sm text-center grade-input quiz-5" name="grades[<?php echo e($student->id); ?>][quiz_5]"
                                                value="<?php echo e(intval($entry->quiz_5 ?? 0)); ?>"></td>
                                        <td class="computed-cell quiz-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->quiz_average !== null ? number_format($entry->quiz_average, 2) : '-'); ?>

                                        </td>

                                        <!-- KNOWLEDGE AVE -->
                                        <td class="computed-cell knowledge-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->knowledge_average !== null ? number_format($entry->knowledge_average, 2) : '-'); ?>

                                        </td>

                                        <!-- OUTPUT -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input output-1" name="grades[<?php echo e($student->id); ?>][output_1]"
                                                value="<?php echo e(intval($entry->output_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input output-2" name="grades[<?php echo e($student->id); ?>][output_2]"
                                                value="<?php echo e(intval($entry->output_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input output-3" name="grades[<?php echo e($student->id); ?>][output_3]"
                                                value="<?php echo e(intval($entry->output_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell output-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->output_average !== null ? number_format($entry->output_average, 2) : '-'); ?>

                                        </td>

                                        <!-- CLASS PART -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input classpart-1"
                                                name="grades[<?php echo e($student->id); ?>][classpart_1]"
                                                value="<?php echo e(intval($entry->classpart_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input classpart-2"
                                                name="grades[<?php echo e($student->id); ?>][classpart_2]"
                                                value="<?php echo e(intval($entry->classpart_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input classpart-3"
                                                name="grades[<?php echo e($student->id); ?>][classpart_3]"
                                                value="<?php echo e(intval($entry->classpart_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell classpart-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->classpart_average !== null ? number_format($entry->classpart_average, 2) : '-'); ?>

                                        </td>

                                        <!-- ACTIVITIES -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input activity-1"
                                                name="grades[<?php echo e($student->id); ?>][activity_1]"
                                                value="<?php echo e(intval($entry->activity_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input activity-2"
                                                name="grades[<?php echo e($student->id); ?>][activity_2]"
                                                value="<?php echo e(intval($entry->activity_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input activity-3"
                                                name="grades[<?php echo e($student->id); ?>][activity_3]"
                                                value="<?php echo e(intval($entry->activity_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell activity-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->activity_average !== null ? number_format($entry->activity_average, 2) : '-'); ?>

                                        </td>

                                        <!-- ASSIGNMENTS -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input assignment-1"
                                                name="grades[<?php echo e($student->id); ?>][assignment_1]"
                                                value="<?php echo e(intval($entry->assignment_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input assignment-2"
                                                name="grades[<?php echo e($student->id); ?>][assignment_2]"
                                                value="<?php echo e(intval($entry->assignment_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input assignment-3"
                                                name="grades[<?php echo e($student->id); ?>][assignment_3]"
                                                value="<?php echo e(intval($entry->assignment_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell assignment-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->assignment_average !== null ? number_format($entry->assignment_average, 2) : '-'); ?>

                                        </td>

                                        <!-- SKILLS AVE -->
                                        <td class="computed-cell skills-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->skills_average !== null ? number_format($entry->skills_average, 2) : '-'); ?>

                                        </td>

                                        <!-- BEHAVIOR -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input behavior-1"
                                                name="grades[<?php echo e($student->id); ?>][behavior_1]"
                                                value="<?php echo e(intval($entry->behavior_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input behavior-2"
                                                name="grades[<?php echo e($student->id); ?>][behavior_2]"
                                                value="<?php echo e(intval($entry->behavior_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input behavior-3"
                                                name="grades[<?php echo e($student->id); ?>][behavior_3]"
                                                value="<?php echo e(intval($entry->behavior_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell behavior-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->behavior_average !== null ? number_format($entry->behavior_average, 2) : '-'); ?>

                                        </td>

                                        <!-- AWARENESS -->
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input awareness-1"
                                                name="grades[<?php echo e($student->id); ?>][awareness_1]"
                                                value="<?php echo e(intval($entry->awareness_1 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input awareness-2"
                                                name="grades[<?php echo e($student->id); ?>][awareness_2]"
                                                value="<?php echo e(intval($entry->awareness_2 ?? 0)); ?>"></td>
                                        <td><input type="text" inputmode="numeric" pattern="[0-9]*" data-min="0" data-max="100"
                                                class="form-control form-control-sm text-center grade-input awareness-3"
                                                name="grades[<?php echo e($student->id); ?>][awareness_3]"
                                                value="<?php echo e(intval($entry->awareness_3 ?? 0)); ?>"></td>
                                        <td class="computed-cell awareness-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->awareness_average !== null ? number_format($entry->awareness_average, 2) : '-'); ?>

                                        </td>

                                        <!-- ATTITUDE AVE -->
                                        <td class="computed-cell attitude-ave table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->attitude_average !== null ? number_format($entry->attitude_average, 2) : '-'); ?>

                                        </td>

                                        <!-- FINAL: K, S, A, GRADE -->
                                        <td class="computed-cell k-display table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->knowledge_average !== null ? number_format($entry->knowledge_average, 2) : '-'); ?>

                                        </td>
                                        <td class="computed-cell s-display table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->skills_average !== null ? number_format($entry->skills_average, 2) : '-'); ?>

                                        </td>
                                        <td class="computed-cell a-display table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->attitude_average !== null ? number_format($entry->attitude_average, 2) : '-'); ?>

                                        </td>
                                        <td class="computed-cell final-grade table-secondary fw-semibold">
                                            <?php echo e($entry && $entry->term_grade !== null ? number_format($entry->term_grade, 2) : '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light py-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <small class="text-muted mb-0">All averages compute automatically as you type.</small>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Save <?php echo e(ucfirst($term)); ?> Grades
                            </button>
                            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                                <li>Transfer all saved <?php echo e(ucfirst($term)); ?> grades to the permanent <strong>grades</strong>
                                    table</li>
                                <li>Lock these grades for admin to fetch and process</li>
                                <li>Make them visible in grade reports</li>
                                <li>This action <strong>CANNOT be undone</strong> without database intervention</li>
                            </ul>
                        </div>
                        <p><strong>Are you sure you want to proceed?</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="<?php echo e(route('teacher.grades.upload', ['classId' => $class->id])); ?>"
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

            // Add event listeners to all grade inputs
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
            document.querySelectorAll('tbody tr').forEach(row => {
                calculateRowAverages(row);
            });

            function calculateRowAverages(row) {
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

                // EXAM: Average of PR and MD
                const examPr = getValue('.exam-pr');
                const examMd = getValue('.exam-md');
                const examAve = (examPr + examMd) / 2;
                const examAveCell = row.querySelector('.exam-ave');
                examAveCell.textContent = (examPr > 0 || examMd > 0) ? examAve.toFixed(2) : (examAveCell
                    .textContent === '-' ? '-' : examAveCell.textContent);

                // QUIZZES: Average of 5 quizzes
                const quizzes = [
                    getValue('.quiz-1'),
                    getValue('.quiz-2'),
                    getValue('.quiz-3'),
                    getValue('.quiz-4'),
                    getValue('.quiz-5')
                ];
                const quizAve = quizzes.some(q => q > 0) ? quizzes.reduce((a, b) => a + b) / quizzes.length : 0;
                const quizAveCell = row.querySelector('.quiz-ave');
                quizAveCell.textContent = quizzes.some(q => q > 0) ? quizAve.toFixed(2) : (quizAveCell
                    .textContent === '-' ? '-' : quizAveCell.textContent);

                // KNOWLEDGE = (Exam 60% + Quiz 40%)
                const knowledge = (examAve * 0.60) + (quizAve * 0.40);
                const knowledgeCell = row.querySelector('.knowledge-ave');
                knowledgeCell.textContent = (examPr > 0 || examMd > 0 || quizzes.some(q => q > 0)) ? knowledge
                    .toFixed(2) : (knowledgeCell.textContent === '-' ? '-' : knowledgeCell.textContent);

                // OUTPUT: Average of 3 items
                const outputs = [
                    getValue('.output-1'),
                    getValue('.output-2'),
                    getValue('.output-3')
                ];
                const outputAve = outputs.some(o => o > 0) ? outputs.reduce((a, b) => a + b) / outputs.length : 0;
                const outputAveCell = row.querySelector('.output-ave');
                outputAveCell.textContent = outputs.some(o => o > 0) ? outputAve.toFixed(2) : (outputAveCell
                    .textContent === '-' ? '-' : outputAveCell.textContent);

                // CLASS PART: Average of 3 items
                const classparts = [
                    getValue('.classpart-1'),
                    getValue('.classpart-2'),
                    getValue('.classpart-3')
                ];
                const classpartAve = classparts.some(c => c > 0) ? classparts.reduce((a, b) => a + b) / classparts
                    .length : 0;
                const classpartAveCell = row.querySelector('.classpart-ave');
                classpartAveCell.textContent = classparts.some(c => c > 0) ? classpartAve.toFixed(2) : (
                    classpartAveCell.textContent === '-' ? '-' : classpartAveCell.textContent);

                // ACTIVITIES: Average of 3 items
                const activities = [
                    getValue('.activity-1'),
                    getValue('.activity-2'),
                    getValue('.activity-3')
                ];
                const activityAve = activities.some(a => a > 0) ? activities.reduce((a, b) => a + b) / activities
                    .length : 0;
                const activityAveCell = row.querySelector('.activity-ave');
                activityAveCell.textContent = activities.some(a => a > 0) ? activityAve.toFixed(2) : (
                    activityAveCell.textContent === '-' ? '-' : activityAveCell.textContent);

                // ASSIGNMENTS: Average of 3 items
                const assignments = [
                    getValue('.assignment-1'),
                    getValue('.assignment-2'),
                    getValue('.assignment-3')
                ];
                const assignmentAve = assignments.some(a => a > 0) ? assignments.reduce((a, b) => a + b) /
                    assignments.length : 0;
                const assignmentAveCell = row.querySelector('.assignment-ave');
                assignmentAveCell.textContent = assignments.some(a => a > 0) ? assignmentAve.toFixed(2) : (
                    assignmentAveCell.textContent === '-' ? '-' : assignmentAveCell.textContent);

                // SKILLS = (Output 40% + ClassPart 30% + Activities 15% + Assignments 15%)
                const skillsHasInput = outputs.some(o => o > 0) || classparts.some(c => c > 0) || activities.some(
                    a => a > 0) || assignments.some(a => a > 0);
                const skills = skillsHasInput ? (outputAve * 0.40) + (classpartAve * 0.30) + (activityAve * 0.15) +
                    (assignmentAve * 0.15) : 0;
                const skillsAveCell = row.querySelector('.skills-ave');
                skillsAveCell.textContent = skillsHasInput ? skills.toFixed(2) : (skillsAveCell.textContent ===
                    '-' ? '-' : skillsAveCell.textContent);

                // BEHAVIOR: Average of 3 items
                const behaviors = [
                    getValue('.behavior-1'),
                    getValue('.behavior-2'),
                    getValue('.behavior-3')
                ];
                const behaviorAve = behaviors.some(b => b > 0) ? behaviors.reduce((a, b) => a + b) / behaviors
                    .length : 0;
                const behaviorAveCell = row.querySelector('.behavior-ave');
                behaviorAveCell.textContent = behaviors.some(b => b > 0) ? behaviorAve.toFixed(2) : (behaviorAveCell
                    .textContent === '-' ? '-' : behaviorAveCell.textContent);

                // AWARENESS: Average of 3 items
                const awareness_arr = [
                    getValue('.awareness-1'),
                    getValue('.awareness-2'),
                    getValue('.awareness-3')
                ];
                const awarenessAve = awareness_arr.some(a => a > 0) ? awareness_arr.reduce((a, b) => a + b) /
                    awareness_arr.length : 0;
                const awarenessAveCell = row.querySelector('.awareness-ave');
                awarenessAveCell.textContent = awareness_arr.some(a => a > 0) ? awarenessAve.toFixed(2) : (
                    awarenessAveCell.textContent === '-' ? '-' : awarenessAveCell.textContent);

                // ATTITUDE = (Behavior 50% + Awareness 50%)
                const attitudeHasInput = behaviors.some(b => b > 0) || awareness_arr.some(a => a > 0);
                const attitude = attitudeHasInput ? (behaviorAve * 0.50) + (awarenessAve * 0.50) : 0;
                const attitudeAveCell = row.querySelector('.attitude-ave');
                attitudeAveCell.textContent = attitudeHasInput ? attitude.toFixed(2) : (attitudeAveCell
                    .textContent === '-' ? '-' : attitudeAveCell.textContent);

                // FINAL GRADE = (K × 40% + S × 50% + A × 10%)
                const finalGrade = hasInput ? (knowledge * 0.40) + (skills * 0.50) + (attitude * 0.10) : 0;

                const kDisplay = row.querySelector('.k-display');
                const sDisplay = row.querySelector('.s-display');
                const aDisplay = row.querySelector('.a-display');
                const finalGradeCell = row.querySelector('.final-grade');

                kDisplay.textContent = (examPr > 0 || examMd > 0 || quizzes.some(q => q > 0)) ? knowledge.toFixed(
                    2) : (kDisplay.textContent === '-' ? '-' : kDisplay.textContent);
                sDisplay.textContent = skillsHasInput ? skills.toFixed(2) : (sDisplay.textContent === '-' ? '-' :
                    sDisplay.textContent);
                aDisplay.textContent = attitudeHasInput ? attitude.toFixed(2) : (aDisplay.textContent === '-' ?
                    '-' : aDisplay.textContent);
                finalGradeCell.textContent = hasInput ? finalGrade.toFixed(2) : (finalGradeCell.textContent ===
                    '-' ? '-' : finalGradeCell.textContent);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/grade_entry.blade.php ENDPATH**/ ?>