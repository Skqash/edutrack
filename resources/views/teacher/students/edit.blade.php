@extends('layouts.teacher')

@section('title', 'Edit Student')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 text-dark fw-bold">
                            <i class="fas fa-user-edit me-2 text-primary"></i>Edit Student
                        </h1>
                        <p class="text-muted mb-0">Update student information</p>
                    </div>
                    <a href="{{ route('teacher.students.index', $class->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Class
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 text-dark">Student Details</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle me-2"></i>Validation Errors</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('teacher.students.update', $student->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="class_id" value="{{ $class->id }}">

                            <!-- Student Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-user text-primary me-2"></i>Full Name
                                </label>
                                <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    value="{{ old('name', $student->name) }}" placeholder="John Doe" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-envelope text-primary me-2"></i>Email Address
                                </label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email', $student->email) }}" placeholder="john.doe@school.edu" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Year and Section Row -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="year" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>Grade Year
                                    </label>
                                    <x-searchable-dropdown
                                        name="year"
                                        id="year"
                                        placeholder="Select year level..."
                                        :options="[
                                            ['id' => '1', 'name' => 'Year 1', 'description' => 'First year students'],
                                            ['id' => '2', 'name' => 'Year 2', 'description' => 'Second year students'],
                                            ['id' => '3', 'name' => 'Year 3', 'description' => 'Third year students'],
                                            ['id' => '4', 'name' => 'Year 4', 'description' => 'Fourth year students']
                                        ]"
                                        :selected="old('year', $student->year)"
                                        required="true"
                                        class="form-select form-select-lg @error('year') is-invalid @enderror"
                                    />
                                    @error('year')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="section" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-layer-group text-primary me-2"></i>Section
                                    </label>
                                    <x-searchable-dropdown
                                        name="section"
                                        id="section"
                                        placeholder="Select section..."
                                        :options="[
                                            ['id' => 'A', 'name' => 'Section A', 'description' => 'Section A'],
                                            ['id' => 'B', 'name' => 'Section B', 'description' => 'Section B'],
                                            ['id' => 'C', 'name' => 'Section C', 'description' => 'Section C'],
                                            ['id' => 'D', 'name' => 'Section D', 'description' => 'Section D'],
                                            ['id' => 'E', 'name' => 'Section E', 'description' => 'Section E']
                                        ]"
                                        :selected="old('section', $student->section)"
                                        required="true"
                                        class="form-select form-select-lg @error('section') is-invalid @enderror"
                                    />
                                    @error('section')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-3 mt-5 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                    <i class="fas fa-save me-2"></i>Update Student
                                </button>
                                <a href="{{ route('teacher.students.index', $class->id) }}" class="btn btn-secondary btn-lg">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-info-circle text-info me-2"></i>Student Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Full Name</small>
                            <strong class="text-dark">{{ $student->name }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Student ID</small>
                            <code class="text-secondary">{{ $student->student_id ?? 'N/A' }}</code>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Enrolled Since</small>
                            <strong class="text-dark">{{ $student->created_at->format('M d, Y') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-book text-info me-2"></i>Class Assignment
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Class</small>
                            <strong class="text-dark">{{ $class->class_name }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Subject</small>
                            <strong class="text-dark">{{ $class->subject->name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Delete Student Section -->
                <div class="card shadow-sm border-danger bg-danger bg-opacity-5">
                    <div class="card-header bg-danger bg-opacity-10 border-danger">
                        <h6 class="mb-0 text-danger fw-semibold">
                            <i class="fas fa-trash text-danger me-2"></i>Danger Zone
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Once you delete this student record, there is no going back. Please be certain.
                        </p>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteStudentModal">
                            <i class="fas fa-trash me-2"></i>Delete Student
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Student Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white border-danger">
                    <h5 class="modal-title" id="deleteStudentModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Student
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-3">
                        <strong>⚠️ Warning!</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete <strong>{{ $student->name }}'s</strong> record completely from the system?</p>
                    <p class="text-muted small mb-3">
                        This will permanently remove:
                    </p>
                    <ul class="text-muted small mb-0">
                        <li>All grade entries for this student</li>
                        <li>All attendance records</li>
                        <li>All assessment data</li>
                        <li>Class enrollment</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
