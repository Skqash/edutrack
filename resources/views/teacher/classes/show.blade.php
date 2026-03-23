@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-0">{{ $class->class_name }}</h1>
                    <small class="text-muted">{{ $class->class_level }} · Section: {{ $class->section ?? 'N/A' }}</small>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('teacher.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteClassModal">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Info Cards -->
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Students</h6>
                            <h3 class="mb-0">{{ $class->students->count() }}</h3>
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
                            <h6 class="text-white-50 mb-1">Total Students</h6>
                            <h3 class="mb-0">{{ $class->total_students }}</h3>
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
                            <h6 class="text-white-50 mb-1">Course</h6>
                            <h5 class="mb-0">{{ $class->course->course_name ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-book fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Status</h6>
                            <h5 class="mb-0">
                                <span class="badge bg-light text-dark">
                                    {{ strtolower($class->status) === 'active' ? 'Active' : ($class->status ?? 'Active') }}
                                </span>
                            </h5>
                        </div>
                        <i class="fas fa-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i> Students in This Class
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-primary">{{ $class->students->count() }} students</span>
                        <button type="button" class="btn btn-sm btn-light text-primary fw-bold" data-bs-toggle="modal"
                            data-bs-target="#addStudentToClassModal">
                            <i class="fas fa-user-plus me-1"></i> Add Student
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($class->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width: 15%;"><i
                                                class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                        <th style="width: 30%;"><i class="fas fa-user me-1 text-primary"></i>Name</th>
                                        <th class="d-none d-md-table-cell" style="width: 20%;"><i
                                                class="fas fa-building me-1 text-primary"></i>Year / Section</th>
                                        <th class="d-none d-md-table-cell" style="width: 20%;"><i
                                                class="fas fa-envelope me-1 text-primary"></i>Email</th>
                                        <th class="d-none d-lg-table-cell" style="width: 15%;"><i
                                                class="fas fa-phone me-1 text-primary"></i>Phone</th>
                                        <th class="text-center" style="width: 12%;"><i
                                                class="fas fa-cog me-1 text-primary"></i>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $student)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge bg-info text-white fw-bold"
                                                    style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $student->student_id ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $student->status ?? 'Active' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small><strong>Year {{ $student->year }}</strong>, Section
                                                    <strong>{{ $student->section }}</strong></small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small>{{ $student->email ?? 'N/A' }}</small>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <small>{{ $student->phone ?? 'N/A' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info"
                                                        title="View Student">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Enter Grade">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                            <p>No students enrolled in this class yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('teacher.students.index', $class->id) }}" class="btn btn-outline-info">
                    <i class="fas fa-graduation-cap me-2"></i> Manage Students
                </a>
                <a href="{{ route('teacher.grades.content', $class->id) }}?term=midterm" class="btn btn-primary">
                    <i class="fas fa-star me-2"></i> Enter Grades for This Class
                </a>
                <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-primary">
                    <i class="fas fa-check-square me-2"></i> Manage Attendance
                </a>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#addStudentToClassModal">
                    <i class="fas fa-user-plus me-2"></i> Add Student
                </button>
            </div>
        </div>
    </div>

    <!-- Add Student Modal for This Class -->
    <div class="modal fade" id="addStudentToClassModal" tabindex="-1" aria-labelledby="addStudentToClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient text-white border-0"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title fw-bold" id="addStudentToClassModalLabel">
                        <i class="fas fa-user-plus me-2"></i> Add Student to {{ $class->class_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs border-bottom" id="classAddStudentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="class-manual-tab" data-bs-toggle="tab"
                                data-bs-target="#class-manual" type="button" role="tab"
                                aria-controls="class-manual" aria-selected="true">
                                <i class="fas fa-keyboard me-2"></i> Manual Entry
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="class-excel-tab" data-bs-toggle="tab"
                                data-bs-target="#class-excel" type="button" role="tab" aria-controls="class-excel"
                                aria-selected="false">
                                <i class="fas fa-file-excel me-2"></i> Excel Import
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="classAddStudentTabContent">
                        <!-- Manual Entry Tab -->
                        <div class="tab-pane fade show active" id="class-manual" role="tabpanel"
                            aria-labelledby="class-manual-tab">
                            <form id="classManualStudentForm" method="POST"
                                action="{{ route('teacher.students.store') }}">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $class->id }}">

                                <div class="mb-3">
                                    <label for="class_manual_name" class="form-label fw-bold">Student Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        id="class_manual_name" name="name" placeholder="e.g., John Doe" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="class_manual_email" class="form-label fw-bold">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="class_manual_email" name="email" placeholder="e.g., john@example.com"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="class_manual_year" class="form-label fw-bold">Year <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg @error('year') is-invalid @enderror"
                                            id="class_manual_year" name="year" required>
                                            <option value="">-- Select Year --</option>
                                            <option value="1">1st Year</option>
                                            <option value="2">2nd Year</option>
                                            <option value="3">3rd Year</option>
                                            <option value="4">4th Year</option>
                                        </select>
                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="class_manual_section" class="form-label fw-bold">Section <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg @error('section') is-invalid @enderror"
                                            id="class_manual_section" name="section" required>
                                            <option value="">-- Select Section --</option>
                                            <option value="A">Section A</option>
                                            <option value="B">Section B</option>
                                            <option value="C">Section C</option>
                                            <option value="D">Section D</option>
                                            <option value="E">Section E</option>
                                        </select>
                                        @error('section')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Password:</strong> A temporary password will be generated automatically. Student
                                    can reset it on first login.
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-lg fw-bold text-white"
                                        style="background-color: #667eea; border: none;">
                                        <i class="fas fa-save me-2"></i> Add Student to {{ $class->class_name }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Excel Import Tab -->
                        <div class="tab-pane fade" id="class-excel" role="tabpanel" aria-labelledby="class-excel-tab">
                            <form id="classExcelImportForm" method="POST"
                                action="{{ route('teacher.students.import') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $class->id }}">

                                <div class="mb-3">
                                    <label for="class_excel_file" class="form-label fw-bold">Excel File (.xlsx) <span
                                            class="text-danger">*</span></label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('excel_file') is-invalid @enderror"
                                        id="class_excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                    @error('excel_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- File Format Instructions -->
                                <div class="alert alert-info border-0 mb-3">
                                    <h6 class="alert-heading fw-bold">
                                        <i class="fas fa-file-excel me-2"></i> Excel File Format
                                    </h6>
                                    <hr class="my-2">
                                    <p class="mb-2 small"><strong>Required Columns:</strong></p>
                                    <ul class="small mb-0">
                                        <li><strong>Column A - Name:</strong> Student full name (required)</li>
                                        <li><strong>Column B - Email:</strong> Valid email address (required)</li>
                                        <li><strong>Column C - Year:</strong> Academic year 1-4 (required)</li>
                                        <li><strong>Column D - Section:</strong> Section A-E (required)</li>
                                    </ul>
                                </div>

                                <!-- Example Data -->
                                <div class="alert alert-light border mb-3">
                                    <h6 class="fw-bold mb-2">Example:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Year</th>
                                                    <th>Section</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>john@example.com</td>
                                                    <td>1</td>
                                                    <td>A</td>
                                                </tr>
                                                <tr>
                                                    <td>Jane Smith</td>
                                                    <td>jane@example.com</td>
                                                    <td>1</td>
                                                    <td>B</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ asset('templates/students_import_template.xlsx') }}"
                                        class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download me-2"></i> Download Template
                                    </a>
                                    <button type="submit" class="btn btn-lg fw-bold text-white"
                                        style="background-color: #667eea; border: none;">
                                        <i class="fas fa-upload me-2"></i> Import Students to {{ $class->class_name }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Class Modal -->
    <div class="modal fade" id="deleteClassModal" tabindex="-1" role="dialog" aria-labelledby="deleteClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white border-danger">
                    <h5 class="modal-title" id="deleteClassModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Class
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-3">
                        <strong>⚠️ Warning!</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete the class <strong>{{ $class->class_name }}</strong>?</p>
                    <p class="text-muted small mb-0">
                        This will remove all associated grade entries for enrolled students.
                        Please ensure you have backed up any important data.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('teacher.classes.destroy', $class->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete Class
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function removeStudent(studentId) {
            if (!confirm('Remove this student from the class?')) {
                return;
            }

            const urlTemplate =
                "{{ route('teacher.classes.students.remove', ['classId' => $class->id, 'studentId' => '__STUDENT_ID__']) }}";
            const url = urlTemplate.replace('__STUDENT_ID__', studentId);

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to remove student');
                    }
                })
                .catch(() => {
                    alert('Failed to remove student');
                });
        }
    </script>
@endsection
