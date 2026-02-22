<?php $__env->startSection('content'); ?>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Assignments</h1>
            <small class="text-muted">Create and manage assignments for your classes</small>
        </div>
    </div>

    <?php if($classes->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You don't have any classes assigned yet.
        </div>
    <?php else: ?>
        <div class="row">
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($class->name); ?></h5>
                            <p class="card-text text-muted">
                                <small><?php echo e($class->subject->name ?? 'No Subject'); ?></small>
                            </p>
                            <p class="mb-3">
                                <strong>Students:</strong> <?php echo e($class->students->count()); ?>

                            </p>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('teacher.assignments.list', $class->id)); ?>"
                                    class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-list me-1"></i> View
                                </a>
                                <a href="<?php echo e(route('teacher.assignments.create', $class->id)); ?>"
                                    class="btn btn-success btn-sm flex-grow-1">
                                    <i class="fas fa-plus me-1"></i> Create
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/assignments/index.blade.php ENDPATH**/ ?>