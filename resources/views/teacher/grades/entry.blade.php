@extends('layouts.teacher')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-0">Enter Grades</h1>
                    <small class="text-muted">{{ $class->class_name }} - {{ $class->class_level }}</small>
                </div>
                <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- KSA Grading System Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card alert alert-info border-0">
                <div class="card-body py-3 px-4">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-2">
                            <strong><i class="fas fa-brain me-2" style="color: #667eea;"></i> Knowledge (30%)</strong>
                            <small class="d-block text-muted">Test scores, conceptual understanding</small>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <strong><i class="fas fa-tools me-2" style="color: #764ba2;"></i> Skills (40%)</strong>
                            <small class="d-block text-muted">Practical application, projects</small>
                        </div>
                        <div class="col-12 col-md-4">
                            <strong><i class="fas fa-handshake me-2" style="color: #f093fb;"></i> Attitude (30%)</strong>
                            <small class="d-block text-muted">Participation, collaboration</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Entry Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Students in {{ $class->class_name }}</h5>
                </div>
                <div class="card-body p-0">
                    <form action="{{ route('teacher.grades.store.old', $class->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student Name</th>
                                        <th class="text-center">
                                            <small><strong>Knowledge</strong><br><span
                                                    class="text-muted">(0-100)</span></small>
                                        </th>
                                        <th class="text-center">
                                            <small><strong>Skills</strong><br><span
                                                    class="text-muted">(0-100)</span></small>
                                        </th>
                                        <th class="text-center">
                                            <small><strong>Attitude</strong><br><span
                                                    class="text-muted">(0-100)</span></small>
                                        </th>
                                        <th class="text-center">
                                            <small><strong>Final Grade</strong></small>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($class->students as $index => $student)
                                        @php
                                            $grade = $existingGrades->get($student->id);
                                        @endphp
                                        <tr>
                                            <td class="ps-3 py-3">
                                                <strong>{{ $student->user->name ?? $student->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $student->admission_number ?? 'N/A' }}</small>
                                            </td>
                                            <td class="py-3">
                                                <input type="number" name="grades[{{ $index }}][knowledge_score]"
                                                    class="form-control form-control-sm knowledge-input" min="0"
                                                    max="100" step="0.5"
                                                    value="{{ $grade ? $grade->knowledge_score : '' }}" placeholder="0-100"
                                                    required>
                                                <input type="hidden" name="grades[{{ $index }}][student_id]"
                                                    value="{{ $student->id }}">
                                                @error("grades.{$index}.knowledge_score")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="py-3">
                                                <input type="number" name="grades[{{ $index }}][skills_score]"
                                                    class="form-control form-control-sm skills-input" min="0"
                                                    max="100" step="0.5"
                                                    value="{{ $grade ? $grade->skills_score : '' }}" placeholder="0-100"
                                                    required>
                                                @error("grades.{$index}.skills_score")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="py-3">
                                                <input type="number" name="grades[{{ $index }}][attitude_score]"
                                                    class="form-control form-control-sm attitude-input" min="0"
                                                    max="100" step="0.5"
                                                    value="{{ $grade ? $grade->attitude_score : '' }}" placeholder="0-100"
                                                    required>
                                                @error("grades.{$index}.attitude_score")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="py-3">
                                                <input type="text" class="form-control form-control-sm final-grade"
                                                    value="{{ $grade ? $grade->final_grade . ' (' . \App\Models\Grade::getGradePoint($grade->final_grade) . ')' : '' }}"
                                                    readonly
                                                    style="background-color: #f8f9fa; text-align: center; font-weight: bold;">
                                            </td>
                                        </tr>
                                        <tr class="d-none d-md-table-row">
                                            <td colspan="5" class="px-3 py-2">
                                                <input type="text" name="grades[{{ $index }}][remarks]"
                                                    class="form-control form-control-sm" placeholder="Remarks (optional)"
                                                    value="{{ $grade ? $grade->remarks : '' }}" maxlength="255">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>No students enrolled in this class yet</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($class->students->count() > 0)
                            <div class="card-footer bg-light d-flex justify-content-between gap-2 flex-wrap">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Grades
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Calculate final grade when KSA scores change
        document.querySelectorAll('.knowledge-input, .skills-input, .attitude-input').forEach(input => {
            input.addEventListener('change', calculateFinalGrades);
            input.addEventListener('input', calculateFinalGrades);
        });

        function calculateFinalGrades() {
            const rows = document.querySelectorAll('tbody tr:not(.d-none)');

            rows.forEach((row, index) => {
                if (row.classList.contains('d-md-table-row')) return;

                const knowledge = parseFloat(row.querySelector('.knowledge-input')?.value) || 0;
                const skills = parseFloat(row.querySelector('.skills-input')?.value) || 0;
                const attitude = parseFloat(row.querySelector('.attitude-input')?.value) || 0;

                // Only calculate if all three scores are filled
                if (knowledge > 0 && skills > 0 && attitude > 0) {
                    // Final Grade = (Knowledge × 0.3) + (Skills × 0.4) + (Attitude × 0.3)
                    const finalGrade = Math.round(
                        (knowledge * 0.3 + skills * 0.4 + attitude * 0.3) * 100
                    ) / 100;

                    // Get letter grade
                    let letterGrade;
                    if (finalGrade >= 90) letterGrade = 'A';
                    else if (finalGrade >= 80) letterGrade = 'B';
                    else if (finalGrade >= 70) letterGrade = 'C';
                    else if (finalGrade >= 60) letterGrade = 'D';
                    else letterGrade = 'F';

                    // Update final grade field
                    const finalGradeInput = row.querySelector('.final-grade');
                    if (finalGradeInput) {
                        finalGradeInput.value = finalGrade + ' (' + letterGrade + ')';
                    }
                }
            });
        }

        // Calculate on page load if grades exist
        document.addEventListener('DOMContentLoaded', calculateFinalGrades);
    </script>
@endsection
