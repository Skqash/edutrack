<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="fas fa-chalkboard-teacher"></i>
            </span>
            Teacher Assignments
        </h3>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.teacher-assignments.create')); ?>" class="btn btn-gradient-primary">
                <i class="fas fa-plus"></i> New Assignment
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="<?php echo e(route('admin.teacher-assignments.index')); ?>" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="teacher_id" class="form-label">Teacher</label>
                                <select name="teacher_id" id="teacher_id" class="form-select">
                                    <option value="">All Teachers</option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                            <?php echo e($teacher->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="department" class="form-label">Department</label>
                                <select name="department" id="department" class="form-select">
                                    <option value="">All Departments</option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($department); ?>" <?php echo e(request('department') == $department ? 'selected' : ''); ?>>
                                            <?php echo e($department); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="academic_year" class="form-label">Academic Year</label>
                                <select name="academic_year" id="academic_year" class="form-select">
                                    <option value="">All Years</option>
                                    <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($year); ?>" <?php echo e(request('academic_year') == $year ? 'selected' : ''); ?>>
                                            <?php echo e($year); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="semester" class="form-label">Semester</label>
                                <select name="semester" id="semester" class="form-select">
                                    <option value="">All Semesters</option>
                                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($semester); ?>" <?php echo e(request('semester') == $semester ? 'selected' : ''); ?>>
                                            <?php echo e($semester); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-gradient-info w-100">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Assignments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Teacher</th>
                                    <th>Assignment</th>
                                    <th>Department</th>
                                    <th>Academic Year</th>
                                    <th>Semester</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2">
                                                    <?php echo e(strtoupper(substr($assignment->teacher->name, 0, 1))); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?php echo e($assignment->teacher->name); ?></div>
                                                    <small class="text-muted"><?php echo e($assignment->teacher->email); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="assignment-details">
                                                <?php if($assignment->class): ?>
                                                    <div class="badge bg-info me-1"><?php echo e($assignment->class->class_name); ?></div>
                                                <?php endif; ?>
                                                <?php if($assignment->subject): ?>
                                                    <div class="badge bg-success me-1"><?php echo e($assignment->subject->subject_code); ?></div>
                                                <?php endif; ?>
                                                <?php if($assignment->course): ?>
                                                    <div class="badge bg-warning me-1"><?php echo e($assignment->course->program_code); ?></div>
                                                <?php endif; ?>
                                                <?php if($assignment->department && !$assignment->course): ?>
                                                    <div class="badge bg-secondary me-1"><?php echo e($assignment->department); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo e($assignment->department ?: 'N/A'); ?></td>
                                        <td><?php echo e($assignment->academic_year); ?></td>
                                        <td><?php echo e($assignment->semester); ?></td>
                                        <td>
                                            <span class="badge bg-gradient-primary"><?php echo e($assignment->student_count); ?> students</span>
                                        </td>
                                        <td>
                                            <?php if($assignment->status == 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif($assignment->status == 'inactive'): ?>
                                                <span class="badge bg-warning">Inactive</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Completed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('admin.teacher-assignments.show', $assignment)); ?>" 
                                                   class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.teacher-assignments.edit', $assignment)); ?>" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.teacher-assignments.destroy', $assignment)); ?>" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No teacher assignments found.</p>
                                            <a href="<?php echo e(route('admin.teacher-assignments.create')); ?>" class="btn btn-gradient-primary">
                                                <i class="fas fa-plus"></i> Create First Assignment
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($assignments->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing <?php echo e($assignments->firstItem()); ?> to <?php echo e($assignments->lastItem()); ?> of <?php echo e($assignments->total()); ?> assignments
                            </div>
                            <?php echo e($assignments->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        color: white;
    }

    .btn-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #138496);
        color: white;
        border: none;
    }

    .btn-gradient-info:hover {
        background: linear-gradient(45deg, #138496, #117a8b);
        color: white;
    }

    .assignment-details {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .assignment-details .badge {
        font-size: 0.75rem;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\teacher-assignments\index.blade.php ENDPATH**/ ?>