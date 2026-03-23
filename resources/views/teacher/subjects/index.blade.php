@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">My Subjects</h2>
                <p class="text-muted mb-0">Manage your assigned courses and teaching materials</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#requestSubjectModal">
                    <i class="fas fa-paper-plane me-2"></i>Request Subject
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                    <i class="fas fa-plus me-2"></i>Create Subject
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                                    <i class="fas fa-book fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ count($courses) }}</h3>
                                <p class="text-muted mb-0">Total Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 text-info rounded p-3">
                                    <i class="fas fa-chalkboard fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ $totalClasses }}</h3>
                                <p class="text-muted mb-0">Total Classes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                                    <i class="fas fa-user-graduate fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ $totalStudents }}</h3>
                                <p class="text-muted mb-0">Total Students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Subject Requests -->
        @if (isset($pendingSubjects) && $pendingSubjects->count() > 0)
            <div class="alert alert-warning" role="alert">
                <h5 class="alert-heading"><i class="fas fa-clock me-2"></i>Pending Subject Assignment Requests</h5>
                <ul class="mb-0">
                    @foreach ($pendingSubjects as $pending)
                        <li><strong>{{ $pending->subject_name }}</strong> ({{ $pending->subject_code }}) - Waiting for admin approval</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Subjects List -->
        @if (count($courses) === 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Subjects Assigned Yet</h5>
                    <p class="text-muted mb-4">You don't have any assigned courses yet. Request a subject from admin or create your own if you're independent.</p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestSubjectModal">
                            <i class="fas fa-paper-plane me-2"></i>Request Subject
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                            <i class="fas fa-plus me-2"></i>Create Subject
                        </button>
                    </div>
                    
                    <!-- Quick Start Guide -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-primary"><i class="fas fa-lightbulb me-2"></i>Quick Start Guide</h6>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <h6 class="small fw-bold">Option 1: Request from Admin</h6>
                                <ul class="small text-muted">
                                    <li>Click "Request Subject" above</li>
                                    <li>Fill in subject details</li>
                                    <li>Wait for admin approval</li>
                                    <li>Start teaching once approved</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="small fw-bold">Option 2: Create Your Own</h6>
                                <ul class="small text-muted">
                                    <li>Click "Create Subject" above</li>
                                    <li>Define your subject details</li>
                                    <li>Subject is immediately available</li>
                                    <li>Create classes for your subject</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @foreach ($courses as $course)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $course['name'] }}</h5>
                                <p class="text-muted mb-0">{{ $course['code'] ?? 'No Code' }}</p>
                                @if ($course['description'])
                                    <p class="text-muted small mt-2 mb-0">{{ $course['description'] }}</p>
                                @endif
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>

                        <div class="d-flex gap-4 mb-3">
                            @if ($course['id'] === 'independent')
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-book text-primary me-2"></i>
                                    <span><strong>{{ count($course['subjects'] ?? []) }}</strong> Subjects</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chalkboard text-info me-2"></i>
                                    <span><strong>{{ $course['class_count'] }}</strong> Classes</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-graduate text-warning me-2"></i>
                                    <span><strong>{{ $course['student_count'] }}</strong> Students</span>
                                </div>
                            @endif
                        </div>

                        @if ($course['id'] === 'independent' && !empty($course['subjects']))
                            <div class="bg-light rounded p-3">
                                <h6 class="mb-3"><i class="fas fa-list me-2"></i>Your Subjects</h6>
                                @foreach ($course['subjects'] as $subject)
                                    <div class="d-flex justify-content-between align-items-center bg-white rounded p-2 mb-2">
                                        <div>
                                            <span class="fw-semibold">{{ $subject['code'] }} - {{ $subject['name'] }}</span>
                                            <small class="text-muted d-block">{{ $subject['credit_hours'] }} units • {{ $subject['category'] }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('teacher.classes.create') }}?subject_id={{ $subject['id'] }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-plus me-1"></i>Create Class
                                            </a>
                                            @if(isset($subject['id']))
                                            <form action="{{ route('teacher.subjects.remove', $subject['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this subject from your assignments?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt me-1"></i>Remove
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif (!empty($course['classes']))
                            <div class="bg-light rounded p-3">
                                <h6 class="mb-3"><i class="fas fa-list me-2"></i>Classes</h6>
                                @foreach ($course['classes'] as $cls)
                                    <div class="d-flex justify-content-between align-items-center bg-white rounded p-2 mb-2">
                                        <span class="fw-semibold">{{ $cls['class_name'] }}</span>
                                        <a href="{{ route('teacher.classes.show', $cls['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Request Subject Modal -->
    <div class="modal fade" id="requestSubjectModal" tabindex="-1" aria-labelledby="requestSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestSubjectModalLabel">
                        <i class="fas fa-paper-plane me-2"></i>Request Subject Assignment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('teacher.subjects.request') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required placeholder="e.g., Introduction to Programming">
                        </div>

                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="e.g., IT101">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required placeholder="Explain why you need this subject..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Subject Modal -->
    <div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubjectModalLabel">
                        <i class="fas fa-plus me-2"></i>Create Your Own Subject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('teacher.subjects.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Create your own subject if you're an independent teacher.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="create_subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_subject_name" name="subject_name" required placeholder="e.g., Web Development Fundamentals">
                            </div>

                            <div class="col-md-4">
                                <label for="create_subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="create_subject_code" name="subject_code" required placeholder="e.g., IT102">
                            </div>

                            <div class="col-md-6">
                                <label for="credit_hours" class="form-label">Credit Hours <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="credit_hours" name="credit_hours" required min="1" max="6" value="3">
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <x-searchable-dropdown
                                    name="category"
                                    id="category"
                                    placeholder="Select category..."
                                    :options="[
                                        ['id' => 'Core', 'name' => 'Core', 'description' => 'Essential course subjects'],
                                        ['id' => 'General Ed', 'name' => 'General Education', 'description' => 'General education requirements'],
                                        ['id' => 'Major', 'name' => 'Major', 'description' => 'Major-specific subjects'],
                                        ['id' => 'Specialization', 'name' => 'Specialization', 'description' => 'Specialized field subjects'],
                                        ['id' => 'Programming', 'name' => 'Programming', 'description' => 'Programming and coding subjects'],
                                        ['id' => 'Mathematics', 'name' => 'Mathematics', 'description' => 'Mathematical subjects'],
                                        ['id' => 'Science', 'name' => 'Science', 'description' => 'Science subjects'],
                                        ['id' => 'Language', 'name' => 'Language', 'description' => 'Language and literature'],
                                        ['id' => 'Business', 'name' => 'Business', 'description' => 'Business and management'],
                                        ['id' => 'Other', 'name' => 'Other', 'description' => 'Other subjects']
                                    ]"
                                    :create-new="true"
                                    create-new-text="+ Add Custom Category"
                                    create-new-value="custom"
                                    class="form-control"
                                />
                                <input type="text" class="form-control mt-2" id="category_other" name="category_other" placeholder="Enter custom category" style="display:none;">
                            </div>

                            <div class="col-md-6">
                                <label for="year_level" class="form-label">Year Level</label>
                                <x-searchable-dropdown
                                    name="year_level"
                                    id="year_level"
                                    placeholder="Select year level..."
                                    :options="[
                                        ['id' => '1', 'name' => '1st Year', 'description' => 'First year students'],
                                        ['id' => '2', 'name' => '2nd Year', 'description' => 'Second year students'],
                                        ['id' => '3', 'name' => '3rd Year', 'description' => 'Third year students'],
                                        ['id' => '4', 'name' => '4th Year', 'description' => 'Fourth year students']
                                    ]"
                                    selected="1"
                                    class="form-control"
                                />
                            </div>

                            <div class="col-md-6">
                                <label for="semester" class="form-label">Semester</label>
                                <x-searchable-dropdown
                                    name="semester"
                                    id="semester"
                                    placeholder="Select semester..."
                                    :options="[
                                        ['id' => '1', 'name' => '1st Semester', 'description' => 'First semester of academic year'],
                                        ['id' => '2', 'name' => '2nd Semester', 'description' => 'Second semester of academic year']
                                    ]"
                                    selected="1"
                                    required="true"
                                    class="form-control"
                                />
                            </div>

                            <div class="col-12">
                                <label for="create_description" class="form-label">Description</label>
                                <textarea class="form-control" id="create_description" name="description" rows="3" placeholder="Brief description of the subject..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle custom category creation
            const categoryDropdown = document.querySelector('[data-dropdown-id="category"]');
            const categoryOtherInput = document.getElementById('category_other');
            
            if (categoryDropdown) {
                categoryDropdown.addEventListener('createNew', function(e) {
                    if (e.detail.value === 'custom') {
                        categoryOtherInput.style.display = 'block';
                        categoryOtherInput.setAttribute('required', 'required');
                        categoryOtherInput.focus();
                    }
                });
                
                // Handle category change
                const categoryHiddenInput = document.getElementById('category_hidden');
                if (categoryHiddenInput) {
                    categoryHiddenInput.addEventListener('change', function() {
                        if (this.value !== 'custom') {
                            categoryOtherInput.style.display = 'none';
                            categoryOtherInput.removeAttribute('required');
                            categoryOtherInput.value = '';
                        }
                    });
                }
            }

            // Handle form submission for custom category
            const createSubjectForm = document.querySelector('#createSubjectModal form');
            if (createSubjectForm) {
                createSubjectForm.addEventListener('submit', function(e) {
                    const categoryValue = document.getElementById('category_hidden').value;
                    const categoryOtherValue = categoryOtherInput.value.trim();
                    
                    if (categoryValue === 'custom' && categoryOtherValue) {
                        // Set the custom category value
                        document.getElementById('category_hidden').value = categoryOtherValue;
                    }
                });
            }
        });
    </script>
@endsection