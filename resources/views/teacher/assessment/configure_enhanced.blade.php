@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="background-color: #ffffff; padding: 2rem; border-radius: 8px; border-left: 5px solid #0066cc;">
                    <h1 class="h3 fw-bold mb-1" style="color: #1a1a1a;">
                        <i class="fas fa-sliders-h me-2" style="color: #0066cc;"></i>Configure Assessment Ranges
                    </h1>
                    <p class="mb-0" style="color: #555;">Set grading scales, assessment maximums, and component weights for
                        <strong>{{ $class->class_name }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Validation Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle me-2"></i>Success!</strong>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Configuration Form -->
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('teacher.assessment.configure.store', $class->id) }}" id="configForm">
                    @csrf

                    <!-- Grading Scale Reference Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #0066cc;">
                                <i class="fas fa-chart-bar me-2"></i>Grading Scale Reference (CHED Philippines)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-muted mb-3"><strong>Score to Grade Point Conversion (4.0 Scale):</strong>
                                    </p>
                                    <div style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead style="background-color: #f8f9fa; position: sticky; top: 0;">
                                                <tr>
                                                    <th
                                                        style="color: #1a1a1a; width: 30%; border-bottom: 2px solid #0066cc;">
                                                        Score Range</th>
                                                    <th
                                                        style="color: #1a1a1a; width: 30%; border-bottom: 2px solid #0066cc;">
                                                        Grade Point</th>
                                                    <th style="color: #1a1a1a; border-bottom: 2px solid #0066cc;">Remarks
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>97-100</td>
                                                    <td><span style="color: #00a86b; font-weight: bold;">1.00</span></td>
                                                    <td>Outstanding</td>
                                                </tr>
                                                <tr style="background-color: #fafafa;">
                                                    <td>94-96</td>
                                                    <td><span style="color: #00a86b; font-weight: bold;">1.25</span></td>
                                                    <td>Excellent</td>
                                                </tr>
                                                <tr>
                                                    <td>91-93</td>
                                                    <td><span style="color: #0066cc; font-weight: bold;">1.50</span></td>
                                                    <td>Very Good</td>
                                                </tr>
                                                <tr style="background-color: #fafafa;">
                                                    <td>88-90</td>
                                                    <td><span style="color: #0066cc; font-weight: bold;">1.75</span></td>
                                                    <td>Good</td>
                                                </tr>
                                                <tr>
                                                    <td>85-87</td>
                                                    <td><span style="color: #0066cc; font-weight: bold;">2.00</span></td>
                                                    <td>Satisfactory</td>
                                                </tr>
                                                <tr style="background-color: #fafafa;">
                                                    <td>82-84</td>
                                                    <td><span style="color: #0066cc; font-weight: bold;">2.25</span></td>
                                                    <td>Fair</td>
                                                </tr>
                                                <tr>
                                                    <td>79-81</td>
                                                    <td><span style="color: #00a86b; font-weight: bold;">2.50</span></td>
                                                    <td>Passing</td>
                                                </tr>
                                                <tr style="background-color: #fafafa;">
                                                    <td>76-78</td>
                                                    <td><span style="color: #00a86b; font-weight: bold;">2.75</span></td>
                                                    <td>Passing</td>
                                                </tr>
                                                <tr>
                                                    <td>75</td>
                                                    <td><span style="color: #ffb619; font-weight: bold;">3.00</span></td>
                                                    <td>Passing</td>
                                                </tr>
                                                <tr style="background-color: #fafafa;">
                                                    <td>70-74</td>
                                                    <td><span style="color: #ffb619; font-weight: bold;">4.00</span></td>
                                                    <td>Passing</td>
                                                </tr>
                                                <tr>
                                                    <td>Below 70</td>
                                                    <td><span style="color: #dc3545; font-weight: bold;">INC</span></td>
                                                    <td>Incomplete/Failed</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Note:</strong> Grades are calculated as percentage (0-100) and automatically
                                        converted to grade points using this scale.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Knowledge Assessment (40% Weight) -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #0066cc;">
                                <i class="fas fa-brain me-2"></i>Knowledge Assessment (40% Weight)
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Quizzes Section (5 Separate Quizzes) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #0066cc;"><i
                                        class="fas fa-question-circle me-2"></i>Quizzes (Quiz 1-5)</h6>
                                <p class="text-muted mb-3"><small>Each quiz is configured separately. Total quiz
                                        contribution to final grade: <strong>16%</strong></small></p>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-bold">Quiz 1 Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="quiz_1_max"
                                                value="{{ old('quiz_1_max', $range->quiz_1_max ?? 50) }}" min="5"
                                                max="100" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Pts</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-bold">Quiz 2 Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="quiz_2_max"
                                                value="{{ old('quiz_2_max', $range->quiz_2_max ?? 50) }}" min="5"
                                                max="100" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Pts</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-bold">Quiz 3 Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="quiz_3_max"
                                                value="{{ old('quiz_3_max', $range->quiz_3_max ?? 50) }}" min="5"
                                                max="100" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Pts</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-bold">Quiz 4 Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="quiz_4_max"
                                                value="{{ old('quiz_4_max', $range->quiz_4_max ?? 50) }}" min="5"
                                                max="100" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Pts</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-bold">Quiz 5 Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="quiz_5_max"
                                                value="{{ old('quiz_5_max', $range->quiz_5_max ?? 50) }}" min="5"
                                                max="100" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Pts</span>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Set maximum points for each quiz. All 5 quizzes combined
                                    contribute 16% to the final grade.</small>
                            </div>

                            <!-- Exams Section -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #0066cc;"><i class="fas fa-book me-2"></i>Exams (Midterm
                                    & Final)</h6>
                                <p class="text-muted mb-3"><small>Exam contribution to final grade:
                                        <strong>24%</strong></small></p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Exam Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="midterm_exam_max"
                                                value="{{ old('midterm_exam_max', $range->midterm_exam_max ?? 100) }}"
                                                min="20" max="200" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Points</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Exam Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="final_exam_max"
                                                value="{{ old('final_exam_max', $range->final_exam_max ?? 100) }}"
                                                min="20" max="200" required style="border-color: #0066cc;">
                                            <span class="input-group-text"
                                                style="background-color: #f0f7ff; border-color: #0066cc;">Points</span>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">Maximum points for each exam period. Average of all exams
                                    contributes 24% to final grade.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Skills Assessment (50% Weight) -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #00a86b;">
                                <i class="fas fa-cogs me-2"></i>Skills Assessment (50% Weight)
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Class Participation (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #00a86b;"><i class="fas fa-users me-2"></i>Class
                                    Participation (3 entries per term)</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="class_participation_midterm"
                                                value="{{ old('class_participation_midterm', $range->class_participation_midterm ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="class_participation_final"
                                                value="{{ old('class_participation_final', $range->class_participation_final ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Activities (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #00a86b;"><i class="fas fa-tasks me-2"></i>Activities
                                    (3 entries per term)</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="activities_midterm"
                                                value="{{ old('activities_midterm', $range->activities_midterm ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="activities_final"
                                                value="{{ old('activities_final', $range->activities_final ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignments (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #00a86b;"><i
                                        class="fas fa-file-alt me-2"></i>Assignments (3 entries per term)</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="assignments_midterm"
                                                value="{{ old('assignments_midterm', $range->assignments_midterm ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="assignments_final"
                                                value="{{ old('assignments_final', $range->assignments_final ?? 15) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 15 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Output/Project (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #00a86b;"><i
                                        class="fas fa-project-diagram me-2"></i>Output/Project (3 entries per term)</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="output_midterm"
                                                value="{{ old('output_midterm', $range->output_midterm ?? 30) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 30 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="output_final"
                                                value="{{ old('output_final', $range->output_final ?? 30) }}"
                                                min="0" max="100" required style="border-color: #00a86b;"
                                                placeholder="e.g., 30 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #e8f8f0; border-color: #00a86b;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Attitude Assessment (10% Weight) -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #ff8c00;">
                                <i class="fas fa-heart me-2"></i>Attitude Assessment (10% Weight)
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Behavior (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #ff8c00;"><i class="fas fa-user-check me-2"></i>Behavior
                                    (3 entries per term)
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="behavior_midterm"
                                                value="{{ old('behavior_midterm', $range->behavior_midterm ?? 5) }}"
                                                min="0" max="100" required style="border-color: #ff8c00;"
                                                placeholder="e.g., 5 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #fff3e0; border-color: #ff8c00;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="behavior_final"
                                                value="{{ old('behavior_final', $range->behavior_final ?? 5) }}"
                                                min="0" max="100" required style="border-color: #ff8c00;"
                                                placeholder="e.g., 5 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #fff3e0; border-color: #ff8c00;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Awareness/Responsiveness (3 entries per term) -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="color: #ff8c00;"><i
                                        class="fas fa-lightbulb me-2"></i>Awareness/Responsiveness (3 entries per term)
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Midterm Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="awareness_midterm"
                                                value="{{ old('awareness_midterm', $range->awareness_midterm ?? 5) }}"
                                                min="0" max="100" required style="border-color: #ff8c00;"
                                                placeholder="e.g., 5 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #fff3e0; border-color: #ff8c00;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 midterm entries combined</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Final Max <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="awareness_final"
                                                value="{{ old('awareness_final', $range->awareness_final ?? 5) }}"
                                                min="0" max="100" required style="border-color: #ff8c00;"
                                                placeholder="e.g., 5 for 3 entries">
                                            <span class="input-group-text"
                                                style="background-color: #fff3e0; border-color: #ff8c00;">Pts</span>
                                        </div>
                                        <small class="text-muted">Max score for all 3 final entries combined</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Attendance Settings -->
                    <div class="card shadow-sm mb-4" style="border-top: 4px solid #6c757d;">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #6c757d;">
                                <i class="fas fa-calendar-check me-2"></i>Attendance Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="attendance_required"
                                            id="attendance_required" value="1"
                                            {{ old('attendance_required', $range->attendance_required ?? false) ? 'checked' : '' }}
                                            style="border-color: #6c757d;">
                                        <label class="form-check-label fw-bold" for="attendance_required">
                                            Track Attendance
                                        </label>
                                        <small class="d-block text-muted mt-1">Enable attendance tracking for this
                                            class</small>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Minimum Attendance % <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="attendance_min_percentage"
                                            value="{{ old('attendance_min_percentage', $range->attendance_min_percentage ?? 75) }}"
                                            min="0" max="100" required style="border-color: #6c757d;">
                                        <span class="input-group-text"
                                            style="background-color: #f8f9fa; border-color: #6c757d;">%</span>
                                    </div>
                                    <small class="text-muted d-block mt-2">Minimum required attendance percentage</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Additional Notes -->
                    <div class="card shadow-sm mb-4" style="border-top: 4px solid #6c757d;">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 2px solid #e9ecef;">
                            <h5 class="mb-0" style="color: #6c757d;">
                                <i class="fas fa-sticky-note me-2"></i>Additional Notes
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Add any special notes, remarks, or assessment guidelines for this class..."
                                style="border-color: #6c757d;">{{ old('notes', $range->notes ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">Optional: Add assessment guidelines, special
                                instructions, or remarks</small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mb-4">
                        <button type="submit" class="btn btn-lg fw-bold"
                            style="background-color: #0066cc; color: white; border: none;">
                            <i class="fas fa-check-circle me-2"></i>Save Configuration
                        </button>
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-lg fw-bold"
                            style="background-color: #ffffff; color: #0066cc; border: 2px solid #0066cc;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info border-start border-5"
                    style="background-color: #ffffff; border-color: #0066cc;">
                    <h5 class="mb-3" style="color: #0066cc;">
                        <i class="fas fa-info-circle me-2"></i>How Assessment Configuration Works
                    </h5>
                    <ul class="mb-0">
                        <li><strong>Knowledge (40%):</strong> Set the number of quizzes (1-10) and their maximum scores plus
                            exam maximums</li>
                        <li><strong>Skills (50%):</strong> Configure maximums for class participation, activities,
                            assignments, and projects</li>
                        <li><strong>Attitude (10%):</strong> Set behavior and awareness component maximum scores</li>
                        <li><strong>Grading Scale:</strong> All scores are converted to 4.0 scale (see reference table
                            above)</li>
                        <li><strong>Final Grade:</strong> Automatically calculated as: (Knowledge × 0.40) + (Skills × 0.50)
                            + (Attitude × 0.10)</li>
                        <li><strong>Attendance:</strong> Optional tracking with minimum percentage requirement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to calculate totals
        function calculateTotals() {
            // Skills Totals
            calculateTotal('class_participation_prelim', 'class_participation_midterm', 'class_participation_final',
                'class_participation_total');
            calculateTotal('activities_prelim', 'activities_midterm', 'activities_final', 'activities_total');
            calculateTotal('assignments_prelim', 'assignments_midterm', 'assignments_final', 'assignments_total');
            calculateTotal('output_prelim', 'output_midterm', 'output_final', 'output_total');

            // Attitude Totals
            calculateTotal('behavior_prelim', 'behavior_midterm', 'behavior_final', 'behavior_total');
            calculateTotal('awareness_prelim', 'awareness_midterm', 'awareness_final', 'awareness_total');
        }

        function calculateTotal(prelim, midterm, final, totalId) {
            const prelimVal = parseInt(document.querySelector(`[name="${prelim}"]`)?.value || 0);
            const midtermVal = parseInt(document.querySelector(`[name="${midterm}"]`)?.value || 0);
            const finalVal = parseInt(document.querySelector(`[name="${final}"]`)?.value || 0);
            const total = prelimVal + midtermVal + finalVal;

            const totalField = document.getElementById(totalId);
            if (totalField) {
                totalField.value = total;
            }
        }

        // Add event listeners to all input fields
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotals();

            const skillsInputs = document.querySelectorAll(
                '[name*="_prelim"], [name*="_midterm"], [name*="_final"]');
            skillsInputs.forEach(input => {
                input.addEventListener('change', calculateTotals);
                input.addEventListener('input', calculateTotals);
            });
        });
    </script>

@endsection
