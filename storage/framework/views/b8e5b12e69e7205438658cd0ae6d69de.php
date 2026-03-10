<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="fas fa-user-graduate"></i>
                </span>
                Students Management
            </h3>
            <div class="page-breadcrumb">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title">All Students</h4>
                            <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-gradient-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Student
                            </a>
                        </div>

                        <?php if($message = Session::get('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo e($message); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="studentsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 12%;"><i class="fas fa-id-card me-1 text-primary"></i>Student ID
                                        </th>
                                        <th style="width: 25%;"><i class="fas fa-user me-1 text-primary"></i>Name</th>
                                        <th style="width: 25%;"><i class="fas fa-envelope me-1 text-primary"></i>Email</th>
                                        <th style="width: 15%;"><i
                                                class="fas fa-calendar-alt me-1 text-primary"></i>Enrollment Date</th>
                                        <th style="width: 10%;"><i class="fas fa-check-circle me-1 text-primary"></i>Status
                                        </th>
                                        <th style="width: 13%;" class="text-center"><i
                                                class="fas fa-cog me-1 text-primary"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-info text-white fw-bold"
                                                    style="font-size: 0.85rem; letter-spacing: 0.5px;"><?php echo e($student->student_id ?? 'N/A'); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge badge-gradient-primary rounded-circle"
                                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        <?php echo e(strtoupper(substr($student->user->name ?? $student->name, 0, 1))); ?>

                                                    </div>
                                                    <span
                                                        class="ms-3 fw-500"><?php echo e($student->user->name ?? $student->name); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted"><?php echo e($student->user->email ?? $student->email); ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo e($student->created_at->format('d M Y')); ?></small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-gradient-success"><?php echo e($student->status ?? 'Active'); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>"
                                                        class="btn btn-sm btn-outline-info" title="Edit Student">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.students.destroy', $student->id)); ?>"
                                                        method="POST" style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure you want to delete this student?')"
                                                            title="Delete Student">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2" style="opacity: 0.5;"></i>
                                                <p class="text-muted">No students found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($students->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-gradient-primary {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
            color: white;
        }

        .btn-gradient-primary {
            background: linear-gradient(45deg, #4099ff, #73b4ff);
            color: white;
            border: none;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(45deg, #2970cc, #4099ff);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/students.blade.php ENDPATH**/ ?>