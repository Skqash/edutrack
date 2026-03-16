<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="fas fa-eye"></i>
            </span>
            Teacher Assignment Details
        </h3>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.teacher-assignments.edit', $teacherAssignment)); ?>" class="btn btn-gradient-warning">
                <i class="fas fa-edit"></i> Edit Assignment
            </a>
            <a href="<?php echo e(route('admin.teacher-assignments.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Assignments
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Assignment Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Assignment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Teacher</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center text-white me-3">
                                        <?php echo e(strtoupper(substr($teacherAssignment->teacher->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-medium fs-5"><?php echo e($teacherAssignment->teacher->name); ?></div>
                                        <small class="text-muted"><?php echo e($teacherAssignment->teacher->email); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    <?php if($teacherAssignment->status == 'active'): ?>
                                        <span class="badge bg-success fs-6">Active</span>
                                    <?php elseif($teacherAssignment->status == 'inactive'): ?>
                                        <span class="badge bg-warning fs-6">Inactive</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary fs-6">Completed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Academic Year</label>
                                <div class="fw-medium"><?php echo e($teacherAssignment->academic_year); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Semester</label>
                                <div class="fw-medium"><?php echo e($teacherAssignment->semester); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted">Department</label>
                                <div class="fw-medium"><?php echo e($teacherAssignment->department ?: 'N/A'); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Assignment Details</label>
                                <div class="assignment-details">
                                    <?php if($teacherAssignment->class): ?>
                                        <div class="d-inline-block me-3 mb-2">
                                            <span class="badge bg-info fs-6">
                                                <i class="fas fa-door-open"></i> <?php echo e($teacherAssignment->class->class_name); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($teacherAssignment->subject): ?>
                                        <div class="d-inline-block me-3 mb-2">
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-book"></i> <?php echo e($teacherAssignment->subject->subject_code); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($teacherAssignment->course): ?>
                                        <div class="d-inline-block me-3 mb-2">
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-graduation-cap"></i> <?php echo e($teacherAssignment->course->program_code); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($teacherAssignment->department && !$teacherAssignment->course): ?>
                                        <div class="d-inline-block me-3 mb-2">
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-building"></i> <?php echo e($teacherAssignment->department); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($teacherAssignment->notes): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Notes</label>
                                    <div class="alert alert-light"><?php echo e($teacherAssignment->notes); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Assigned Date</label>
                                <div class="fw-medium"><?php echo e($teacherAssignment->assigned_at->format('M d, Y h:i A')); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <div class="fw-medium"><?php echo e($teacherAssignment->updated_at->format('M d, Y h:i A')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="display-4 text-primary"><?php echo e($teacherAssignment->student_count); ?></div>
                        <div class="text-muted">Assigned Students</div>
                    </div>

                    <?php if($teacherAssignment->class): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Class Capacity:</span>
                            <span class="fw-medium"><?php echo e($teacherAssignment->class->capacity); ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-gradient-primary" style="width: <?php echo e($teacherAssignment->class->utilizationPercentage); ?>%"></div>
                        </div>
                        <div class="text-center small text-muted mb-3">
                            <?php echo e($teacherAssignment->class->utilizationPercentage); ?>% Utilization
                        </div>
                    <?php endif; ?>

                    <?php if($teacherAssignment->subject): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subject Credits:</span>
                            <span class="fw-medium"><?php echo e($teacherAssignment->subject->credit_hours); ?> units</span>
                        </div>
                    <?php endif; ?>

                    <?php if($teacherAssignment->course): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Course Department:</span>
                            <span class="fw-medium"><?php echo e($teacherAssignment->course->department); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Students -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users"></i> Assigned Students 
                        <span class="badge bg-gradient-primary ms-2"><?php echo e($teacherAssignment->students->count()); ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($teacherAssignment->students->isNotEmpty()): ?>
                        <div class="row">
                            <?php $__currentLoopData = $teacherAssignment->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-gradient-success rounded-circle d-flex align-items-center justify-content-center text-white me-3">
                                                    <?php echo e(strtoupper(substr($student->user->name, 0, 1))); ?>

                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium"><?php echo e($student->user->name); ?></div>
                                                    <small class="text-muted">
                                                        <?php echo e($student->student_id); ?> • Year <?php echo e($student->year); ?> • <?php echo e($student->section); ?>

                                                    </small>
                                                    <?php if($student->class): ?>
                                                        <div class="small text-info mt-1">
                                                            <i class="fas fa-door-open"></i> <?php echo e($student->class->class_name); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No students assigned to this teacher assignment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #e0a800);
        color: #212529;
        border: none;
    }

    .btn-gradient-warning:hover {
        background: linear-gradient(45deg, #e0a800, #d39e00);
        color: #212529;
    }

    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .assignment-details {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .assignment-details .badge {
        font-size: 0.875rem;
        padding: 8px 12px;
    }

    .avatar-lg {
        width: 64px;
        height: 64px;
        font-size: 1.5rem;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 300;
    }

    .progress {
        background-color: #e9ecef;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\teacher-assignments\show.blade.php ENDPATH**/ ?>