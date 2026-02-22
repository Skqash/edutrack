@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-user-plus me-2"></i>Add Student to Class</h2>
                    <a href="{{ route('teacher.classes') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Classes
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Manual Addition Form -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Manual Student Entry</h5>
                    </div>
                    <div class="card-body">
                        <form id="manualStudentForm" method="POST" action="{{ route('teacher.students.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="class_id" class="form-label">Class<span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" id="class_id"
                                    name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach ($myClasses as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->level }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Student Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Enter full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="student@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="admission_number" class="form-label">Admission Number</label>
                                <input type="text" class="form-control @error('admission_number') is-invalid @enderror"
                                    id="admission_number" name="admission_number" placeholder="e.g., ADM-2024-001">
                                @error('admission_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="roll_number" class="form-label">Roll Number</label>
                                <input type="text" class="form-control @error('roll_number') is-invalid @enderror"
                                    id="roll_number" name="roll_number" placeholder="e.g., 01">
                                @error('roll_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>Add Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Excel Import Form -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Bulk Import (Excel)</h5>
                    </div>
                    <div class="card-body">
                        <form id="excelImportForm" method="POST" action="{{ route('teacher.students.import') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="class_id_excel" class="form-label">Class<span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" id="class_id_excel"
                                    name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach ($myClasses as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->name }} ({{ $class->level }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="excel_file" class="form-label">Excel File (.xlsx)<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('excel_file') is-invalid @enderror"
                                    id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                <small class="text-muted d-block mt-2">
                                    <strong>Required columns:</strong> Name, Email, Admission Number (optional), Roll Number
                                    (optional)
                                </small>
                                @error('excel_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info mb-3">
                                <h6><i class="fas fa-info-circle me-2"></i>Excel File Format</h6>
                                <small>
                                    <p class="mb-1"><strong>Column A:</strong> Name (required)</p>
                                    <p class="mb-1"><strong>Column B:</strong> Email (required)</p>
                                    <p class="mb-1"><strong>Column C:</strong> Admission Number (optional)</p>
                                    <p class="mb-0"><strong>Column D:</strong> Roll Number (optional)</p>
                                </small>
                            </div>

                            <a href="{{ asset('templates/students_import_template.xlsx') }}"
                                class="btn btn-sm btn-secondary mb-3 w-100">
                                <i class="fas fa-download me-2"></i>Download Template
                            </a>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-upload me-2"></i>Import Students
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Added Students -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Class Students</h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3" style="width: 15%;"><i
                                                    class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                            <th style="width: 25%;"><i class="fas fa-user me-1 text-primary"></i>Name</th>
                                            <th style="width: 20%;"><i class="fas fa-envelope me-1 text-primary"></i>Email
                                            </th>
                                            <th style="width: 15%;"><i class="fas fa-file me-1 text-primary"></i>Admission
                                                #</th>
                                            <th style="width: 10%;"><i class="fas fa-list me-1 text-primary"></i>Roll #
                                            </th>
                                            <th style="width: 10%;"><i
                                                    class="fas fa-check-circle me-1 text-primary"></i>Status</th>
                                            <th style="width: 5%;" class="text-center"><i
                                                    class="fas fa-trash me-1 text-primary"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td class="ps-3">
                                                    <span class="badge bg-info text-white fw-bold"
                                                        style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $student->student_id ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $student->user->name ?? 'N/A' }}</strong>
                                                <td>{{ $student->user->email ?? 'N/A' }}</td>
                                                <td>{{ $student->admission_number ?? '-' }}</td>
                                                <td>{{ $student->roll_number ?? '-' }}</td>
                                                <td>
                                                    <span class="badge"
                                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        {{ ucfirst($student->status ?? 'Active') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="javascript:void(0)" class="btn btn-outline-secondary"
                                                            title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger"
                                                            title="Remove" onclick="confirmRemove({{ $student->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-2 opacity-50"></i>
                                <p>No students added yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmRemove(studentId) {
            if (confirm('Are you sure you want to remove this student from the class?')) {
                // Implement removal logic
                console.log('Remove student:', studentId);
            }
        }
    </script>
@endsection
