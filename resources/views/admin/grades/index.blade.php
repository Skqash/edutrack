@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-warning text-white me-2">
                    <i class="fas fa-chart-bar"></i>
                </span>
                Grades Management
            </h3>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.grades.create') }}" class="btn btn-gradient-success">
                <i class="fas fa-plus"></i> Add Grade
            </a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <!-- Desktop View -->
        <div class="card shadow-sm d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Grade</th>
                            <th>Semester</th>
                            <th>Year</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                            <tr>
                                <td><strong>{{ $grade->student->user->name ?? 'N/A' }}</strong></td>
                                <td>{{ $grade->subject->subject_name ?? 'N/A' }}</td>
                                <td>{{ $grade->marks_obtained }}/{{ $grade->total_marks }}</td>
                                <td>
                                    <span class="badge {{ 
                                        $grade->grade == 'A' ? 'bg-success' : 
                                        ($grade->grade == 'B' ? 'bg-info' : 
                                        ($grade->grade == 'C' ? 'bg-warning' : 
                                        ($grade->grade == 'D' ? 'bg-secondary' : 'bg-danger')))
                                    }}">
                                        {{ $grade->grade }}
                                    </span>
                                </td>
                                <td>{{ $grade->semester }}</td>
                                <td>{{ $grade->academic_year }}</td>
                                <td class="text-center">
                                    <div class="action-buttons justify-content-center">
                                        <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.grades.destroy', $grade) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $grades->links() }}
            </div>
        </div>

        <!-- Mobile View -->
        <div class="d-md-none">
            @foreach ($grades as $grade)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title mb-2">{{ $grade->student->user->name ?? 'N/A' }}</h5>
                                <p class="mb-2 text-muted">
                                    <strong>Subject:</strong> {{ $grade->subject->subject_name ?? 'N/A' }}
                                </p>
                                <p class="mb-2">
                                    <strong>Marks:</strong> {{ $grade->marks_obtained }}/{{ $grade->total_marks }}
                                </p>
                                <p class="mb-2">
                                    <strong>Grade:</strong>
                                    <span class="badge {{ 
                                        $grade->grade == 'A' ? 'bg-success' : 
                                        ($grade->grade == 'B' ? 'bg-info' : 
                                        ($grade->grade == 'C' ? 'bg-warning' : 
                                        ($grade->grade == 'D' ? 'bg-secondary' : 'bg-danger')))
                                    }}">
                                        {{ $grade->grade }}
                                    </span>
                                </p>
                                <p class="mb-3">
                                    <strong>Semester {{ $grade->semester }}, {{ $grade->academic_year }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.grades.edit', $grade) }}" class="btn btn-action btn-edit w-100 mb-2">
                                <i class="fas fa-edit"></i> Edit Grade
                            </a>
                            <form action="{{ route('admin.grades.destroy', $grade) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete w-100">
                                    <i class="fas fa-trash"></i> Delete Grade
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Mobile Pagination -->
            <div class="d-flex justify-content-center">
                {{ $grades->links() }}
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

    .page-title-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .card-title {
            font-size: 16px !important;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endsection
