<?php $__env->startSection('content'); ?>
    <style>
        /* Modern Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
            color: white;
        }

        .page-header-modern .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-modern .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header-modern .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .page-header-modern .header-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header-modern .header-subtitle {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .page-header-modern .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-white {
            background: white;
            color: #667eea;
        }

        .btn-modern-white:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-modern-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-modern-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        /* Card Improvements */
        .card-modern {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-modern .card-body {
            padding: 2rem;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title-modern {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Table Improvements */
        .table-modern {
            margin-bottom: 0;
        }

        .table-modern thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern .header-title {
                font-size: 1.5rem;
            }

            .page-header-modern .header-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
        }
    </style>

    <div class="content-wrapper">
        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h1 class="header-title">Student Management</h1>
                        <p class="header-subtitle">Manage and monitor all student records</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-modern btn-modern-outline">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-modern btn-modern-white">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h4 class="card-title-modern">All Students</h4>
                        <span class="badge bg-primary"><?php echo e($students->total()); ?> Total</span>
                    </div>
                    <div class="card-body">
                        <?php if($message = Session::get('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?php echo e($message); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-id-card me-2"></i>Student ID</th>
                                        <th><i class="fas fa-user me-2"></i>Name</th>
                                        <th><i class="fas fa-envelope me-2"></i>Email</th>
                                        <th><i class="fas fa-calendar-alt me-2"></i>Enrollment Date</th>
                                        <th><i class="fas fa-check-circle me-2"></i>Status</th>
                                        <th class="text-center"><i class="fas fa-cog me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-modern bg-info"><?php echo e($student->student_id ?? 'N/A'); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar-circle">
                                                        <?php echo e(strtoupper(substr($student->name, 0, 1))); ?>

                                                    </div>
                                                    <span class="fw-semibold"><?php echo e($student->name); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo e($student->email); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo e($student->created_at->format('d M Y')); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-modern bg-success"><?php echo e($student->status ?? 'Active'); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>"
                                                        class="btn btn-outline-primary" title="Edit Student">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('admin.students.destroy', $student->id)); ?>"
                                                        method="POST" style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger"
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
                                                <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                                                <p class="text-muted mb-0">No students found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($students->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($students->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/students.blade.php ENDPATH**/ ?>