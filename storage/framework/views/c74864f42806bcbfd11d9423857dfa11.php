

<?php $__env->startSection('content'); ?>
    <style>
        /* Remove spinner buttons from number inputs - Cross-browser compatible */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
            display: none !important;
        }

        input[type="number"] {
            -moz-appearance: textfield !important;
        }

        /* Professional grade input styling */
        .grade-input,
        .quiz-input,
        .exam-input {
            background-color: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .grade-input:focus,
        .quiz-input:focus,
        .exam-input:focus {
            background-color: #ffffff !important;
            color: #212529 !important;
            border-color: #0066cc !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25) !important;
        }

        .grade-input::placeholder,
        .quiz-input::placeholder,
        .exam-input::placeholder {
            color: #6c757d;
        }
    </style>

    <div class="container-fluid my-4">
        <!-- Form Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; padding: 2rem; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fas fa-document-alt me-2"></i>Edutrack Grade Entry Form</h2>
                            <p class="mb-0"><?php echo e($class->class_name); ?> - <?php echo e($class->subject->name ?? 'N/A'); ?> | Term:
                                <strong><?php echo e(ucfirst($term)); ?></strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?php echo e(route('teacher.assessment.configure', $class->id)); ?>"
                                class="btn btn-light btn-sm me-2">
                                <i class="fas fa-sliders-h"></i> Configure
                            </a>
                            <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($errors instanceof \Illuminate\Support\MessageBag && $errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Errors:</strong>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('teacher.grades.store.enhanced', $class->id)); ?>" id="gradeForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="term" value="<?php echo e($term); ?>">

            <!-- Assessment Configuration Summary -->
            <?php if($range): ?>
                <div class="card mb-4 shadow-sm border-left-info">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i> Grading Configuration</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <small><strong>Knowledge (40%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Quizzes: <?php echo e($range->num_quizzes ?? 5); ?> ×
                                    <?php echo e((int) ($range->total_quiz_items / ($range->num_quizzes ?? 5))); ?> items
                                    <br>Exams: Prelim, Midterm, Final
                                </small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Skills (50%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Output • Class Participation
                                    <br>Activities • Assignments
                                </small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Attitude (10%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Behavior (50%)
                                    <br>Awareness (50%)
                                </small>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="<?php echo e(route('teacher.assessment.configure', $class->id)); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modify Configuration
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Grading Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-table me-2"></i>Student Grade Entry (<?php echo e($students->count()); ?> students)
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                            <!-- Header Row 1: Main Components -->
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; width: 6%;"><i
                                            class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                    <th rowspan="2" style="vertical-align: middle; width: 12%;"><i
                                            class="fas fa-user me-1 text-primary"></i>Name</th>
                                    <th colspan="<?php echo e(($range->num_quizzes ?? 5) + 3); ?>" class="text-center bg-primary"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <strong>KNOWLEDGE (40%)</strong>
                                    </th>
                                    <th colspan="5" class="text-center" style="background-color: #f8a623; color: white;">
                                        <strong>SKILLS (50%)</strong>
                                    </th>
                                    <th colspan="3" class="text-center" style="background-color: #17a2b8; color: white;">
                                        <strong>ATTITUDE (10%)</strong>
                                    </th>
                                    <th colspan="2" class="text-center text-white" style="background-color: #6c757d;">
                                        <strong>SCORE</strong>
                                    </th>
                                </tr>

                                <!-- Header Row 2: Sub-components -->
                                <tr style="background-color: #f8f9fa;">
                                    <!-- Knowledge Sub-Headers -->
                                    <?php for($q = 1; $q <= ($range->num_quizzes ?? 5); $q++): ?>
                                        <th class="text-center text-nowrap"
                                            style="background-color: #e3f2fd; font-weight: 600;">Q<?php echo e($q); ?></th>
                                    <?php endfor; ?>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e3f2fd; font-weight: 600;">Mid</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e3f2fd; font-weight: 600;">Final</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #b3e5fc; font-weight: 600; border-left: 2px solid #667eea;">
                                        K%</th>

                                    <!-- Skills Sub-Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Output</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">C.Part</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Activ</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Assign</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #ffe0b2; font-weight: 600; border-left: 2px solid #f8a623;">
                                        S%</th>

                                    <!-- Attitude Sub-Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e0f2f1; font-weight: 600;">Behav</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e0f2f1; font-weight: 600;">Aware</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #b2dfdb; font-weight: 600; border-left: 2px solid #17a2b8;">
                                        A%</th>

                                    <!-- Score Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e8e8e8; font-weight: 600; border-left: 2px solid #6c757d;">
                                        Final</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #d8d8d8; font-weight: 600;">Letter</th>
                                </tr>
                            </thead>

                            <!-- Data Rows -->
                            <tbody>
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $grade = $grades->where('student_id', $student->id)->first();
                                        $studentName = $student->user->name ?? ($student->name ?? 'Unknown');
                                    ?>
                                    <tr>
                                        <td style="font-weight: 600; vertical-align: middle; text-align: center;">
                                            <span class="badge bg-info text-white fw-bold"
                                                style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                <?php echo e($student->student_id ?? 'N/A'); ?>

                                            </span>
                                            <input type="hidden" name="grades[<?php echo e($student->id); ?>][student_id]"
                                                value="<?php echo e($student->id); ?>">
                                        </td>
                                        <td style="font-weight: 600; vertical-align: middle;">
                                            <?php echo e($studentName); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e($student->admission_number ?? 'N/A'); ?></small>
                                        </td>

                                        <!-- Knowledge Quizzes -->
                                        <?php for($q = 1; $q <= ($range->num_quizzes ?? 5); $q++): ?>
                                            <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                                <input type="number"
                                                    name="grades[<?php echo e($student->id); ?>][q<?php echo e($q); ?>]"
                                                    class="form-control form-control-sm text-center quiz-input"
                                                    value="<?php echo e(old("grades.$student->id.q$q", $grade?->{'q' . $q} ?? '')); ?>"
                                                    min="0" max="<?php echo e($range->{'quiz_' . $q . '_max'} ?? 100); ?>"
                                                    placeholder="0-<?php echo e($range->{'quiz_' . $q . '_max'} ?? 100); ?>"
                                                    style="font-size: 0.75rem; padding: 2px;">
                                            </td>
                                        <?php endfor; ?>

                                        <!-- Exams -->
                                        <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][midterm_exam]"
                                                class="form-control form-control-sm text-center exam-input"
                                                value="<?php echo e(old("grades.$student->id.midterm_exam", $grade?->midterm_exam ?? '')); ?>"
                                                min="0" max="<?php echo e($range->midterm_exam_max ?? 100); ?>"
                                                placeholder="0-<?php echo e($range->midterm_exam_max ?? 100); ?>"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][final_exam]"
                                                class="form-control form-control-sm text-center exam-input"
                                                value="<?php echo e(old("grades.$student->id.final_exam", $grade?->final_exam ?? '')); ?>"
                                                min="0" max="<?php echo e($range->final_exam_max ?? 100); ?>"
                                                placeholder="0-<?php echo e($range->final_exam_max ?? 100); ?>"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Knowledge Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #b3e5fc; font-weight: 600; border-left: 2px solid #667eea; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="<?php echo e($grade?->knowledge_score ?? '0'); ?>" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Skills Components -->
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][output_score]"
                                                class="form-control form-control-sm text-center grade-input"
                                                value="<?php echo e(old("grades.$student->id.output_score", $grade?->output_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number"
                                                name="grades[<?php echo e($student->id); ?>][class_participation_score]"
                                                class="form-control form-control-sm text-center grade-input"
                                                value="<?php echo e(old("grades.$student->id.class_participation_score", $grade?->class_participation_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][activities_score]"
                                                class="form-control form-control-sm text-center grade-input"
                                                value="<?php echo e(old("grades.$student->id.activities_score", $grade?->activities_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][assignments_score]"
                                                class="form-control form-control-sm text-center grade-input"
                                                value="<?php echo e(old("grades.$student->id.assignments_score", $grade?->assignments_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Skills Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #ffe0b2; font-weight: 600; border-left: 2px solid #f8a623; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="<?php echo e($grade?->skills_score ?? '0'); ?>" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Attitude Components -->
                                        <td class="text-center" style="background-color: #f0fffe; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][behavior_score]"
                                                class="form-control form-control-sm text-center"
                                                value="<?php echo e(old("grades.$student->id.behavior_score", $grade?->behavior_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #f0fffe; padding: 4px;">
                                            <input type="number" name="grades[<?php echo e($student->id); ?>][awareness_score]"
                                                class="form-control form-control-sm text-center"
                                                value="<?php echo e(old("grades.$student->id.awareness_score", $grade?->awareness_score ?? '')); ?>"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Attitude Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #b2dfdb; font-weight: 600; border-left: 2px solid #17a2b8; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="<?php echo e($grade?->attitude_score ?? '0'); ?>" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Final Grade (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #e8e8e8; font-weight: 600; border-left: 2px solid #6c757d; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="<?php echo e($grade?->final_grade ?? '0'); ?>" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Grade Point (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #d8d8d8; font-weight: 700; padding: 4px;">
                                            <?php
                                                $gradePoint =
                                                    \App\Models\Grade::getGradePoint($grade?->final_grade ?? 0) ?? '-';
                                                $bgColor =
                                                    $gradePoint === 1.0
                                                        ? '#90EE90'
                                                        : ($gradePoint === 1.25 || $gradePoint === 1.5
                                                            ? '#87CEEB'
                                                            : ($gradePoint === 1.75 || $gradePoint === 2.0
                                                                ? '#FFD700'
                                                                : ($gradePoint === 2.25 || $gradePoint === 2.5
                                                                    ? '#FFA07A'
                                                                    : ($gradePoint === 'INC'
                                                                        ? '#FFB6C6'
                                                                        : '#F0F0F0'))));
                                            ?>
                                            <div
                                                style="background-color: <?php echo e($bgColor); ?>; border-radius: 4px; padding: 2px; font-size: 0.85rem;">
                                                <?php echo e($gradePoint); ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                All scores are on a 0-100 scale. Calculated fields update automatically.
                            </small>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save All Grades
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Grade Scale Reference -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">Grading Scale Reference</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Letter Grade Scale:</strong>
                                <ul class="list-unstyled small">
                                    <li><span
                                            style="background-color: #90EE90; padding: 2px 6px; border-radius: 3px;">A</span>
                                        = 90-100 (Excellent)</li>
                                    <li><span
                                            style="background-color: #87CEEB; padding: 2px 6px; border-radius: 3px;">B</span>
                                        = 80-89 (Very Good)</li>
                                    <li><span
                                            style="background-color: #FFD700; padding: 2px 6px; border-radius: 3px;">C</span>
                                        = 70-79 (Good)</li>
                                    <li><span
                                            style="background-color: #FFA07A; padding: 2px 6px; border-radius: 3px;">D</span>
                                        = 60-69 (Fair)</li>
                                    <li><span
                                            style="background-color: #FFB6C6; padding: 2px 6px; border-radius: 3px;">F</span>
                                        = 0-59 (Fail)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>KSA Weighting:</strong>
                                <ul class="list-unstyled small">
                                    <li><strong style="color: #667eea;">Knowledge:</strong> 40% (Quizzes 40% + Exams 60%)
                                    </li>
                                    <li><strong style="color: #f8a623;">Skills:</strong> 50% (Output 40% + C.Part 30% +
                                        Activ 15% + Assign 15%)</li>
                                    <li><strong style="color: #17a2b8;">Attitude:</strong> 10% (Behavior 50% + Awareness
                                        50%)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-sm td {
            vertical-align: middle;
        }

        .table-sm input[type="number"] {
            border: 1px solid #ddd;
        }

        .table-sm input[type="number"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-sm {
            height: 28px !important;
            padding: 0.25rem 0.5rem !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/entry_updated.blade.php ENDPATH**/ ?>