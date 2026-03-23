@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Courses Management</h1>
            <p class="mb-0 text-muted">
                @if($adminCampus)
                    Campus: <span class="badge bg-primary">{{ $adminCampus }}</span>
                @else
                    System-wide Management
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Course
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportCourses('csv')">Export CSV</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportCourses('excel')">Export Excel</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0">{{ $statistics['total'] }}</div>
                    <div class="small">Total Courses</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0">{{ $statistics['active'] }}</div>
                    <div class="small">Active</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0">{{ $statistics['inactive'] }}</div>
                    <div class="small">Inactive</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="h4 mb-0">{{ $statistics['without_heads'] }}</div>
                    <div class="small">No Program Head</div>
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
            <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Course name, code, description...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select class="form-select" name="department_id" id="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Courses List</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                    <i class="fas fa-check-square"></i> Select All
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="bulkActionsBtn" disabled>
                        <i class="fas fa-cogs"></i> Bulk Actions
                    </button>
                    <ul class="dropdown-menu">
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
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Department</th>
                            <th>Program Head</th>
                            <th>Years</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th>Classes</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td>
                                <input type="checkbox" class="course-checkbox" value="{{ $course->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $course->program_code }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $course->program_name }}</div>
                                @if($course->description)
                                    <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $course->department->department_name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($course->head)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($course->head->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $course->head->name }}</div>
                                            <small class="text-muted">{{ $course->head->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No Head Assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $course->total_years }} Years</span>
                            </td>
                            <td>
                                @if($course->status === 'Active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $course->students_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $course->classes_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.courses.manageSubjects', $course) }}">
                                                <i class="fas fa-book me-2"></i>Manage Subjects
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteCourse({{ $course->id }})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No courses found</p>
                                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus"></i> Add First Course
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} results
                    </div>
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Bulk actions functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.course-checkbox');
    
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
    const checkedBoxes = document.querySelectorAll('.course-checkbox:checked');
    const bulkBtn = document.getElementById('bulkActionsBtn');
    
    bulkBtn.disabled = checkedBoxes.length === 0;
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.course-checkbox:checked');
    const courseIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (courseIds.length === 0) {
        alert('Please select courses first');
        return;
    }
    
    let confirmMessage = `Are you sure you want to ${action} ${courseIds.length} course(s)?`;
    if (action === 'delete') {
        confirmMessage = `Are you sure you want to delete ${courseIds.length} course(s)? This action cannot be undone.`;
    }
    
    if (!confirm(confirmMessage)) return;
    
    fetch('{{ route("admin.courses.bulk-action") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            action: action,
            course_ids: courseIds
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

function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('admin.courses.index') }}/${courseId}`;
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
}

function exportCourses(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('format', format);
    window.open(`{{ route('admin.courses.export') }}?${params.toString()}`, '_blank');
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
@endsection