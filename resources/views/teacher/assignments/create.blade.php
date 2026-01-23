@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">Create New Assignment</h1>
                <small class="text-muted">{{ $class->name }}</small>
            </div>
            <a href="{{ route('teacher.assignments.list', $class->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Form Errors</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Assignment Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.assignments.store', $class->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Assignment Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" placeholder="Enter assignment title" value="{{ old('title') }}" required>
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="4" placeholder="Enter assignment description" required>{{ old('description') }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea class="form-control @error('instructions') is-invalid @enderror" id="instructions" name="instructions"
                        rows="3" placeholder="Enter assignment instructions (optional)">{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror"
                                id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                            @error('due_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="max_score" class="form-label">Max Score <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_score') is-invalid @enderror"
                                id="max_score" name="max_score" placeholder="100" value="{{ old('max_score', 100) }}"
                                min="1" max="1000" required>
                            @error('max_score')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="term" class="form-label">Term <span class="text-danger">*</span></label>
                    <select class="form-select @error('term') is-invalid @enderror" id="term" name="term" required>
                        <option value="">-- Select Term --</option>
                        <option value="midterm" {{ old('term') === 'midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="final" {{ old('term') === 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                    @error('term')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('teacher.assignments.list', $class->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
