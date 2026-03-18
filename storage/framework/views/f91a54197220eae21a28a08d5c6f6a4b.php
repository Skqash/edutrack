<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-eye me-2" style="color: #6c757d;"></i>Subject Details
                    </h2>
                    <p class="text-muted">View complete subject information and assignments</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('admin.subjects.edit', $subject)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Subject
                    </a>
                    <a href="<?php echo e(route('admin.subjects.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-book me-2" style="color: #6c757d;"></i>Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Subject Code</label>
                                <h6 class="fw-bold text-dark"><?php echo e($subject->subject_code); ?></h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Credit Hours</label>
                                <h6 class="fw-bold text-dark">
                                    <span class="badge bg-secondary"><?php echo e($subject->credit_hours); ?> Units</span>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Subject Name</label>
                        <h5 class="fw-bold text-dark"><?php echo e($subject->subject_name); ?></h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Category</label>
                                <h6 class="fw-bold text-dark">
                                    <span class="badge bg-light text-dark border"><?php echo e($subject->category); ?></span>
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Type</label>
                                <h6 class="fw-bold text-dark">
                                    <span class="badge bg-<?php echo e($subject->type == 'Core' ? 'primary' : ($subject->type == 'Elective' ? 'warning' : 'info')); ?>">
                                        <?php echo e($subject->type ?? 'General'); ?>

                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Description</label>
                        <p class="text-dark"><?php echo e($subject->description ?? 'No description available'); ?></p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-graduation-cap me-2" style="color: #6c757d;"></i>Program Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($subject->course): ?>
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold"><?php echo e($subject->course->program_name); ?></h6>
                                    <p class="mb-0 text-muted">
                                        Program Code: <?php echo e($subject->course->program_code); ?><br>
                                        <?php echo e($subject->course->college ?? 'No college specified'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">General Education</h6>
                                    <p class="mb-0 text-muted">This subject is not assigned to a specific degree program</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <label class="text-muted small">Program Field</label>
                        <h6 class="fw-bold text-dark"><?php echo e($subject->program ?? 'General Education'); ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie me-2" style="color: #6c757d;"></i>Instructor Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($subject->instructor): ?>
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tie fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold"><?php echo e($subject->instructor->name); ?></h6>
                                    <p class="mb-0 text-muted">
                                        Role: <?php echo e($subject->instructor->role); ?><br>
                                        Email: <?php echo e($subject->instructor->email); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">No Instructor Assigned</h6>
                                    <p class="mb-0 text-muted">This subject doesn't have an assigned instructor</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cog me-2" style="color: #6c757d;"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.subjects.edit', $subject)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit Subject
                        </a>
                        
                        <form action="<?php echo e(route('admin.subjects.destroy', $subject)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i> Delete Subject
                            </button>
                        </form>
                        
                        <a href="<?php echo e(route('admin.subjects.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/subjects/show.blade.php ENDPATH**/ ?>