<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Teachers Management</h1>
            <p class="mb-0 text-muted">
                <?php if($adminCampus): ?>
                    Campus: <span class="badge bg-primary"><?php echo e($adminCampus); ?></span>
                <?php else: ?>
                    System-wide Management
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.teachers.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Teacher
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportTeachers('csv')">Export CSV</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportTeachers('excel')">Export Excel</a></li>
                </ul>
            </div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload"></i> Import
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['total']); ?></div>
                    <div class="small">Total</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['active']); ?></div>
                    <div class="small">Active</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['inactive']); ?></div>
                    <div class="small">Inactive</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['approved']); ?></div>
                    <div class="small">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['pending']); ?></div>
                    <div class="small">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card bg-dark text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0"><?php echo e($statistics['independent']); ?></div>
                    <div class="small">Independent</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.teachers.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Name, email, phone...">
                </div>
                <?php if(!$adminCampus): ?>
                <div class="col-md-2">
                    <label class="form-label">Campus</label>
                    <select class="form-select" name="campus">
                        <option value="">All Campuses</option>
                        <option value="CPSU - Bayambang Campus" <?php echo e(request('campus') === 'CPSU - Bayambang Campus' ? 'selected' : ''); ?>>Bayambang</option>
                        <option value="CPSU - Binalonan Campus" <?php echo e(request('campus') === 'CPSU - Binalonan Campus' ? 'selected' : ''); ?>>Binalonan</option>
                        <option value="CPSU - Infanta Campus" <?php echo e(request('campus') === 'CPSU - Infanta Campus' ? 'selected' : ''); ?>>Infanta</option>
                        <option value="CPSU - San Carlos Campus" <?php echo e(request('campus') === 'CPSU - San Carlos Campus' ? 'selected' : ''); ?>>San Carlos</option>
                        <option value="CPSU - San Quintin Campus" <?php echo e(request('campus') === 'CPSU - San Quintin Campus' ? 'selected' : ''); ?>>San Quintin</option>
                        <option value="CPSU - Sta. Maria Campus" <?php echo e(request('campus') === 'CPSU - Sta. Maria Campus' ? 'selected' : ''); ?>>Sta. Maria</option>
                        <option value="CPSU - Tarlac Campus" <?php echo e(request('campus') === 'CPSU - Tarlac Campus' ? 'selected' : ''); ?>>Tarlac</option>
                        <option value="CPSU - Urdaneta Campus" <?php echo e(request('campus') === 'CPSU - Urdaneta Campus' ? 'selected' : ''); ?>>Urdaneta</option>
                        <option value="independent" <?php echo e(request('campus') === 'independent' ? 'selected' : ''); ?>>Independent</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="Active" <?php echo e(request('status') === 'Active' ? 'selected' : ''); ?>>Active</option>
                        <option value="Inactive" <?php echo e(request('status') === 'Inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Approval</label>
                    <select class="form-select" name="approval_status">
                        <option value="">All Approvals</option>
                        <option value="approved" <?php echo e(request('approval_status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="pending" <?php echo e(request('approval_status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="rejected" <?php echo e(request('approval_status') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Teachers List</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                    <i class="fas fa-check-square"></i> Select All
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="bulkActionsBtn" disabled>
                        <i class="fas fa-cogs"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('approve_campus')">Approve Campus</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('reject_campus')">Reject Campus</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">Activate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">Deactivate</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">Delete Selected</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Campus</th>
                            <th>Status</th>
                            <th>Campus Status</th>
                            <th>Classes</th>
                            <th>Subjects</th>
                            <th>Joined</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="teacher-checkbox" value="<?php echo e($teacher->id); ?>" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <?php echo e(strtoupper(substr($teacher->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo e($teacher->name); ?></div>
                                        <?php if($teacher->phone): ?>
                                            <small class="text-muted"><?php echo e($teacher->phone); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($teacher->email); ?></td>
                            <td>
                                <?php if($teacher->campus): ?>
                                    <span class="badge bg-info"><?php echo e(str_replace('CPSU - ', '', $teacher->campus)); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Independent</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($teacher->status === 'Active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($teacher->campus): ?>
                                    <?php if($teacher->campus_status === 'approved'): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Approved
                                        </span>
                                    <?php elseif($teacher->campus_status === 'pending'): ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    <?php elseif($teacher->campus_status === 'rejected'): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Rejected
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?php echo e($teacher->classes_count ?? 0); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?php echo e($teacher->subjects_count ?? 0); ?></span>
                            </td>
                            <td>
                                <small class="text-muted"><?php echo e($teacher->created_at->format('M d, Y')); ?></small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.teachers.show', $teacher)); ?>" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.teachers.edit', $teacher)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?php echo e(route('admin.teachers.subjects', $teacher)); ?>">
                                                <i class="fas fa-book me-2"></i>Manage Subjects
                                            </a></li>
                                            <?php if($teacher->campus && $teacher->campus_status === 'pending'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-success" href="#" onclick="approveCampus(<?php echo e($teacher->id); ?>)">
                                                    <i class="fas fa-check me-2"></i>Approve Campus
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="rejectCampus(<?php echo e($teacher->id); ?>)">
                                                    <i class="fas fa-times me-2"></i>Reject Campus
                                                </a></li>
                                            <?php elseif($teacher->campus && $teacher->campus_status === 'approved'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-warning" href="#" onclick="revokeCampus(<?php echo e($teacher->id); ?>)">
                                                    <i class="fas fa-undo me-2"></i>Revoke Campus
                                                </a></li>
                                            <?php endif; ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTeacher(<?php echo e($teacher->id); ?>)">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No teachers found</p>
                                <a href="<?php echo e(route('admin.teachers.create')); ?>" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add First Teacher
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($teachers->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing <?php echo e($teachers->firstItem()); ?> to <?php echo e($teachers->lastItem()); ?> of <?php echo e($teachers->total()); ?> results
                    </div>
                    <?php echo e($teachers->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Teachers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.teachers.import')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" class="form-control" name="file" accept=".csv,.xlsx" required>
                        <div class="form-text">Supported formats: CSV, Excel (.xlsx)</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="auto_approve" id="autoApprove">
                        <label class="form-check-label" for="autoApprove">
                            Auto-approve campus affiliations
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Bulk actions functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.teacher-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function selectAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    selectAll.checked = true;
    toggleSelectAll();
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.teacher-checkbox:checked');
    const bulkBtn = document.getElementById('bulkActionsBtn');
    
    bulkBtn.disabled = checkedBoxes.length === 0;
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.teacher-checkbox:checked');
    const teacherIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (teacherIds.length === 0) {
        alert('Please select teachers first');
        return;
    }
    
    let confirmMessage = `Are you sure you want to ${action.replace('_', ' ')} ${teacherIds.length} teacher(s)?`;
    if (action === 'delete') {
        confirmMessage = `Are you sure you want to delete ${teacherIds.length} teacher(s)? This action cannot be undone.`;
    }
    
    if (!confirm(confirmMessage)) return;
    
    let reason = null;
    if (action === 'reject_campus') {
        reason = prompt('Please provide a reason for rejection:');
        if (reason === null) return;
    }
    
    fetch('<?php echo e(route("admin.teachers.bulk-action")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            action: action,
            teacher_ids: teacherIds,
            reason: reason
        })
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
        alert('An error occurred');
    });
}

// Individual actions
function approveCampus(teacherId) {
    if (!confirm('Approve this teacher\'s campus affiliation?')) return;
    
    fetch(`<?php echo e(route('admin.teachers.index')); ?>/${teacherId}/approve-campus`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function rejectCampus(teacherId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason === null) return;
    
    fetch(`<?php echo e(route('admin.teachers.index')); ?>/${teacherId}/reject-campus`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function revokeCampus(teacherId) {
    if (!confirm('Revoke this teacher\'s campus affiliation?')) return;
    
    fetch(`<?php echo e(route('admin.teachers.index')); ?>/${teacherId}/revoke-campus`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function deleteTeacher(teacherId) {
    if (!confirm('Are you sure you want to delete this teacher? This action cannot be undone.')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `<?php echo e(route('admin.teachers.index')); ?>/${teacherId}`;
    form.innerHTML = `
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    `;
    document.body.appendChild(form);
    form.submit();
}

function exportTeachers(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('format', format);
    window.open(`<?php echo e(route('admin.teachers.export')); ?>?${params.toString()}`, '_blank');
}
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/teachers/index.blade.php ENDPATH**/ ?>