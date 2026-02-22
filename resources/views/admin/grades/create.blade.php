@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Add Grade
        </h3>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.grades.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="student_id" class="form-label"><i class="fas fa-user-graduate"></i> Student</label>
                            <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                                name="student_id" required>
                                <option value="">Select a student...</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_id ?? 'N/A' }} → {{ $student->user->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject_id" class="form-label"><i class="fas fa-book"></i> Subject</label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id"
                                name="subject_id" required>
                                <option value="">Select a subject...</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="marks_obtained" class="form-label"><i class="fas fa-star"></i> Marks
                                    Obtained</label>
                                <input type="number" class="form-control @error('marks_obtained') is-invalid @enderror"
                                    id="marks_obtained" name="marks_obtained" placeholder="e.g., 85"
                                    value="{{ old('marks_obtained') }}" step="0.01" required>
                                @error('marks_obtained')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="total_marks" class="form-label"><i class="fas fa-sum"></i> Total Marks</label>
                                <input type="number" class="form-control @error('total_marks') is-invalid @enderror"
                                    id="total_marks" name="total_marks" placeholder="e.g., 100"
                                    value="{{ old('total_marks') }}" step="0.01" required>
                                @error('total_marks')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="grade" class="form-label"><i class="fas fa-letter-a"></i> Grade</label>
                            <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade"
                                required>
                                <option value="">Select grade...</option>
                                <option value="A" {{ old('grade') == 'A' ? 'selected' : '' }}>A (90-100%)</option>
                                <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B (80-89%)</option>
                                <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C (70-79%)</option>
                                <option value="D" {{ old('grade') == 'D' ? 'selected' : '' }}>D (60-69%)</option>
                                <option value="F" {{ old('grade') == 'F' ? 'selected' : '' }}>F (Below 60%)</option>
                            </select>
                            @error('grade')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="semester" class="form-label"><i class="fas fa-clock"></i> Semester</label>
                                <select class="form-select @error('semester') is-invalid @enderror" id="semester"
                                    name="semester" required>
                                    <option value="">Select semester...</option>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                            Semester {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('semester')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="academic_year" class="form-label"><i class="fas fa-calendar-alt"></i> Academic
                                    Year</label>
                                <input type="text" class="form-control @error('academic_year') is-invalid @enderror"
                                    id="academic_year" name="academic_year" placeholder="e.g., 2023-24"
                                    value="{{ old('academic_year') }}" required>
                                @error('academic_year')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-success">
                                <i class="fas fa-save"></i> Add Grade
                            </button>
                            <a href="{{ route('admin.grades.index') }}" class="btn btn-outline-secondary">
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

            .form-row {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
@endsection
