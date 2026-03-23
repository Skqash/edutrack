@extends('layouts.super')

@section('content')
    <style>
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .student-table-actions {
            gap: 4px;
        }

        .management-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .tab-content-panel {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .confirmation-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 4px;
            margin: 1rem 0;
        }

        .confirmation-notice i {
            color: #ff9800;
        }
    </style>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-0">{{ $class->class_name }}</h1>
                    <small class="text-muted">
                        Year {{ $class->year }} - Section {{ strtoupper($class->section ?? 'N/A') }}
                        @if ($class->school_year)
                            · School Year: {{ $class->school_year }}
                        @endif
                    </small>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('super.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('super.classes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Enrolled Students</h6>
                            <h3 class="mb-0">{{ $totalStudentsEnrolled ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Max Capacity</h6>
                            <h3 class="mb-0">{{ $class->capacity ?? 50 }}</h3>
                        </div>
                        <i class="fas fa-door-open fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Semester</h6>
                            <h3 class="mb-0">{{ $class->semester ?? 1 }}</h3>
                        </div>
                        <i class="fas fa-calendar fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">School Year</h6>
                            <h5 class="mb-0">{{ $class->school_year ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Management Tabs -->
    <div class="card border-0 shadow-sm">
        <ul class="nav nav-tabs management-tabs card-header bg-white border-0" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="student-list-tab" data-bs-toggle="tab"
                    data-bs-target="#student-list-content" type="button" role="tab"
                    aria-controls="student-list-content" aria-selected="true">
                    <i class="fas fa-list me-2"></i>Student List
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="add-manually-tab" data-bs-toggle="tab"
                    data-bs-target="#add-manually-content" type="button" role="tab"
                    aria-controls="add-manually-content" aria-selected="false">
                    <i class="fas fa-user-plus me-2"></i>Add Manually
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="bulk-import-tab" data-bs-toggle="tab"
                    data-bs-target="#bulk-import-content" type="button" role="tab" aria-controls="bulk-import-content"
                    aria-selected="false">
                    <i class="fas fa-file-excel me-2"></i>Bulk Import
                </button>
            </li>
        </ul>

        <!-- Tab 1: Student List -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="student-list-content" role="tabpanel"
                aria-labelledby="student-list-tab">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Students in This Class
                            <span class="badge bg-light text-primary">{{ $totalStudentsEnrolled ?? 0 }} students</span>
                        </h5>
                    </div>

                    @if ($totalStudentsEnrolled > 0)
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-sm" id="studentSearch"
                                placeholder="Search by name, ID, or email..." style="max-width: 300px;">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0" id="studentTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width: 12%;">
                                            <i class="fas fa-id-card me-1"></i>Student ID
                                        </th>
                                        <th style="width: 25%;">
                                            <i class="fas fa-user me-1"></i>Name
                                        </th>
                                        <th class="d-none d-md-table-cell" style="width: 20%;">
                                            <i class="fas fa-envelope me-1"></i>Email
                                        </th>
                                        <th class="d-none d-lg-table-cell" style="width: 15%;">
                                            <i class="fas fa-phone me-1"></i>Phone
                                        </th>
                                        <th class="d-none d-xl-table-cell" style="width: 15%;">
                                            <i class="fas fa-book me-1"></i>Course
                                        </th>
                                        <th class="text-center" style="width: 10%;">
                                            <i class="fas fa-cog me-1"></i>Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $student)
                                        <tr class="student-row" data-name="{{ $student->user->name }}"
                                            data-id="{{ $student->student_id }}"
                                            data-email="{{ $student->user->email }}">
                                            <td class="ps-3">
                                                <span class="badge bg-info text-white fw-bold"
                                                    style="font-size: 0.85rem;">{{ $student->student_id ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $student->user->name ?? $student->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $student->status ?? 'Active' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small>{{ $student->user->email ?? 'N/A' }}</small>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <small>{{ $student->user->phone ?? 'N/A' }}</small>
                                            </td>
                                            <td class="d-none d-xl-table-cell">
                                                <small>{{ $student->class->course->program_name ?? 'N/A' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm student-table-actions" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        title="Remove from class"
                                                        onclick="confirmRemoveStudent({{ $student->id }}, '{{ $student->user->name }}')">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            No students enrolled in this class yet. Use the tabs above to add students.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab 2: Add Manually -->
            <div class="tab-pane fade" id="add-manually-content" role="tabpanel" aria-labelledby="add-manually-tab">
                <div class="card-body">
                    <h5 class="form-section-title">
                        <i class="fas fa-user-plus me-2"></i>Add Student Manually
                    </h5>

                    <div class="confirmation-notice">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> The student will be added to this class. If they are already in
                        another class, you must first remove them from that class.
                    </div>

                    <form id="addStudentForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="studentSelect" class="form-label fw-bold">
                                    <i class="fas fa-search me-1"></i>Select Student
                                </label>
                                <select class="form-select form-select-lg" id="studentSelect" name="student_id" required>
                                    <option value="">-- Choose a student --</option>
                                    @php
                                        $allStudents = \App\Models\Student::where('class_id', null)
                                            ->orWhere('class_id', $class->id)
                                            ->with('user')
                                            ->orderBy('student_id')
                                            ->get();
                                    @endphp
                                    @foreach ($allStudents as $student)
                                        <option value="{{ $student->id }}"
                                            {{ $student->class_id == $class->id ? 'disabled' : '' }}>
                                            {{ $student->student_id }} - {{ $student->user->name }}
                                            @if ($student->class_id && $student->class_id !== $class->id)
                                                (In another class)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a student.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-check me-1"></i>Confirmation
                                </label>
                                <div class="form-check ms-0">
                                    <input class="form-check-input" type="checkbox" id="confirmAdd" required>
                                    <label class="form-check-label" for="confirmAdd">
                                        I confirm to add this student to <strong>{{ $class->class_name }}</strong>
                                    </label>
                                    <div class="invalid-feedback d-block">
                                        Please confirm before adding the student.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Add Student
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-redo me-2"></i>Clear
                            </button>
                        </div>
                    </form>

                    <div id="addStudentResponse" class="mt-3"></div>
                </div>
            </div>

            <!-- Tab 3: Bulk Excel Import -->
            <div class="tab-pane fade" id="bulk-import-content" role="tabpanel" aria-labelledby="bulk-import-tab">
                <div class="card-body">
                    <h5 class="form-section-title">
                        <i class="fas fa-file-csv me-2"></i>Bulk Import Students from CSV
                    </h5>

                    <div class="confirmation-notice">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Please prepare a CSV (.csv) file with student IDs. The file should have
                        a header row in the first line, and student IDs in the following rows.
                    </div>

                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-info-circle me-2"></i>File Format Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Column A:</strong> Student ID (required) - use header like "Student ID", "Student
                                Id", or "ID"</li>
                            <li><strong>File type:</strong> CSV (.csv) - Plain text file with comma-separated values</li>
                            <li><strong>Max file size:</strong> 2MB</li>
                            <li><strong>Example CSV content (open in any text editor):</strong><br><code
                                    style="background: #f5f5f5; padding: 8px; display: inline-block; margin-top: 5px;">Student
                                    ID<br>STU001<br>STU002<br>STU003</code></li>
                            <li><strong>How to convert Excel to CSV:</strong> Open your Excel file → File → Save As → Select
                                format "CSV (Comma delimited)" → Save</li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form id="importExcelForm" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="excelFile" class="form-label fw-bold">
                                    <i class="fas fa-upload me-1"></i>Select CSV File
                                </label>
                                <input type="file" class="form-control form-control-lg" id="excelFile"
                                    name="excel_file" accept=".csv,.txt" required>
                                <small class="form-text text-muted">
                                    Supported format: .csv or .txt (max 2MB)</small>
                                <div class="invalid-feedback d-block">
                                    Please select a valid CSV file.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-check me-1"></i>Confirmation
                                </label>
                                <div class="form-check ms-0">
                                    <input class="form-check-input" type="checkbox" id="confirmImport" required>
                                    <label class="form-check-label" for="confirmImport">
                                        I confirm to import students from the selected CSV file to
                                        <strong>{{ $class->class_name }}</strong>
                                    </label>
                                    <div class="invalid-feedback d-block">
                                        Please confirm before importing students.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="confirm" id="confirmValue">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-upload me-2"></i>Import Students
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-redo me-2"></i>Clear
                            </button>
                        </div>
                    </form>

                    <div id="importExcelResponse" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Student search functionality
        document.getElementById('studentSearch')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.student-row');
            rows.forEach(row => {
                const text = (row.dataset.name + ' ' + row.dataset.id + ' ' + row.dataset.email)
                    .toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Add student manually
        document.getElementById('addStudentForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            const responseDiv = document.getElementById('addStudentResponse');

            try {
                const response = await fetch('{{ route('super.classes.add-student-manually', $class->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    responseDiv.innerHTML =
                        `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    responseDiv.innerHTML =
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                }
            } catch (error) {
                responseDiv.innerHTML =
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>Error: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            }
        });

        // Bulk import Excel
        document.getElementById('importExcelForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            formData.set('confirm', 'yes');
            const responseDiv = document.getElementById('importExcelResponse');

            //Show loading
            responseDiv.innerHTML =
                `<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Importing...</span></div>`;

            try {
                const response = await fetch('{{ route('super.classes.import-excel', $class->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    let errorsHtml = '';
                    if (data.errors && data.errors.length > 0) {
                        errorsHtml =
                            `<div class="alert alert-warning mt-3"><strong>Warnings (${data.error_count}):</strong><ul class="mb-0 mt-2">`;
                        data.errors.forEach(err => {
                            errorsHtml += `<li>${err}</li>`;
                        });
                        errorsHtml += '</ul></div>';
                    }

                    responseDiv.innerHTML =
                        `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> ${data.message} (${data.added} students added)
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>${errorsHtml}`;
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    responseDiv.innerHTML =
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i>${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                }
            } catch (error) {
                responseDiv.innerHTML =
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>Error: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            }
        });

        // Remove student confirmation
        function confirmRemoveStudent(studentId, studentName) {
            if (confirm(`Are you sure you want to remove "${studentName}" from this class?`)) {
                removeStudent(studentId);
            }
        }

        // Remove student
        async function removeStudent(studentId) {
            try {
                const response = await fetch('{{ route('super.classes.remove-student', $class->id) }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        student_id: studentId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Student removed successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error removing student: ' + error.message);
            }
        }
    </script>
@endsection
