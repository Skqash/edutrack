<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">Attendance History - <?php echo e($class->name); ?></h1>
                <small class="text-muted">Search and revisit previous attendance records</small>
            </div>
            <div>
                <a href="<?php echo e(route('teacher.attendance.manage', $class->id)); ?>" class="btn btn-outline-secondary me-2">Manage
                    Today's Attendance</a>
                <a href="<?php echo e(route('teacher.attendance')); ?>" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('teacher.attendance.history', $class->id)); ?>"
                class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo e($start ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="<?php echo e($end ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select">
                        <option value="">All Students</option>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>"
                                <?php echo e(isset($studentId) && $studentId == $s->id ? 'selected' : ''); ?>>
                                <?php echo e($s->user->name ?? $s->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($att->date); ?></td>
                                <td><?php echo e(optional($att->student->user)->name ?? ($att->student->name ?? 'N/A')); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo e($att->status === 'Present' ? 'success' : ($att->status === 'Absent' ? 'danger' : ($att->status === 'Late' ? 'warning' : 'secondary'))); ?>">
                                        <?php echo e($att->status); ?>

                                    </span>
                                </td>
                                <td><?php echo e($class->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No attendance records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <?php echo e($attendances->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\attendance\history.blade.php ENDPATH**/ ?>