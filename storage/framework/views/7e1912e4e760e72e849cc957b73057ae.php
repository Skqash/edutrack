<?php $__env->startSection('content'); ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h3 fw-bold mb-0">My Classes</h1>
                <small class="text-muted">View and manage your assigned classes</small>
            </div>
        </div>
    </div>
</div>

<!-- Classes Grid/List -->
<div class="row mb-4">
    <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 transition-all" style="transition: all 0.3s ease;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1"><?php echo e($class->class_name); ?></h5>
                            <small class="text-muted"><?php echo e($class->class_level); ?></small>
                        </div>
                        <span class="badge bg-primary"><?php echo e($class->section ?? 'N/A'); ?></span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="fas fa-users me-1"></i> Students Enrolled
                        </small>
                        <h4 class="mb-0"><?php echo e($class->students->count()); ?></h4>
                    </div>

                    <?php if($class->course): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-book me-1"></i> Course
                            </small>
                            <p class="mb-0"><?php echo e($class->course->course_name); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="fas fa-door-open me-1"></i> Capacity
                        </small>
                        <div class="progress" style="height: 5px;">
                            <?php
                                $percentage = ($class->students->count() / ($class->capacity ?? 1)) * 100;
                            ?>
                            <div class="progress-bar" style="width: <?php echo e($percentage); ?>%;" role="progressbar"></div>
                        </div>
                        <small class="text-muted"><?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?> students</small>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 d-flex gap-2">
                    <a href="<?php echo e(route('teacher.classes.show', $class->id)); ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="fas fa-eye me-1"></i> View Details
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-keyboard me-1"></i> Enter Grades
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=midterm">Midterm</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('teacher.grades.entry', $class->id)); ?>?term=final">Final</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                    <p class="text-muted">No classes assigned yet</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if($classes->hasPages()): ?>
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            <?php echo e($classes->links()); ?>

        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/index.blade.php ENDPATH**/ ?>