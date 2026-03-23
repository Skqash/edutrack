@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Mark Attendance
        </h3>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.attendance.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="student_id" class="form-label"><i class="fas fa-user-graduate"></i> Student</label>
                            <select class="form-select @error('student_id') is-invalid @enderror" id="student_id"
                                name="student_id" required>
                                <option value="">Select a student...</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_id ?? 'N/A' }} → {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="class_id" class="form-label"><i class="fas fa-door-open"></i> Class</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id"
                                name="class_id" required>
                                <option value="">Select a class...</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="date" class="form-label"><i class="fas fa-calendar"></i> Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-check-square"></i> Attendance
                                Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="">Select status...</option>
                                <option value="Present" {{ old('status') == 'Present' ? 'selected' : '' }}>Present</option>
                                <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                <option value="Late" {{ old('status') == 'Late' ? 'selected' : '' }}>Late</option>
                                <option value="Leave" {{ old('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label"><i class="fas fa-notes-medical"></i> Notes
                                (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                placeholder="Add any notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-success">
                                <i class="fas fa-save"></i> Mark Attendance
                            </button>
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
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
