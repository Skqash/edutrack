<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-university"></i>
            </span>
            <?php echo e($course->program_name ?? $course->course_name); ?>

        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.courses.index')); ?>">Academic Programs</a></li>
                <li class="breadcrumb-item active">Program Details</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Program Code</label>
                                <h5 class="fw-bold text-success"><?php echo e($course->program_code ?? $course->course_code); ?></h5>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">College</label>
                                <h5 class="fw-bold"><?php echo e($course->college ?? 'Not specified'); ?></h5>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Duration</label>
                                <h5 class="fw-bold"><?php echo e($course->duration ?? '4 Years'); ?></h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Department</label>
                                <h5 class="fw-bold"><?php echo e($course->department ?? 'General Education'); ?></h5>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Student Capacity</label>
                                <h5 class="fw-bold"><?php echo e($course->current_students ?? 0); ?> / <?php echo e($course->max_students ?? 50); ?></h5>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Status</label>
                                <h5>
                                    <span class="badge bg-<?php echo e($course->status == 'Active' ? 'success' : 'danger'); ?> fs-6">
                                        <?php echo e($course->status); ?>

                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Description</label>
                        <p class="text-muted"><?php echo e($course->description ?? 'No description available for this program.'); ?></p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.courses.edit', $course)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Program
                        </a>
                        <a href="<?php echo e(route('admin.courses.manageSubjects', $course)); ?>" class="btn btn-success">
                            <i class="fas fa-book me-2"></i>Manage Subjects
                        </a>
                        <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Programs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>Program Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Enrollment Rate</span>
                            <span class="fw-bold"><?php echo e(round((($course->current_students ?? 0) / ($course->max_students ?? 50)) * 100)); ?>%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: <?php echo e(round((($course->current_students ?? 0) / ($course->max_students ?? 50)) * 100)); ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="mb-2">
                            <i class="fas fa-users fa-2x text-muted"></i>
                        </div>
                        <h4 class="fw-bold"><?php echo e($course->current_students ?? 0); ?></h4>
                        <p class="text-muted small">Current Students</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\courses\show.blade.php ENDPATH**/ ?>