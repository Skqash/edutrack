@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">Grade Assignment</h1>
                <small class="text-muted">{{ $assignment->title }}</small>
            </div>
            <a href="{{ route('teacher.assignments.list', $class->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Assignment:</strong> {{ $assignment->title }}</p>
                            <p class="mb-2"><strong>Max Score:</strong> {{ $assignment->max_score }}</p>
                            <p class="mb-0"><strong>Term:</strong> {{ ucfirst($assignment->term) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Due Date:</strong> {{ $assignment->due_date->format('M d, Y h:i A') }}
                            </p>
                            <p class="mb-0"><strong>Submissions:</strong>
                                <span
                                    class="badge bg-primary">{{ $submissions->where('status', 'submitted')->count() }}</span>
                                <span class="badge bg-success">{{ $submissions->where('status', 'graded')->count() }}
                                    Graded</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($submissions->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No submissions yet for this assignment.
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Score</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $submission->student->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $submission->status === 'graded' ? 'success' : ($submission->status === 'submitted' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                    @if ($submission->isLate())
                                        <span class="badge bg-warning">Late</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($submission->submitted_at)
                                        {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($submission->isGraded())
                                        <strong>{{ $submission->score }} / {{ $assignment->max_score }}</strong>
                                    @else
                                        <span class="text-muted">Not Graded</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#gradeModal{{ $submission->id }}">
                                        <i class="fas fa-edit me-1"></i> Grade
                                    </button>
                                </td>
                            </tr>

                            <!-- Grade Modal -->
                            <div class="modal fade" id="gradeModal{{ $submission->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Grade Assignment -
                                                {{ $submission->student->user->name ?? 'N/A' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form
                                            action="{{ route('teacher.assignments.score', [$class->id, $assignment->id, $submission->id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="score{{ $submission->id }}" class="form-label">Score <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control"
                                                            id="score{{ $submission->id }}" name="score" step="0.01"
                                                            min="0" max="{{ $assignment->max_score }}"
                                                            value="{{ old('score', $submission->score) }}" required>
                                                        <span class="input-group-text">/
                                                            {{ $assignment->max_score }}</span>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="feedback{{ $submission->id }}"
                                                        class="form-label">Feedback</label>
                                                    <textarea class="form-control" id="feedback{{ $submission->id }}" name="feedback" rows="3"
                                                        placeholder="Enter feedback for the student...">{{ old('feedback', $submission->feedback) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i> Save Score
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">No submissions found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
