@extends('layouts.teacher')

@section('content')
    <div class="container-fluid py-4" style="margin-top: 80px;">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-cogs me-2"></i>
                            Grading Mode Configuration
                        </h4>
                        <p class="mb-0 opacity-75">{{ $class->class_name }} | {{ ucfirst($request->get('term', 'midterm')) }}
                            Term</p>
                    </div>
                    <div>
                        <a href="{{ route('teacher.grades') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mode Selection Cards -->
        <div class="row mb-4">
            @foreach ($modes as $modeKey => $modeInfo)
                <div class="col-lg-6 mb-3">
                    <div class="card mode-card" data-mode="{{ $modeKey }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $modeInfo['name'] }}</h5>
                                    <p class="text-muted small mb-2">{{ $modeInfo['description'] }}</p>
                                    <small class="text-success d-block mb-2"><strong>Best for:</strong>
                                        {{ $modeInfo['best_for'] }}</small>
                                </div>
                                @if ($settings && $settings->grading_mode === $modeKey)
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-check"></i> Active
                                    </span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-2">Features:</strong>
                                <ul class="list-unstyled small">
                                    @foreach ($modeInfo['features'] as $feature)
                                        <li class="mb-1">
                                            <i class="fas fa-check-circle text-success me-1"></i> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm select-mode-btn"
                                data-mode="{{ $modeKey }}">
                                <i class="fas fa-arrow-right me-1"></i>
                                {{ $settings && $settings->grading_mode === $modeKey ? 'Update' : 'Select' }} Mode
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Configuration Form -->
        <form id="modeConfigForm" action="{{ route('teacher.grades.mode.update', $class->id) }}" method="POST">
            @csrf

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-sliders-h me-2"></i>
                        Grading Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <input type="hidden" name="term" value="{{ request()->input('term', 'midterm') }}">

                    <!-- Grading Mode Selection -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Select Grading Mode *</strong></label>
                        <div id="modeSelection">
                            @foreach ($modes as $modeKey => $modeInfo)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="grading_mode"
                                        id="mode_{{ $modeKey }}" value="{{ $modeKey }}"
                                        {{ $settings && $settings->grading_mode === $modeKey ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mode_{{ $modeKey }}">
                                        <strong>{{ $modeInfo['name'] }}</strong> - {{ $modeInfo['description'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <!-- KSA Percentages -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label"><strong>Knowledge % *</strong></label>
                            <input type="number" class="form-control" name="knowledge_percentage"
                                value="{{ $settings->knowledge_percentage ?? 40 }}" min="0" max="100"
                                step="0.01" required>
                            <small class="text-muted">Percentage of final grade</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Skills % *</strong></label>
                            <input type="number" class="form-control" name="skills_percentage"
                                value="{{ $settings->skills_percentage ?? 50 }}" min="0" max="100"
                                step="0.01" required>
                            <small class="text-muted">Percentage of final grade</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Attitude % *</strong></label>
                            <input type="number" class="form-control" name="attitude_percentage"
                                value="{{ $settings->attitude_percentage ?? 10 }}" min="0" max="100"
                                step="0.01" required>
                            <small class="text-muted">Percentage of final grade</small>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>KSA Total:</strong> <span id="ksaTotal">100</span>% (must equal 100%)
                    </div>

                    <hr>

                    <!-- Quiz Entry Mode -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Quiz Entry Mode *</strong></label>
                        <div id="quizModeSelection">
                            @foreach ($quizModes as $quizKey => $quizLabel)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="quiz_entry_mode"
                                        id="quiz_{{ $quizKey }}" value="{{ $quizKey }}"
                                        {{ $settings && $settings->quiz_entry_mode === $quizKey ? 'checked' : '' }}>
                                    <label class="form-check-label" for="quiz_{{ $quizKey }}">
                                        {{ $quizLabel }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <!-- Output Format -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Grading Sheet Format *</strong></label>
                        <select name="output_format" class="form-select" required>
                            <option value="standard"
                                {{ $settings && $settings->output_format === 'standard' ? 'selected' : '' }}>
                                Standard Format - Full KSA breakdown with components
                            </option>
                            <option value="detailed"
                                {{ $settings && $settings->output_format === 'detailed' ? 'selected' : '' }}>
                                Detailed Format - All components with calculations
                            </option>
                            <option value="summary"
                                {{ $settings && $settings->output_format === 'summary' ? 'selected' : '' }}>
                                Summary Format - Final grades only
                            </option>
                        </select>
                    </div>

                    <hr>

                    <!-- Feature Toggles -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_esignature" id="enableESig"
                                    value="1" {{ $settings && $settings->enable_esignature ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableESig">
                                    <strong>Enable E-Signature Upload</strong>
                                    <br>
                                    <small class="text-muted">Allow students to upload e-signatures for attendance</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_auto_calculation"
                                    id="enableAutoCalc" value="1" checked>
                                <label class="form-check-label" for="enableAutoCalc">
                                    <strong>Enable Auto Calculation</strong>
                                    <br>
                                    <small class="text-muted">System auto-calculates grades from components</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_weighted_components"
                                    id="enableWeighted" value="1" checked>
                                <label class="form-check-label" for="enableWeighted">
                                    <strong>Enable Weighted Components</strong>
                                    <br>
                                    <small class="text-muted">Components have individual weights</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Grading Thresholds -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Passing Grade (out of 100) *</strong></label>
                            <input type="number" class="form-control" name="passing_grade"
                                value="{{ $settings->passing_grade ?? 75 }}" min="0" max="100"
                                step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Attendance Weight %</strong></label>
                            <input type="number" class="form-control" name="attendance_weight_percentage"
                                value="{{ $settings->attendance_weight_percentage ?? 0 }}" min="0" max="100"
                                step="0.01">
                            <small class="text-muted">0 = attendance doesn't affect final grade</small>
                        </div>
                    </div>

                    <!-- Hybrid Mode Component Configuration -->
                    <div id="hybridConfigSection" style="display: none;" class="mb-4">
                        <hr>
                        <h5 class="mb-3">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Component Entry Modes
                        </h5>
                        <p class="text-muted">Select whether each component should use manual or automated entry:</p>

                        <div id="componentModesContainer">
                            @if ($components)
                                @foreach ($components->groupBy('category') as $category => $categoryComponents)
                                    <div class="mb-3">
                                        <h6 class="text-muted">{{ $category }}</h6>
                                        @foreach ($categoryComponents as $component)
                                            <div class="form-check mb-2">
                                                <select name="component_modes[{{ $component->id }}]"
                                                    class="form-select form-select-sm">
                                                    <option value="manual">Manual - Teacher enters scores</option>
                                                    <option value="automated">Automated - System calculates</option>
                                                </select>
                                                <small class="text-muted d-block mt-1">{{ $component->name }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Save Configuration
                        </button>
                        <a href="{{ route('teacher.grades') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .mode-card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .mode-card:hover {
            border-color: #007bff;
            box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.15);
        }

        .mode-card.active {
            border-color: #28a745;
            background-color: #f0f8f5;
        }

        #ksaTotal {
            font-weight: bold;
            color: #28a745;
        }

        #ksaTotal.invalid {
            color: #dc3545;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('modeConfigForm');
            const modeRadios = document.querySelectorAll('input[name="grading_mode"]');
            const hybridSection = document.getElementById('hybridConfigSection');

            // KSA percentage calculator
            function updateKSATotal() {
                const k = parseFloat(document.querySelector('input[name="knowledge_percentage"]').value) || 0;
                const s = parseFloat(document.querySelector('input[name="skills_percentage"]').value) || 0;
                const a = parseFloat(document.querySelector('input[name="attitude_percentage"]').value) || 0;
                const total = k + s + a;

                const totalEl = document.getElementById('ksaTotal');
                totalEl.textContent = total.toFixed(2);
                totalEl.classList.toggle('invalid', Math.abs(total - 100) > 0.01);
            }

            document.querySelectorAll(
                    'input[name="knowledge_percentage"], input[name="skills_percentage"], input[name="attitude_percentage"]'
                )
                .forEach(el => el.addEventListener('change', updateKSATotal));

            updateKSATotal();

            // Show/hide hybrid configuration
            modeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'hybrid') {
                        hybridSection.style.display = 'block';
                    } else {
                        hybridSection.style.display = 'none';
                    }
                });
            });

            // Trigger on page load
            const activeMode = document.querySelector('input[name="grading_mode"]:checked');
            if (activeMode && activeMode.value === 'hybrid') {
                hybridSection.style.display = 'block';
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                const total = parseFloat(document.querySelector('input[name="knowledge_percentage"]')
                        .value) +
                    parseFloat(document.querySelector('input[name="skills_percentage"]').value) +
                    parseFloat(document.querySelector('input[name="attitude_percentage"]').value);

                if (Math.abs(total - 100) > 0.01) {
                    e.preventDefault();
                    alert('Knowledge + Skills + Attitude must equal 100%');
                    return false;
                }
            });

            // Mode card selection
            document.querySelectorAll('.select-mode-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const mode = this.dataset.mode;
                    document.getElementById('mode_' + mode).checked = true;

                    // Scroll to form
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });
        });
    </script>
@endsection
