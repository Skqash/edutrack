

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
        .grade-input {
            background-color: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .grade-input:focus {
            background-color: #ffffff !important;
            color: #212529 !important;
            border-color: #0066cc !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25) !important;
        }

        .grade-input::placeholder {
            color: #6c757d;
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2><i class="fas fa-pen-fancy me-2"></i>EduTrack Grade Entry Form</h2>
                        <p class="text-muted mb-0"><strong>Class:</strong> <?php echo e($class->name); ?> | <strong>Term:</strong>
                            <?php echo e(ucfirst($term)); ?></p>
                    </div>
                    <a href="<?php echo e(route('teacher.grades')); ?>" class="btn fw-bold"
                        style="background-color: #0066cc; color: white; border: none;">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Grading Information Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                        <h6 class="mb-0" style="color: #1a1a1a;"><i class="fas fa-info-circle me-2"></i>EduTrack Grading
                            System - Philippines</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-graduation-cap me-2" style="color: #0066cc;"></i>Knowledge
                                    (40%)</strong>
                                <div class="small text-muted mt-1">
                                    Quizzes 40% + Exams 60%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-rocket me-2" style="color: #00a86b;"></i>Skills (50%)</strong>
                                <div class="small text-muted mt-1">
                                    Output 40%, Class Part 30%, Activities 15%, Assignments 15%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-heart me-2" style="color: #ff8c00;"></i>Attitude (10%)</strong>
                                <div class="small text-muted mt-1">
                                    Behavior 50% + Awareness 50%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grading Form -->
        <form method="POST" action="<?php echo e(route('teacher.grades.store', $class->id)); ?>" id="gradingForm">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="term" value="<?php echo e($term); ?>">

            <!-- Students Grid -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th
                                style="background-color: #ffffff; border-left: 4px solid #0066cc; color: #1a1a1a; font-weight: 600; width: 8%;">
                                <i class="fas fa-id-card me-1 text-primary"></i>Student ID
                            </th>
                            <th
                                style="background-color: #ffffff; border-left: 2px solid #0066cc; color: #1a1a1a; font-weight: 600; width: 12%;">
                                <i class="fas fa-user me-1 text-primary"></i>Name
                            </th>

                            <!-- KNOWLEDGE Section -->
                            <th colspan="7"
                                style="background-color: #f8f9fa; color: #0066cc; text-align: center; font-weight: 600; border-top: 2px solid #0066cc;">
                                <small>KNOWLEDGE (40%)</small>
                            </th>

                            <!-- SKILLS Section - Updated for 3 entries -->
                            <th colspan="12"
                                style="background-color: #f8f9fa; color: #00a86b; text-align: center; font-weight: 600; border-top: 2px solid #00a86b;">
                                <small>SKILLS (50%) - 3 Entries per Component</small>
                            </th>

                            <!-- ATTITUDE Section - Updated for 3 entries -->
                            <th colspan="6"
                                style="background-color: #f8f9fa; color: #ff8c00; text-align: center; font-weight: 600; border-top: 2px solid #ff8c00;">
                                <small>ATTITUDE (10%) - 3 Entries per Component</small>
                            </th>

                            <!-- FINAL Grade -->
                            <th
                                style="background-color: #ffffff; color: #1a1a1a; font-weight: 600; border-left: 4px solid #0066cc;">
                                Final Grade</th>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>

                            <!-- Knowledge Sub-headers -->
                            <th><small>Q1</small></th>
                            <th><small>Q2</small></th>
                            <th><small>Q3</small></th>
                            <th><small>Q4</small></th>
                            <th><small>Q5</small></th>
                            <th><small>PR Exam</small></th>
                            <th><small>MD Exam</small></th>

                            <!-- Skills Sub-headers (3 entries each) -->
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Output (MT)</small></th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Class Part (MT)</small>
                            </th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Activities (MT)</small>
                            </th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Assignments (MT)</small>
                            </th>

                            <!-- Attitude Sub-headers (3 entries each) -->
                            <th colspan="3" style="text-align: center; color: #ff8c00;"><small>Behavior (MT)</small></th>
                            <th colspan="3" style="text-align: center; color: #ff8c00;"><small>Awareness (MT)</small>
                            </th>

                            <!-- Final Grade -->
                            <th><small>Grade</small></th>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="7"></th>

                            <!-- Entry numbers for Skills -->
                            <th><small>O1</small></th>
                            <th><small>O2</small></th>
                            <th><small>O3</small></th>
                            <th><small>CP1</small></th>
                            <th><small>CP2</small></th>
                            <th><small>CP3</small></th>
                            <th><small>Act1</small></th>
                            <th><small>Act2</small></th>
                            <th><small>Act3</small></th>
                            <th><small>Asg1</small></th>
                            <th><small>Asg2</small></th>
                            <th><small>Asg3</small></th>

                            <!-- Entry numbers for Attitude -->
                            <th><small>B1</small></th>
                            <th><small>B2</small></th>
                            <th><small>B3</small></th>
                            <th><small>A1</small></th>
                            <th><small>A2</small></th>
                            <th><small>A3</small></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-2">
                                    <span class="badge bg-info text-white fw-bold"
                                        style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                        <?php echo e($student->student_id ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="ps-2">
                                    <small><strong><?php echo e($student->user->name); ?></strong></small>
                                </td>

                                <!-- KNOWLEDGE INPUTS -->
                                <?php $__currentLoopData = ['q1', 'q2', 'q3', 'q4', 'q5']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $quizNum = substr($quiz, 1); ?>
                                    <td>
                                        <input type="number" class="form-control form-control-sm quiz-input grade-field"
                                            data-field="<?php echo e($quiz); ?>" data-student-id="<?php echo e($student->id); ?>"
                                            data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                            name="grades[<?php echo e($student->id); ?>][<?php echo e($quiz); ?>]" min="0"
                                            max="<?php echo e($range ? $range->{'quiz_' . $quizNum . '_max'} ?? 100 : 100); ?>"
                                            step="0.5" data-student="<?php echo e($student->id); ?>"
                                            value="<?php echo e($grades->where('student_id', $student->id)->first()?->$quiz ?? ''); ?>"
                                            placeholder="0-<?php echo e($range ? $range->{'quiz_' . $quizNum . '_max'} ?? 100 : 100); ?>">
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <!-- EXAM INPUTS -->
                                <td>
                                    <input type="number" class="form-control form-control-sm exam-input grade-field"
                                        data-field="midterm_exam" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][midterm_exam]" min="0"
                                        max="<?php echo e($range ? $range->midterm_exam_max ?? 100 : 100); ?>" step="0.5"
                                        data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->midterm_exam ?? ''); ?>"
                                        placeholder="0-<?php echo e($range ? $range->midterm_exam_max ?? 100 : 100); ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm exam-input grade-field"
                                        data-field="final_exam" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][final_exam]" min="0"
                                        max="<?php echo e($range ? $range->final_exam_max ?? 100 : 100); ?>" step="0.5"
                                        data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->final_exam ?? ''); ?>"
                                        placeholder="0-<?php echo e($range ? $range->final_exam_max ?? 100 : 100); ?>">
                                </td>

                                <!-- SKILLS INPUTS - 3 entries each -->
                                <!-- Output (O1, O2, O3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="output_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][output_1]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->output_1 ?? ''); ?>"
                                        placeholder="O1" title="Output Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="output_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][output_2]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->output_2 ?? ''); ?>"
                                        placeholder="O2" title="Output Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="output_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][output_3]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->output_3 ?? ''); ?>"
                                        placeholder="O3" title="Output Entry 3">
                                </td>

                                <!-- Class Participation (CP1, CP2, CP3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="class_participation_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][class_participation_1]" min="0"
                                        max="100" step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->class_participation_1 ?? ''); ?>"
                                        placeholder="CP1" title="Class Participation Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="class_participation_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][class_participation_2]" min="0"
                                        max="100" step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->class_participation_2 ?? ''); ?>"
                                        placeholder="CP2" title="Class Participation Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="class_participation_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][class_participation_3]" min="0"
                                        max="100" step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->class_participation_3 ?? ''); ?>"
                                        placeholder="CP3" title="Class Participation Entry 3">
                                </td>

                                <!-- Activities (Act1, Act2, Act3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="activities_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][activities_1]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->activities_1 ?? ''); ?>"
                                        placeholder="Act1" title="Activities Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="activities_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][activities_2]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->activities_2 ?? ''); ?>"
                                        placeholder="Act2" title="Activities Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="activities_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][activities_3]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->activities_3 ?? ''); ?>"
                                        placeholder="Act3" title="Activities Entry 3">
                                </td>

                                <!-- Assignments (Asg1, Asg2, Asg3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="assignments_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][assignments_1]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->assignments_1 ?? ''); ?>"
                                        placeholder="Asg1" title="Assignments Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="assignments_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][assignments_2]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->assignments_2 ?? ''); ?>"
                                        placeholder="Asg2" title="Assignments Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input grade-field"
                                        data-field="assignments_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][assignments_3]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->assignments_3 ?? ''); ?>"
                                        placeholder="Asg3" title="Assignments Entry 3">
                                </td>

                                <!-- ATTITUDE INPUTS - 3 entries each -->
                                <!-- Behavior (B1, B2, B3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="behavior_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][behavior_1]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->behavior_1 ?? ''); ?>"
                                        placeholder="B1" title="Behavior Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="behavior_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][behavior_2]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->behavior_2 ?? ''); ?>"
                                        placeholder="B2" title="Behavior Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="behavior_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][behavior_3]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->behavior_3 ?? ''); ?>"
                                        placeholder="B3" title="Behavior Entry 3">
                                </td>

                                <!-- Awareness (A1, A2, A3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="awareness_1" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][awareness_1]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->awareness_1 ?? ''); ?>"
                                        placeholder="A1" title="Awareness Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="awareness_2" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][awareness_2]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->awareness_2 ?? ''); ?>"
                                        placeholder="A2" title="Awareness Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input grade-field"
                                        data-field="awareness_3" data-student-id="<?php echo e($student->id); ?>"
                                        data-class-id="<?php echo e($class->id); ?>" data-term="<?php echo e($term); ?>"
                                        name="grades[<?php echo e($student->id); ?>][awareness_3]" min="0" max="100"
                                        step="0.5" data-student="<?php echo e($student->id); ?>"
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->awareness_3 ?? ''); ?>"
                                        placeholder="A3" title="Awareness Entry 3">
                                </td>

                                <!-- FINAL GRADE DISPLAY -->
                                <td>
                                    <input type="text" class="form-control form-control-sm final-grade"
                                        name="grades[<?php echo e($student->id); ?>][final_grade]" readonly
                                        value="<?php echo e($grades->where('student_id', $student->id)->first()?->final_grade ?? ''); ?>"
                                        style="background-color: #f0f4ff; text-align: center; font-weight: bold;">
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Remarks Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                            <h6 class="mb-0" style="color: #1a1a1a;">Additional Notes</h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="remarks" rows="3"
                                placeholder="Enter any remarks about this grading period..." style="border: 1px solid #ddd; border-radius: 5px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?php echo e(route('teacher.grades')); ?>" class="btn fw-bold"
                            style="background-color: #e9ecef; color: #1a1a1a; border: none;">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn fw-bold"
                            style="background-color: #00a86b; color: white; border: none;">
                            <i class="fas fa-save me-2"></i>Save Grades
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .table-bordered th {
            padding: 8px 4px !important;
            font-size: 11px;
            text-align: center;
            font-weight: 600;
        }

        .table-bordered td {
            padding: 4px 2px !important;
        }

        .form-control-sm {
            font-size: 12px;
            height: 32px;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-control-sm:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
        }
    </style>

    <script>
        let autoSaveTimeout;
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            '<?php echo e(csrf_token()); ?>';

        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listeners to grade fields
            const gradeFields = document.querySelectorAll('.grade-field');

            gradeFields.forEach(field => {
                field.addEventListener('blur', function(e) {
                    autoSaveGrade(this);
                });
                field.addEventListener('change', function(e) {
                    autoSaveGrade(this);
                });
                field.addEventListener('input', function(e) {
                    calculateFinalGrade(this);
                });
            });
            
            // Prevent form from clearing saved grades when Submit button is clicked
            const gradingForm = document.getElementById('gradingForm');
            if (gradingForm) {
                gradingForm.addEventListener('submit', function(e) {
                    console.log('Form submitted - grades already auto-saved to database');
                    // Form can still submit for additional remarks or final submission
                });
            }
        });

        /**
         * Auto-save grade field to database via AJAX
         */
        function autoSaveGrade(element) {
            if (!element || !element.dataset) return;

            const field = element.dataset.field;
            const studentId = element.dataset.studentId;
            const classId = element.dataset.classId;
            const term = element.dataset.term;
            const value = element.value || null;

            if (!field || !studentId || !classId || !term) {
                console.warn('Missing required data attributes:', {
                    field,
                    studentId,
                    classId,
                    term
                });
                return;
            }

            // Add saving indicator
            element.style.borderColor = '#ffc107';
            element.style.backgroundColor = '#fffacd';

            // Store the value being saved
            const savedValue = element.value;
            console.log('Saving value:', savedValue, 'for field:', field);

            // Send AJAX request to the correct route
            const saveUrl = '<?php echo e(route('teacher.grades.save-field')); ?>';
            console.log('Saving grade to:', saveUrl);

            fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        student_id: parseInt(studentId),
                        class_id: parseInt(classId),
                        term: term,
                        field: field,
                        value: value ? parseFloat(value) : null
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Save successful:', data);
                    if (data.success) {
                        // Success - change border to green
                        element.style.borderColor = '#28a745';
                        element.style.backgroundColor = '#f0fff4';

                        // CRITICAL: Ensure the input value is retained
                        element.value = savedValue;
                        console.log('✅ Value retained in input:', element.value);

                        // Update final grade if returned
                        if (data.final_grade) {
                            const row = element.closest('tr');
                            if (row) {
                                const finalGradeInput = row.querySelector('.final-grade');
                                if (finalGradeInput) {
                                    finalGradeInput.value = parseFloat(data.final_grade).toFixed(2);
                                    console.log('✅ Final grade updated:', finalGradeInput.value);
                                }
                            }
                        }

                        // Reset style after 1 second
                        setTimeout(() => {
                            element.style.borderColor = '';
                            element.style.backgroundColor = '';
                            // Verify value is still there after reset
                            console.log('✅ After reset, input value:', element.value);
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Save failed');
                    }
                })
                .catch(error => {
                    console.error('❌ Error saving grade:', error);
                    // Restore the value in case of error
                    element.value = savedValue;
                    console.log('❌ After error, input value restored to:', element.value);

                    // Error - change border to red
                    element.style.borderColor = '#dc3545';
                    element.style.backgroundColor = '#fff5f5';

                    setTimeout(() => {
                        element.style.borderColor = '';
                        element.style.backgroundColor = '';
                    }, 2000);
                });
        }

        /**
         * Calculate final grade locally (for display only)
         */
        function calculateFinalGrade(element) {
            // Ensure element is valid
            if (!element || typeof element !== 'object') return;

            // Find the parent row
            let row = null;
            if (element.closest && typeof element.closest === 'function') {
                row = element.closest('tr');
            } else if (element.parentElement) {
                // Fallback for older browsers or edge cases
                row = element.parentElement;
                while (row && row.tagName !== 'TR') {
                    row = row.parentElement;
                }
            }

            if (!row) return;

            // Get all input values
            const q1 = parseFloat(row.querySelector('input[name*="[q1]"]')?.value) || 0;
            const q2 = parseFloat(row.querySelector('input[name*="[q2]"]')?.value) || 0;
            const q3 = parseFloat(row.querySelector('input[name*="[q3]"]')?.value) || 0;
            const q4 = parseFloat(row.querySelector('input[name*="[q4]"]')?.value) || 0;
            const q5 = parseFloat(row.querySelector('input[name*="[q5]"]')?.value) || 0;
            const midterm = parseFloat(row.querySelector('input[name*="[midterm_exam]"]')?.value) || 0;
            const final = parseFloat(row.querySelector('input[name*="[final_exam]"]')?.value) || 0;

            // Get individual Skills entries (O1, O2, O3, CP1-CP3, Act1-Act3, Asg1-Asg3)
            const o1 = parseFloat(row.querySelector('input[data-field="output_1"]')?.value) || 0;
            const o2 = parseFloat(row.querySelector('input[data-field="output_2"]')?.value) || 0;
            const o3 = parseFloat(row.querySelector('input[data-field="output_3"]')?.value) || 0;

            const cp1 = parseFloat(row.querySelector('input[data-field="class_participation_1"]')?.value) || 0;
            const cp2 = parseFloat(row.querySelector('input[data-field="class_participation_2"]')?.value) || 0;
            const cp3 = parseFloat(row.querySelector('input[data-field="class_participation_3"]')?.value) || 0;

            const act1 = parseFloat(row.querySelector('input[data-field="activities_1"]')?.value) || 0;
            const act2 = parseFloat(row.querySelector('input[data-field="activities_2"]')?.value) || 0;
            const act3 = parseFloat(row.querySelector('input[data-field="activities_3"]')?.value) || 0;

            const asg1 = parseFloat(row.querySelector('input[data-field="assignments_1"]')?.value) || 0;
            const asg2 = parseFloat(row.querySelector('input[data-field="assignments_2"]')?.value) || 0;
            const asg3 = parseFloat(row.querySelector('input[data-field="assignments_3"]')?.value) || 0;

            // Get individual Attitude entries (B1-B3, A1-A3)
            const b1 = parseFloat(row.querySelector('input[data-field="behavior_1"]')?.value) || 0;
            const b2 = parseFloat(row.querySelector('input[data-field="behavior_2"]')?.value) || 0;
            const b3 = parseFloat(row.querySelector('input[data-field="behavior_3"]')?.value) || 0;

            const a1 = parseFloat(row.querySelector('input[data-field="awareness_1"]')?.value) || 0;
            const a2 = parseFloat(row.querySelector('input[data-field="awareness_2"]')?.value) || 0;
            const a3 = parseFloat(row.querySelector('input[data-field="awareness_3"]')?.value) || 0;

            // Calculate Knowledge (40%)
            const quizzes = [q1, q2, q3, q4, q5].filter(v => v > 0);
            const quizAvg = quizzes.length > 0 ? quizzes.reduce((a, b) => a + b) / quizzes.length : 0;
            const examAvg = (midterm + final) / 2;
            const knowledge = (quizAvg * 0.40) + (examAvg * 0.60);

            // Calculate Skills (50%) - average each component then apply weights
            const outputAvg = (o1 + o2 + o3) / 3;
            const cpAvg = (cp1 + cp2 + cp3) / 3;
            const actAvg = (act1 + act2 + act3) / 3;
            const asgAvg = (asg1 + asg2 + asg3) / 3;
            const skills = (outputAvg * 0.40) + (cpAvg * 0.30) + (actAvg * 0.15) + (asgAvg * 0.15);

            // Calculate Attitude (10%) - average each component then apply weights
            const behaviorAvg = (b1 + b2 + b3) / 3;
            const awarenessAvg = (a1 + a2 + a3) / 3;
            const attitude = (behaviorAvg * 0.50) + (awarenessAvg * 0.50);

            // Calculate Final Grade (K=40%, S=50%, A=10%)
            const finalGrade = (knowledge * 0.40) + (skills * 0.50) + (attitude * 0.10);

            // Display final grade
            const finalGradeInput = row.querySelector('.final-grade');
            if (finalGradeInput) {
                finalGradeInput.value = isNaN(finalGrade) || finalGrade === 0 ? '' : finalGrade.toFixed(2);
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/entry_ched.blade.php ENDPATH**/ ?>