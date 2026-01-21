@extends('layouts.admin')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Courses Management</h2>
                    <p class="text-muted">Manage all courses offered in your institution</p>
                </div>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add New Course
                </a>
            </div>
        </div>
    </div>

    <!-- Courses Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #3498db;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 12px; letter-spacing: 0.5px;">
                                Total Courses</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3498db;">{{ $courses->count() }}</h2>
                        </div>
                        <i class="fas fa-book" style="font-size: 40px; color: rgba(52, 152, 219, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #27ae60;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 12px; letter-spacing: 0.5px;">
                                Active Courses</h6>
                            <h2 class="mb-0 fw-bold" style="color: #27ae60;">{{ $courses->where('status', 'Active')->count() }}</h2>
                        </div>
                        <i class="fas fa-check-circle" style="font-size: 40px; color: rgba(39, 174, 96, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #f39c12;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 12px; letter-spacing: 0.5px;">
                                Total Credit Hours</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f39c12;">{{ $courses->sum('credit_hours') }}</h2>
                        </div>
                        <i class="fas fa-users" style="font-size: 40px; color: rgba(243, 156, 18, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="courseSearch" placeholder="Search courses...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold" style="color: #2c3e50;">Course Code</th>
                            <th class="fw-bold" style="color: #2c3e50;">Course Name</th>
                            <th class="fw-bold" style="color: #2c3e50;">Instructor</th>
                            <th class="fw-bold" style="color: #2c3e50;">Students</th>
                            <th class="fw-bold" style="color: #2c3e50;">Status</th>
                            <th class="fw-bold" style="color: #2c3e50;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td><strong>{{ $course->course_code }}</strong></td>
                            <td>{{ $course->course_name }}</td>
                            <td>{{ $course->instructor->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info">{{ $course->credit_hours }}</span></td>
                            <td><span class="badge bg-{{ $course->status == 'Active' ? 'success' : 'danger' }}">{{ $course->status }}</span></td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted">No courses found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection