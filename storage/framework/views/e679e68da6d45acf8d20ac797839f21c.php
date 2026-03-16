

<?php $__env->startSection('content'); ?>
    <style>
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

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .subject-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
            margin-bottom: 1rem;
        }

        .subject-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .subject-card .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .subject-card .subject-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .subject-card .subject-code {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .subject-card .subject-stats {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .subject-card .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .subject-card .stat-item i {
            color: #667eea;
        }

        .class-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .class-item {
            background: white;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .class-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .class-item:last-child {
            margin-bottom: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern .header-title {
                font-size: 1.5rem;
            }

            .subject-card .subject-stats {
                flex-direction: column;
                gap: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h1 class="header-title">My Subjects</h1>
                        <p class="header-subtitle">Manage your assigned courses and teaching materials</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-modern btn-modern-outline">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <button type="button" class="btn btn-modern btn-modern-white" data-bs-toggle="modal" data-bs-target="#requestSubjectModal">
                        <i class="fas fa-paper-plane"></i> Request Subject
                    </button>
                    <button type="button" class="btn btn-modern btn-modern-white" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                        <i class="fas fa-plus"></i> Create Subject
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-value" style="color: #667eea;"><?php echo e(count($courses)); ?></div>
                    <div class="stat-label">Total Courses</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div class="stat-value" style="color: #3b82f6;"><?php echo e($totalClasses); ?></div>
                    <div class="stat-label">Total Classes</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-value" style="color: #f59e0b;"><?php echo e($totalStudents); ?></div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="row">
            <div class="col-12">
                <?php if(count($courses) === 0): ?>
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h5>No Subjects Assigned Yet</h5>
                        <p>You don't have any assigned courses yet. Request a subject from admin or create your own if you're independent.</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestSubjectModal">
                                <i class="fas fa-paper-plane me-2"></i>Request Subject
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                <i class="fas fa-plus me-2"></i>Create Subject
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="subject-card">
                            <div class="subject-header">
                                <div>
                                    <div class="subject-title"><?php echo e($course['name']); ?></div>
                                    <div class="subject-code"><?php echo e($course['code'] ?? 'No Code'); ?></div>
                                    <?php if($course['description']): ?>
                                        <p class="text-muted small mt-2 mb-0"><?php echo e($course['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="badge bg-primary">Active</span>
                                </div>
                            </div>

                            <div class="subject-stats">
                                <div class="stat-item">
                                    <i class="fas fa-chalkboard"></i>
                                    <span><strong><?php echo e($course['class_count']); ?></strong> Classes</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    <span><strong><?php echo e($course['student_count']); ?></strong> Students</span>
                                </div>
                            </div>

                            <?php if(!empty($course['classes'])): ?>
                                <div class="class-list">
                                    <h6 class="mb-3"><i class="fas fa-list me-2"></i>Classes</h6>
                                    <?php $__currentLoopData = $course['classes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="class-item">
                                            <span class="fw-semibold"><?php echo e($cls['class_name']); ?></span>
                                            <a href="<?php echo e(route('teacher.classes.show', $cls['id'])); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Request Subject Modal -->
    <div class="modal fade" id="requestSubjectModal" tabindex="-1" aria-labelledby="requestSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestSubjectModalLabel">
                        <i class="fas fa-paper-plane me-2"></i>Request Subject Assignment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('teacher.subjects.request')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Request a subject from the admin to be assigned to you.
                        </div>

                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required placeholder="e.g., Introduction to Programming">
                        </div>

                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="e.g., IT101">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required placeholder="Explain why you need this subject..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Subject Modal -->
    <div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubjectModalLabel">
                        <i class="fas fa-plus me-2"></i>Create Your Own Subject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('teacher.subjects.create')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Create your own subject if you're an independent teacher.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="create_subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_subject_name" name="subject_name" required placeholder="e.g., Web Development Fundamentals">
                            </div>

                            <div class="col-md-4">
                                <label for="create_subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_subject_code" name="subject_code" required placeholder="e.g., IT102">
                            </div>

                            <div class="col-md-6">
                                <label for="credit_hours" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="credit_hours" name="credit_hours" required min="1" max="6" value="3">
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="Programming">Programming</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Science">Science</option>
                                    <option value="Language">Language</option>
                                    <option value="Business">Business</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="create_description" class="form-label">Description</label>
                                <textarea class="form-control" id="create_description" name="description" rows="3" placeholder="Brief description of the subject..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/subjects/index.blade.php ENDPATH**/ ?>