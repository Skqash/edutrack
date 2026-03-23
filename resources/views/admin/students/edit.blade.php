@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-user-edit"></i>
            </span>
            Edit Student
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label"><i class="fas fa-user"></i> First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" 
                                           name="first_name" placeholder="Enter first name" value="{{ old('first_name', $student->first_name) }}" required>
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="middle_name" class="form-label"><i class="fas fa-user"></i> Middle Name (Optional)</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" 
                                           name="middle_name" placeholder="Enter middle name" value="{{ old('middle_name', $student->middle_name) }}">
                                    @error('middle_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label"><i class="fas fa-user"></i> Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" 
                                           name="last_name" placeholder="Enter last name" value="{{ old('last_name', $student->last_name) }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label for="suffix" class="form-label"><i class="fas fa-tag"></i> Suffix</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror" id="suffix" 
                                           name="suffix" placeholder="Jr." value="{{ old('suffix', $student->suffix) }}">
                                    @error('suffix')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                                   name="email" placeholder="student@example.com" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="student_id" class="form-label"><i class="fas fa-id-badge"></i> Student ID</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" id="student_id" 
                                   name="student_id" value="{{ old('student_id', $student->student_id) }}" required>
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_id" class="form-label"><i class="fas fa-graduation-cap"></i> Course/Program</label>
                            <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                                <option value="">Select a course...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->program_name }} ({{ $course->program_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="year" class="form-label"><i class="fas fa-calendar"></i> Year Level</label>
                                    <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required>
                                        <option value="">Select year...</option>
                                        <option value="1" {{ old('year', $student->year) == '1' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2" {{ old('year', $student->year) == '2' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3" {{ old('year', $student->year) == '3' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4" {{ old('year', $student->year) == '4' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                    @error('year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section" class="form-label"><i class="fas fa-users"></i> Section</label>
                                    <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                                        <option value="">Select section...</option>
                                        <option value="A" {{ old('section', $student->section) == 'A' ? 'selected' : '' }}>Section A</option>
                                        <option value="B" {{ old('section', $student->section) == 'B' ? 'selected' : '' }}>Section B</option>
                                        <option value="C" {{ old('section', $student->section) == 'C' ? 'selected' : '' }}>Section C</option>
                                        <option value="D" {{ old('section', $student->section) == 'D' ? 'selected' : '' }}>Section D</option>
                                        <option value="E" {{ old('section', $student->section) == 'E' ? 'selected' : '' }}>Section E</option>
                                    </select>
                                    @error('section')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Active" {{ old('status', $student->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $student->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($student->campus)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This student is assigned to <strong>{{ $student->campus }}</strong> campus.
                        </div>
                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Update Student
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Student Information</h5>
                    <ul class="small list-unstyled">
                        <li><strong>Student ID:</strong> {{ $student->student_id }}</li>
                        <li><strong>Email:</strong> {{ $student->email }}</li>
                        <li><strong>Course:</strong> {{ $student->course->program_name ?? 'Not assigned' }}</li>
                        <li><strong>Year & Section:</strong> {{ $student->year }}{{ $student->section ?? '' }}</li>
                        <li><strong>Campus:</strong> {{ $student->campus ?? 'Not assigned' }}</li>
                        <li><strong>Created:</strong> {{ $student->created_at->format('d M Y') }}</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Delete this student permanently</p>
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('This action cannot be undone. Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash"></i> Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #2970cc, #4099ff);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4099ff;
        box-shadow: 0 0 0 0.2rem rgba(64, 153, 255, 0.25);
    }
</style>
@endsection
