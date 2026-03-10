@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-edit"></i>
            </span>
            Edit Degree Program
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Academic Programs</a></li>
                <li class="breadcrumb-item active">Edit Program</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-university me-2 text-primary"></i>Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="program_code" class="form-label fw-bold">
                                        <i class="fas fa-barcode text-primary"></i> Program Code
                                    </label>
                                    <input type="text" class="form-control @error('program_code') is-invalid @enderror" 
                                           id="program_code" name="program_code" 
                                           placeholder="e.g., BSIT, BEED, BS-Agri" 
                                           value="{{ old('program_code', $course->program_code) }}" required>
                                    <div class="form-text">Standard university program code (e.g., BSIT, BSCS, BEED)</div>
                                    @error('program_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration" class="form-label fw-bold">
                                        <i class="fas fa-clock text-primary"></i> Program Duration
                                    </label>
                                    <select class="form-select @error('duration') is-invalid @enderror" 
                                            id="duration" name="duration" required>
                                        <option value="">Select duration...</option>
                                        <option value="1 Year" {{ old('duration', $course->duration) == '1 Year' ? 'selected' : '' }}>1 Year</option>
                                        <option value="2 Years" {{ old('duration', $course->duration) == '2 Years' ? 'selected' : '' }}>2 Years</option>
                                        <option value="3 Years" {{ old('duration', $course->duration) == '3 Years' ? 'selected' : '' }}>3 Years</option>
                                        <option value="4 Years" {{ old('duration', $course->duration) == '4 Years' ? 'selected' : '' }}>4 Years</option>
                                        <option value="5 Years" {{ old('duration', $course->duration) == '5 Years' ? 'selected' : '' }}>5 Years</option>
                                    </select>
                                    @error('duration')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="program_name" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-primary"></i> Program Name
                            </label>
                            <input type="text" class="form-control @error('program_name') is-invalid @enderror" 
                                   id="program_name" name="program_name" 
                                   placeholder="e.g., Bachelor of Science in Information Technology" 
                                   value="{{ old('program_name', $course->program_name) }}" required>
                            <div class="form-text">Full degree program name</div>
                            @error('program_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="college" class="form-label fw-bold">
                                        <i class="fas fa-building text-primary"></i> College
                                    </label>
                                    <select class="form-select @error('college') is-invalid @enderror" 
                                            id="college" name="college" required>
                                        <option value="">Select college...</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college }}" {{ old('college', $course->college) == $college ? 'selected' : '' }}>
                                                {{ $college }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="department" class="form-label fw-bold">
                                        <i class="fas fa-sitemap text-primary"></i> Department
                                    </label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" 
                                           placeholder="e.g., Department of Computer Science" 
                                           value="{{ old('department', $course->department) }}">
                                    <div class="form-text">Optional: Specific department within the college</div>
                                    @error('department')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left text-primary"></i> Program Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Describe the degree program, objectives, and career opportunities...">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_students" class="form-label fw-bold">
                                        <i class="fas fa-users text-primary"></i> Maximum Students
                                    </label>
                                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                           id="max_students" name="max_students" 
                                           placeholder="50" min="1" max="500" 
                                           value="{{ old('max_students', $course->max_students) ?? 50 }}">
                                    @error('max_students')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on text-primary"></i> Status
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="Active" {{ old('status', $course->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $course->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="head_id" class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-primary"></i> Program Head
                                    </label>
                                    <select class="form-select @error('head_id') is-invalid @enderror" 
                                            id="head_id" name="head_id">
                                        <option value="">Select program head...</option>
                                        @foreach($heads as $head)
                                            <option value="{{ $head->id }}" {{ old('head_id', $course->head_id) == $head->id ? 'selected' : '' }}>
                                                {{ $head->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Optional: Assigned program head</div>
                                    @error('head_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Programs
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Program
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Program Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Current Program Details</h6>
                        <div class="small">
                            <div class="mb-2"><strong>Code:</strong> {{ $course->program_code }}</div>
                            <div class="mb-2"><strong>Name:</strong> {{ $course->program_name }}</div>
                            <div class="mb-2"><strong>College:</strong> {{ $course->college }}</div>
                            <div class="mb-2"><strong>Students:</strong> {{ $course->current_students ?? 0 }} / {{ $course->max_students ?? 50 }}</div>
                            <div class="mb-2"><strong>Status:</strong> {{ $course->status }}</div>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-2"></i>View Program Details
                        </a>
                        <a href="{{ route('admin.courses.manageSubjects', $course) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-book me-2"></i>Manage Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                                                                                                    
