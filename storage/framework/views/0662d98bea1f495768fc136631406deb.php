

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="mb-4">
        <h2 class="h3"><i class="fas fa-graduation-cap text-primary"></i> Student Dashboard</h2>
        <p class="text-muted">Welcome, <strong><?php echo e(auth()->user()->name); ?></strong></p>
    </div>

    <!-- KPI Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Enrolled Classes</p>
                            <h3 class="text-primary mb-0">0</h3>
                        </div>
                        <i class="fas fa-chalkboard fa-3x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Courses Enrolled</p>
                            <h3 class="text-success mb-0">0</h3>
                        </div>
                        <i class="fas fa-book fa-3x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Assignments Due</p>
                            <h3 class="text-warning mb-0">0</h3>
                        </div>
                        <i class="fas fa-list-check fa-3x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Getting Started</h5>
                </div>
                <div class="card-body">
                    <p>Welcome to EduTrack! You are logged in as a student. Here you can:</p>
                    <ul>
                        <li>View your enrolled classes</li>
                        <li>Access course materials and assignments</li>
                        <li>Submit assignments</li>
                        <li>View your grades</li>
                        <li>Check your attendance</li>
                    </ul>
                    <p class="text-muted mt-3">More features coming soon!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="mt-4">
        <a href="<?php echo e(route('logout')); ?>" class="btn btn-outline-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\student\dashboard.blade.php ENDPATH**/ ?>