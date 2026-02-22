<?php $__env->startSection('title', 'My Courses'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 text-dark fw-bold">
                            <i class="fas fa-book me-2 text-primary"></i>My Courses
                        </h1>
                        <p class="text-muted mb-0">View all courses you are teaching</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition" style="transition: transform 0.3s;">
                        <div class="card-body">
                            <!-- Subject Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title text-dark fw-bold mb-1"><?php echo e($course['name']); ?></h5>
                                    <?php if($course['description']): ?>
                                        <p class="text-muted small mb-0"><?php echo e(Str::limit($course['description'], 80)); ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-primary text-white">
                                    <i class="fas fa-book-open me-1"></i>Course
                                </span>
                            </div>

                            <!-- Subject Stats -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Classes</small>
                                        <h6 class="mb-0 text-dark fw-bold"><?php echo e($course['class_count'] ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded text-center">
                                        <small class="text-muted d-block">Students</small>
                                        <h6 class="mb-0 text-dark fw-bold"><?php echo e($course['student_count'] ?? 0); ?></h6>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Code (if available) -->
                            <?php if($course['code'] ?? null): ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <small class="text-muted d-block mb-1">Course Code</small>
                                    <code class="text-secondary"><?php echo e($course['code']); ?></code>
                                </div>
                            <?php endif; ?>

                            <!-- Classes Using This Course -->
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2 fw-semibold">
                                    <i class="fas fa-layer-group me-1"></i>Classes
                                </small>
                                <?php if($course['classes'] && count($course['classes']) > 0): ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $course['classes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-info text-dark"><?php echo e($class['class_name']); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-info-circle me-1"></i>No classes assigned yet
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 mt-3 pt-3 border-top">
                                <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-sm btn-outline-primary flex-grow-1" title="View All Classes">
                                    <i class="fas fa-layer-group me-1"></i>Classes
                                </a>
                                <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-sm btn-primary" title="Create New Class">
                                    <i class="fas fa-plus me-1"></i>Add
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 bg-light p-5 text-center">
                        <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-dark mt-3 fw-bold">No Courses Assigned</h5>
                        <p class="text-muted mb-0">
                            You don't have any courses assigned yet. Create a class to get started, and select a course during class creation.
                        </p>
                        <a href="<?php echo e(route('teacher.classes.create')); ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Create Your First Class
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary Stats -->
        <?php if(count($courses) > 0): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 text-dark fw-semibold">
                                <i class="fas fa-chart-bar text-primary me-2"></i>Course Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h4 class="text-primary fw-bold"><?php echo e(count($courses)); ?></h4>
                                        <small class="text-muted">Total Courses Teaching</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h4 class="text-info fw-bold"><?php echo e($totalClasses); ?></h4>
                                        <small class="text-muted">Total Classes</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h4 class="text-success fw-bold"><?php echo e($totalStudents); ?></h4>
                                        <small class="text-muted">Total Students</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h4 class="text-warning fw-bold"><?php echo e(round($totalStudents / max($totalClasses, 1), 1)); ?></h4>
                                        <small class="text-muted">Avg Students/Class</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/subjects/index.blade.php ENDPATH**/ ?>