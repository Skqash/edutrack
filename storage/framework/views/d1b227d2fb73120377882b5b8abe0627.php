

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3"><i class="fas fa-chart-bar"></i> Database Statistics</h2>
        <a href="<?php echo e(route('super.dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="row">
        <!-- Connection Info -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-link"></i> Connection Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Host</h6>
                                    <p class="card-text"><strong><?php echo e($connection['host'] ?? 'localhost'); ?></strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Database</h6>
                                    <p class="card-text"><strong><?php echo e($connection['database'] ?? 'edutrack_db'); ?></strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Driver</h6>
                                    <p class="card-text"><strong><?php echo e($connection['driver'] ?? 'MySQL'); ?></strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Status</h6>
                                    <p class="card-text"><span class="badge bg-success">Connected</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Statistics -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-table"></i> Table Statistics (<?php echo e(count($tables) ?? 0); ?> tables)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Table Name</th>
                                <th class="text-end">Rows</th>
                                <th class="text-end">Size</th>
                                <th class="text-end">Engine</th>
                                <th class="text-end">Collation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $tables ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($table['name'] ?? 'N/A'); ?></strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-info"><?php echo e($table['rows'] ?? 0); ?></span>
                                    </td>
                                    <td class="text-end"><?php echo e($table['size'] ?? 'N/A'); ?></td>
                                    <td class="text-end"><?php echo e($table['engine'] ?? 'InnoDB'); ?></td>
                                    <td class="text-end text-muted small"><?php echo e($table['collation'] ?? 'utf8mb4_unicode_ci'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No table statistics available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Storage Summary -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-hdd"></i> Storage Usage</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Database Size</span>
                            <strong><?php echo e($storage['database_size'] ?? 'N/A'); ?></strong>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-primary" style="width: <?php echo e(min($storage['usage_percent'] ?? 0, 100)); ?>%">
                                <?php echo e($storage['usage_percent'] ?? 0); ?>%
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Total Tables</td>
                            <td class="text-end"><strong><?php echo e($storage['total_tables'] ?? 0); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Total Rows</td>
                            <td class="text-end"><strong><?php echo e($storage['total_rows'] ?? 0); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Average Row Size</td>
                            <td class="text-end"><strong><?php echo e($storage['avg_row_size'] ?? 'N/A'); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Growth Chart Placeholder -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Data Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Last Backup</td>
                            <td class="text-end"><small class="text-muted"><?php echo e($storage['last_backup'] ?? 'Never'); ?></small></td>
                        </tr>
                        <tr>
                            <td>Last Optimization</td>
                            <td class="text-end"><small class="text-muted"><?php echo e($storage['last_optimize'] ?? 'Never'); ?></small></td>
                        </tr>
                        <tr>
                            <td>Active Connections</td>
                            <td class="text-end"><strong><?php echo e($storage['connections'] ?? 0); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Queries/Hour</td>
                            <td class="text-end"><strong><?php echo e($storage['queries_per_hour'] ?? 'N/A'); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Management Actions -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Database Maintenance</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <form action="<?php echo e(route('super.tools.optimize')); ?>" method="POST" onsubmit="return confirm('Optimize all tables?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-wrench"></i> Optimize Tables
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="<?php echo e(route('super.tools.repair')); ?>" method="POST" onsubmit="return confirm('Repair all tables?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-hammer"></i> Repair Tables
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="<?php echo e(route('super.tools.backup')); ?>" method="GET">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-save"></i> Backup Database
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="<?php echo e(route('super.tools.export-sql')); ?>" method="GET">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-download"></i> Export SQL
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/super/tools/database.blade.php ENDPATH**/ ?>