@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="fas fa-book"></i>
                </span>
                Manage Subjects - {{ $teacher->name }}
            </h3>
            <div class="page-breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-primary ms-2">
                    <i class="fas fa-chalkboard-user"></i> Teachers
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Pending Subject Requests for approval -->
            <div class="col-md-12 mb-4">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <h4 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Pending Subject requests</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($pendingSubjects) && $pendingSubjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Teacher</th>
                                            <th>Course</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingSubjects as $subject)
                                            <tr>
                                                <td>{{ $subject->subject_code }}</td>
                                                <td>{{ $subject->subject_name }}</td>
                                                <td>{{ $teacher->name }}</td>
                                                <td>{{ $subject->program->program_name ?? 'N/A' }}</td>
                                                <td>
                                                    <form
                                                        action="{{ route('admin.teachers.subjects.approve', [$teacher->id, $subject->id]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success">Approve</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.teachers.subjects.reject', [$teacher->id, $subject->id]) }}"
                                                        method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        <button class="btn btn-sm btn-danger">Reject</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mb-0 text-muted">No pending subject requests at the moment.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Subjects -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-book me-2"></i> Assigned Subjects
                            <span class="badge bg-success ms-2">{{ $assignedSubjects->count() }}</span>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($assignedSubjects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Credits</th>
                                            <th>Course</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignedSubjects as $subject)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-info">{{ $subject->subject_code }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $subject->subject_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $subject->category }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $subject->credit_hours }}
                                                        units</span>
                                                </td>
                                                <td>
                                                    <small
                                                        class="text-muted">{{ $subject->program->program_name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <form
                                                        action="{{ route('admin.teachers.remove-subject', [$teacher->id, $subject->id]) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Remove this subject assignment?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No subjects assigned yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Available Subjects -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i> Available Subjects
                            <span class="badge bg-primary ms-2">{{ $availableSubjects->count() }}</span>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($availableSubjects->count() > 0)
                            <form action="{{ route('admin.teachers.assign-subjects', $teacher->id) }}" method="POST">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                                </th>
                                                <th>Subject Code</th>
                                                <th>Subject Name</th>
                                                <th>Credits</th>
                                                <th>Course</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($availableSubjects as $subject)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="subject_ids[]"
                                                            value="{{ $subject->id }}"
                                                            class="form-check-input subject-checkbox">
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $subject->subject_code }}</span>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $subject->subject_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $subject->category }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $subject->credit_hours }}
                                                            units</span>
                                                    </td>
                                                    <td>
                                                        <small
                                                            class="text-muted">{{ $subject->program->program_name ?? 'N/A' }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($availableSubjects->count() > 0)
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <small class="text-muted">
                                                <span id="selectedCount">0</span> subject(s) selected
                                            </small>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary" id="assignBtn" disabled>
                                            <i class="fas fa-plus me-2"></i>Assign Selected Subjects
                                        </button>
                                    </div>
                                @endif
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">All available subjects are already assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Info Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="badge badge-gradient-warning rounded-circle me-3"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $teacher->name }}</h5>
                                <p class="text-muted mb-0">{{ $teacher->email }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Joined {{ $teacher->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-gradient-success">Active Teacher</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
            const selectedCountSpan = document.getElementById('selectedCount');
            const assignBtn = document.getElementById('assignBtn');

            function updateSelectedCount() {
                const checkedCount = document.querySelectorAll('.subject-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                assignBtn.disabled = checkedCount === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                subjectCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            subjectCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();

                    // Update select all checkbox state
                    const totalCheckboxes = subjectCheckboxes.length;
                    const checkedCheckboxes = document.querySelectorAll('.subject-checkbox:checked')
                        .length;

                    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
                    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes <
                        totalCheckboxes;
                });
            });

            // Initialize
            updateSelectedCount();
        });
    </script>
@endsection
