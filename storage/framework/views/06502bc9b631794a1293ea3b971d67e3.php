

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3"><i class="fas fa-users"></i> Student Management</h2>
        <a href="<?php echo e(route('super.students.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Student
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="search" id="studentSearch" class="form-control form-control-sm" placeholder="Search students...">
                </div>
                <div class="col-md-6">
                    <select id="classFilter" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        <?php $__currentLoopData = $classes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->class_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Enrollment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="student-row" data-class="<?php echo e($student->class_id ?? ''); ?>">
                            <td><strong><?php echo e($student->student_id); ?></strong></td>
                            <td><?php echo e($student->user->name ?? $student->name); ?></td>
                            <td><?php echo e($student->user->email ?? $student->email); ?></td>
                            <td><?php echo e($student->class->class_name ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($student->status === 'Active' ? 'success' : 'secondary'); ?>">
                                    <?php echo e($student->status); ?>

                                </span>
                            </td>
                            <td><?php echo e($student->enrollment_date ? $student->enrollment_date->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editStudent(<?php echo e($student->id); ?>)">Edit</button>
                                <form action="#" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No students found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <?php echo e($students->links()); ?>

        </div>
    </div>

    <div class="mt-3">
        <form action="<?php echo e(route('super.students.delete-all')); ?>" method="POST" onsubmit="return confirm('Delete ALL students? This cannot be undone!')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Students</button>
        </form>
    </div>
</div>

<script>
document.getElementById('studentSearch').addEventListener('keyup', function() {
    let query = this.value.toLowerCase();
    document.querySelectorAll('.student-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
});

document.getElementById('classFilter').addEventListener('change', function() {
    let classId = this.value;
    document.querySelectorAll('.student-row').forEach(row => {
        row.style.display = !classId || row.dataset.class === classId ? '' : 'none';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/students/index.blade.php ENDPATH**/ ?>