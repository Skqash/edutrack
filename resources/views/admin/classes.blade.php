@extends('layouts.admin')

@section('content')
    <style>
        /* Modern Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
            color: white;
        }

        .page-header-modern .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-modern .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header-modern .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .page-header-modern .header-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header-modern .header-subtitle {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .page-header-modern .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-white {
            background: white;
            color: #667eea;
        }

        .btn-modern-white:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern .header-title {
                font-size: 1.5rem;
            }

            .page-header-modern .header-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
        }
    </style>

    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <h1 class="header-title">Classes Management</h1>
                    <p class="header-subtitle">Manage all classes and organize students by class sections</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.classes.create') }}" class="btn btn-modern btn-modern-white">
                    <i class="fas fa-plus"></i> Add New Class
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
                            <tr data-class-id="{{ $class->id }}">
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.classes.edit', $class->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.classes.show', $class->id) }}"
                                            class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            onclick="openStudentModal({{ $class->id }}, '{{ $class->class_name }}')">
                                            <i class="fas fa-user-plus"></i> Add Students
                                        </button>
                                        <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
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
    </div>

    @include('admin.classes.partials.student-assignment-modal', [
        'courses' => $courses,
        'departments' => $departments,
    ])
@endsection
