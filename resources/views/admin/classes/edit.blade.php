@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-edit"></i>
            </span>
            Edit Class
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="class_name" class="form-label"><i class="fas fa-door-open"></i> Class Name</label>
                            <input type="text" class="form-control @error('class_name') is-invalid @enderror" id="class_name" 
                                   name="class_name" value="{{ $class->class_name }}" required>
                            @error('class_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="class_level" class="form-label"><i class="fas fa-layer-group"></i> Class Level</label>
                            <input type="number" class="form-control @error('class_level') is-invalid @enderror" id="class_level" 
                                   name="class_level" value="{{ $class->class_level }}" required>
                            @error('class_level')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="section" class="form-label"><i class="fas fa-tag"></i> Section</label>
                            <input type="text" class="form-control @error('section') is-invalid @enderror" id="section" 
                                   name="section" value="{{ $class->section }}" required>
                            @error('section')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="capacity" class="form-label"><i class="fas fa-users"></i> Capacity</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" 
                                   name="capacity" value="{{ $class->capacity }}" min="10" required>
                            @error('capacity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="teacher_id" class="form-label"><i class="fas fa-chalkboard-user"></i> Class Teacher</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                                <option value="">Select a teacher...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                      name="description" rows="4">{{ $class->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Active" {{ $class->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $class->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Update Class
                            </button>
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">
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
