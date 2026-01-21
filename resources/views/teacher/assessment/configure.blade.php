@extends('layouts.teacher')

@section('content')
<div class="container-fluid my-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-2">
                <i class="fas fa-sliders-h"></i> Configure Assessment Ranges
            </h2>
            <p class="text-muted">Set the maximum item ranges for quizzes, exams, skills, and attitude components</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-book"></i> {{ $class->class_name }} - {{ $class->subject->name ?? 'N/A' }}
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.assessment.store', $class->id) }}">
                        @csrf

                        <!-- Knowledge Components -->
                        <div class="section mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-brain"></i> Knowledge Assessment Ranges (40% of Term Grade)
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quiz 1 - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="quiz_1_max" 
                                            value="{{ old('quiz_1_max', $range->quiz_1_max) }}" 
                                            min="5" max="100" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 20</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quiz 2 - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="quiz_2_max" 
                                            value="{{ old('quiz_2_max', $range->quiz_2_max) }}" 
                                            min="5" max="100" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 15</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quiz 3 - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="quiz_3_max" 
                                            value="{{ old('quiz_3_max', $range->quiz_3_max) }}" 
                                            min="5" max="100" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 25</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quiz 4 - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="quiz_4_max" 
                                            value="{{ old('quiz_4_max', $range->quiz_4_max) }}" 
                                            min="5" max="100" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 20</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quiz 5 - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="quiz_5_max" 
                                            value="{{ old('quiz_5_max', $range->quiz_5_max) }}" 
                                            min="5" max="100" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 20</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Prelim Exam - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="prelim_exam_max" 
                                            value="{{ old('prelim_exam_max', $range->prelim_exam_max) }}" 
                                            min="20" max="200" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 60</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Midterm Exam - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="midterm_exam_max" 
                                            value="{{ old('midterm_exam_max', $range->midterm_exam_max) }}" 
                                            min="20" max="200" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 60</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Final Exam - Max Items <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="final_exam_max" 
                                            value="{{ old('final_exam_max', $range->final_exam_max) }}" 
                                            min="20" max="200" required>
                                        <span class="input-group-text">items</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 60</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Skills Components -->
                        <div class="section mb-4">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-toolbox"></i> Skills Assessment Ranges (50% of Term Grade)
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Output - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="output_max" 
                                            value="{{ old('output_max', $range->output_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (40% of Skills)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Class Participation - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="class_participation_max" 
                                            value="{{ old('class_participation_max', $range->class_participation_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (30% of Skills)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Activities - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="activities_max" 
                                            value="{{ old('activities_max', $range->activities_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (15% of Skills)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assignments - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="assignments_max" 
                                            value="{{ old('assignments_max', $range->assignments_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (15% of Skills)</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Attitude Components -->
                        <div class="section mb-4">
                            <h5 class="text-warning mb-3">
                                <i class="fas fa-heart"></i> Attitude Assessment Ranges (10% of Term Grade)
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Behavior - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="behavior_max" 
                                            value="{{ old('behavior_max', $range->behavior_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (50% of Attitude)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Awareness - Max Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="awareness_max" 
                                            value="{{ old('awareness_max', $range->awareness_max) }}" 
                                            min="10" max="200" required>
                                        <span class="input-group-text">points</span>
                                    </div>
                                    <small class="form-text text-muted">Default: 100 (50% of Attitude)</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Attendance Configuration -->
                        <div class="section mb-4">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-clipboard-list"></i> Attendance Configuration
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Attendance Max - Total Classes <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="attendance_max" 
                                            value="{{ old('attendance_max', $range->attendance_max) }}" 
                                            min="1" max="500" required>
                                        <span class="input-group-text">classes</span>
                                    </div>
                                    <small class="form-text text-muted">Total class meetings for the term</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Attendance Required? <span class="text-danger">*</span></label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="attendance_required" 
                                            id="attendanceRequired" value="1"
                                            {{ old('attendance_required', $range->attendance_required) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="attendanceRequired">
                                            Track attendance for this class
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Notes -->
                        <div class="section mb-4">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Add any special notes about assessment ranges...">{{ old('notes', $range->notes) }}</textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Configuration
                            </button>
                            <a href="{{ route('teacher.grades') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong><i class="fas fa-info-circle"></i> How It Works:</strong>
                <ul class="mb-0 mt-2">
                    <li>Set maximum item counts for each quiz and exam type</li>
                    <li>Scores are automatically normalized to 0-100 scale</li>
                    <li>Percentage weighting remains fixed: Knowledge 40%, Skills 50%, Attitude 10%</li>
                    <li>All component weightings are maintained regardless of max item counts</li>
                    <li>Changes only affect this class and subject combination</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.section {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.input-group-text {
    background-color: #e9ecef;
    font-weight: 500;
}
</style>
@endsection
