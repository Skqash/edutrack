@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white me-2">
                    <i class="fas fa-clipboard-check"></i>
                </span>
                Attendance Management
            </h3>
        </div>
        <div class="col-auto ms-auto">
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-gradient-success">
                <i class="fas fa-plus"></i> Mark Attendance
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
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance as $record)
                            <tr>
                                <td><strong>{{ $record->student->user->name ?? 'N/A' }}</strong></td>
                                <td>{{ $record->class->class_name ?? 'N/A' }}</td>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ 
                                        $record->status == 'Present' ? 'bg-success' : 
                                        ($record->status == 'Absent' ? 'bg-danger' : 
                                        ($record->status == 'Late' ? 'bg-warning' : 'bg-info'))
                                    }}">
                                        {{ $record->status }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($record->notes, 30) }}</td>
                                <td class="text-center">
                                    <div class="action-buttons justify-content-center">
                                        <a href="{{ route('admin.attendance.edit', $record) }}" class="btn btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.attendance.destroy', $record) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
                {{ $attendance->links() }}
            </div>
        </div>

        <!-- Mobile View -->
        <div class="d-md-none">
            @foreach ($attendance as $record)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title mb-2">{{ $record->student->user->name ?? 'N/A' }}</h5>
                                <p class="mb-2 text-muted">
                                    <strong>Class:</strong> {{ $record->class->class_name ?? 'N/A' }}
                                </p>
                                <p class="mb-2">
                                    <strong>Date:</strong> {{ $record->date->format('M d, Y') }}
                                </p>
                                <p class="mb-3">
                                    <strong>Status:</strong> 
                                    <span class="badge {{ 
                                        $record->status == 'Present' ? 'bg-success' : 
                                        ($record->status == 'Absent' ? 'bg-danger' : 
                                        ($record->status == 'Late' ? 'bg-warning' : 'bg-info'))
                                    }}">
                                        {{ $record->status }}
                                    </span>
                                </p>
                                @if($record->notes)
                                    <p class="mb-3 text-muted">
                                        <strong>Notes:</strong> {{ $record->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('admin.attendance.edit', $record) }}" class="btn btn-action btn-edit w-100 mb-2">
                                <i class="fas fa-edit"></i> Edit Record
                            </a>
                            <form action="{{ route('admin.attendance.destroy', $record) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete w-100">
                                    <i class="fas fa-trash"></i> Delete Record
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Mobile Pagination -->
            <div class="d-flex justify-content-center">
                {{ $attendance->links() }}
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
