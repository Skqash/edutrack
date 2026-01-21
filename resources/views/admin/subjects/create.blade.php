@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Add New Subject
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.subjects.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="subject_code" class="form-label"><i class="fas fa-barcode"></i> Subject Code</label>
                            <input type="text" class="form-control @error('subject_code') is-invalid @enderror" id="subject_code" 
                                   name="subject_code" placeholder="e.g., MATH101" value="{{ old('subject_code') }}" required>
                            @error('subject_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject_name" class="form-label"><i class="fas fa-book"></i> Subject Name</label>
                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror" id="subject_name" 
                                   name="subject_name" placeholder="Enter subject name" value="{{ old('subject_name') }}" required>
                            @error('subject_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_id" class="form-label"><i class="fas fa-book-open"></i> Course</label>
                            <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id">
                                <option value="">Select a course (Optional)...</option>
                                @foreach(\App\Models\Course::where('status', 'Active')->get() as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }} ({{ $course->course_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="credits" class="form-label"><i class="fas fa-star"></i> Credits</label>
                            <input type="number" class="form-control @error('credits') is-invalid @enderror" id="credits" 
                                   name="credits" placeholder="e.g., 4" value="{{ old('credits') }}" min="1" required>
                            @error('credits')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                      name="description" rows="4" placeholder="Enter subject description">{{ old('description') }}</textarea>
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
                                <i class="fas fa-save"></i> Add Subject
                            </button>
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Subject Guidelines</h5>
                    <ul class="small">
                        <li>Subject code must be unique</li>
                        <li>Optionally link to an existing course</li>
                        <li>Credits should reflect course load</li>
                        <li>Description helps students understand content</li>
                        <li>Only active subjects appear in course listings</li>
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
