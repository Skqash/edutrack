@extends('layouts.teacher')

@section('content')
    <style>
        /* Enhanced Modern Design System */
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .create-class-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Page Header */
        .page-header-create {
            background: linear-gradient(135deg, var(--white) 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .page-header-create::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            transform: translate(50%, -50%);
            opacity: 0.05;
        }

        .page-header-create h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-header-create p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        /* Enhanced Form Card */
        .form-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .form-card:hover {
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        .form-card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-card-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .form-card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .form-card-body {
            padding: 2.5rem;
        }

        /* Enhanced Form Groups */
        .form-group-modern {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label-modern {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-label-modern i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control-modern,
        .form-select-modern {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
            position: relative;
        }

        .form-control-modern:focus,
        .form-select-modern:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        .form-control-modern:hover,
        .form-select-modern:hover {
            border-color: var(--primary-light);
        }

        .form-text-modern {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-text-modern i {
            margin-right: 0.25rem;
            font-size: 0.75rem;
        }

        /* Enhanced Buttons */
        .btn-submit-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-submit-modern:hover::before {
            left: 100%;
        }

        .btn-submit-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-cancel-modern {
            background: var(--white);
            color: var(--text-secondary);
            padding: 1rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .btn-cancel-modern:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            text-decoration: none;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid var(--border-color);
            justify-content: flex-end;
        }

        .required-mark {
            color: var(--danger-color);
            margin-left: 0.25rem;
            font-weight: 700;
        }

        /* Enhanced Row Spacing */
        .row {
            margin-bottom: 0;
        }

        .row>div {
            padding-right: 1rem;
            padding-left: 1rem;
        }

        /* Validation States */
        .is-invalid {
            border-color: var(--danger-color) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .is-valid {
            border-color: var(--success-color) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2310b981' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        /* Progress Indicator */
        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .progress-step::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: var(--border-color);
            z-index: -1;
        }

        .progress-step:last-child::before {
            display: none;
        }

        .progress-step.active::before {
            background: var(--primary-color);
        }

        .progress-step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--white);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            transition: var(--transition);
        }

        .progress-step.active .progress-step-circle {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .progress-step-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-align: center;
            max-width: 80px;
        }

        .progress-step.active .progress-step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .create-class-container {
                margin: 1rem auto;
                padding: 1rem;
            }

            .form-card-body {
                padding: 1.5rem;
            }

            .page-header-create {
                padding: 1.5rem;
            }

            .page-header-create h1 {
                font-size: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit-modern,
            .btn-cancel-modern {
                width: 100%;
                justify-content: center;
            }

            .progress-indicator {
                display: none;
            }

            .row>div {
                padding-right: 0.5rem;
                padding-left: 0.5rem;
            }
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .btn-submit-modern.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: var(--white);
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

    <div class="create-class-container">
        <!-- Page Header -->
        <div class="page-header-create">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Create New Class</h1>
                    <p class="text-muted mb-0">Set up a new class for your students</p>
                </div>
                <a href="{{ route('teacher.classes') }}" class="btn-cancel-modern">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-indicator">
            <div class="progress-step active" id="step1">
                <div class="progress-step-circle">1</div>
                <div class="progress-step-label">Basic Info</div>
            </div>
            <div class="progress-step" id="step2">
                <div class="progress-step-circle">2</div>
                <div class="progress-step-label">Class Details</div>
            </div>
            <div class="progress-step" id="step3">
                <div class="progress-step-circle">3</div>
                <div class="progress-step-label">Review</div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="h5 mb-0">
                    <i class="fas fa-chalkboard me-2"></i>
                    Class Information
                </h2>
            </div>

            <div class="form-card-body">
                <form action="{{ route('teacher.classes.store') }}" method="POST">
                    @csrf

                    <!-- Class Name -->
                    <div class="form-group-modern">
                        <label for="class_name" class="form-label-modern">
                            <i class="fas fa-chalkboard-teacher me-1"></i>
                            Class Name
                            <span class="required-mark">*</span>
                        </label>
                        <input type="text" class="form-control-modern @error('class_name') is-invalid @enderror"
                            id="class_name" name="class_name" value="{{ old('class_name') }}"
                            placeholder="e.g., BSIT 1-A, CS 101, Math 201" required>
                        @error('class_name')
                            <div class="text-danger form-text-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">
                            <i class="fas fa-info-circle"></i>
                            Enter a descriptive name for your class (e.g., BSIT 1-A, CS 101, Math 201)
                        </small>
                    </div>

                    <!-- Subject Selection -->
                    <div class="form-group-modern">
                        <label for="subject_id" class="form-label-modern">
                            <i class="fas fa-book-open me-1"></i>
                            Subject
                            <span class="required-mark">*</span>
                        </label>
                        <select class="form-select-modern @error('subject_id') is-invalid @enderror" id="subject_id"
                            name="subject_id" required>
                            <option value="">-- Select Subject --</option>
                            @if (isset($assignedSubjects) && $assignedSubjects->count() > 0)
                                @foreach ($assignedSubjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                                        data-code="{{ $subject->subject_code ?? '' }}"
                                        data-units="{{ $subject->credit_hours ?? 3 }}"
                                        data-course="{{ $subject->course->course_name ?? '' }}">
                                        {{ $subject->subject_code ?? '' }} - {{ $subject->subject_name }}
                                        @if ($subject->course)
                                            ({{ $subject->course->course_name }})
                                        @endif
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No subjects available</option>
                            @endif
                        </select>
                        @error('subject_id')
                            <div class="text-danger form-text-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">
                            <i class="fas fa-info-circle"></i>
                            Select the subject you will be teaching in this class
                        </small>
                    </div>

                    <!-- Course Selection -->
                    <div class="form-group-modern">
                        <label for="course_id" class="form-label-modern">
                            <i class="fas fa-book me-1"></i>
                            Course/Program
                            <span class="required-mark">*</span>
                        </label>
                        <select class="form-select-modern @error('course_id') is-invalid @enderror" id="course_id"
                            name="course_id" required>
                            <option value="">-- Select Course --</option>
                            @if (isset($courses))
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" data-school="{{ $course->college ?? '' }}"
                                        data-department="{{ $course->department ?? '' }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }} ({{ $course->course_code ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('course_id')
                            <div class="text-danger form-text-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">
                            <i class="fas fa-info-circle"></i>
                            Select the course/program this class belongs to. If the subject you selected has a matching
                            course, it will be automatically selected.
                        </small>
                    </div>

                    <!-- Year and Section Row -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="year" class="form-label-modern">
                                    <i class="fas fa-calendar me-1"></i>
                                    Year Level
                                </label>
                                <select class="form-select-modern @error('year') is-invalid @enderror" id="year"
                                    name="year">
                                    <option value="">-- Select Year --</option>
                                    <option value="1" {{ old('year') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year') == '4' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year')
                                    <div class="text-danger form-text-modern">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="section" class="form-label-modern">
                                    <i class="fas fa-users me-1"></i>
                                    Section
                                </label>
                                <select class="form-select-modern @error('section') is-invalid @enderror" id="section"
                                    name="section">
                                    <option value="">-- Select Section --</option>
                                    <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A
                                    </option>
                                    <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B
                                    </option>
                                    <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>Section C
                                    </option>
                                    <option value="D" {{ old('section') == 'D' ? 'selected' : '' }}>Section D
                                    </option>
                                    <option value="E" {{ old('section') == 'E' ? 'selected' : '' }}>Section E
                                    </option>
                                </select>
                                @error('section')
                                    <div class="text-danger form-text-modern">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="capacity" class="form-label-modern">
                                    <i class="fas fa-user-friends me-1"></i>
                                    Capacity
                                </label>
                                <input type="number" class="form-control-modern @error('capacity') is-invalid @enderror"
                                    id="capacity" name="capacity" value="{{ old('capacity', 40) }}" min="1"
                                    max="100" placeholder="40">
                                @error('capacity')
                                    <div class="text-danger form-text-modern">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Semester and Academic Year Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="semester" class="form-label-modern">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Semester
                                    <span class="required-mark">*</span>
                                </label>
                                <select class="form-select-modern @error('semester') is-invalid @enderror" id="semester"
                                    name="semester" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First
                                        Semester</option>
                                    <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second
                                        Semester</option>
                                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer
                                    </option>
                                </select>
                                @error('semester')
                                    <div class="text-danger form-text-modern">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label for="academic_year" class="form-label-modern">
                                    <i class="fas fa-calendar me-1"></i>
                                    Academic Year
                                    <span class="required-mark">*</span>
                                </label>
                                <input type="text"
                                    class="form-control-modern @error('academic_year') is-invalid @enderror"
                                    id="academic_year" name="academic_year"
                                    value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}"
                                    placeholder="e.g., 2024-2025" required>
                                @error('academic_year')
                                    <div class="text-danger form-text-modern">{{ $message }}</div>
                                @enderror
                                <small class="form-text-modern">Format: YYYY-YYYY (e.g., 2024-2025)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group-modern">
                        <label for="description" class="form-label-modern">
                            <i class="fas fa-align-left me-1"></i>
                            Description (Optional)
                        </label>
                        <textarea class="form-control-modern @error('description') is-invalid @enderror" id="description" name="description"
                            rows="4" placeholder="Add any additional information about this class...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger form-text-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">Optional: Add notes or special instructions for this class</small>
                    </div>

                    <!-- Student Assignment -->
                    <div class="form-group-modern">
                        <label class="form-label-modern">
                            <i class="fas fa-user-graduate me-1"></i>
                            Assign Students (Optional)
                        </label>
                        <div class="form-text-modern mb-2">
                            You can select existing students to add to this class. The selected students will have their
                            class and school/department information updated automatically.
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="text-muted small">Quick presets:</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="presetAll">All
                                        Students</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="presetYear1">Year
                                        1</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="presetYear2">Year
                                        2</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="presetYear3">Year
                                        3</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="presetYear4">Year
                                        4</button>
                                    <button type="button" class="btn btn-sm btn-outline-info ms-auto"
                                        id="previewChangesBtn">
                                        <i class="fas fa-eye me-1"></i> Preview Changes
                                    </button>
                                </div>
                                <small class="form-text-modern">Use presets to auto-select students by year. Preview shows
                                    what will be updated before saving.</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_year_filter" class="form-label-modern">Filter by Year</label>
                                <select id="student_year_filter" class="form-select-modern">
                                    <option value="">All Years</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="student_search" class="form-label-modern">Search by Student ID or Name</label>
                                <input type="text" id="student_search" class="form-control-modern"
                                    placeholder="Search students...">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-semibold">Available Students</span>
                                        <span class="badge bg-secondary ms-2" id="availableCount">0</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            id="selectAllAvailable">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="deselectAllAvailable">Clear</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-semibold">Selected Students</span>
                                        <span class="badge bg-success ms-2" id="selectedCount">0</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            id="selectAllSelected">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            id="deselectAllSelected">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded p-3" style="height: 320px; overflow-y: auto;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div id="availableStudents" class="h-100">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-spinner fa-spin"></i> Loading students...
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="selectedStudents" class="h-100">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-spinner fa-spin"></i> Loading students...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input for selected students -->
                    <input type="hidden" name="assigned_students" id="assigned_students"
                        value="{{ old('assigned_students', '') }}">

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit-modern">
                            <i class="fas fa-check"></i>
                            Create Class
                        </button>
                        <a href="{{ route('teacher.classes') }}" class="btn-cancel-modern">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Changes Modal -->
    <div class="modal fade" id="previewChangesModal" tabindex="-1" aria-labelledby="previewChangesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewChangesModalLabel">Preview Changes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Class</strong>: <span id="previewClassName">-</span><br>
                        <strong>Course</strong>: <span id="previewCourseName">-</span><br>
                        <strong>School</strong>: <span id="previewSchool">-</span><br>
                        <strong>Department</strong>: <span id="previewDepartment">-</span><br>
                        <strong>Selected Students</strong>: <span id="previewStudentCount">0</span>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> Saving will update the selected students' assigned class, school, and
                        department.
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-2">Preview Student List (first 20)</h6>
                        <ul class="list-group" id="previewStudentList" style="max-height: 250px; overflow-y: auto;"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmPreviewSaveBtn">Proceed to Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject_id');
            const courseSelect = document.getElementById('course_id');
            const classNameInput = document.getElementById('class_name');
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.btn-submit-modern');

            // Student assignment controls
            const studentYearFilter = document.getElementById('student_year_filter');
            const studentSearch = document.getElementById('student_search');
            const availableStudentsDiv = document.getElementById('availableStudents');
            const selectedStudentsDiv = document.getElementById('selectedStudents');
            const assignedStudentsInput = document.getElementById('assigned_students');
            const availableCount = document.getElementById('availableCount');
            const selectedCount = document.getElementById('selectedCount');
            const getStudentsUrl = '{{ route('teacher.classes.get-students') }}';

            let allStudents = [];
            const selectedStudents = new Set();

            // Initialize selection from previous input (if any)
            if (assignedStudentsInput && assignedStudentsInput.value) {
                (assignedStudentsInput.value || '').split(',').map(id => Number(id.trim())).filter(id => !isNaN(id))
                    .forEach(id => selectedStudents.add(id));
            }

            // Helper: get selected course meta (school / department)
            function getCourseMeta() {
                const option = courseSelect?.options[courseSelect.selectedIndex];
                return {
                    courseName: option?.text || '',
                    school: option?.dataset?.school || '',
                    department: option?.dataset?.department || '',
                };
            }

            // Preview button state
            function updatePreviewButtonState() {
                const btn = document.getElementById('previewChangesBtn');
                if (!btn) return;
                btn.disabled = selectedStudents.size === 0;
            }

            // Select all students currently visible in the filtered list
            function selectAllFiltered() {
                filterStudents().forEach(student => selectedStudents.add(student.id));
                renderAvailableStudents();
                renderSelectedStudents();
            }

            // Show preview modal with summary
            function showPreview() {
                const className = classNameInput.value.trim() || 'N/A';
                const courseMeta = getCourseMeta();
                const studentCount = selectedStudents.size;
                const studentsPreview = Array.from(selectedStudents)
                    .map(id => allStudents.find(s => s.id === id))
                    .filter(Boolean)
                    .slice(0, 20);

                document.getElementById('previewClassName').textContent = className;
                document.getElementById('previewCourseName').textContent = courseMeta.courseName || 'N/A';
                document.getElementById('previewSchool').textContent = courseMeta.school || 'N/A';
                document.getElementById('previewDepartment').textContent = courseMeta.department || 'N/A';
                document.getElementById('previewStudentCount').textContent = studentCount;

                const list = document.getElementById('previewStudentList');
                if (list) {
                    list.innerHTML = studentsPreview.length ?
                        studentsPreview.map(s => `<li class="list-group-item">${s.name} (${s.student_id})</li>`)
                        .join('') :
                        '<li class="list-group-item text-muted">No students selected</li>';
                }

                const modalEl = document.getElementById('previewChangesModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }

            // Progress tracking
            function updateProgress() {
                const steps = document.querySelectorAll('.progress-step');
                let currentStep = 1;

                // Check form completion
                const basicInfoComplete = classNameInput.value.trim() !== '' && subjectSelect.value !== '';
                const detailsComplete = courseSelect.value !== '' &&
                    document.getElementById('semester').value !== '' &&
                    document.getElementById('academic_year').value.trim() !== '';

                // Update progress steps
                if (basicInfoComplete) {
                    steps[0].classList.add('active');
                    steps[1].classList.add('active');
                    currentStep = 2;
                }

                if (detailsComplete) {
                    steps[1].classList.add('active');
                    steps[2].classList.add('active');
                    currentStep = 3;
                }

                return currentStep;
            }

            // Auto-populate course when subject is selected
            if (subjectSelect) {
                subjectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];

                    if (selectedOption.value) {
                        const subjectCode = selectedOption.dataset.code || '';
                        const courseName = selectedOption.dataset.course || '';

                        // Try to find and select the matching course
                        if (courseName) {
                            for (let i = 0; i < courseSelect.options.length; i++) {
                                if (courseSelect.options[i].text.includes(courseName)) {
                                    courseSelect.value = courseSelect.options[i].value;
                                    break;
                                }
                            }
                        }

                        // Suggest a class name if empty
                        if (!classNameInput.value && subjectCode) {
                            const year = document.getElementById('year').value || '';
                            const section = document.getElementById('section').value || '';
                            if (year && section) {
                                classNameInput.value = `${subjectCode} - Year ${year} Section ${section}`;
                            }
                        }
                    }

                    updateProgress();
                });
            }

            // Update class name suggestion when year or section changes
            const yearSelect = document.getElementById('year');
            const sectionSelect = document.getElementById('section');

            function updateClassNameSuggestion() {
                if (!classNameInput.value) {
                    const subjectOption = subjectSelect.options[subjectSelect.selectedIndex];
                    const subjectCode = subjectOption.dataset.code || '';
                    const year = yearSelect.value;
                    const section = sectionSelect.value;

                    if (subjectCode && year && section) {
                        classNameInput.value = `${subjectCode} - Year ${year} Section ${section}`;
                    }
                }

                updateProgress();
            }

            if (yearSelect) yearSelect.addEventListener('change', updateClassNameSuggestion);
            if (sectionSelect) sectionSelect.addEventListener('change', updateClassNameSuggestion);

            // Real-time validation
            function validateField(field) {
                const value = field.value.trim();
                const formGroup = field.closest('.form-group-modern');

                if (field.hasAttribute('required') && value === '') {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                } else if (value !== '') {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                } else {
                    field.classList.remove('is-invalid', 'is-valid');
                }

                updateProgress();
            }

            // Add validation to all required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', () => validateField(field));
                field.addEventListener('input', () => {
                    if (field.classList.contains('is-invalid')) {
                        validateField(field);
                    }
                });
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                // Validate all fields
                let isValid = true;
                requiredFields.forEach(field => {
                    validateField(field);
                    if (field.classList.contains('is-invalid')) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();

                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.focus();
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }

                // Add loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Class...';
            });

            // Student assignment helper functions
            function updateSelectedCount() {
                const ids = Array.from(selectedStudents);
                if (selectedCount) selectedCount.textContent = ids.length;
                if (assignedStudentsInput) assignedStudentsInput.value = ids.join(',');
                updatePreviewButtonState();
            }

            function filterStudents() {
                return allStudents.filter(student => {
                    if (studentYearFilter && studentYearFilter.value && String(student.year) !== String(
                            studentYearFilter.value)) return false;
                    if (studentSearch && studentSearch.value) {
                        const search = studentSearch.value.toLowerCase();
                        return student.name.toLowerCase().includes(search) || student.student_id
                            .toLowerCase().includes(search);
                    }
                    return true;
                });
            }

            function renderAvailableStudents() {
                const available = filterStudents().filter(student => !selectedStudents.has(student.id));
                if (availableCount) availableCount.textContent = available.length;

                if (!available.length) {
                    availableStudentsDiv.innerHTML =
                        '<div class="text-center text-muted">No available students</div>';
                    return;
                }

                availableStudentsDiv.innerHTML = available.map(student => `
            <div class="student-item d-flex align-items-center p-2 border-bottom hover-bg-light">
                <input type="checkbox" class="form-check-input me-2" value="${student.id}" ${selectedStudents.has(student.id) ? 'checked' : ''}>
                <div class="flex-grow-1">
                    <div class="fw-bold">${student.name}</div>
                    <small class="text-muted">${student.student_id} • ${student.course_name || 'N/A'} • Year ${student.year || '-'} • ${student.section || '-'}</small>
                </div>
            </div>
        `).join('');

                // Attach change listeners
                availableStudentsDiv.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.addEventListener('change', () => toggleStudent(Number(checkbox.value)));
                });
            }

            function renderSelectedStudents() {
                const selectedIds = Array.from(selectedStudents);
                if (selectedIds.length === 0) {
                    selectedStudentsDiv.innerHTML =
                        '<div class="text-center text-muted">No students selected</div>';
                    updateSelectedCount();
                    return;
                }

                selectedStudentsDiv.innerHTML = selectedIds.map(id => {
                    const student = allStudents.find(s => s.id === id) || {
                        id,
                        name: 'Unknown',
                        student_id: '',
                        course_name: '',
                        year: '',
                        section: ''
                    };
                    return `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <input type="checkbox" class="form-check-input me-2" checked value="${student.id}">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">${student.student_id ? student.student_id + ' • ' : ''}${student.course_name ? student.course_name + ' • ' : ''}${student.year ? 'Year ' + student.year + ' • ' : ''}${student.section || ''}</small>
                    </div>
                </div>
            `;
                }).join('');

                selectedStudentsDiv.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.addEventListener('change', () => toggleStudent(Number(checkbox.value)));
                });

                updateSelectedCount();
            }

            function toggleStudent(studentId) {
                if (selectedStudents.has(studentId)) {
                    selectedStudents.delete(studentId);
                } else {
                    selectedStudents.add(studentId);
                }

                renderAvailableStudents();
                renderSelectedStudents();
            }

            function loadStudents() {
                return fetch(getStudentsUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: JSON.stringify({
                            year: studentYearFilter ? studentYearFilter.value : null,
                            search: studentSearch ? studentSearch.value : null,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        allStudents = data.students || [];
                        renderAvailableStudents();
                        renderSelectedStudents();
                        updatePreviewButtonState();
                    })
                    .catch(() => {
                        availableStudentsDiv.innerHTML =
                            '<div class="text-center text-danger">Error loading students</div>';
                    });
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Events for student selection filters
            if (studentYearFilter) studentYearFilter.addEventListener('change', loadStudents);
            if (studentSearch) studentSearch.addEventListener('input', debounce(loadStudents, 300));

            document.getElementById('selectAllAvailable')?.addEventListener('click', () => {
                const availableIds = Array.from(availableStudentsDiv.querySelectorAll(
                    'input[type="checkbox"]')).map(cb => Number(cb.value));
                availableIds.forEach(id => selectedStudents.add(id));
                renderAvailableStudents();
                renderSelectedStudents();
            });

            document.getElementById('deselectAllAvailable')?.addEventListener('click', () => {
                const availableIds = Array.from(availableStudentsDiv.querySelectorAll(
                    'input[type="checkbox"]')).map(cb => Number(cb.value));
                availableIds.forEach(id => selectedStudents.delete(id));
                renderAvailableStudents();
                renderSelectedStudents();
            });

            document.getElementById('selectAllSelected')?.addEventListener('click', () => {
                allStudents.forEach(student => selectedStudents.add(student.id));
                renderAvailableStudents();
                renderSelectedStudents();
            });

            document.getElementById('deselectAllSelected')?.addEventListener('click', () => {
                selectedStudents.clear();
                renderAvailableStudents();
                renderSelectedStudents();
            });

            // Preset selection buttons
            document.getElementById('presetAll')?.addEventListener('click', () => {
                if (studentYearFilter) studentYearFilter.value = '';
                if (studentSearch) studentSearch.value = '';
                loadStudents().then(() => selectAllFiltered());
            });
            document.getElementById('presetYear1')?.addEventListener('click', () => {
                if (studentYearFilter) studentYearFilter.value = '1';
                loadStudents().then(() => selectAllFiltered());
            });
            document.getElementById('presetYear2')?.addEventListener('click', () => {
                if (studentYearFilter) studentYearFilter.value = '2';
                loadStudents().then(() => selectAllFiltered());
            });
            document.getElementById('presetYear3')?.addEventListener('click', () => {
                if (studentYearFilter) studentYearFilter.value = '3';
                loadStudents().then(() => selectAllFiltered());
            });
            document.getElementById('presetYear4')?.addEventListener('click', () => {
                if (studentYearFilter) studentYearFilter.value = '4';
                loadStudents().then(() => selectAllFiltered());
            });

            // Preview button
            document.getElementById('previewChangesBtn')?.addEventListener('click', showPreview);
            document.getElementById('confirmPreviewSaveBtn')?.addEventListener('click', () => {
                const modalEl = document.getElementById('previewChangesModal');
                if (modalEl) {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                }
                form.requestSubmit();
            });

            // Initialize student list
            loadStudents();

            // Add input formatting for academic year
            const academicYearInput = document.getElementById('academic_year');
            if (academicYearInput) {
                academicYearInput.addEventListener('input', function() {
                    let value = this.value.replace(/[^\d-]/g, '');

                    // Auto-format as YYYY-YYYY
                    if (value.length === 4 && !value.includes('-')) {
                        value = value + '-';
                    } else if (value.length > 9) {
                        value = value.substring(0, 9);
                    }

                    this.value = value;
                    updateProgress();
                });
            }

            // Initialize progress
            updateProgress();
        });
    </script>

@endsection
