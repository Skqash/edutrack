@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Subjects Management</h2>
                    <p class="text-muted">Organize and manage all subjects offered in courses</p>
                </div>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add New Subject
                </a>
            </div>
        </div>
    </div>


    <!-- Subjects by Category Chart -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2" style="color: #3498db;"></i> Subjects Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subjectCategoryChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2" style="color: #27ae60;"></i> Categories
                    </h5>
                </div>
                <div class="card-body">
                    <div class="category-list" style="font-size: 14px;">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Science</span>
                            <span class="badge bg-primary">12</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Mathematics</span>
                            <span class="badge bg-success">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Languages</span>
                            <span class="badge bg-warning">10</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Social Studies</span>
                            <span class="badge bg-info">12</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Technical</span>
                            <span class="badge bg-danger">6</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search subjects...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold">Subject Code</th>
                            <th class="fw-bold">Subject Name</th>
                            <th class="fw-bold">Category</th>
                            <th class="fw-bold">Credit Hours</th>
                            <th class="fw-bold">Instructor</th>
                            <th class="fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->subject_code }}</strong></td>
                                <td>{{ $subject->subject_name }}</td>
                                <td><span class="badge bg-light text-dark">{{ $subject->category }}</span></td>
                                <td>{{ $subject->credit_hours }}</td>
                                <td>{{ $subject->instructor->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">No subjects found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Subject Modal -->
    <div class="modal fade" id="subjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header border-0 bg-primary text-white">
                    <h5 class="modal-title fw-bold">Add New Subject</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                </div>
            </div>
        </div>
    @endsection
