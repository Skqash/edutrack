@extends('layouts.teacher')

@section('content')
<div class="container-fluid py-4" style="margin-top: 80px;">
    <!-- Header Section -->
    <div class="card mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Grade Entry - {{ $class->class_name }}
                    </h4>
                    <p class="mb-0 opacity-75">{{ $class->course->program_name ?? 'N/A' }} | {{ ucfirst($term) }} Term</p>
                </div>
                <div>
                    <a href="{{ route('teacher.grades') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                    <a href="{{ route('teacher.grades.settings.index', $class->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-cog me-1"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Entry Form -->
    <div class="card">
        <div class="card-body">
            <form id="gradeEntryForm" action="{{ route('teacher.grades.store', $class->id) }}?term={{ $term }}" method="POST">
                @csrf
                
                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="gradeTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 200px;">Student Name</th>
                                <th style="width: 120px;">Student ID</th>
                                
                                <!-- Knowledge Components (40%) -->
                                <th colspan="3" class="text-center bg-info bg-opacity-10">
                                    Knowledge (40%)
                                </th>
                                
                                <!-- Skills Components (50%) -->
                                <th colspan="3" class="text-center bg-success bg-opacity-10">
                                    Skills (50%)
                                </th>
                                
                                <!-- Attitude Components (10%) -->
                                <th colspan="2" class="text-center bg-warning bg-opacity-10">
                                    Attitude (10%)
                                </th>
                                
                                <th class="text-center bg-primary bg-opacity-10">Final Grade</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                
                                <!-- Knowledge Sub-components -->
                                <th class="text-center">Exam (60%)</th>
                                <th class="text-center">Quiz 1 (20%)</th>
                                <th class="text-center">Quiz 2 (20%)</th>
                                
                                <!-- Skills Sub-components -->
                                <th class="text-center">Output (40%)</th>
                                <th class="text-center">Class Part. (30%)</th>
                                <th class="text-center">Activities (30%)</th>
                                
                                <!-- Attitude Sub-components -->
                                <th class="text-center">Behavior (50%)</th>
                                <th class="text-center">Awareness (50%)</th>
                                
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                                @php
                                    $entry = $entries->get($student->id);
                                @endphp
                                <tr data-student-id="{{ $student->id }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'N/A' }}</td>
                                    <td>{{ $student->student_id }}</td>
                                    
                                    <!-- Knowledge Components -->
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input knowledge-input" 
                                               name="grades[{{ $student->id }}][exam]" 
                                               data-component="knowledge"
                                               data-weight="0.6"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->exam ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input knowledge-input" 
                                               name="grades[{{ $student->id }}][quiz_1]" 
                                               data-component="knowledge"
                                               data-weight="0.2"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->quiz_1 ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input knowledge-input" 
                                               name="grades[{{ $student->id }}][quiz_2]" 
                                               data-component="knowledge"
                                               data-weight="0.2"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->quiz_2 ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    
                                    <!-- Skills Components -->
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input skills-input" 
                                               name="grades[{{ $student->id }}][output]" 
                                               data-component="skills"
                                               data-weight="0.4"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->output ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input skills-input" 
                                               name="grades[{{ $student->id }}][class_participation]" 
                                               data-component="skills"
                                               data-weight="0.3"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->class_participation ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input skills-input" 
                                               name="grades[{{ $student->id }}][activities]" 
                                               data-component="skills"
                                               data-weight="0.3"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->activities ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    
                                    <!-- Attitude Components -->
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input attitude-input" 
                                               name="grades[{{ $student->id }}][behavior]" 
                                               data-component="attitude"
                                               data-weight="0.5"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->behavior ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm grade-input attitude-input" 
                                               name="grades[{{ $student->id }}][awareness]" 
                                               data-component="attitude"
                                               data-weight="0.5"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $entry->awareness ?? '' }}"
                                               placeholder="0-100">
                                    </td>
                                    
                                    <!-- Final Grade (Auto-calculated) -->
                                    <td class="text-center">
                                        <strong class="final-grade text-primary">{{ number_format($entry->final_grade ?? 0, 2) }}</strong>
                                        <input type="hidden" name="grades[{{ $student->id }}][final_grade]" class="final-grade-input" value="{{ $entry->final_grade ?? 0 }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No students found in this class</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <button type="button" class="btn btn-secondary" onclick="clearAllGrades()">
                            <i class="fas fa-eraser me-1"></i> Clear All
                        </button>
                        <button type="button" class="btn btn-info" onclick="calculateAllGrades()">
                            <i class="fas fa-calculator me-1"></i> Calculate All
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i> Save Grades
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .grade-input {
        text-align: center;
        font-weight: 500;
    }
    
    .grade-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }
    
    .final-grade {
        font-size: 1.1rem;
        display: block;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 0.25rem;
    }
    
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate grades on input change
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function() {
            calculateRowGrade(this.closest('tr'));
        });
    });
    
    // Calculate all grades on page load
    calculateAllGrades();
});

function calculateRowGrade(row) {
    const knowledgeInputs = row.querySelectorAll('.knowledge-input');
    const skillsInputs = row.querySelectorAll('.skills-input');
    const attitudeInputs = row.querySelectorAll('.attitude-input');
    
    // Calculate Knowledge average (weighted)
    let knowledgeTotal = 0;
    let knowledgeCount = 0;
    knowledgeInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        const weight = parseFloat(input.dataset.weight) || 0;
        knowledgeTotal += value * weight;
        if (input.value) knowledgeCount++;
    });
    const knowledgeAvg = knowledgeCount > 0 ? knowledgeTotal : 0;
    
    // Calculate Skills average (weighted)
    let skillsTotal = 0;
    let skillsCount = 0;
    skillsInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        const weight = parseFloat(input.dataset.weight) || 0;
        skillsTotal += value * weight;
        if (input.value) skillsCount++;
    });
    const skillsAvg = skillsCount > 0 ? skillsTotal : 0;
    
    // Calculate Attitude average (weighted)
    let attitudeTotal = 0;
    let attitudeCount = 0;
    attitudeInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        const weight = parseFloat(input.dataset.weight) || 0;
        attitudeTotal += value * weight;
        if (input.value) attitudeCount++;
    });
    const attitudeAvg = attitudeCount > 0 ? attitudeTotal : 0;
    
    // Calculate final grade using KSA formula
    // Final = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
    const finalGrade = (knowledgeAvg * 0.40) + (skillsAvg * 0.50) + (attitudeAvg * 0.10);
    
    // Update display
    const finalGradeDisplay = row.querySelector('.final-grade');
    const finalGradeInput = row.querySelector('.final-grade-input');
    
    if (finalGradeDisplay && finalGradeInput) {
        finalGradeDisplay.textContent = finalGrade.toFixed(2);
        finalGradeInput.value = finalGrade.toFixed(2);
        
        // Color code based on grade
        if (finalGrade >= 90) {
            finalGradeDisplay.className = 'final-grade text-success';
        } else if (finalGrade >= 75) {
            finalGradeDisplay.className = 'final-grade text-primary';
        } else if (finalGrade >= 60) {
            finalGradeDisplay.className = 'final-grade text-warning';
        } else {
            finalGradeDisplay.className = 'final-grade text-danger';
        }
    }
}

function calculateAllGrades() {
    document.querySelectorAll('#gradeTable tbody tr').forEach(row => {
        if (row.dataset.studentId) {
            calculateRowGrade(row);
        }
    });
}

function clearAllGrades() {
    if (confirm('Are you sure you want to clear all grades? This action cannot be undone.')) {
        document.querySelectorAll('.grade-input').forEach(input => {
            input.value = '';
        });
        calculateAllGrades();
    }
}

// Form validation before submit
document.getElementById('gradeEntryForm').addEventListener('submit', function(e) {
    const hasAnyGrade = Array.from(document.querySelectorAll('.grade-input')).some(input => input.value);
    
    if (!hasAnyGrade) {
        e.preventDefault();
        alert('Please enter at least one grade before saving.');
        return false;
    }
    
    return true;
});
</script>
@endsection
