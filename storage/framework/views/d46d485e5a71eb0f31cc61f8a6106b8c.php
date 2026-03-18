

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3"><i class="fas fa-star"></i> Grade Management</h2>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select id="classFilter" class="form-select form-select-sm">
                                <option value="">All Classes</option>
                                <?php $__currentLoopData = $classes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->class_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="studentFilter" class="form-select form-select-sm">
                                <option value="">All Students</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="search" id="subjectSearch" class="form-control form-control-sm" placeholder="Search subject...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="gradesTable">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="grade-row" data-class="<?php echo e($grade->class_id ?? ''); ?>" data-subject="<?php echo e($grade->subject->subject_code ?? ''); ?>">
                            <td><?php echo e($grade->student->user->name ?? $grade->student->name); ?></td>
                            <td><strong><?php echo e($grade->subject->subject_name ?? 'N/A'); ?></strong></td>
                            <td><?php echo e($grade->student->class->class_name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($grade->score ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <strong class="text-<?php echo e($grade->grade === 'A' ? 'success' : ($grade->grade === 'F' ? 'danger' : 'warning')); ?>">
                                    <?php echo e($grade->grade ?? 'N/A'); ?>

                                </strong>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($grade->status === 'Pass' ? 'success' : 'danger'); ?>">
                                    <?php echo e($grade->status); ?>

                                </span>
                            </td>
                            <td><?php echo e($grade->graded_date ? $grade->graded_date->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editGrade(<?php echo e($grade->id); ?>)">Edit</button>
                                <form action="#" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No grades found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <?php echo e($grades->links()); ?>

        </div>
    </div>

    <div class="mt-3">
        <form action="<?php echo e(route('super.grades.delete-all')); ?>" method="POST" onsubmit="return confirm('Delete ALL grades? This cannot be undone!')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Grades</button>
        </form>
    </div>
</div>

<script>
document.getElementById('subjectSearch').addEventListener('keyup', function() {
    let query = this.value.toLowerCase();
    document.querySelectorAll('.grade-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
});

document.getElementById('classFilter').addEventListener('change', function() {
    let classId = this.value;
    document.querySelectorAll('.grade-row').forEach(row => {
        row.style.display = !classId || row.dataset.class === classId ? '' : 'none';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/grades/index.blade.php ENDPATH**/ ?>