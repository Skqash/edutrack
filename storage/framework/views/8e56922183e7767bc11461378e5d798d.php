

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3"><i class="fas fa-book"></i> Course Management</h2>
        <a href="<?php echo e(route('super.courses.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Course
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Instructor</th>
                        <th>Credits</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($course->course_code); ?></strong></td>
                            <td><?php echo e($course->course_name); ?></td>
                            <td><?php echo e($course->instructor->name ?? 'N/A'); ?></td>
                            <td><?php echo e($course->credit_hours ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($course->status === 'Active' ? 'success' : 'secondary'); ?>">
                                    <?php echo e($course->status); ?>

                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editCourse(<?php echo e($course->id); ?>)">Edit</button>
                                <form action="#" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No courses found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <?php echo e($courses->links()); ?>

        </div>
    </div>

    <div class="mt-3">
        <form action="<?php echo e(route('super.courses.delete-all')); ?>" method="POST" onsubmit="return confirm('Delete ALL courses? This cannot be undone!')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Courses</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/courses/index.blade.php ENDPATH**/ ?>