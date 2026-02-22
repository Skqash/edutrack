<?php $__env->startSection('content'); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h3 fw-bold mb-0"><?php echo e($class->class_name); ?></h1>
                <small class="text-muted"><?php echo e($class->class_level); ?> · Section: <?php echo e($class->section ?? 'N/A'); ?></small>
            </div>
            <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Class Info Cards -->
<div class="row mb-4">
    <div class="col-12 col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Students</h6>
                        <h3 class="mb-0"><?php echo e($class->students->count()); ?></h3>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3 mb-3">
        <div class="card stat-card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Capacity</h6>
                        <h3 class="mb-0"><?php echo e($class->capacity); ?></h3>
                    </div>
                    <i class="fas fa-door-open fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3 mb-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Subject</h6>
                        <h5 class="mb-0"><?php echo e($class->subject->subject_name ?? 'N/A'); ?></h5>
                    </div>
                    <i class="fas fa-book fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3 mb-3">
        <div class="card stat-card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Status</h6>
                        <h5 class="mb-0">
                            <span class="badge bg-light text-dark">
                                <?php echo e($class->status === 'active' ? 'Active' : 'Inactive'); ?>

                            </span>
                        </h5>
                    </div>
                    <i class="fas fa-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i> Students in This Class
                </h5>
                <span class="badge bg-light text-primary"><?php echo e($class->students->count()); ?> students</span>
            </div>
            <div class="card-body p-0">
                <?php if($class->students->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Student Name</th>
                                    <th class="d-none d-md-table-cell">Admission No.</th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="d-none d-lg-table-cell">Phone</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $class->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-3">
                                            <strong><?php echo e($student->user->name ?? $student->name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($student->status ?? 'Active'); ?></small>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <code><?php echo e($student->admission_number ?? 'N/A'); ?></code>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <small><?php echo e($student->user->email ?? 'N/A'); ?></small>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <small><?php echo e($student->user->phone ?? 'N/A'); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-info" title="View Student">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" title="Enter Grade">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                        <p>No students enrolled in this class yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>" class="btn btn-primary">
                <i class="fas fa-star me-2"></i> Enter Grades for This Class
            </a>
            <a href="<?php echo e(route('teacher.attendance')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-check-square me-2"></i> Manage Attendance
            </a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/show.blade.php ENDPATH**/ ?>