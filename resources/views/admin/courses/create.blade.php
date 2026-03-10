@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Add New Degree Program
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Academic Programs</a></li>
                <li class="breadcrumb-item active">Add Program</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-university me-2 text-success"></i>Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="program_code" class="form-label fw-bold">
                                        <i class="fas fa-barcode text-success"></i> Program Code
                                    </label>
                                    <input type="text" class="form-control @error('program_code') is-invalid @enderror" 
                                           id="program_code" name="program_code" 
                                           placeholder="e.g., BSIT, BEED, BS-Agri" 
                                           value="{{ old('program_code') }}" required>
                                    <div class="form-text">Standard university program code (e.g., BSIT, BSCS, BEED)</div>
                                    @error('program_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration" class="form-label fw-bold">
                                        <i class="fas fa-clock text-success"></i> Program Duration
                                    </label>
                                    <select class="form-select @error('duration') is-invalid @enderror" 
                                            id="duration" name="duration" required>
                                        <option value="">Select duration...</option>
                                        <option value="1 Year" {{ old('duration') == '1 Year' ? 'selected' : '' }}>1 Year</option>
                                        <option value="2 Years" {{ old('duration') == '2 Years' ? 'selected' : '' }}>2 Years</option>
                                        <option value="3 Years" {{ old('duration') == '3 Years' ? 'selected' : '' }}>3 Years</option>
                                        <option value="4 Years" {{ old('duration') == '4 Years' ? 'selected' : '' }}>4 Years</option>
                                        <option value="5 Years" {{ old('duration') == '5 Years' ? 'selected' : '' }}>5 Years</option>
                                    </select>
                                    @error('duration')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="program_name" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-success"></i> Program Name
                            </label>
                            <input type="text" class="form-control @error('program_name') is-invalid @enderror" 
                                   id="program_name" name="program_name" 
                                   placeholder="e.g., Bachelor of Science in Information Technology" 
                                   value="{{ old('program_name') }}" required>
                            <div class="form-text">Full degree program name</div>
                            @error('program_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="college" class="form-label fw-bold">
                                        <i class="fas fa-building text-success"></i> College
                                    </label>
                                    <select class="form-select @error('college') is-invalid @enderror" 
                                            id="college" name="college" required>
                                        <option value="">Select college...</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college }}" {{ old('college') == $college ? 'selected' : '' }}>
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
                                        <i class="fas fa-sitemap text-success"></i> Department
                                    </label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" 
                                           placeholder="e.g., Department of Computer Science" 
                                           value="{{ old('department') }}">
                                    <div class="form-text">Optional: Specific department within the college</div>
                                    @error('department')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left text-success"></i> Program Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Describe the degree program, objectives, and career opportunities...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_students" class="form-label fw-bold">
                                        <i class="fas fa-users text-success"></i> Maximum Students
                                    </label>
                                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                           id="max_students" name="max_students" 
                                           placeholder="50" min="1" max="500" 
                                           value="{{ old('max_students') ?? 50 }}">
                                    @error('max_students')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on text-success"></i> Status
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="head_id" class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-success"></i> Program Head
                                    </label>
                                    <select class="form-select @error('head_id') is-invalid @enderror" 
                                            id="head_id" name="head_id">
                                        <option value="">Select program head...</option>
                                        @foreach($heads as $head)
                                            <option value="{{ $head->id }}" {{ old('head_id') == $head->id ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i> Create Program
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
                        <i class="fas fa-info-circle text-info me-2"></i>Program Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <ul class="mb-0 small">
                            <li>Use standard program codes (BSIT, BEED, BS-Agri)</li>
                            <li>Provide clear, descriptive program names</li>
                            <li>Assign appropriate college for organization</li>
                            <li>Set realistic student capacity limits</li>
                        </ul>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Common Program Codes</h6>
                    <div class="small">
                        <div class="mb-2"><strong>BSIT</strong> - Information Technology</div>
                        <div class="mb-2"><strong>BSCS</strong> - Computer Science</div>
                        <div class="mb-2"><strong>BEED</strong> - Elementary Education</div>
                        <div class="mb-2"><strong>BSED</strong> - Secondary Education</div>
                        <div class="mb-2"><strong>BS-Agri</strong> - Agriculture</div>
                        <div class="mb-2"><strong>BSBA</strong> - Business Administration</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                                
                                            
