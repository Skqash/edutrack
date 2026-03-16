<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">⚙️ Grade Configuration</h4>
                <small class="text-muted">Configure assessment limits for <?php echo e($class->name ?? $class->class_name); ?></small>
            </div>
            <div>
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-sm btn-outline-secondary">← Back</a>
            </div>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <strong>Errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <strong>✅ Success!</strong> Configuration saved successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('teacher.assessment.configure.store', $class->id)); ?>" class="card-body">
            <?php echo csrf_field(); ?>

            <style>
                .config-section {
                    background-color: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #0c5aa0;
                    margin-bottom: 25px;
                }
                .config-section h5 {
                    color: #0c5aa0;
                    margin-bottom: 20px;
                    font-weight: 600;
                }
            </style>

            <!-- KNOWLEDGE ASSESSMENT (40% of Term Grade) -->
            <div class="config-section">
                <h5>📚 KNOWLEDGE ASSESSMENT (40% of Term Grade)</h5>

                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">📖 Exams</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Preliminary Exam (PR) - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="exam_pr_max" 
                                    value="<?php echo e(old('exam_pr_max', $range->exam_pr_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Midterm Exam (MD) - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="exam_md_max" 
                                    value="<?php echo e(old('exam_md_max', $range->exam_md_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">✏️ Quizzes (Q1-Q5)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Number of Quizzes</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="numQuizzes" name="num_quizzes" 
                                    value="<?php echo e(old('num_quizzes', $range->num_quizzes ?? 5)); ?>" 
                                    min="1" max="10" required>
                                <span class="input-group-text">quizzes</span>
                            </div>
                            <small class="form-text text-muted">Fixed: Always 5 (Q1, Q2, Q3, Q4, Q5)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Quiz - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="quiz_max" 
                                    value="<?php echo e(old('quiz_max', $range->quiz_max ?? 25)); ?>" 
                                    min="5" max="100" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 25 (applies to all quizzes)</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Knowledge Weight Breakdown:</strong> Exams (60%) + Quizzes (40%)
                </div>
            </div>

            <!-- SKILLS ASSESSMENT (50% of Term Grade) -->
            <div class="config-section">
                <h5>🎯 SKILLS ASSESSMENT (50% of Term Grade)</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">🏆 Output (O1, O2, O3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Output Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="output_max" 
                                    value="<?php echo e(old('output_max', $range->output_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (40% of Skills)</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-success mb-3">👥 Class Participation (C1, C2, C3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Class Part Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="classpart_max" 
                                    value="<?php echo e(old('classpart_max', $range->classpart_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (30% of Skills)</small>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">⚡ Activities (A1, A2, A3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Activity Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="activity_max" 
                                    value="<?php echo e(old('activity_max', $range->activity_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (15% of Skills)</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-success mb-3">📋 Assignments (As1, As2, As3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Assignment Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="assignment_max" 
                                    value="<?php echo e(old('assignment_max', $range->assignment_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (15% of Skills)</small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Skills Weight Breakdown:</strong> Output (40%) + Class Part (30%) + Activities (15%) + Assignments (15%)
                </div>
            </div>

            <!-- ATTITUDE ASSESSMENT (10% of Term Grade) -->
            <div class="config-section">
                <h5>😊 ATTITUDE ASSESSMENT (10% of Term Grade)</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-warning mb-3">✨ Behavior (B1, B2, B3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Behavior Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="behavior_max" 
                                    value="<?php echo e(old('behavior_max', $range->behavior_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (50% of Attitude)</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-warning mb-3">👁️ Awareness (Aw1, Aw2, Aw3)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Each Awareness Item - Max Score</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="awareness_max" 
                                    value="<?php echo e(old('awareness_max', $range->awareness_max ?? 100)); ?>" 
                                    min="10" max="500" required>
                                <span class="input-group-text">points</span>
                            </div>
                            <small class="form-text text-muted">Default: 100 per item (50% of Attitude)</small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Attitude Weight Breakdown:</strong> Behavior (50%) + Awareness (50%)
                </div>
            </div>

            <!-- NOTES -->
            <div class="config-section">
                <h5>📝 Additional Notes</h5>
                <textarea class="form-control" name="notes" rows="4" 
                    placeholder="Add any special notes about assessment ranges for this class..."><?php echo e(old('notes', $range->notes ?? '')); ?></textarea>
            </div>

            <!-- SUMMARY TABLE -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info bg-opacity-10">
                    <h6 class="mb-0 text-info"><i class="fas fa-table me-2"></i>Configuration Summary</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Component</th>
                                    <th>Items</th>
                                    <th>Per Item Max</th>
                                    <th>Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="fw-bold bg-light">📚 KNOWLEDGE (40%)</td>
                                </tr>
                                <tr>
                                    <td>Exams (PR + MD)</td>
                                    <td>2</td>
                                    <td>100 points each</td>
                                    <td>60% of Knowledge</td>
                                </tr>
                                <tr>
                                    <td>Quizzes (Q1-Q5)</td>
                                    <td>5</td>
                                    <td>25 points each</td>
                                    <td>40% of Knowledge</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="fw-bold bg-light">🎯 SKILLS (50%)</td>
                                </tr>
                                <tr>
                                    <td>Output (O1-O3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>40% of Skills</td>
                                </tr>
                                <tr>
                                    <td>Class Participation (C1-C3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>30% of Skills</td>
                                </tr>
                                <tr>
                                    <td>Activities (A1-A3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>15% of Skills</td>
                                </tr>
                                <tr>
                                    <td>Assignments (As1-As3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>15% of Skills</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="fw-bold bg-light">😊 ATTITUDE (10%)</td>
                                </tr>
                                <tr>
                                    <td>Behavior (B1-B3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>50% of Attitude</td>
                                </tr>
                                <tr>
                                    <td>Awareness (Aw1-Aw3)</td>
                                    <td>3</td>
                                    <td>100 points each</td>
                                    <td>50% of Attitude</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="fw-bold bg-light">📌 FINAL GRADE</td>
                                </tr>
                                <tr>
                                    <td>Final Grade = (K × 40%) + (S × 50%) + (A × 10%)</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex gap-2 justify-content-between mb-4">
                <a href="<?php echo e(route('teacher.grades')); ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Save Configuration
                </button>
            </div>

        </form>
    </div>

    <!-- INFO CARD -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>How It Works</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li><strong>All scores are normalized to 0-100 scale</strong> - Your configured maximums are automatically converted</li>
                <li><strong>Component weights are fixed</strong> - Knowledge 40%, Skills 50%, Attitude 10%</li>
                <li><strong>Calculations are automatic</strong> - Grade entry form automatically computes all averages</li>
                <li><strong>Changes apply to this class only</strong> - Each class can have different configuration</li>
                <li><strong>3 items per component</strong> - Each skill/attitude component has 3 assessment entry points per term</li>
            </ul>
        </div>
    </div>
</div>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\assessment\configure.blade.php ENDPATH**/ ?>