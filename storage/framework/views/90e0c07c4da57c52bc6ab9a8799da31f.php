<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Classes Management</h2>
                    <p class="text-muted">Manage all classes and organize students by class sections</p>
                </div>
                <a href="<?php echo e(route('admin.classes.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Class Capacity Chart -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2" style="color: #3498db;"></i> Class Capacity Utilization
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="classCapacityChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-layer-group me-2" style="color: #27ae60;"></i> Class Levels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="category-list" style="font-size: 14px;">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 10</span>
                            <span class="badge bg-primary">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 11</span>
                            <span class="badge bg-success">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Class 12</span>
                            <span class="badge bg-warning">8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search classes...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold">Class Name</th>
                            <th class="fw-bold">Class Level</th>
                            <th class="fw-bold">Section</th>
                            <th class="fw-bold">Students</th>
                            <th class="fw-bold">Class Teacher</th>
                            <th class="fw-bold">Capacity</th>
                            <th class="fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-class-id="<?php echo e($class->id); ?>">
                                <td><strong><?php echo e($class->class_name); ?></strong></td>
                                <td><span class="badge bg-light text-dark"><?php echo e($class->class_level); ?></span></td>
                                <td><?php echo e($class->section); ?></td>
                                <td><span class="badge bg-info"><?php echo e($class->students()->count()); ?></span></td>
                                <td><?php echo e($class->teacher->name ?? 'N/A'); ?></td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar <?php echo e($class->utilizationPercentage() > 85 ? 'bg-danger' : ($class->utilizationPercentage() > 70 ? 'bg-warning' : 'bg-success')); ?>"
                                            style="width: <?php echo e($class->utilizationPercentage()); ?>%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.classes.edit', $class->id)); ?>"
                                            class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="<?php echo e(route('admin.classes.show', $class->id)); ?>"
                                            class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            onclick="openStudentModal(<?php echo e($class->id); ?>, '<?php echo e($class->class_name); ?>')">
                                            <i class="fas fa-user-plus"></i> Add Students
                                        </button>
                                        <form action="<?php echo e(route('admin.classes.destroy', $class->id)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">No classes found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <?php echo $__env->make('admin.classes.partials.student-assignment-modal', [
        'courses' => $courses,
        'departments' => $departments,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\classes.blade.php ENDPATH**/ ?>