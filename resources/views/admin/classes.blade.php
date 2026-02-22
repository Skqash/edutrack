@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Classes Management</h2>
                    <p class="text-muted">Manage all classes and organize students by class sections</p>
                </div>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Class Capacity Chart -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2" style="color: #3498db;"></i> Class Capacity Utilization
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="classCapacityChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-layer-group me-2" style="color: #27ae60;"></i> Class Levels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="category-list" style="font-size: 14px;">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 10</span>
                            <span class="badge bg-primary">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 11</span>
                            <span class="badge bg-success">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Class 12</span>
                            <span class="badge bg-warning">8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search classes...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold">Class Name</th>
                            <th class="fw-bold">Class Level</th>
                            <th class="fw-bold">Section</th>
                            <th class="fw-bold">Students</th>
                            <th class="fw-bold">Class Teacher</th>
                            <th class="fw-bold">Capacity</th>
                            <th class="fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td><strong>{{ $class->class_name }}</strong></td>
                                <td><span class="badge bg-light text-dark">{{ $class->class_level }}</span></td>
                                <td>{{ $class->section }}</td>
                                <td><span class="badge bg-info">{{ $class->students()->count() }}</span></td>
                                <td>{{ $class->teacher->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar {{ $class->utilizationPercentage() > 85 ? 'bg-danger' : ($class->utilizationPercentage() > 70 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ $class->utilizationPercentage() }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.classes.edit', $class->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST"
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
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">No classes found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
