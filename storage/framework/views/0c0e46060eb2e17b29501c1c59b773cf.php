<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-book"></i>
            </span>
            Manage Subjects - <?php echo e($course->program_name ?? $course->course_name); ?>

        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.courses.index')); ?>">Academic Programs</a></li>
                <li class="breadcrumb-item active">Manage Subjects</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>Program Subjects
                        </h5>
                        <a href="<?php echo e(route('admin.courses.show', $course)); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Program
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($course->subjects->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Type</th>
                                        <th>Credit Hours</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $course->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($subject->subject_code); ?></strong></td>
                                        <td><?php echo e($subject->subject_name); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($subject->type == 'Core' ? 'primary' : ($subject->type == 'Elective' ? 'info' : 'secondary')); ?>">
                                                <?php echo e($subject->type ?? 'General'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($subject->credit_hours ?? 3); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No subjects assigned to this program yet</h5>
                            <p class="text-muted">Start by adding subjects to this degree program.</p>
                            <a href="<?php echo e(route('admin.subjects.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Subject
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\courses\subjects.blade.php ENDPATH**/ ?>