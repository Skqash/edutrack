@extends('layouts.teacher')

@section('content')
    <div style="height: var(--topbar-height);"></div>

    <style>
        /* Advanced Grade Entry System Styles */
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --knowledge-color: #3b82f6;
            --skills-color: #10b981;
            --attitude-color: #8b5cf6;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .grade-system-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .system-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .system-body {
            padding: 2rem;
        }

        /* Grading Scheme Configuration */
        .grading-config {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .config-section {
            margin-bottom: 1.5rem;
        }

        .config-section h5 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .component-item {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .component-item:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .component-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .component-name {
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .component-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: var(--info-color);
            color: white;
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
        }

        .btn-add {
            background: var(--success-color);
            color: white;
        }

        .btn-icon:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        .component-config {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
            align-items: end;
        }

        .form-control-sm {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control-sm:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Grade Entry Table */
        .grade-table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .grade-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.875rem;
        }

        .grade-table thead {
            background: var(--light-bg);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .grade-table th {
            padding: 1rem 0.75rem;
            text-align: center;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            position: relative;
        }

        .grade-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .grade-input {
            width: 80px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-align: center;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .grade-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .grade-input.knowledge {
            background-color: rgba(59, 130, 246, 0.05);
            border-color: var(--knowledge-color);
        }

        .grade-input.skills {
            background-color: rgba(16, 185, 129, 0.05);
            border-color: var(--skills-color);
        }

        .grade-input.attitude {
            background-color: rgba(139, 92, 246, 0.05);
            border-color: var(--attitude-color);
        }

        /* KSA Color Coding */
        .knowledge-header {
            background-color: var(--knowledge-color) !important;
            color: white !important;
        }

        .skills-header {
            background-color: var(--skills-color) !important;
            color: white !important;
        }

        .attitude-header {
            background-color: var(--attitude-color) !important;
            color: white !important;
        }

        .calculated-grade {
            background-color: var(--light-bg);
            font-weight: 600;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.5rem;
            min-width: 60px;
        }

        .final-grade {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Weight Display */
        .weight-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .weight-knowledge {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--knowledge-color);
        }

        .weight-skills {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--skills-color);
        }

        .weight-attitude {
            background-color: rgba(139, 92, 246, 0.1);
            color: var(--attitude-color);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--text-secondary);
            padding: 0.75rem 2rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 16px 16px 0 0;
            border: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .component-config {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .grade-table {
                font-size: 0.75rem;
            }

            .grade-input {
                width: 60px;
                padding: 0.25rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
            }
        }

        /* Grade Statistics Styles */
        .grade-summary-container {
            margin-bottom: 2rem;
        }

        .stat-item {
            padding: 1rem;
            background: var(--light-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Grade Distribution */
        .grade-distribution {
            margin-top: 1rem;
        }

        .grade-bar {
            display: flex;
            height: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .grade-range {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .grade-range.excellent {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .grade-range.good {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .grade-range.fair {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .grade-range.poor {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .grade-range span {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .grade-range:hover span {
            opacity: 1;
        }

        /* Grade Status Badges */
        .grade-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .grade-status.excellent {
            background: var(--success-color);
            color: white;
        }

        .grade-status.good {
            background: #3b82f6;
            color: white;
        }

        .grade-status.fair {
            background: var(--warning-color);
            color: white;
        }

        .grade-status.poor {
            background: var(--danger-color);
            color: white;
        }

        .grade-status.incomplete {
            background: var(--secondary-color);
            color: white;
        }

        /* Grade Input Validation */
        .grade-input {
            transition: all 0.3s ease;
        }

        .grade-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .grade-input.is-valid {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .grade-input.is-invalid {
            border-color: var(--danger-color);
            background: rgba(239, 68, 68, 0.05);
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Advanced Grade Entry System -->
    <div class="container-fluid pt-5 pb-4">
        <div class="grade-system-container">
            <!-- System Header -->
            <div class="system-header">
                <div>
                    <h2 class="h4 mb-1">📊 Advanced Grade Entry System</h2>
                    <p class="mb-0 opacity-75">{{ $class->name }} - {{ ucfirst($term) }} Term</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('teacher.grades.content', $class->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                    <button class="btn btn-success btn-sm" onclick="calculateAllGrades()">
                        <i class="fas fa-calculator me-1"></i> Calculate All
                    </button>
                </div>
            </div>

            <div class="system-body">
                <!-- Quick Info Banner -->
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2">
                                <i class="fas fa-info-circle me-2"></i>
                                Grade Entry for {{ ucfirst($term) }} Term
                            </h6>
                            <p class="mb-0 small">
                                Enter grades for each student using the KSA (Knowledge-Skills-Attitude) framework.
                                Components are loaded from your configuration.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('teacher.grades.content', $class->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cog me-1"></i> Manage Components
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Grading Configuration Section -->
                <div class="grading-config mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-sliders-h me-2"></i>
                                Grading Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Term Weights</h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="midtermWeight" class="form-label">Midterm Weight (%)</label>
                                            <input type="number" class="form-control" id="midtermWeight" value="40"
                                                min="0" max="100" step="1">
                                        </div>
                                        <div class="col-6">
                                            <label for="finalWeight" class="form-label">Final Weight (%)</label>
                                            <input type="number" class="form-control" id="finalWeight" value="60"
                                                min="0" max="100" step="1">
                                        </div>
                                    </div>
                                    <small class="text-muted">Weights must total 100%</small>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Grade Settings</h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-label mb-1">Attendance Affects Grade</label>
                                            <small class="text-muted d-block">Include attendance in grade
                                                calculations</small>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="attendanceToggle"
                                                {{ $ksaSettings->attendance_affects_grade ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="attendanceToggle"></label>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-primary btn-sm" onclick="saveConfiguration()">
                                            <i class="fas fa-save me-1"></i> Save Configuration
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grade Statistics Summary -->
                <div class="grade-summary-container mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient text-white"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="mb-0">📊 Grade Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number" id="totalStudents">0</div>
                                        <div class="stat-label">Total Students</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-success" id="averageGrade">0.0</div>
                                        <div class="stat-label">Average Grade</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-info" id="passingRate">0%</div>
                                        <div class="stat-label">Passing Rate (≥75)</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <div class="stat-number text-warning" id="completionRate">0%</div>
                                        <div class="stat-label">Completion Rate</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Distribution -->
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">Grade Distribution</h6>
                                <div class="grade-distribution">
                                    <div class="grade-bar">
                                        <div class="grade-range excellent" id="excellentCount" style="width: 0%">
                                            <span>Excellent (90-100)</span>
                                        </div>
                                        <div class="grade-range good" id="goodCount" style="width: 0%">
                                            <span>Good (80-89)</span>
                                        </div>
                                        <div class="grade-range fair" id="fairCount" style="width: 0%">
                                            <span>Fair (70-79)</span>
                                        </div>
                                        <div class="grade-range poor" id="poorCount" style="width: 0%">
                                            <span>Poor (<70)< /span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grade Entry Table -->
                <div class="grade-table-container">
                    <table class="grade-table" id="gradeTable">
                        <thead>
                            <tr>
                                <th rowspan="2" style="min-width: 150px;">Student Name</th>

                                <!-- Knowledge Headers -->
                                <th colspan="2" class="knowledge-header">
                                    <i class="fas fa-brain me-1"></i>Knowledge
                                    ({{ ($ksaSettings->knowledge_weight ?? 0.4) * 100 }}%)
                                </th>

                                <!-- Skills Headers -->
                                <th colspan="2" class="skills-header">
                                    <i class="fas fa-tools me-1"></i>Skills
                                    ({{ ($ksaSettings->skills_weight ?? 0.5) * 100 }}%)
                                </th>

                                <!-- Attitude Headers -->
                                <th colspan="2" class="attitude-header">
                                    <i class="fas fa-heart me-1"></i>Attitude
                                    ({{ ($ksaSettings->attitude_weight ?? 0.1) * 100 }}%)
                                </th>

                                <th rowspan="2" style="min-width: 80px;">Midterm Grade</th>
                                <th rowspan="2" style="min-width: 80px;">Final Grade</th>
                                <th rowspan="2" style="min-width: 80px;">Final Score</th>
                                <th rowspan="2" style="min-width: 80px;">Status</th>
                            </tr>
                            <tr>
                                <!-- Knowledge Sub-components -->
                                @if (isset($ksaSettings->components->knowledge))
                                    @foreach ($ksaSettings->components->knowledge as $index => $component)
                                        <th class="knowledge-header">{{ $component->name }} ({{ $component->weight }}%)
                                        </th>
                                    @endforeach
                                @else
                                    <th class="knowledge-header">Exams (60%)</th>
                                    <th class="knowledge-header">Quizzes (40%)</th>
                                @endif

                                <!-- Skills Sub-components -->
                                @if (isset($ksaSettings->components->skills))
                                    @foreach ($ksaSettings->components->skills as $index => $component)
                                        <th class="skills-header">{{ $component->name }} ({{ $component->weight }}%)</th>
                                    @endforeach
                                @else
                                    <th class="skills-header">Activities (50%)</th>
                                    <th class="skills-header">Assignments (50%)</th>
                                @endif

                                <!-- Attitude Sub-components -->
                                @if (isset($ksaSettings->components->attitude))
                                    @foreach ($ksaSettings->components->attitude as $index => $component)
                                        <th class="attitude-header">{{ $component->name }} ({{ $component->weight }}%)
                                        </th>
                                    @endforeach
                                @else
                                    <th class="attitude-header">Behavior (50%)</th>
                                    <th class="attitude-header">Awareness (50%)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="gradeTableBody">
                            <!-- Student rows will be populated here -->
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="exportGrades()">
                        <i class="fas fa-download me-2"></i>Export Template
                    </button>
                    <button class="btn btn-secondary" onclick="importGrades()">
                        <i class="fas fa-upload me-2"></i>Import Grades
                    </button>
                    <button class="btn btn-info" onclick="calculateAllGrades()">
                        <i class="fas fa-calculator me-2"></i>Calculate All
                    </button>
                    <button class="btn btn-primary" onclick="saveGrades()">
                        <i class="fas fa-save me-2"></i>Save All Grades
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Component Edit Modal -->
    <div class="modal fade" id="componentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Component</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Component Name</label>
                        <input type="text" id="modalComponentName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight (%)</label>
                        <input type="number" id="modalComponentWeight" class="form-control" min="0"
                            max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Score</label>
                        <input type="number" id="modalComponentMax" class="form-control" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateComponent()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Advanced Grade Entry System JavaScript
        let gradeData = {};

        // Initialize component configs from PHP ksaSettings
        let componentConfigs = {
            knowledge: {
                weight: {{ ($ksaSettings->knowledge_weight ?? 0.4) * 100 }},
                components: []
            },
            skills: {
                weight: {{ ($ksaSettings->skills_weight ?? 0.5) * 100 }},
                components: []
            },
            attitude: {
                weight: {{ ($ksaSettings->attitude_weight ?? 0.1) * 100 }},
                components: []
            }
        };

        let currentEditCategory = null;
        let currentEditIndex = null;

        // Initialize the system
        document.addEventListener('DOMContentLoaded', function() {
            initializeComponents();
            loadStudents();
            setupEventListeners();
            updateTableHeaders();
            updateGradeStatistics(); // Initialize statistics
        });

        function initializeComponents() {
            // Initialize component configurations
            ['knowledge', 'skills', 'attitude'].forEach(category => {
                const container = document.getElementById(category + 'Components');
                const rows = container.querySelectorAll('.component-row');

                componentConfigs[category].components = [];
                rows.forEach((row, index) => {
                    const inputs = row.querySelectorAll('input');
                    componentConfigs[category].components.push({
                        name: inputs[0].value,
                        weight: parseFloat(inputs[1].value),
                        maxScore: parseFloat(inputs[2].value),
                        index: index
                    });
                });
            });
        }

        function loadStudents() {
            const students = @json(
                $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name ?? 'Unknown',
                    ];
                }));

            const tbody = document.getElementById('gradeTableBody');
            tbody.innerHTML = '';

            students.forEach(student => {
                gradeData[student.id] = {
                    name: student.name,
                    components: {}
                };

                const row = createStudentRow(student);
                tbody.appendChild(row);
            });
        }

        function createStudentRow(student) {
            const row = document.createElement('tr');
            row.dataset.studentId = student.id;

            // Student name cell
            const nameCell = document.createElement('td');
            nameCell.innerHTML = `
        <div class="student-info">
            <strong>${student.name}</strong>
            <small class="text-muted d-block">ID: ${student.id}</small>
        </div>
    `;
            row.appendChild(nameCell);

            // Component input cells
            ['knowledge', 'skills', 'attitude'].forEach(category => {
                componentConfigs[category].components.forEach(component => {
                    const inputCell = document.createElement('td');
                    inputCell.innerHTML = `
                <input type="number" 
                       class="form-control form-control-sm grade-input" 
                       data-student-id="${student.id}" 
                       data-category="${category}" 
                       data-component-index="${component.index}"
                       placeholder="0-${component.max_score || 100}"
                       min="0" 
                       max="${component.max_score || 100}"
                       step="0.1"
                       oninput="calculateGrades(${student.id})">
            `;
                    row.appendChild(inputCell);
                });
            });

            // Grade display cells
            const midtermCell = document.createElement('td');
            midtermCell.innerHTML = '<span id="midterm-' + student.id + '" class="grade-display">-</span>';
            row.appendChild(midtermCell);

            const finalCell = document.createElement('td');
            finalCell.innerHTML = '<span id="final-' + student.id + '" class="grade-display">-</span>';
            row.appendChild(finalCell);

            const scoreCell = document.createElement('td');
            scoreCell.innerHTML = '<span id="score-' + student.id + '" class="grade-display">-</span>';
            row.appendChild(scoreCell);

            // Status cell
            const statusCell = document.createElement('td');
            statusCell.innerHTML = '<span id="status-' + student.id + '" class="grade-status incomplete">Incomplete</span>';
            row.appendChild(statusCell);

            return row;
        }

        function calculateGrades(studentId) {
            const midtermWeight = parseFloat(document.getElementById('midtermWeight').value) || 40;
            const finalWeight = parseFloat(document.getElementById('finalWeight').value) || 60;

            let midtermScores = {};
            let finalScores = {};
            let hasAllInputs = true;

            // Calculate scores for each category
            ['knowledge', 'skills', 'attitude'].forEach(category => {
                let categoryScore = 0;
                let categoryTotal = 0;

                componentConfigs[category].components.forEach(component => {
                    const input = document.querySelector(
                        `input[data-student-id="${studentId}"][data-category="${category}"][data-component-index="${component.index}"]`
                    );

                    if (input && input.value !== '') {
                        const value = parseFloat(input.value) || 0;
                        const maxScore = component.max_score || 100;
                        const weightedScore = (value / maxScore) * component.weight;

                        categoryScore += weightedScore;
                        categoryTotal += component.weight;

                        gradeData[studentId].components[`${category}_${component.index}`] = value;

                        // Validate input
                        validateGradeInput(input, value, 0, maxScore);
                    } else {
                        hasAllInputs = false;
                        if (input) validateGradeInput(input, null, 0, component.max_score || 100);
                    }
                });

                const categoryFinal = categoryTotal > 0 ? (categoryScore / categoryTotal) * 100 : 0;
                midtermScores[category] = categoryFinal;
                finalScores[category] =
                categoryFinal; // Same for now, can be modified for different midterm/final components
            });

            // Calculate midterm grade
            const midtermGrade = (
                (midtermScores.knowledge * componentConfigs.knowledge.weight / 100) +
                (midtermScores.skills * componentConfigs.skills.weight / 100) +
                (midtermScores.attitude * componentConfigs.attitude.weight / 100)
            );

            // Calculate final grade
            const finalGrade = (
                (finalScores.knowledge * componentConfigs.knowledge.weight / 100) +
                (finalScores.skills * componentConfigs.skills.weight / 100) +
                (finalScores.attitude * componentConfigs.attitude.weight / 100)
            );

            // Calculate final score
            const finalScore = (midtermGrade * midtermWeight / 100) + (finalGrade * finalWeight / 100);

            // Update display
            const midtermElement = document.getElementById(`midterm-${studentId}`);
            const finalElement = document.getElementById(`final-${studentId}`);
            const scoreElement = document.getElementById(`score-${studentId}`);
            const statusElement = document.getElementById(`status-${studentId}`);

            if (midtermElement) midtermElement.textContent = hasAllInputs ? midtermGrade.toFixed(1) : '-';
            if (finalElement) finalElement.textContent = hasAllInputs ? finalGrade.toFixed(1) : '-';
            if (scoreElement) scoreElement.textContent = hasAllInputs ? finalScore.toFixed(1) : '-';

            // Update status badge
            if (statusElement && hasAllInputs) {
                const status = getGradeStatus(finalScore);
                statusElement.className = `grade-status ${status.class}`;
                statusElement.textContent = status.label;
            } else if (statusElement) {
                statusElement.className = 'grade-status incomplete';
                statusElement.textContent = 'Incomplete';
            }

            // Store in grade data
            gradeData[studentId].midtermGrade = midtermGrade;
            gradeData[studentId].finalGrade = finalGrade;
            gradeData[studentId].finalScore = finalScore;
            gradeData[studentId].hasAllInputs = hasAllInputs;

            // Update statistics
            updateGradeStatistics();
        }

        function getGradeStatus(score) {
            if (score >= 90) return {
                class: 'excellent',
                label: 'Excellent'
            };
            if (score >= 80) return {
                class: 'good',
                label: 'Good'
            };
            if (score >= 75) return {
                class: 'fair',
                label: 'Fair'
            };
            if (score >= 0) return {
                class: 'poor',
                label: 'Poor'
            };
            return {
                class: 'incomplete',
                label: 'Incomplete'
            };
        }

        function validateGradeInput(input, value, min, max) {
            if (!input) return;

            input.classList.remove('is-valid', 'is-invalid');
            input.classList.add('grade-input');

            if (value !== null && value !== '') {
                if (value >= min && value <= max) {
                    input.classList.add('is-valid');
                } else {
                    input.classList.add('is-invalid');
                }
            }
        }

        function updateGradeStatistics() {
            const students = Object.keys(gradeData);
            const totalStudents = students.length;
            let totalGrade = 0;
            let passingCount = 0;
            let completedCount = 0;

            const distribution = {
                excellent: 0,
                good: 0,
                fair: 0,
                poor: 0
            };

            students.forEach(studentId => {
                const data = gradeData[studentId];
                if (data.finalScore && data.hasAllInputs) {
                    totalGrade += data.finalScore;
                    completedCount++;

                    if (data.finalScore >= 75) passingCount++;

                    if (data.finalScore >= 90) distribution.excellent++;
                    else if (data.finalScore >= 80) distribution.good++;
                    else if (data.finalScore >= 70) distribution.fair++;
                    else distribution.poor++;
                }
            });

            const averageGrade = completedCount > 0 ? totalGrade / completedCount : 0;
            const passingRate = completedCount > 0 ? (passingCount / completedCount) * 100 : 0;
            const completionRate = totalStudents > 0 ? (completedCount / totalStudents) * 100 : 0;

            // Update statistics display
            document.getElementById('totalStudents').textContent = totalStudents;
            document.getElementById('averageGrade').textContent = averageGrade.toFixed(1);
            document.getElementById('passingRate').textContent = passingRate.toFixed(0) + '%';
            document.getElementById('completionRate').textContent = completionRate.toFixed(0) + '%';

            // Update grade distribution
            if (completedCount > 0) {
                document.getElementById('excellentCount').style.width = (distribution.excellent / completedCount * 100) +
                    '%';
                document.getElementById('goodCount').style.width = (distribution.good / completedCount * 100) + '%';
                document.getElementById('fairCount').style.width = (distribution.fair / completedCount * 100) + '%';
                document.getElementById('poorCount').style.width = (distribution.poor / completedCount * 100) + '%';
            }
        }

        function calculateAllGrades() {
            showLoading();

            setTimeout(() => {
                Object.keys(gradeData).forEach(studentId => {
                    calculateGrades(studentId);
                });
                hideLoading();
                showNotification('All grades calculated successfully!', 'success');
            }, 500);
        }

        function saveGrades() {
            showLoading();

            const grades = [];
            Object.keys(gradeData).forEach(studentId => {
                const data = gradeData[studentId];
                if (data.hasAllInputs) {
                    grades.push({
                        student_id: studentId,
                        components: data.components,
                        midterm_grade: data.midtermGrade,
                        final_grade: data.finalGrade,
                        final_score: data.finalScore
                    });
                }
            });

            // Send to server
            fetch(`/teacher/grades/advanced/{{ $class->id }}/save-grades`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        term: '{{ $term }}',
                        grades: grades
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification('Failed to save grades: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error saving grades:', error);
                    showNotification('Error saving grades. Please try again.', 'error');
                });
        }

        function showLoading() {
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(overlay);
        }

        function hideLoading() {
            const overlay = document.querySelector('.loading-overlay');
            if (overlay) overlay.remove();
        }

        function updateTableHeaders() {
            // Update headers based on current component configuration
            const headerRow = document.querySelector('#gradeTable thead tr:last-child');
            const knowledgeHeaders = headerRow.querySelectorAll('.knowledge-header');
            const skillsHeaders = headerRow.querySelectorAll('.skills-header');
            const attitudeHeaders = headerRow.querySelectorAll('.attitude-header');

            // Update knowledge headers
            componentConfigs.knowledge.components.forEach((component, index) => {
                if (knowledgeHeaders[index]) {
                    knowledgeHeaders[index].textContent = `${component.name} (${component.weight}%)`;
                }
            });

            // Update skills headers
            componentConfigs.skills.components.forEach((component, index) => {
                if (skillsHeaders[index]) {
                    skillsHeaders[index].textContent = `${component.name} (${component.weight}%)`;
                }
            });

            // Update attitude headers
            componentConfigs.attitude.components.forEach((component, index) => {
                if (attitudeHeaders[index]) {
                    attitudeHeaders[index].textContent = `${component.name} (${component.weight}%)`;
                }
            });
        }

        function addComponent(category) {
            const container = document.getElementById(category + 'Components');
            const newIndex = container.children.length;

            const componentRow = document.createElement('div');
            componentRow.className = 'component-row';
            componentRow.dataset.index = newIndex;

            componentRow.innerHTML = `
        <input type="text" class="form-control-sm" placeholder="Component name">
        <input type="number" class="form-control-sm" placeholder="Weight %" value="0" min="0" max="100">
        <input type="number" class="form-control-sm" placeholder="Max score" value="100">
        <button class="btn-icon btn-delete" onclick="removeComponent('${category}', ${newIndex})">
            <i class="fas fa-trash"></i>
        </button>
    `;

            container.appendChild(componentRow);

            // Re-initialize components
            initializeComponents();
            updateTableHeaders();

            // Re-create student rows with new components
            const tbody = document.getElementById('gradeTableBody');
            tbody.innerHTML = '';
            Object.keys(gradeData).forEach(studentId => {
                const student = {
                    id: studentId,
                    name: gradeData[studentId].name
                };
                const row = createStudentRow(student);
                tbody.appendChild(row);
            });
        }

        function removeComponent(category, index) {
            const container = document.getElementById(category + 'Components');
            const row = container.querySelector(`[data-index="${index}"]`);

            if (container.children.length > 1) {
                row.remove();
                initializeComponents();
                updateTableHeaders();

                // Re-create student rows
                const tbody = document.getElementById('gradeTableBody');
                tbody.innerHTML = '';
                Object.keys(gradeData).forEach(studentId => {
                    const student = {
                        id: studentId,
                        name: gradeData[studentId].name
                    };
                    const row = createStudentRow(student);
                    tbody.appendChild(row);
                });
            }
        }

        function handleWeightChange(category, componentIndex, newWeight) {
            const components = componentConfigs[category].components;
            const otherComponents = components.filter((_, index) => index !== componentIndex);
            const otherTotalWeight = otherComponents.reduce((sum, comp) => sum + comp.weight, 0);
            const totalWeight = otherTotalWeight + newWeight;

            // Check if the new weight would exceed 100%
            if (totalWeight > 100) {
                showNotification(
                    `❌ Weight validation failed. Total would exceed 100% (${otherTotalWeight}% + ${newWeight}% = ${totalWeight}%). Please adjust other weights first.`,
                    'error');

                // Reset to previous value
                const input = document.querySelector(
                    `#${category}Components .component-row[data-index="${componentIndex}"] input:nth-child(2)`);
                if (input) {
                    input.value = components[componentIndex].weight;
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 2000);
                }
                return;
            }

            // If valid, redistribute remaining weight among other components
            const remainingWeight = 100 - newWeight;
            if (otherComponents.length > 0 && remainingWeight > 0) {
                // Calculate equal distribution
                const baseWeight = Math.floor(remainingWeight / otherComponents.length * 100) / 100;
                const totalBaseWeight = baseWeight * otherComponents.length;
                const remainder = Math.round((remainingWeight - totalBaseWeight) * 100) / 100;

                // Distribute base weight and remainder
                otherComponents.forEach((component, index) => {
                    let weight = baseWeight;
                    if (index < remainder * 100) { // Distribute remainder as 0.01 increments
                        weight += 0.01;
                    }
                    component.weight = Math.round(weight * 100) / 100; // Round to 2 decimal places
                });

                // Update UI for other components
                otherComponents.forEach((_, index) => {
                    const actualIndex = components.findIndex((_, i) => i !== componentIndex && components[i] ===
                        otherComponents[index - (index > componentIndex ? 1 : 0)]);
                    const input = document.querySelector(
                        `#${category}Components .component-row[data-index="${actualIndex}"] input:nth-child(2)`);
                    if (input) {
                        input.value = otherComponents[index].weight;
                    }
                });

                showNotification(
                    `✅ Weight updated! Remaining ${remainingWeight}% redistributed among ${otherComponents.length} other component(s).`,
                    'success');
            }

            // Update the changed component's weight
            components[componentIndex].weight = newWeight;

            // Update component configs
            initializeComponents();
            updateTableHeaders();
            calculateAllGrades();
        }

        function setupEventListeners() {
            // Weight change listeners for midterm/final
            document.getElementById('midtermWeight').addEventListener('input', function() {
                const finalWeight = 100 - parseFloat(this.value);
                document.getElementById('finalWeight').value = finalWeight;
                recalculateAllGrades();
            });

            document.getElementById('finalWeight').addEventListener('input', function() {
                const midtermWeight = 100 - parseFloat(this.value);
                document.getElementById('midtermWeight').value = midtermWeight;
                recalculateAllGrades();
            });

            // Attendance toggle listener
            document.getElementById('attendanceToggle').addEventListener('change', function() {
                toggleAttendanceAffectsGrade(this.checked);
            });

            // Component input listeners - specifically handle weight changes
            document.querySelectorAll('.component-row input:nth-child(2)').forEach(
            input => { // Weight inputs (2nd input in each row)
                input.addEventListener('input', function() {
                    const row = this.closest('.component-row');
                    const category = row.closest('[id$="Components"]').id.replace('Components',
                    ''); // Extract category from container ID
                    const componentIndex = parseInt(row.dataset.index);
                    const newWeight = parseFloat(this.value) || 0;

                    // Trigger automatic redistribution
                    handleWeightChange(category, componentIndex, newWeight);
                });
            });

            // Other component inputs (name and max score)
            document.querySelectorAll('.component-row input:not(:nth-child(2))').forEach(input => {
                input.addEventListener('input', function() {
                    initializeComponents();
                    updateTableHeaders();
                });
            });
        }

        function recalculateAllGrades() {
            Object.keys(gradeData).forEach(studentId => {
                calculateGrades(studentId);
            });
        }

        function saveConfiguration() {
            const config = {
                midtermWeight: document.getElementById('midtermWeight').value,
                finalWeight: document.getElementById('finalWeight').value,
                components: componentConfigs,
                attendance_affects_grade: document.getElementById('attendanceToggle').checked
            };

            // Save to localStorage or send to server
            localStorage.setItem('gradeConfig', JSON.stringify(config));

            // Show success message
            showNotification('Configuration saved successfully!', 'success');
        }

        function toggleAttendanceAffectsGrade(enabled) {
            // Send request to server to update attendance setting
            fetch(`{{ route('teacher.components.toggle-attendance', $class->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        attendance_affects_grade: enabled,
                        term: '{{ $term }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        // Update grade calculations if needed
                        calculateAllGrades();
                    } else {
                        showNotification(data.message, 'error');
                        // Reset toggle if failed
                        document.getElementById('attendanceToggle').checked = !enabled;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to update attendance setting', 'error');
                    // Reset toggle if failed
                    document.getElementById('attendanceToggle').checked = !enabled;
                });
        }

        function resetToDefault() {
            if (confirm('Are you sure you want to reset to default configuration?')) {
                document.getElementById('midtermWeight').value = 40;
                document.getElementById('finalWeight').value = 60;

                // Reset components to default
                location.reload();
            }
        }

        function saveGrades() {
            const grades = [];
            Object.keys(gradeData).forEach(studentId => {
                grades.push({
                    studentId: studentId,
                    name: gradeData[studentId].name,
                    components: gradeData[studentId].components,
                    midtermGrade: gradeData[studentId].midtermGrade,
                    finalGrade: gradeData[studentId].finalGrade,
                    finalScore: gradeData[studentId].finalScore
                });
            });

            console.log('Saving grades:', grades);
            showNotification('Grades saved successfully!', 'success');
        }

        function exportGrades() {
            // Create CSV template
            let csv = 'Student Name,';

            // Add headers for each component
            ['knowledge', 'skills', 'attitude'].forEach(category => {
                componentConfigs[category].components.forEach(component => {
                    csv += `${component.name},`;
                });
            });

            csv += '\n';

            // Add student data
            Object.keys(gradeData).forEach(studentId => {
                csv += `${gradeData[studentId].name},`;
                ['knowledge', 'skills', 'attitude'].forEach(category => {
                    componentConfigs[category].components.forEach(component => {
                        const value = gradeData[studentId].components[
                            `${category}_${component.index}`] || '';
                        csv += `${value},`;
                    });
                });
                csv += '\n';
            });

            // Download CSV
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'grade_template.csv';
            a.click();
        }

        function importGrades() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.csv';
            input.onchange = function(e) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Parse CSV and update grade data
                    showNotification('Grades imported successfully!', 'success');
                };
                reader.readAsText(file);
            };
            input.click();
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        </div>
    `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>

@endsection
