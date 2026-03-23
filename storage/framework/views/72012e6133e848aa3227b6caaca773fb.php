<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-university me-2"></i>
                        Campus Affiliation Requests
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4" id="approvalTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                                <i class="fas fa-clock me-1"></i>
                                Pending Approval (<?php echo e($pendingCount); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                                <i class="fas fa-check me-1"></i>
                                Approved (<?php echo e($approvedCount); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
                                <i class="fas fa-times me-1"></i>
                                Rejected (<?php echo e($rejectedCount); ?>)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="approvalTabsContent">
                        <!-- Pending Approvals -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <?php if($pendingTeachers->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Email</th>
                                                <th>Requested Campus</th>
                                                <th>Registration Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $pendingTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0"><?php echo e($teacher->name); ?></h6>
                                                                <small class="text-muted">ID: <?php echo e($teacher->employee_id ?? 'N/A'); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo e($teacher->email); ?></td>
                                                    <td>
                                                        <span class="badge bg-warning"><?php echo e($teacher->campus); ?></span>
                                                    </td>
                                                    <td><?php echo e($teacher->created_at->format('M d, Y')); ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-success" onclick="approveCampus(<?php echo e($teacher->id); ?>)">
                                                                <i class="fas fa-check me-1"></i> Approve
                                                            </button>
                                                            <button type="button" class="btn btn-danger" onclick="rejectCampus(<?php echo e($teacher->id); ?>)">
                                                                <i class="fas fa-times me-1"></i> Reject
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>No Pending Requests</h5>
                                    <p class="text-muted">All campus affiliation requests have been processed.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Approved -->
                        <div class="tab-pane fade" id="approved" role="tabpanel">
                            <?php if($approvedTeachers->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Email</th>
                                                <th>Campus</th>
                                                <th>Approved Date</th>
                                                <th>Approved By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $approvedTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0"><?php echo e($teacher->name); ?></h6>
                                                                <small class="text-muted">ID: <?php echo e($teacher->employee_id ?? 'N/A'); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo e($teacher->email); ?></td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo e($teacher->campus); ?></span>
                                                    </td>
                                                    <td><?php echo e($teacher->campus_approved_at ? $teacher->campus_approved_at->format('M d, Y') : 'N/A'); ?></td>
                                                    <td><?php echo e($teacher->approvedBy->name ?? 'N/A'); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm" onclick="revokeCampus(<?php echo e($teacher->id); ?>)">
                                                            <i class="fas fa-undo me-1"></i> Revoke
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Approved Teachers</h5>
                                    <p class="text-muted">No teachers have been approved for campus affiliation yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Rejected -->
                        <div class="tab-pane fade" id="rejected" role="tabpanel">
                            <?php if($rejectedTeachers->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Email</th>
                                                <th>Requested Campus</th>
                                                <th>Rejected Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $rejectedTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-danger rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0"><?php echo e($teacher->name); ?></h6>
                                                                <small class="text-muted">ID: <?php echo e($teacher->employee_id ?? 'N/A'); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo e($teacher->email); ?></td>
                                                    <td>
                                                        <span class="badge bg-danger"><?php echo e($teacher->campus); ?></span>
                                                    </td>
                                                    <td><?php echo e($teacher->campus_approved_at ? $teacher->campus_approved_at->format('M d, Y') : 'N/A'); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-sm" onclick="approveCampus(<?php echo e($teacher->id); ?>)">
                                                            <i class="fas fa-check me-1"></i> Approve
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                                    <h5>No Rejected Requests</h5>
                                    <p class="text-muted">No campus affiliation requests have been rejected.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveCampus(teacherId) {
    if (confirm('Are you sure you want to approve this teacher\'s campus affiliation?')) {
        fetch(`/admin/teachers/${teacherId}/approve-campus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function rejectCampus(teacherId) {
    if (confirm('Are you sure you want to reject this teacher\'s campus affiliation?')) {
        fetch(`/admin/teachers/${teacherId}/reject-campus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function revokeCampus(teacherId) {
    if (confirm('Are you sure you want to revoke this teacher\'s campus affiliation?')) {
        fetch(`/admin/teachers/${teacherId}/revoke-campus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/teachers/campus_approvals.blade.php ENDPATH**/ ?>