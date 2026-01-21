@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Add New Course
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.courses.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="course_code" class="form-label"><i class="fas fa-barcode"></i> Course Code</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" id="course_code" 
                                   name="course_code" placeholder="e.g., CS101" value="{{ old('course_code') }}" required>
                            @error('course_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_name" class="form-label"><i class="fas fa-book"></i> Course Name</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" 
                                   name="course_name" placeholder="Enter course name" value="{{ old('course_name') }}" required>
                            @error('course_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="instructor_id" class="form-label"><i class="fas fa-user-tie"></i> Instructor</label>
                            <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                                <option value="">Select an instructor...</option>
                                @foreach(\App\Models\User::where('role', 'teacher')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('instructor_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="credit_hours" class="form-label"><i class="fas fa-clock"></i> Credit Hours</label>
                            <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" id="credit_hours" 
                                   name="credit_hours" placeholder="e.g., 3" value="{{ old('credit_hours') }}" min="1" required>
                            @error('credit_hours')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                      name="description" rows="4" placeholder="Enter course description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-success">
                                <i class="fas fa-save"></i> Add Course
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Course Guidelines</h5>
                    <ul class="small">
                        <li>Course code must be unique</li>
                        <li>Select an instructor who will teach this course</li>
                        <li>Credit hours should be between 1-6</li>
                        <li>Description helps students understand course content</li>
                        <li>Only active courses appear in student enrollment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-success {
        background: linear-gradient(45deg, #27ae60, #2ecc71);
        color: white;
        border: none;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(45deg, #229954, #27ae60);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #27ae60;
        box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
    }
</style>
@endsection
