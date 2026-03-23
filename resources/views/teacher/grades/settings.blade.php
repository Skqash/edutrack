@extends('layouts.teacher')

@section('content')
<style>
    .settings-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    
    .ksa-slider-container {
        padding: 2rem;
    }
    
    .slider-group {
        margin-bottom: 2rem;
    }
    
    .slider-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .slider-value {
        font-size: 1.25rem;
        color: #667eea;
    }
    
    input[type="range"] {
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: #e0e0e0;
        outline: none;
        -webkit-appearance: none;
    }
    
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #667eea;
        cursor: pointer;
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #667eea;
        cursor: pointer;
    }
    
    .progress-bar-ksa {
        height: 30px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        margin-top: 1rem;
    }
    
    .progress-segment {
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .component-list {
        list-style: none;
        padding: 0;
    }
    
    .component-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .component-info {
        flex: 1;
    }
    
    .component-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .badge-knowledge { background-color: #2196F3; }
    .badge-skills { background-color: #4CAF50; }
    .badge-attitude { background-color: #9C27B0; }
    
    .locked-banner {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .attendance-category-card {
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .attendance-category-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }
    
    .attendance-category-card.selected {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }
    
    .attendance-category-card .form-check-input {
        display: none;
    }
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">⚙️ Grade Settings</h2>
            <p class="text-muted mb-0">{{ $class->class_name }} - {{ ucfirst($term) }} Term</p>
        </div>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="{{ route('teacher.grades.settings', ['classId' => $class->id, 'term' => 'midterm']) }}" 
                   class="btn btn-sm {{ $term === 'midterm' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Midterm
                </a>
                <a href="{{ route('teacher.grades.settings', ['classId' => $class->id, 'term' => 'final']) }}" 
                   class="btn btn-sm {{ $term === 'final' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Final
                </a>
            </div>
            <a href="{{ route('teacher.grades.entry', $class->id) }}?term={{ $term }}" class="btn btn-sm btn-success">
                <i class="fas fa-edit"></i> Grade Entry
            </a>
            <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($ksaSettings->is_locked)
        <div class="locked-banner">
            <i class="fas fa-lock me-2"></i>
            <strong>Settings Locked:</strong> These settings are currently locked. Unlock to make changes.
            <form method="POST" action="{{ route('teacher.grades.settings.toggle-lock', [$class->id, $term]) }}" class="d-inline">
                @csrf
                <input type="hidden" name="term" value="{{ $term }}">
                <button type="submit" class="btn btn-sm btn-warning ms-2">
                    <i class="fas fa-unlock"></i> Unlock Settings
                </button>
            </form>
        </div>
    @endif

    <!-- KSA Percentage Settings -->
    <div class="settings-card">
        <div class="settings-header">
            <h4 class="mb-0"><i class="fas fa-percentage me-2"></i>KSA Percentage Distribution</h4>
            <small>Adjust the weight of Knowledge, Skills, and Attitude in final grade calculation</small>
        </div>
        <div class="ksa-slider-container">
            <form method="POST" action="{{ route('teacher.grades.settings.update-ksa', $class->id) }}" id="ksaForm">
                @csrf
                <input type="hidden" name="term" value="{{ $term }}">
                
                <!-- Knowledge Slider -->
                <div class="slider-group">
                    <div class="slider-label">
                        <span><i class="fas fa-brain text-primary"></i> Knowledge (K)</span>
                        <span class="slider-value" id="knowledge-value">{{ $ksaSettings->knowledge_weight ?? 40 }}%</span>
                    </div>
                    <input type="range" name="knowledge_weight" id="knowledge-slider" 
                           min="0" max="100" value="{{ $ksaSettings->knowledge_weight ?? 40 }}"
                           {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                </div>

                <!-- Skills Slider -->
                <div class="slider-group">
                    <div class="slider-label">
                        <span><i class="fas fa-cogs text-success"></i> Skills (S)</span>
                        <span class="slider-value" id="skills-value">{{ $ksaSettings->skills_weight ?? 50 }}%</span>
                    </div>
                    <input type="range" name="skills_weight" id="skills-slider" 
                           min="0" max="100" value="{{ $ksaSettings->skills_weight ?? 50 }}"
                           {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                </div>

                <!-- Attitude Slider -->
                <div class="slider-group">
                    <div class="slider-label">
                        <span><i class="fas fa-heart text-danger"></i> Attitude (A)</span>
                        <span class="slider-value" id="attitude-value">{{ $ksaSettings->attitude_weight ?? 10 }}%</span>
                    </div>
                    <input type="range" name="attitude_weight" id="attitude-slider" 
                           min="0" max="100" value="{{ $ksaSettings->attitude_weight ?? 10 }}"
                           {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                </div>

                <!-- Visual Progress Bar -->
                <div class="progress-bar-ksa" id="ksa-progress">
                    <div class="progress-segment" style="width: {{ $ksaSettings->knowledge_weight ?? 40 }}%; background: #2196F3;" id="k-segment">
                        K: <span id="k-percent">{{ $ksaSettings->knowledge_weight ?? 40 }}</span>%
                    </div>
                    <div class="progress-segment" style="width: {{ $ksaSettings->skills_weight ?? 50 }}%; background: #4CAF50;" id="s-segment">
                        S: <span id="s-percent">{{ $ksaSettings->skills_weight ?? 50 }}</span>%
                    </div>
                    <div class="progress-segment" style="width: {{ $ksaSettings->attitude_weight ?? 10 }}%; background: #9C27B0;" id="a-segment">
                        A: <span id="a-percent">{{ $ksaSettings->attitude_weight ?? 10 }}</span>%
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <span class="badge bg-secondary" id="total-badge">Total: <span id="total-percent">100</span>%</span>
                </div>

                @if(!$ksaSettings->is_locked)
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Save KSA Percentages
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Attendance Settings -->
    <div class="settings-card">
        <div class="settings-header">
            <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Attendance Configuration</h4>
            <small>Configure how attendance affects the final grade</small>
        </div>
        <div class="ksa-slider-container">
            <form method="POST" action="{{ route('teacher.grades.settings.update-attendance', $class->id) }}" id="attendanceForm">
                @csrf
                <input type="hidden" name="term" value="{{ $term }}">
                
                <div class="row">
                    <!-- Number of Meetings -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Total Number of Meetings
                            </label>
                            <input type="number" 
                                   name="total_meetings" 
                                   class="form-control form-control-lg" 
                                   value="{{ $ksaSettings->total_meetings ?? 20 }}" 
                                   min="1" 
                                   max="100"
                                   {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}
                                   required>
                            <small class="text-muted">
                                Total class meetings for {{ ucfirst($term) }} term
                            </small>
                        </div>
                    </div>

                    <!-- Attendance Weight -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-percentage text-success me-2"></i>
                                Attendance Weight
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" 
                                       name="attendance_weight" 
                                       id="attendance-weight-input"
                                       class="form-control" 
                                       value="{{ $ksaSettings->attendance_weight ?? 10 }}" 
                                       min="0" 
                                       max="100"
                                       step="0.1"
                                       {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">
                                Weight within selected category
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Attendance Category Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-bullseye text-warning me-2"></i>
                        Attendance Affects Which Category?
                    </label>
                    <p class="text-muted small mb-3">
                        Choose which KSA category attendance should impact. For example:
                        <br>• <strong>Skills-based subjects</strong> (Lab, PE, Workshop) → Select Skills
                        <br>• <strong>Theory-based subjects</strong> (Math, Science) → Select Knowledge
                        <br>• <strong>Behavior-focused subjects</strong> (Values, Ethics) → Select Attitude
                    </p>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-check attendance-category-card {{ ($ksaSettings->attendance_category ?? 'skills') === 'knowledge' ? 'selected' : '' }}" 
                                 onclick="selectAttendanceCategory('knowledge')" 
                                 style="{{ ($ksaSettings->is_locked ?? false) ? 'pointer-events: none; opacity: 0.6;' : '' }}">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="attendance_category" 
                                       id="attendance-knowledge" 
                                       value="knowledge"
                                       {{ ($ksaSettings->attendance_category ?? 'skills') === 'knowledge' ? 'checked' : '' }}
                                       {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                                <label class="form-check-label w-100" for="attendance-knowledge">
                                    <div class="text-center py-3">
                                        <i class="fas fa-brain fa-3x text-primary mb-2"></i>
                                        <h5 class="mb-1">Knowledge</h5>
                                        <small class="text-muted">Theory-based subjects</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check attendance-category-card {{ ($ksaSettings->attendance_category ?? 'skills') === 'skills' ? 'selected' : '' }}" 
                                 onclick="selectAttendanceCategory('skills')"
                                 style="{{ ($ksaSettings->is_locked ?? false) ? 'pointer-events: none; opacity: 0.6;' : '' }}">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="attendance_category" 
                                       id="attendance-skills" 
                                       value="skills"
                                       {{ ($ksaSettings->attendance_category ?? 'skills') === 'skills' ? 'checked' : '' }}
                                       {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                                <label class="form-check-label w-100" for="attendance-skills">
                                    <div class="text-center py-3">
                                        <i class="fas fa-cogs fa-3x text-success mb-2"></i>
                                        <h5 class="mb-1">Skills</h5>
                                        <small class="text-muted">Practical/Lab subjects</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check attendance-category-card {{ ($ksaSettings->attendance_category ?? 'skills') === 'attitude' ? 'selected' : '' }}" 
                                 onclick="selectAttendanceCategory('attitude')"
                                 style="{{ ($ksaSettings->is_locked ?? false) ? 'pointer-events: none; opacity: 0.6;' : '' }}">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="attendance_category" 
                                       id="attendance-attitude" 
                                       value="attitude"
                                       {{ ($ksaSettings->attendance_category ?? 'skills') === 'attitude' ? 'checked' : '' }}
                                       {{ ($ksaSettings->is_locked ?? false) ? 'disabled' : '' }}>
                                <label class="form-check-label w-100" for="attendance-attitude">
                                    <div class="text-center py-3">
                                        <i class="fas fa-heart fa-3x text-danger mb-2"></i>
                                        <h5 class="mb-1">Attitude</h5>
                                        <small class="text-muted">Behavior-focused subjects</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Formula Display -->
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="fas fa-calculator me-2"></i>Attendance Calculation Formula</h6>
                    <p class="mb-2">
                        <strong>Attendance Score</strong> = (Attendance Count / Total Meetings) × 50 + 50
                    </p>
                    <p class="mb-2">
                        <strong>Category Impact</strong> = Attendance Score × <span id="weight-display">{{ $ksaSettings->attendance_weight ?? 10 }}</span>%
                    </p>
                    <hr>
                    <p class="mb-0 small">
                        <strong>Example:</strong> If a student attends 17 out of 20 meetings:
                        <br>• Attendance Score = (17/20) × 50 + 50 = <strong>92.5</strong>
                        <br>• Impact on <span id="category-display">{{ ucfirst($ksaSettings->attendance_category ?? 'Skills') }}</span> = 92.5 × {{ $ksaSettings->attendance_weight ?? 10 }}% = <strong>{{ number_format(92.5 * (($ksaSettings->attendance_weight ?? 10) / 100), 2) }}</strong> points
                    </p>
                </div>

                @if(!$ksaSettings->is_locked)
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Save Attendance Settings
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Component Management -->
    <div class="row">
        <!-- Knowledge Components -->
        <div class="col-md-4">
            <div class="settings-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-brain me-2"></i>Knowledge Components</h5>
                </div>
                <div class="card-body">
                    <ul class="component-list">
                        @forelse($components['knowledge'] ?? [] as $component)
                            <li class="component-item">
                                <div class="component-info">
                                    <strong>{{ $component->name }}</strong>
                                    <div class="small text-muted">
                                        Max: {{ $component->max_score }} pts | Weight: {{ $component->weight_percentage }}%
                                    </div>
                                </div>
                                <div class="component-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editComponent({{ $component->id }})" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('teacher.grades.settings.delete-component', [$class->id, $component->id]) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this component?')" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center py-3">No components yet</li>
                        @endforelse
                    </ul>
                    @if(!$ksaSettings->is_locked)
                        <button class="btn btn-sm btn-primary w-100 mt-2" onclick="showAddModal('knowledge')">
                            <i class="fas fa-plus me-1"></i>Add Component
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Skills Components -->
        <div class="col-md-4">
            <div class="settings-card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Skills Components</h5>
                </div>
                <div class="card-body">
                    <ul class="component-list">
                        @forelse($components['skills'] ?? [] as $component)
                            <li class="component-item">
                                <div class="component-info">
                                    <strong>{{ $component->name }}</strong>
                                    <div class="small text-muted">
                                        Max: {{ $component->max_score }} pts | Weight: {{ $component->weight_percentage }}%
                                    </div>
                                </div>
                                <div class="component-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editComponent({{ $component->id }})" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('teacher.grades.settings.delete-component', [$class->id, $component->id]) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this component?')" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center py-3">No components yet</li>
                        @endforelse
                    </ul>
                    @if(!$ksaSettings->is_locked)
                        <button class="btn btn-sm btn-success w-100 mt-2" onclick="showAddModal('skills')">
                            <i class="fas fa-plus me-1"></i>Add Component
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attitude Components -->
        <div class="col-md-4">
            <div class="settings-card">
                <div class="card-header bg-purple text-white" style="background-color: #9C27B0;">
                    <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Attitude Components</h5>
                </div>
                <div class="card-body">
                    <ul class="component-list">
                        @forelse($components['attitude'] ?? [] as $component)
                            <li class="component-item">
                                <div class="component-info">
                                    <strong>{{ $component->name }}</strong>
                                    <div class="small text-muted">
                                        Max: {{ $component->max_score }} pts | Weight: {{ $component->weight_percentage }}%
                                    </div>
                                </div>
                                <div class="component-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editComponent({{ $component->id }})" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('teacher.grades.settings.delete-component', [$class->id, $component->id]) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this component?')" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center py-3">No components yet</li>
                        @endforelse
                    </ul>
                    @if(!$ksaSettings->is_locked)
                        <button class="btn btn-sm btn-purple w-100 mt-2" style="background-color: #9C27B0; color: white;" onclick="showAddModal('attitude')">
                            <i class="fas fa-plus me-1"></i>Add Component
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="settings-card">
        <div class="card-body">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-flex gap-2 flex-wrap">
                <form method="POST" action="{{ route('teacher.grades.settings.initialize', $class->id) }}">
                    @csrf
                    <input type="hidden" name="term" value="{{ $term }}">
                    <button type="submit" class="btn btn-outline-primary" onclick="return confirm('Initialize default components? This will add standard KSA components.')" {{ $ksaSettings->is_locked ? 'disabled' : '' }}>
                        <i class="fas fa-magic me-1"></i>Initialize Default Components
                    </button>
                </form>
                
                <form method="POST" action="{{ route('teacher.grades.settings.toggle-lock', [$class->id, $term]) }}">
                    @csrf
                    <input type="hidden" name="term" value="{{ $term }}">
                    <button type="submit" class="btn {{ $ksaSettings->is_locked ? 'btn-warning' : 'btn-secondary' }}">
                        <i class="fas fa-{{ $ksaSettings->is_locked ? 'unlock' : 'lock' }} me-1"></i>
                        {{ $ksaSettings->is_locked ? 'Unlock' : 'Lock' }} Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Component Modal -->
<div class="modal fade" id="addComponentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('teacher.grades.settings.add-component', $class->id) }}">
                @csrf
                <input type="hidden" name="term" value="{{ $term }}">
                <input type="hidden" name="category" id="add-category">
                
                <div class="modal-header">
                    <h5 class="modal-title">Add New Component</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Component Type</label>
                        <input type="text" name="component_type" class="form-control" placeholder="e.g., quiz, output, activity" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Quiz 6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maximum Score</label>
                        <input type="number" name="max_score" class="form-control" value="100" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight Percentage</label>
                        <input type="number" name="weight_percentage" class="form-control" value="10" min="0" max="100" step="0.01" required>
                        <small class="text-muted">Percentage weight within this category</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Component</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// KSA Slider Logic
const knowledgeSlider = document.getElementById('knowledge-slider');
const skillsSlider = document.getElementById('skills-slider');
const attitudeSlider = document.getElementById('attitude-slider');

const knowledgeValue = document.getElementById('knowledge-value');
const skillsValue = document.getElementById('skills-value');
const attitudeValue = document.getElementById('attitude-value');

const kSegment = document.getElementById('k-segment');
const sSegment = document.getElementById('s-segment');
const aSegment = document.getElementById('a-segment');

const kPercent = document.getElementById('k-percent');
const sPercent = document.getElementById('s-percent');
const aPercent = document.getElementById('a-percent');

const totalPercent = document.getElementById('total-percent');
const totalBadge = document.getElementById('total-badge');

function updateKSA() {
    const k = parseInt(knowledgeSlider.value);
    const s = parseInt(skillsSlider.value);
    const a = parseInt(attitudeSlider.value);
    const total = k + s + a;
    
    knowledgeValue.textContent = k + '%';
    skillsValue.textContent = s + '%';
    attitudeValue.textContent = a + '%';
    
    kSegment.style.width = k + '%';
    sSegment.style.width = s + '%';
    aSegment.style.width = a + '%';
    
    kPercent.textContent = k;
    sPercent.textContent = s;
    aPercent.textContent = a;
    
    totalPercent.textContent = total;
    
    if (total === 100) {
        totalBadge.className = 'badge bg-success';
    } else {
        totalBadge.className = 'badge bg-danger';
    }
}

knowledgeSlider?.addEventListener('input', updateKSA);
skillsSlider?.addEventListener('input', updateKSA);
attitudeSlider?.addEventListener('input', updateKSA);

function showAddModal(category) {
    document.getElementById('add-category').value = category;
    new bootstrap.Modal(document.getElementById('addComponentModal')).show();
}

function editComponent(id) {
    // TODO: Implement edit modal
    alert('Edit functionality coming soon!');
}

// Attendance category selection
function selectAttendanceCategory(category) {
    // Remove selected class from all cards
    document.querySelectorAll('.attendance-category-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.getElementById('attendance-' + category).checked = true;
    
    // Update category display in formula
    document.getElementById('category-display').textContent = category.charAt(0).toUpperCase() + category.slice(1);
}

// Update weight display when attendance weight changes
document.getElementById('attendance-weight-input')?.addEventListener('input', function() {
    document.getElementById('weight-display').textContent = this.value;
});
</script>
@endsection
