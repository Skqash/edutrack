@extends('layouts.teacher')

@section('content')

<!-- Dashboard Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h3 fw-bold mb-0">Welcome, {{ auth()->user()->name ?? 'Teacher' }}!</h1>
                <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
            </div>
            <button class="btn btn-primary d-none d-md-inline-block">
                <i class="fas fa-plus me-2"></i> Add Grade
            </button>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-12 col-sm-6 col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Classes</h6>
                        <h3 class="mb-0">{{ $myClasses ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-door-open fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-3">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #17c88e 0%, #6ba3d4 100%); border: none;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Students</h6>
                        <h3 class="mb-0">{{ $totalStudents ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-3">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #6ba3d4 0%, #17c88e 100%); border: none;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Grades Posted</h6>
                        <h3 class="mb-0">{{ $gradesPosted ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-star fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3 mb-3">
        <div class="card stat-card text-white h-100" style="background: linear-gradient(135deg, #2196F3 0%, #17c88e 100%); border: none;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white-50 mb-1">Pending Tasks</h6>
                        <h3 class="mb-0">{{ $pendingTasks ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-tasks fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="row mb-4">
    <!-- Left Column - Recent Classes -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <h5 class="mb-0">
                    <i class="fas fa-door-open me-2"></i> My Classes
                </h5>
                <a href="javascript:void(0)" class="btn btn-sm btn-light text-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Class</th>
                                <th>Level</th>
                                <th class="d-none d-md-table-cell">Students</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3"><strong>Mathematics 101</strong></td>
                                <td><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">Year 1</span></td>
                                <td class="d-none d-md-table-cell">35</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3"><strong>Science 201</strong></td>
                                <td><span class="badge" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">Year 2</span></td>
                                <td class="d-none d-md-table-cell">28</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3"><strong>English 301</strong></td>
                                <td><span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">Year 3</span></td>
                                <td class="d-none d-md-table-cell">32</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - KSA Grading System Info -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i> KSA Grading System
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Comprehensive grading based on three key components:</p>

                <!-- Knowledge -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="fas fa-brain me-2" style="color: #667eea;"></i> Knowledge
                        </h6>
                        <small class="text-muted">30%</small>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 30%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                    </div>
                    <small class="text-muted">Conceptual understanding, test scores, comprehension</small>
                </div>

                <!-- Skills -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="fas fa-tools me-2" style="color: #764ba2;"></i> Skills
                        </h6>
                        <small class="text-muted">40%</small>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 40%; background: linear-gradient(90deg, #764ba2 0%, #667eea 100%);"></div>
                    </div>
                    <small class="text-muted">Practical application, project completion, hands-on ability</small>
                </div>

                <!-- Attitude -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="fas fa-handshake me-2" style="color: #667eea;"></i> Attitude
                        </h6>
                        <small class="text-muted">30%</small>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" style="width: 30%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                    </div>
                    <small class="text-muted">Participation, discipline, collaboration, effort</small>
                </div>

                <hr class="my-3">
                <div class="alert alert-light mb-0">
                    <small class="fw-bold">Final Grade = (Knowledge × 0.3) + (Skills × 0.4) + (Attitude × 0.3)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Grades Posted -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Recent Grades Posted
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Student</th>
                                <th>Class</th>
                                <th class="d-none d-md-table-cell">Knowledge</th>
                                <th class="d-none d-md-table-cell">Skills</th>
                                <th class="d-none d-md-table-cell">Attitude</th>
                                <th>Final Grade</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3">John Doe</td>
                                <td>Math 101</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-a">A (92)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-a">A (88)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-b">B (85)</span>
                                </td>
                                <td>
                                    <span class="grade-badge grade-a">A (88)</span>
                                </td>
                                <td><small>2 days ago</small></td>
                            </tr>
                            <tr>
                                <td class="ps-3">Jane Smith</td>
                                <td>Science 201</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-b">B (85)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-a">A (90)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-a">A (89)</span>
                                </td>
                                <td>
                                    <span class="grade-badge grade-a">A (88)</span>
                                </td>
                                <td><small>3 days ago</small></td>
                            </tr>
                            <tr>
                                <td class="ps-3">Mike Johnson</td>
                                <td>English 301</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-b">B (80)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-b">B (78)</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="grade-badge grade-c">C (75)</span>
                                </td>
                                <td>
                                    <span class="grade-badge grade-b">B (78)</span>
                                </td>
                                <td><small>1 week ago</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light text-center">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary">View All Grades</a>
            </div>
        </div>
    </div>
</div>

@endsection