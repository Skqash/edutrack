

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3"><i class="fas fa-heartbeat"></i> System Health Check</h2>
        <a href="<?php echo e(route('super.dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <!-- Database Health -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-database"></i> Database</h5>
                    <span class="badge bg-<?php echo e($health['database']['status'] === 'healthy' ? 'success' : 'danger'); ?>">
                        <?php echo e(ucfirst($health['database']['status'])); ?>

                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>Connection:</strong></td>
                            <td><?php echo e($health['database']['connected'] ? '✓ Connected' : '✗ Failed'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Database:</strong></td>
                            <td><?php echo e($health['database']['name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tables:</strong></td>
                            <td><?php echo e($health['database']['table_count']); ?> tables</td>
                        </tr>
                        <tr>
                            <td><strong>Total Records:</strong></td>
                            <td><?php echo e($health['database']['total_records']); ?> records</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Storage Health -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-folder"></i> Storage</h5>
                    <span class="badge bg-<?php echo e($health['storage']['status'] === 'healthy' ? 'success' : 'warning'); ?>">
                        <?php echo e(ucfirst($health['storage']['status'])); ?>

                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>Writable:</strong></td>
                            <td><?php echo e($health['storage']['writable'] ? '✓ Yes' : '✗ No'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Space:</strong></td>
                            <td><?php echo e($health['storage']['total_space']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Free Space:</strong></td>
                            <td><?php echo e($health['storage']['free_space']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Usage:</strong></td>
                            <td><?php echo e($health['storage']['usage_percent']); ?>%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cache Health -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Cache</h5>
                    <span class="badge bg-<?php echo e($health['cache']['status'] === 'healthy' ? 'success' : 'warning'); ?>">
                        <?php echo e(ucfirst($health['cache']['status'])); ?>

                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>Driver:</strong></td>
                            <td><?php echo e(ucfirst($health['cache']['driver'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Accessible:</strong></td>
                            <td><?php echo e($health['cache']['accessible'] ? '✓ Yes' : '✗ No'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Size:</strong></td>
                            <td><?php echo e($health['cache']['size'] ?? 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Log Health -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Logs</h5>
                    <span class="badge bg-<?php echo e($health['logs']['status'] === 'healthy' ? 'success' : 'warning'); ?>">
                        <?php echo e(ucfirst($health['logs']['status'])); ?>

                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>Log File:</strong></td>
                            <td><?php echo e($health['logs']['file_exists'] ? '✓ Exists' : '✗ Missing'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Size:</strong></td>
                            <td><?php echo e($health['logs']['size']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Writable:</strong></td>
                            <td><?php echo e($health['logs']['writable'] ? '✓ Yes' : '✗ No'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Recent Errors:</strong></td>
                            <td><span class="badge bg-<?php echo e($health['logs']['error_count'] > 0 ? 'warning' : 'success'); ?>"><?php echo e($health['logs']['error_count']); ?></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Overall Health -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 text-center">
                            <div class="mb-2">
                                <h6>Overall Status</h6>
                                <h4 class="text-<?php echo e($health['overall'] === 'Healthy' ? 'success' : 'warning'); ?>">
                                    <?php echo e($health['overall']); ?>

                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-2">
                                <h6>Last Check</h6>
                                <p><?php echo e(now()->format('M d, Y H:i:s')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="mb-2">
                                <h6>Uptime</h6>
                                <p>✓ Running</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <form action="<?php echo e(route('super.health.refresh')); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync"></i> Refresh Check
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <?php if($health['recommendations']): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Recommendations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <?php $__currentLoopData = $health['recommendations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recommendation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2">
                                    <i class="fas fa-arrow-right text-warning"></i>
                                    <?php echo e($recommendation); ?>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/tools/health.blade.php ENDPATH**/ ?>