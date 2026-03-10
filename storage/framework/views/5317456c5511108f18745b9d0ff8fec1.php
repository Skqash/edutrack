<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="fas fa-chalkboard-user"></i>
                </span>
                Teachers Management
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
                            <h4 class="card-title">All Teachers</h4>
                            <a href="<?php echo e(route('admin.teachers.create')); ?>" class="btn btn-gradient-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Teacher
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
                            <table class="table table-hover" id="teachersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joining Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge badge-gradient-warning rounded-circle"
                                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <?php echo e(strtoupper(substr($teacher->name, 0, 1))); ?>

                                                    </div>
                                                    <span class="ms-3"><?php echo e($teacher->name); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($teacher->email); ?></td>
                                            <td><?php echo e($teacher->created_at->format('d M Y')); ?></td>
                                            <td>
                                                <span class="badge bg-gradient-success">Active</span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('admin.teachers.edit', $teacher->id)); ?>"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.teachers.subjects', $teacher->id)); ?>"
                                                    class="btn btn-sm btn-primary" title="Manage Subjects">
                                                    <i class="fas fa-book"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.teachers.destroy', $teacher->id)); ?>"
                                                    method="POST" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted">No teachers found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($teachers->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-gradient-warning {
            background: linear-gradient(45deg, #ffa500, #ffb833);
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/teachers.blade.php ENDPATH**/ ?>