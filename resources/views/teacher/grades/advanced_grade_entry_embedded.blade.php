<!-- Embedded Advanced Grade Entry (Dynamic Components from Database) -->
<div class="p-4">
    <!-- Quick Info Banner -->
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-md-10">
                <h6 class="mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Grade Entry for {{ ucfirst($term) }} Term - {{ $class->class_name }}
                </h6>
                <p class="mb-0 small">
                    Enter grades for each student. Components are dynamically loaded from your configuration.
                    Add or remove components in the "Settings & Components" tab.
                </p>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-success btn-sm" onclick="calculateAllGrades()">
                    <i class="fas fa-calculator me-1"></i> Calculate All
                </button>
            </div>
        </div>
    </div>

    @if($knowledgeComponents->isEmpty() && $skillsComponents->isEmpty() && $attitudeComponents->isEmpty())
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle me-2"></i>No Components Configured</h5>
            <p class="mb-2">You haven't added any assessment components yet. Please add components to start entering grades.</p>
            <button class="btn btn-primary" onclick="document.getElementById('settings-tab').click()">
                <i class="fas fa-cog me-2"></i>Go to Settings & Components
            </button>
        </div>
    @else
        <!-- Grade Entry Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto; max-height: 70vh;">
                    <table class="table table-hover mb-0 table-fixed" id="gradeTable" style="min-width: 1200px;">
                        <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background: white;">
                            <tr>
                                <th style="min-width: 150px;">Student Name</th>
                                
                                @if($knowledgeComponents->count() > 0)
                                    @php
                                        $examCount = $knowledgeComponents->where('subcategory', 'Exam')->count();
                                        $quizCount = $knowledgeComponents->where('subcategory', 'Quiz')->count();
                                    @endphp
                                    <th colspan="{{ $examCount + ($examCount > 0 ? 1 : 0) + $quizCount + ($quizCount > 0 ? 1 : 0) + 1 }}" class="text-center bg-primary text-white">
                                        <i class="fas fa-brain me-1"></i>Knowledge (40%)
                                    </th>
                                @endif
                                
                                @if($skillsComponents->count() > 0)
                                    @php
                                        $outputCount = $skillsComponents->where('subcategory', 'Output')->count();
                                        $cpCount = $skillsComponents->where('subcategory', 'Participation')->count();
                                        $activityCount = $skillsComponents->where('subcategory', 'Activity')->count();
                                        $assignmentCount = $skillsComponents->where('subcategory', 'Assignment')->count();
                                    @endphp
                                    <th colspan="{{ $outputCount + ($outputCount > 0 ? 1 : 0) + $cpCount + ($cpCount > 0 ? 1 : 0) + $activityCount + ($activityCount > 0 ? 1 : 0) + $assignmentCount + ($assignmentCount > 0 ? 1 : 0) + 1 }}" class="text-center bg-success text-white">
                                        <i class="fas fa-tools me-1"></i>Skills (50%)
                                    </th>
                                @endif
                                
                                @if($attitudeComponents->count() > 0)
                                    @php
                                        $behaviorCount = $attitudeComponents->where('subcategory', 'Behavior')->count();
                                        $awarenessCount = $attitudeComponents->where('subcategory', 'Awareness')->count();
                                    @endphp
                                    <th colspan="{{ $behaviorCount + ($behaviorCount > 0 ? 1 : 0) + $awarenessCount + ($awarenessCount > 0 ? 1 : 0) + 1 }}" class="text-center bg-warning text-white">
                                        <i class="fas fa-heart me-1"></i>Attitude (10%)
                                    </th>
                                @endif
                                
                                @if($ksaSettings->total_meetings > 0)
                                    <th class="text-center bg-info text-white">
                                        <i class="fas fa-calendar-check me-1"></i>Attendance
                                    </th>
                                @endif
                                
                                <th class="text-center">{{ ucfirst($term) }} Grade</th>
                                <th class="text-center">Status</th>
                            </tr>
                            <tr>
                                <th></th>
                                <!-- Knowledge Sub-components -->
                                @foreach($knowledgeComponents->where('subcategory', 'Exam') as $component)
                                    <th class="text-center bg-primary bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($knowledgeComponents->where('subcategory', 'Exam')->count() > 0)
                                    <th class="text-center bg-primary bg-opacity-25">
                                        <strong>Exam Ave</strong>
                                    </th>
                                @endif
                                
                                @foreach($knowledgeComponents->where('subcategory', 'Quiz') as $component)
                                    <th class="text-center bg-primary bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($knowledgeComponents->where('subcategory', 'Quiz')->count() > 0)
                                    <th class="text-center bg-primary bg-opacity-25">
                                        <strong>Quiz Ave</strong>
                                    </th>
                                @endif
                                
                                <th class="text-center bg-primary bg-opacity-50">
                                    <strong>K Ave</strong>
                                </th>
                                
                                <!-- Skills Sub-components -->
                                @foreach($skillsComponents->where('subcategory', 'Output') as $component)
                                    <th class="text-center bg-success bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($skillsComponents->where('subcategory', 'Output')->count() > 0)
                                    <th class="text-center bg-success bg-opacity-25">
                                        <strong>Output Ave</strong>
                                    </th>
                                @endif
                                
                                @foreach($skillsComponents->where('subcategory', 'Participation') as $component)
                                    <th class="text-center bg-success bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($skillsComponents->where('subcategory', 'Participation')->count() > 0)
                                    <th class="text-center bg-success bg-opacity-25">
                                        <strong>CP Ave</strong>
                                    </th>
                                @endif
                                
                                @foreach($skillsComponents->where('subcategory', 'Activity') as $component)
                                    <th class="text-center bg-success bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($skillsComponents->where('subcategory', 'Activity')->count() > 0)
                                    <th class="text-center bg-success bg-opacity-25">
                                        <strong>Activity Ave</strong>
                                    </th>
                                @endif
                                
                                @foreach($skillsComponents->where('subcategory', 'Assignment') as $component)
                                    <th class="text-center bg-success bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($skillsComponents->where('subcategory', 'Assignment')->count() > 0)
                                    <th class="text-center bg-success bg-opacity-25">
                                        <strong>Assignment Ave</strong>
                                    </th>
                                @endif
                                
                                <th class="text-center bg-success bg-opacity-50">
                                    <strong>S Ave</strong>
                                </th>
                                
                                <!-- Attitude Sub-components -->
                                @foreach($attitudeComponents->where('subcategory', 'Behavior') as $component)
                                    <th class="text-center bg-warning bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($attitudeComponents->where('subcategory', 'Behavior')->count() > 0)
                                    <th class="text-center bg-warning bg-opacity-25">
                                        <strong>Behavior Ave</strong>
                                    </th>
                                @endif
                                
                                @foreach($attitudeComponents->where('subcategory', 'Awareness') as $component)
                                    <th class="text-center bg-warning bg-opacity-10">
                                        {{ $component->name }}<br>
                                        <small>({{ $component->weight }}%)</small>
                                    </th>
                                @endforeach
                                @if($attitudeComponents->where('subcategory', 'Awareness')->count() > 0)
                                    <th class="text-center bg-warning bg-opacity-25">
                                        <strong>Awareness Ave</strong>
                                    </th>
                                @endif
                                
                                <th class="text-center bg-warning bg-opacity-50">
                                    <strong>A Ave</strong>
                                </th>
                                
                                @if($ksaSettings->total_meetings > 0)
                                    <th class="text-center bg-info bg-opacity-25">
                                        <strong>Att. Score</strong><br>
                                        <small>({{ $ksaSettings->attendance_weight }}% of {{ ucfirst($ksaSettings->attendance_category ?? 'N/A') }})</small>
                                    </th>
                                @endif
                                
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($class->students as $student)
                                <tr data-student-id="{{ $student->id }}">
                                    <td class="fw-semibold student-name-cell">
                                        <div class="student-name">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'N/A' }}</div>
                                        <div class="student-id-number">{{ $student->student_id ?? 'N/A' }}</div>
                                    </td>
                                    
                                    <!-- KNOWLEDGE SECTION -->
                                    <!-- Exam Inputs -->
                                    @foreach($knowledgeComponents->where('subcategory', 'Exam') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input knowledge exam-input" 
                                                   data-category="knowledge" 
                                                   data-subcategory="exam"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($knowledgeComponents->where('subcategory', 'Exam')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-primary exam-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Quiz Inputs -->
                                    @foreach($knowledgeComponents->where('subcategory', 'Quiz') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input knowledge quiz-input" 
                                                   data-category="knowledge" 
                                                   data-subcategory="quiz"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($knowledgeComponents->where('subcategory', 'Quiz')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-primary quiz-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Knowledge Average -->
                                    <td class="text-center">
                                        <span class="badge bg-primary knowledge-ave">0.00</span>
                                    </td>
                                    
                                    <!-- SKILLS SECTION -->
                                    <!-- Output Inputs -->
                                    @foreach($skillsComponents->where('subcategory', 'Output') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input skills output-input" 
                                                   data-category="skills" 
                                                   data-subcategory="output"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($skillsComponents->where('subcategory', 'Output')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-success output-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Class Participation Inputs -->
                                    @foreach($skillsComponents->where('subcategory', 'Participation') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input skills participation-input" 
                                                   data-category="skills" 
                                                   data-subcategory="participation"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($skillsComponents->where('subcategory', 'Participation')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-success participation-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Activity Inputs -->
                                    @foreach($skillsComponents->where('subcategory', 'Activity') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input skills activity-input" 
                                                   data-category="skills" 
                                                   data-subcategory="activity"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($skillsComponents->where('subcategory', 'Activity')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-success activity-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Assignment Inputs -->
                                    @foreach($skillsComponents->where('subcategory', 'Assignment') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input skills assignment-input" 
                                                   data-category="skills" 
                                                   data-subcategory="assignment"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($skillsComponents->where('subcategory', 'Assignment')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-success assignment-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Skills Average -->
                                    <td class="text-center">
                                        <span class="badge bg-success skills-ave">0.00</span>
                                    </td>
                                    
                                    <!-- ATTITUDE SECTION -->
                                    <!-- Behavior Inputs -->
                                    @foreach($attitudeComponents->where('subcategory', 'Behavior') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input attitude behavior-input" 
                                                   data-category="attitude" 
                                                   data-subcategory="behavior"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($attitudeComponents->where('subcategory', 'Behavior')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-warning behavior-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Awareness Inputs -->
                                    @foreach($attitudeComponents->where('subcategory', 'Awareness') as $component)
                                        <td>
                                            @php
                                                $existingScore = $componentEntries[$student->id][$component->id]->raw_score ?? '';
                                            @endphp
                                            <input type="number" 
                                                   class="form-control form-control-sm grade-input attitude awareness-input" 
                                                   data-category="attitude" 
                                                   data-subcategory="awareness"
                                                   data-component-id="{{ $component->id }}"
                                                   data-weight="{{ $component->weight }}"
                                                   data-max="{{ $component->max_score }}"
                                                   data-passing="{{ $component->passing_score ?? 0 }}"
                                                   min="0" 
                                                   max="{{ $component->max_score }}" 
                                                   step="0.01"
                                                   value="{{ $existingScore }}"
                                                   placeholder="{{ $component->max_score }}">
                                        </td>
                                    @endforeach
                                    @if($attitudeComponents->where('subcategory', 'Awareness')->count() > 0)
                                        <td class="text-center">
                                            <span class="badge bg-warning awareness-ave">0.00</span>
                                        </td>
                                    @endif
                                    
                                    <!-- Attitude Average -->
                                    <td class="text-center">
                                        <span class="badge bg-warning attitude-ave">0.00</span>
                                    </td>
                                    
                                    <!-- Attendance Score -->
                                    @if($ksaSettings->total_meetings > 0)
                                        <td class="text-center">
                                            @php
                                                $attData = $attendanceData[$student->id] ?? null;
                                                $attScore = $attData['attendance_score'] ?? 0;
                                                $attCount = $attData['attendance_count'] ?? 0;
                                                $totalMeetings = $attData['total_meetings'] ?? $ksaSettings->total_meetings;
                                                $attPercentage = $attData['attendance_percentage'] ?? 0;
                                            @endphp
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge bg-info attendance-score" 
                                                      data-score="{{ $attScore }}"
                                                      data-weight="{{ $ksaSettings->attendance_weight }}"
                                                      data-category="{{ $ksaSettings->attendance_category }}"
                                                      title="Attendance: {{ $attCount }}/{{ $totalMeetings }} ({{ number_format($attPercentage, 1) }}%)">
                                                    {{ number_format($attScore, 2) }}
                                                </span>
                                                <small class="text-muted mt-1">{{ $attCount }}/{{ $totalMeetings }}</small>
                                            </div>
                                        </td>
                                    @endif
                                    
                                    <!-- Calculated Grade -->
                                    <td class="text-center">
                                        <span class="badge bg-primary calculated-grade" data-grade="0">0.00</span>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="text-center">
                                        <span class="badge bg-secondary status-badge">Pending</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No students enrolled in this class</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <!-- Mobile Toggle Button (visible only on mobile) -->
                <div class="mobile-actions-toggle d-md-none mb-3">
                    <button class="btn btn-outline-primary w-100" onclick="toggleMobileActions()" id="toggleActionsBtn">
                        <i class="fas fa-ellipsis-h me-2"></i>Show More Actions
                    </button>
                </div>
                
                <!-- Actions Container -->
                <div class="d-flex justify-content-between align-items-center grade-actions-container" id="gradeActionsContainer">
                    <div class="action-buttons-left">
                        <button class="btn btn-outline-secondary" onclick="exportGrades()">
                            <i class="fas fa-download me-1"></i>Export Template
                        </button>
                        <button class="btn btn-outline-secondary" onclick="importGrades()">
                            <i class="fas fa-upload me-1"></i>Import Grades
                        </button>
                    </div>
                    <button class="btn btn-primary btn-lg save-grades-btn" onclick="saveGrades()">
                        <i class="fas fa-save me-2"></i>Save All Grades
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Student name and ID styling - Desktop */
    .student-name-cell {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .student-name {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
    }
    
    .student-id-number {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 400;
        font-family: 'Courier New', monospace;
    }
    
    /* Hide mobile toggle button on desktop */
    .mobile-actions-toggle {
        display: none;
    }
    
    /* Show actions by default on desktop */
    .grade-actions-container {
        display: flex !important;
    }
    
    .action-buttons-left {
        display: flex;
        gap: 0.5rem;
    }
    
    /* Table scrolling - Fixed for horizontal scroll */
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        position: relative;
        margin-left: 0;
        padding-left: 0;
    }
    
    .table-fixed {
        table-layout: auto;
        position: relative;
        margin-left: 0;
    }
    
    /* Sticky first column - Student Name (Desktop) */
    .table-fixed th:first-child,
    .table-fixed td:first-child {
        position: sticky !important;
        left: 0 !important;
        background: white !important;
        z-index: 10 !important;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1) !important;
        min-width: 180px;
        max-width: 180px;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        margin-left: 0 !important;
    }
    
    .table-fixed thead th:first-child {
        z-index: 20 !important;
        background: #f8f9fa !important;
        font-weight: 700;
    }
    
    /* Ensure thead stays on top during vertical scroll */
    .table-fixed thead {
        position: sticky;
        top: 0;
        z-index: 15;
        background: white;
    }
    
    /* First column in thead gets highest z-index */
    .table-fixed thead th:first-child {
        z-index: 25 !important;
    }
    
    /* Remove any left margin/padding from table container */
    .card-body {
        padding-left: 0 !important;
    }
    
    .grade-input {
        width: 90px;
        text-align: center;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    /* Remove number input spinners */
    .grade-input::-webkit-outer-spin-button,
    .grade-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    .grade-input[type=number] {
        -moz-appearance: textfield;
    }
    
    .grade-input.knowledge {
        border-color: #3b82f6;
    }
    
    .grade-input.skills {
        border-color: #10b981;
    }
    
    .grade-input.attitude {
        border-color: #f59e0b;
    }
    
    /* Validation states */
    .grade-input.is-valid {
        border-color: #10b981 !important;
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .grade-input.is-invalid {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.1);
        animation: shake 0.3s;
    }
    
    /* Pass/Fail color indicators */
    .grade-input.passed {
        border-color: #10b981 !important;
        background-color: rgba(16, 185, 129, 0.15);
        font-weight: 600;
    }
    
    .grade-input.failed {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.15);
        font-weight: 600;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .calculated-grade {
        font-size: 1rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
    }

    /* ===================================
       MOBILE RESPONSIVE STYLES
       =================================== */
    
    @media (max-width: 768px) {
        /* Container adjustments */
        .p-4 {
            padding: 1rem !important;
        }
        
        /* Remove padding from card body on mobile */
        .card-body {
            padding: 0 !important;
        }

        /* Alert banner */
        .alert {
            font-size: 0.875rem;
            padding: 0.75rem;
            margin: 0 1rem 1rem 1rem;
        }

        .alert .row {
            flex-direction: column;
        }

        .alert .col-md-10,
        .alert .col-md-2 {
            width: 100%;
            text-align: left !important;
        }

        .alert .btn {
            width: 100%;
            margin-top: 0.5rem;
        }

        /* Table container */
        .table-responsive {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* Table */
        #gradeTable {
            font-size: 0.75rem;
            min-width: 100%;
            margin-left: 0 !important;
        }

        #gradeTable th,
        #gradeTable td {
            padding: 0.5rem 0.25rem;
            white-space: nowrap;
        }

        /* Sticky first column - Student Name */
        #gradeTable th:first-child,
        #gradeTable td:first-child {
            position: sticky !important;
            left: 0 !important;
            z-index: 10 !important;
            min-width: 150px !important;
            max-width: 150px !important;
            width: 150px !important;
            font-size: 0.8125rem !important;
            padding: 0.625rem 0.5rem !important;
            padding-left: 0.5rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
            line-height: 1.3 !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
            background: white !important;
            margin-left: 0 !important;
        }

        /* Make student name column header more visible */
        #gradeTable thead th:first-child {
            background: #4f46e5 !important;
            color: white !important;
            font-weight: 700 !important;
            border-right: 3px solid #4338ca !important;
            text-align: center !important;
            font-size: 0.8125rem !important;
            vertical-align: middle !important;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.15) !important;
            z-index: 50 !important;
        }

        /* Student name cells */
        #gradeTable tbody td:first-child {
            background: #ffffff !important;
            font-weight: 600 !important;
            border-right: 3px solid #cbd5e1 !important;
            color: #1e293b !important;
            text-align: left !important;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.08) !important;
            z-index: 10 !important;
        }
        
        /* Student name and ID styling */
        .student-name-cell {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.25rem !important;
        }
        
        .student-name {
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            line-height: 1.3 !important;
        }
        
        .student-id-number {
            font-size: 0.6875rem !important;
            color: #64748b !important;
            font-weight: 400 !important;
            font-family: 'Courier New', monospace !important;
        }
        
        /* Fix table scrolling */
        .table-responsive {
            position: relative !important;
            overflow-x: auto !important;
            overflow-y: visible !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        /* Ensure thead is sticky during vertical scroll */
        #gradeTable thead {
            position: sticky !important;
            top: 0 !important;
            z-index: 40 !important;
            background: white !important;
        }
        
        /* Ensure first column header stays on top of everything */
        #gradeTable thead th:first-child {
            z-index: 50 !important;
        }
        
        /* Fix for horizontal scrolling - prevent column from moving */
        #gradeTable {
            position: relative !important;
        }
        
        #gradeTable tbody td:first-child,
        #gradeTable thead th:first-child {
            will-change: transform !important;
        }

        /* Header text */
        #gradeTable thead th {
            font-size: 0.625rem !important;
            padding: 0.5rem 0.25rem !important;
            line-height: 1.2 !important;
            vertical-align: middle !important;
        }

        #gradeTable thead th small {
            font-size: 0.5625rem !important;
            display: block !important;
            margin-top: 0.125rem !important;
        }

        /* Main category headers (Knowledge, Skills, Attitude) */
        #gradeTable thead tr:first-child th {
            font-size: 0.6875rem !important;
            font-weight: 700 !important;
            padding: 0.625rem 0.375rem !important;
        }

        /* Sub-headers */
        #gradeTable thead tr:nth-child(2) th {
            font-size: 0.5625rem !important;
            padding: 0.375rem 0.25rem !important;
        }

        /* Grade inputs */
        .grade-input {
            width: 55px;
            padding: 0.375rem 0.25rem;
            font-size: 0.75rem;
        }

        /* Badges */
        .badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.375rem;
        }

        .calculated-grade {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }

        /* Attendance column */
        .attendance-score {
            font-size: 0.625rem;
        }

        .attendance-score small {
            font-size: 0.5625rem;
        }

        /* Card footer */
        .card-footer {
            padding: 0.75rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 2px solid #e2e8f0;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Mobile actions toggle button */
        .mobile-actions-toggle {
            display: block !important;
            margin-bottom: 0.75rem !important;
        }
        
        #toggleActionsBtn {
            font-size: 0.875rem !important;
            padding: 0.625rem 1rem !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }
        
        #toggleActionsBtn:active {
            transform: scale(0.98);
        }
        
        /* Hide actions by default on mobile */
        .grade-actions-container {
            display: none !important;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        /* Show actions when toggled */
        .grade-actions-container.show {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.75rem !important;
            opacity: 1 !important;
            max-height: 500px !important;
            margin-bottom: 0.75rem !important;
        }
        
        .grade-actions-container.show .action-buttons-left {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
            width: 100% !important;
        }
        
        .grade-actions-container.show .action-buttons-left .btn {
            width: 100% !important;
            font-size: 0.875rem !important;
            padding: 0.625rem 1rem !important;
        }
        
        .grade-actions-container.show .save-grades-btn {
            width: 100% !important;
            font-size: 1rem !important;
            padding: 0.75rem 1rem !important;
            font-weight: 600 !important;
        }

        /* Add padding to table to account for fixed footer */
        .table-responsive {
            padding-bottom: 180px !important;
        }
    }

    @media (max-width: 480px) {
        .p-4 {
            padding: 0.75rem !important;
        }

        #gradeTable {
            font-size: 0.625rem !important;
        }

        #gradeTable th,
        #gradeTable td {
            padding: 0.375rem 0.125rem !important;
        }

        #gradeTable th:first-child,
        #gradeTable td:first-child {
            min-width: 120px !important;
            max-width: 120px !important;
            width: 120px !important;
            font-size: 0.75rem !important;
            padding: 0.5rem 0.5rem !important;
            line-height: 1.3 !important;
        }
        
        #gradeTable thead th:first-child {
            font-size: 0.75rem !important;
        }
        
        .student-name {
            font-size: 0.75rem !important;
        }
        
        .student-id-number {
            font-size: 0.625rem !important;
        }

        /* Make headers even more compact */
        #gradeTable thead th {
            font-size: 0.5625rem !important;
            padding: 0.375rem 0.125rem !important;
        }

        #gradeTable thead tr:first-child th {
            font-size: 0.625rem !important;
        }

        #gradeTable thead tr:nth-child(2) th {
            font-size: 0.5rem !important;
        }

        .grade-input {
            width: 45px !important;
            padding: 0.25rem 0.125rem !important;
            font-size: 0.625rem !important;
        }

        .badge {
            font-size: 0.5625rem !important;
            padding: 0.1875rem 0.3125rem !important;
        }

        .calculated-grade {
            font-size: 0.625rem !important;
            padding: 0.25rem 0.375rem !important;
        }

        .alert h6 {
            font-size: 0.875rem !important;
        }

        .alert p {
            font-size: 0.75rem !important;
        }

        .btn {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem !important;
        }
    }

    /* Landscape mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        .table-responsive {
            max-height: 60vh;
            overflow-y: auto;
        }

        .card-footer {
            position: relative;
        }

        .table-responsive {
            padding-bottom: 1rem;
        }
    }

    /* Touch-friendly improvements */
    @media (max-width: 768px) {
        .grade-input {
            min-height: 36px;
        }

        .btn {
            min-height: 44px;
        }

        /* Larger tap targets */
        button,
        .btn,
        a.btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>

<script>
// Note: classId and term variables are inherited from parent scope (grade_content.blade.php)
// Do NOT redeclare them here to avoid "Identifier already declared" errors

// Grade calculation logic with SUBCATEGORY AVERAGES, weighted components, VALIDATION, PASS/FAIL colors, and x50+50 transmutation
function calculateAllGrades() {
    const rows = document.querySelectorAll('#gradeTable tbody tr[data-student-id]');
    let hasErrors = false;
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('.grade-input');
        let hasInvalidInput = false;
        
        // Group scores by subcategory
        const subcategoryData = {
            exam: { scores: [], weights: [] },
            quiz: { scores: [], weights: [] },
            output: { scores: [], weights: [] },
            participation: { scores: [], weights: [] },
            activity: { scores: [], weights: [] },
            assignment: { scores: [], weights: [] },
            behavior: { scores: [], weights: [] },
            awareness: { scores: [], weights: [] }
        };
        
        // Process each input and group by subcategory
        inputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            const maxScore = parseFloat(input.dataset.max) || 100;
            const passingScore = parseFloat(input.dataset.passing) || 0;
            const weight = parseFloat(input.dataset.weight) || 0;
            const subcategory = input.dataset.subcategory;
            
            // Remove all state classes first
            input.classList.remove('is-invalid', 'is-valid', 'passed', 'failed');
            
            // VALIDATION: Check if score exceeds max
            if (value > maxScore) {
                input.classList.add('is-invalid');
                hasInvalidInput = true;
                hasErrors = true;
                input.title = `Score cannot exceed ${maxScore}`;
            } else if (value < 0) {
                input.classList.add('is-invalid');
                hasInvalidInput = true;
                hasErrors = true;
                input.title = 'Score cannot be negative';
            } else if (value > 0) {
                // Check pass/fail if passing score is set
                const percentage = (value / maxScore) * 100;
                if (passingScore > 0) {
                    if (percentage >= passingScore) {
                        input.classList.add('passed');
                        input.title = `Passed (≥${passingScore}%)`;
                    } else {
                        input.classList.add('failed');
                        input.title = `Failed (<${passingScore}%)`;
                    }
                } else {
                    input.classList.add('is-valid');
                    input.title = '';
                }
            } else {
                input.title = '';
            }
            
            // Apply x50+50 transmutation to EACH component score
            // Formula: (score / max_score × 50) + 50
            const transmuted = value > 0 ? ((value / maxScore) * 50) + 50 : 0;
            
            // Store transmuted scores and weights by subcategory
            if (subcategory && subcategoryData[subcategory]) {
                subcategoryData[subcategory].scores.push(transmuted);
                subcategoryData[subcategory].weights.push(weight);
            }
        });
        
        // Calculate subcategory averages
        const subcategoryAverages = {};
        Object.keys(subcategoryData).forEach(subcat => {
            const data = subcategoryData[subcat];
            if (data.scores.length > 0) {
                // Average of all transmuted scores in this subcategory
                const sum = data.scores.reduce((a, b) => a + b, 0);
                subcategoryAverages[subcat] = sum / data.scores.length;
            } else {
                subcategoryAverages[subcat] = 0;
            }
        });
        
        // Update subcategory average badges
        const examAve = row.querySelector('.exam-ave');
        const quizAve = row.querySelector('.quiz-ave');
        const outputAve = row.querySelector('.output-ave');
        const participationAve = row.querySelector('.participation-ave');
        const activityAve = row.querySelector('.activity-ave');
        const assignmentAve = row.querySelector('.assignment-ave');
        const behaviorAve = row.querySelector('.behavior-ave');
        const awarenessAve = row.querySelector('.awareness-ave');
        
        if (examAve) examAve.textContent = subcategoryAverages.exam.toFixed(2);
        if (quizAve) quizAve.textContent = subcategoryAverages.quiz.toFixed(2);
        if (outputAve) outputAve.textContent = subcategoryAverages.output.toFixed(2);
        if (participationAve) participationAve.textContent = subcategoryAverages.participation.toFixed(2);
        if (activityAve) activityAve.textContent = subcategoryAverages.activity.toFixed(2);
        if (assignmentAve) assignmentAve.textContent = subcategoryAverages.assignment.toFixed(2);
        if (behaviorAve) behaviorAve.textContent = subcategoryAverages.behavior.toFixed(2);
        if (awarenessAve) awarenessAve.textContent = subcategoryAverages.awareness.toFixed(2);
        
        // Calculate category averages from subcategory averages using their weights
        // Knowledge = Exam (60%) + Quiz (40%)
        let knowledgeAvg = 0;
        const examWeight = 60, quizWeight = 40;
        if (subcategoryAverages.exam > 0 || subcategoryAverages.quiz > 0) {
            knowledgeAvg = (subcategoryAverages.exam * (examWeight / 100)) + 
                          (subcategoryAverages.quiz * (quizWeight / 100));
        }
        
        // Skills = Output (40%) + Participation (30%) + Activity (15%) + Assignment (15%)
        let skillsAvg = 0;
        const outputWeight = 40, participationWeight = 30, activityWeight = 15, assignmentWeight = 15;
        if (subcategoryAverages.output > 0 || subcategoryAverages.participation > 0 || 
            subcategoryAverages.activity > 0 || subcategoryAverages.assignment > 0) {
            skillsAvg = (subcategoryAverages.output * (outputWeight / 100)) + 
                       (subcategoryAverages.participation * (participationWeight / 100)) +
                       (subcategoryAverages.activity * (activityWeight / 100)) +
                       (subcategoryAverages.assignment * (assignmentWeight / 100));
        }
        
        // Attitude = Behavior (50%) + Awareness (50%)
        let attitudeAvg = 0;
        const behaviorWeight = 50, awarenessWeight = 50;
        if (subcategoryAverages.behavior > 0 || subcategoryAverages.awareness > 0) {
            attitudeAvg = (subcategoryAverages.behavior * (behaviorWeight / 100)) + 
                         (subcategoryAverages.awareness * (awarenessWeight / 100));
        }
        
        // Update category average badges
        const kAve = row.querySelector('.knowledge-ave');
        const sAve = row.querySelector('.skills-ave');
        const aAve = row.querySelector('.attitude-ave');
        
        if (kAve) kAve.textContent = knowledgeAvg.toFixed(2);
        if (sAve) sAve.textContent = skillsAvg.toFixed(2);
        if (aAve) aAve.textContent = attitudeAvg.toFixed(2);
        
        // Calculate final grade using KSA weights from settings
        const kWeight = {{ $ksaSettings->knowledge_weight ?? 0.4 }};
        const sWeight = {{ $ksaSettings->skills_weight ?? 0.5 }};
        const aWeight = {{ $ksaSettings->attitude_weight ?? 0.1 }};
        const passingGrade = {{ $ksaSettings->passing_grade ?? 74 }};
        let finalGrade = (knowledgeAvg * kWeight) + (skillsAvg * sWeight) + (attitudeAvg * aWeight);
        
        // Apply attendance if configured
        const attendanceScoreElement = row.querySelector('.attendance-score');
        if (attendanceScoreElement) {
            const attendanceScore = parseFloat(attendanceScoreElement.dataset.score) || 0;
            const attendanceWeight = parseFloat(attendanceScoreElement.dataset.weight) || 0;
            const attendanceCategory = attendanceScoreElement.dataset.category;
            
            // Apply attendance to the specified category
            if (attendanceScore > 0 && attendanceWeight > 0 && attendanceCategory) {
                const weightDecimal = attendanceWeight / 100;
                
                if (attendanceCategory === 'knowledge') {
                    // Add attendance contribution to knowledge
                    const attendanceContribution = attendanceScore * weightDecimal;
                    knowledgeAvg = (knowledgeAvg * (1 - weightDecimal)) + attendanceContribution;
                    finalGrade = (knowledgeAvg * kWeight) + (skillsAvg * sWeight) + (attitudeAvg * aWeight);
                } else if (attendanceCategory === 'skills') {
                    // Add attendance contribution to skills
                    const attendanceContribution = attendanceScore * weightDecimal;
                    skillsAvg = (skillsAvg * (1 - weightDecimal)) + attendanceContribution;
                    finalGrade = (knowledgeAvg * kWeight) + (skillsAvg * sWeight) + (attitudeAvg * aWeight);
                } else if (attendanceCategory === 'attitude') {
                    // Add attendance contribution to attitude
                    const attendanceContribution = attendanceScore * weightDecimal;
                    attitudeAvg = (attitudeAvg * (1 - weightDecimal)) + attendanceContribution;
                    finalGrade = (knowledgeAvg * kWeight) + (skillsAvg * sWeight) + (attitudeAvg * aWeight);
                }
            }
        }
        
        // Update display
        const gradeDisplay = row.querySelector('.calculated-grade');
        const statusBadge = row.querySelector('.status-badge');
        
        if (gradeDisplay) {
            gradeDisplay.textContent = finalGrade.toFixed(2);
            gradeDisplay.dataset.grade = finalGrade;
            
            // Update badge color based on CHED scale (passing >= 74, decimal <= 3.0)
            if (hasInvalidInput) {
                gradeDisplay.className = 'badge bg-danger calculated-grade';
                gradeDisplay.textContent = 'ERROR';
            } else if (finalGrade >= 95) {
                gradeDisplay.className = 'badge bg-success calculated-grade';
            } else if (finalGrade >= 86) {
                gradeDisplay.className = 'badge bg-primary calculated-grade';
            } else if (finalGrade >= 74) {
                gradeDisplay.className = 'badge bg-info calculated-grade';
            } else if (finalGrade > 0) {
                gradeDisplay.className = 'badge bg-danger calculated-grade';
            } else {
                gradeDisplay.className = 'badge bg-secondary calculated-grade';
            }
        }
        
        if (statusBadge) {
            if (hasInvalidInput) {
                statusBadge.textContent = 'Invalid';
                statusBadge.className = 'badge bg-danger status-badge';
            } else if (finalGrade >= passingGrade) {
                statusBadge.textContent = 'Passed';
                statusBadge.className = 'badge bg-success status-badge';
            } else if (finalGrade > 0) {
                statusBadge.textContent = 'Failed';
                statusBadge.className = 'badge bg-danger status-badge';
            } else {
                statusBadge.textContent = 'Pending';
                statusBadge.className = 'badge bg-secondary status-badge';
            }
        }
    });
    
    // Show warning if there are errors
    if (hasErrors) {
        showWarning('⚠️ Some scores exceed the maximum allowed values. Please correct them before saving.');
    }
}

// Show warning message
function showWarning(message) {
    // Remove existing warning
    const existingWarning = document.getElementById('validation-warning');
    if (existingWarning) {
        existingWarning.remove();
    }
    
    // Create new warning
    const warning = document.createElement('div');
    warning.id = 'validation-warning';
    warning.className = 'alert alert-warning alert-dismissible fade show position-fixed';
    warning.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 400px;';
    warning.innerHTML = `
        <strong>Validation Error!</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(warning);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (warning.parentNode) {
            warning.remove();
        }
    }, 5000);
}

// Auto-calculate on input
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.grade-input');
    
    // Add input event listener for auto-calculation
    inputs.forEach(input => {
        input.addEventListener('input', calculateAllGrades);
    });
    
    // Calculate grades on page load to show existing data
    calculateAllGrades();
    
    // Add arrow key navigation
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            const currentRow = input.closest('tr');
            const currentCell = input.closest('td');
            const allInputs = Array.from(inputs);
            const currentIndex = allInputs.indexOf(input);
            
            switch(e.key) {
                case 'ArrowRight':
                    e.preventDefault();
                    // Move to next input
                    if (currentIndex < allInputs.length - 1) {
                        allInputs[currentIndex + 1].focus();
                        allInputs[currentIndex + 1].select();
                    }
                    break;
                    
                case 'ArrowLeft':
                    e.preventDefault();
                    // Move to previous input
                    if (currentIndex > 0) {
                        allInputs[currentIndex - 1].focus();
                        allInputs[currentIndex - 1].select();
                    }
                    break;
                    
                case 'ArrowDown':
                    e.preventDefault();
                    // Move to same column, next row
                    const nextRow = currentRow.nextElementSibling;
                    if (nextRow) {
                        const cellIndex = Array.from(currentRow.children).indexOf(currentCell);
                        const nextInput = nextRow.children[cellIndex]?.querySelector('.grade-input');
                        if (nextInput) {
                            nextInput.focus();
                            nextInput.select();
                        }
                    }
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    // Move to same column, previous row
                    const prevRow = currentRow.previousElementSibling;
                    if (prevRow) {
                        const cellIndex = Array.from(currentRow.children).indexOf(currentCell);
                        const prevInput = prevRow.children[cellIndex]?.querySelector('.grade-input');
                        if (prevInput) {
                            prevInput.focus();
                            prevInput.select();
                        }
                    }
                    break;
                    
                case 'Enter':
                    e.preventDefault();
                    // Move to next row, same column
                    const enterNextRow = currentRow.nextElementSibling;
                    if (enterNextRow) {
                        const cellIndex = Array.from(currentRow.children).indexOf(currentCell);
                        const enterNextInput = enterNextRow.children[cellIndex]?.querySelector('.grade-input');
                        if (enterNextInput) {
                            enterNextInput.focus();
                            enterNextInput.select();
                        }
                    }
                    break;
            }
        });
        
        // Select all text on focus for easy overwriting
        input.addEventListener('focus', function() {
            this.select();
        });
    });
});

// Save grades function with validation
function saveGrades() {
    // First, check for validation errors
    const invalidInputs = document.querySelectorAll('.grade-input.is-invalid');
    if (invalidInputs.length > 0) {
        showWarning('❌ Cannot save! Please fix all validation errors first (scores exceeding maximum values).');
        return;
    }
    
    const rows = document.querySelectorAll('#gradeTable tbody tr[data-student-id]');
    const gradesData = [];
    let emptyCount = 0;
    
    rows.forEach(row => {
        const studentId = row.dataset.studentId;
        const inputs = row.querySelectorAll('.grade-input');
        const studentGrades = {
            student_id: studentId,
            components: []
        };
        
        let hasAnyGrade = false;
        inputs.forEach(input => {
            const score = parseFloat(input.value) || 0;
            if (score > 0) hasAnyGrade = true;
            
            studentGrades.components.push({
                component_id: input.dataset.componentId,
                score: score
            });
        });
        
        if (!hasAnyGrade) emptyCount++;
        gradesData.push(studentGrades);
    });
    
    // Warn if many students have no grades
    if (emptyCount > 0 && emptyCount === rows.length) {
        if (!confirm('⚠️ No grades have been entered for any student. Do you want to save anyway?')) {
            return;
        }
    } else if (emptyCount > rows.length / 2) {
        if (!confirm(`⚠️ ${emptyCount} out of ${rows.length} students have no grades entered. Continue saving?`)) {
            return;
        }
    }
    
    // Show loading
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    
    // Send to backend
    fetch(`/teacher/grades/save/${classId}?term=${term}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ grades: gradesData })
    })
    .then(response => response.json())
    .then(data => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        
        if (data.success) {
            showNotification('✅ Grades saved successfully!', 'success');
        } else {
            showWarning('❌ Error saving grades: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        showWarning('❌ Error saving grades. Please try again.');
    });
}

// Show notification (success/info)
function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 400px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Export grades function
function exportGrades() {
    window.location.href = `/teacher/grades/export/${classId}?term=${term}`;
}

// Import grades function
function importGrades() {
    alert('Import functionality will be implemented');
}

// Toggle mobile actions visibility
function toggleMobileActions() {
    const container = document.getElementById('gradeActionsContainer');
    const toggleBtn = document.getElementById('toggleActionsBtn');
    
    if (container.classList.contains('show')) {
        container.classList.remove('show');
        toggleBtn.innerHTML = '<i class="fas fa-ellipsis-h me-2"></i>Show More Actions';
    } else {
        container.classList.add('show');
        toggleBtn.innerHTML = '<i class="fas fa-times me-2"></i>Hide Actions';
    }
}
</script>
