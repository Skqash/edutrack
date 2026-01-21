@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-edit"></i>
            </span>
            Edit Course
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="course_code" class="form-label"><i class="fas fa-barcode"></i> Course Code</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" id="course_code" 
                                   name="course_code" value="{{ $course->course_code }}" required>
                            @error('course_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_name" class="form-label"><i class="fas fa-book"></i> Course Name</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" 
                                   name="course_name" value="{{ $course->course_name }}" required>
                            @error('course_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="instructor_id" class="form-label"><i class="fas fa-user-tie"></i> Instructor</label>
                            <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                                <option value="">Select an instructor...</option>
                                @foreach($instructors as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $course->instructor_id == $teacher->id ? 'selected' : '' }}>
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
                                   name="credit_hours" value="{{ $course->credit_hours }}" min="1" required>
                            @error('credit_hours')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                      name="description" rows="4">{{ $course->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Active" {{ $course->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $course->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Update Course
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #003d82);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endsection
