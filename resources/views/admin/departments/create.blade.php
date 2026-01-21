@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="fas fa-plus-circle"></i>
        </span>
        Add New Department
    </h3>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="department_code" class="form-label"><i class="fas fa-barcode"></i> Department Code</label>
                        <input type="text" class="form-control @error('department_code') is-invalid @enderror" id="department_code" 
                               name="department_code" placeholder="e.g., CS" value="{{ old('department_code') }}" required>
                        @error('department_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="department_name" class="form-label"><i class="fas fa-building"></i> Department Name</label>
                        <input type="text" class="form-control @error('department_name') is-invalid @enderror" id="department_name" 
                               name="department_name" placeholder="e.g., Computer Science" value="{{ old('department_name') }}" required>
                        @error('department_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="head_id" class="form-label"><i class="fas fa-user-tie"></i> Department Head</label>
                        <select class="form-select @error('head_id') is-invalid @enderror" id="head_id" name="head_id">
                            <option value="">Select a head (Optional)...</option>
                            @foreach($heads as $head)
                                <option value="{{ $head->id }}" {{ old('head_id') == $head->id ? 'selected' : '' }}>
                                    {{ $head->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('head_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                  name="description" rows="4" placeholder="Enter department description">{{ old('description') }}</textarea>
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
                            <i class="fas fa-save"></i> Add Department
                        </button>
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
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

    @media (max-width: 768px) {
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@endsection
