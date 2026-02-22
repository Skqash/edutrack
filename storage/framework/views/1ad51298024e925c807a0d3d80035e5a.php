<?php $__env->startSection('content'); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h3 fw-bold mb-0"><i class="fas fa-pen-fancy me-2"></i>CHED Grade Management</h1>
                <small class="text-muted">Enter and manage student grades using CHED Philippines system</small>
            </div>
            <a href="<?php echo e(route('teacher.students.add')); ?>" class="btn btn-success">
                <i class="fas fa-user-plus me-2"></i>Add Students
            </a>
        </div>
    </div>
</div>

<!-- Success Message -->
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i> <?php echo e(session('info')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Classes Cards with Term Selection -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <h5 class="mb-0">
                    <i class="fas fa-door-open me-2"></i> Select Class & Term to Enter Grades
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if($classes->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Class Name</th>
                                    <th class="d-none d-md-table-cell">Level</th>
                                    <th class="d-none d-md-table-cell">Students</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-3">
                                            <strong><?php echo e($class->name); ?></strong>
                                            <?php if($class->section): ?>
                                                <br><small class="text-muted">Section <?php echo e($class->section); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <?php echo e($class->level ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <strong><?php echo e($class->students()->count()); ?></strong> students
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('teacher.grades.entry', ['classId' => $class->id, 'term' => 'midterm'])); ?>" 
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i> Midterm
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('teacher.grades.entry', ['classId' => $class->id, 'term' => 'final'])); ?>" 
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit me-1"></i> Final
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <p>No classes assigned yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Grades Posted -->
<?php if($recentGrades->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i> Recent Grades Posted
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Student</th>
                                    <th class="d-none d-md-table-cell">Subject</th>
                                    <th class="text-center d-none d-lg-table-cell">
                                        <small>Knowledge<br><span class="text-muted">30%</span></small>
                                    </th>
                                    <th class="text-center d-none d-lg-table-cell">
                                        <small>Skills<br><span class="text-muted">40%</span></small>
                                    </th>
                                    <th class="text-center d-none d-lg-table-cell">
                                        <small>Attitude<br><span class="text-muted">30%</span></small>
                                    </th>
                                    <th class="text-center">Final Grade</th>
                                    <th class="d-none d-md-table-cell">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-3">
                                            <strong><?php echo e($grade->student->user->name ?? $grade->student->name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($grade->student->admission_number ?? 'N/A'); ?></small>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <?php echo e($grade->subject->subject_name ?? 'N/A'); ?>

                                        </td>
                                        <td class="text-center d-none d-lg-table-cell">
                                            <span class="grade-badge" style="background-color: #e7f3ff; color: #004085; padding: 6px 10px; border-radius: 5px;">
                                                <?php echo e($grade->knowledge_score); ?>

                                            </span>
                                        </td>
                                        <td class="text-center d-none d-lg-table-cell">
                                            <span class="grade-badge" style="background-color: #e7f0ff; color: #003366; padding: 6px 10px; border-radius: 5px;">
                                                <?php echo e($grade->skills_score); ?>

                                            </span>
                                        </td>
                                        <td class="text-center d-none d-lg-table-cell">
                                            <span class="grade-badge" style="background-color: #fff4e7; color: #663300; padding: 6px 10px; border-radius: 5px;">
                                                <?php echo e($grade->attitude_score); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="grade-badge" style="padding: 6px 12px; border-radius: 5px; font-weight: bold;
                                                <?php if($grade->final_grade >= 90): ?>
                                                    background-color: #d4edda; color: #155724;
                                                <?php elseif($grade->final_grade >= 80): ?>
                                                    background-color: #cfe2ff; color: #084298;
                                                <?php elseif($grade->final_grade >= 70): ?>
                                                    background-color: #fff3cd; color: #664d03;
                                                <?php elseif($grade->final_grade >= 60): ?>
                                                    background-color: #f8d7da; color: #842029;
                                                <?php else: ?>
                                                    background-color: #f8d7da; color: #842029;
                                                <?php endif; ?>
                                            ">
                                                <?php echo e($grade->final_grade); ?> (<?php echo e(\App\Models\Grade::getLetterGrade($grade->final_grade)); ?>)
                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <small class="text-muted"><?php echo e($grade->created_at->diffForHumans()); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/index.blade.php ENDPATH**/ ?>