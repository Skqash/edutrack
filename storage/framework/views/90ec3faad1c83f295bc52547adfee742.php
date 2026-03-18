

<?php $__env->startSection('content'); ?>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0;
        }

        .profile-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }

        .profile-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .info-group {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 0;
        }

        .info-group:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #212529;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        .avatar-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #0066cc;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            margin: 0 auto 1rem;
        }

        .badge-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #e7f3ff;
            color: #0066cc;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0066cc;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
    </style>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 fw-bold mb-2"><?php echo e($user->name); ?></h1>
                    <span class="badge-status">Active Teacher</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?php echo e(route('teacher.profile.edit')); ?>" class="btn btn-light me-2">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </a>
                    <a href="<?php echo e(route('teacher.profile.change-password')); ?>" class="btn btn-outline-light">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie me-2"></i> Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo e($user->name); ?></div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">
                            <a href="mailto:<?php echo e($user->email); ?>" class="text-decoration-none"><?php echo e($user->email); ?></a>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">
                            <?php if($user->phone): ?>
                                <a href="tel:<?php echo e($user->phone); ?>" class="text-decoration-none"><?php echo e($user->phone); ?></a>
                            <?php else: ?>
                                <span class="text-muted">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($teacher): ?>
                        <div class="info-group">
                            <div class="info-label">Specialization</div>
                            <div class="info-value">
                                <?php echo e($teacher->specialization ?? 'Not specified'); ?>

                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Department</div>
                            <div class="info-value">
                                <?php echo e($teacher->department ?? 'Not specified'); ?>

                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">Biography</div>
                            <div class="info-value">
                                <?php if($teacher->bio): ?>
                                    <p><?php echo e($teacher->bio); ?></p>
                                <?php else: ?>
                                    <span class="text-muted">No biography provided</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-group">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">
                            <?php echo e($user->created_at->format('F j, Y')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Avatar & Quick Actions -->
            <div class="card profile-card">
                <div class="card-body">
                    <div class="avatar-section">
                        <div class="avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="fw-bold mb-2"><?php echo e($user->name); ?></h5>
                        <p class="text-muted small mb-3">Teacher Account</p>
                        <span class="badge bg-success">Active</span>
                    </div>

                    <div class="action-buttons">
                        <a href="<?php echo e(route('teacher.profile.edit')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i> Account Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="info-label">Role</div>
                        <div class="info-value">
                            <span class="badge bg-primary">Teacher</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Account Status</div>
                        <div class="info-value">
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value small text-muted">
                            <?php echo e($user->updated_at->diffForHumans()); ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="card profile-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-link me-2"></i> Quick Links
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo e(route('teacher.dashboard')); ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2 text-primary"></i> Dashboard
                    </a>
                    <a href="<?php echo e(route('teacher.classes')); ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-door-open me-2 text-primary"></i> My Classes
                    </a>
                    <a href="<?php echo e(route('teacher.grades')); ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-star me-2 text-primary"></i> Grades
                    </a>
                    <a href="<?php echo e(route('teacher.profile.change-password')); ?>"
                        class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2 text-primary"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/profile/show.blade.php ENDPATH**/ ?>