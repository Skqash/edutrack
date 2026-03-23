@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create New Course</h1>
            <p class="mb-0 text-muted">
                @if($adminCampus)
                    Campus: <span class="badge bg-primary">{{ $adminCampus }}</span>
                @else
                    System-wide Course
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Courses
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Course Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.courses.store') }}" method="POST" id="courseForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="program_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('program_code') is-invalid @enderror" 
                                       id="program_code" name="program_code" value="{{ old('program_code') }}" 
                                       placeholder="e.g., BSIT" maxlength="10" required>
                                @error('program_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Short code for the course (max 10 characters)</div>
                            </div>
                            
                            <div class="col-md-8 mb-3">
                                <label for="program_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('program_name') is-invalid @enderror" 
                                       id="program_name" name="program_name" value="{{ old('program_name') }}" 
                                       placeholder="e.g., Bachelor of Science in Information Technology" required>
                                @error('program_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('department') is-invalid @enderror" 
                                       id="department" 
                                       name="department" 
                                       value="{{ old('department') }}" 
                                       list="departmentList"
                                       placeholder="Type to search or create new department..." 
                                       required>
                                <datalist id="departmentList">
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->department_name }}">
                                    @endforeach
                                </datalist>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Select existing or type new department name
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="program_head_id" class="form-label">Program Head</label>
                                <select class="form-select @error('program_head_id') is-invalid @enderror" 
                                        id="program_head_id" name="program_head_id">
                                    <option value="">No Program Head</option>
                                    @foreach($heads as $head)
                                        <option value="{{ $head->id }}" {{ old('program_head_id') == $head->id ? 'selected' : '' }}
                                                data-email="{{ $head->email }}" data-campus="{{ $head->campus }}">
                                            {{ $head->name }}
                                            @if($head->campus)
                                                ({{ str_replace('CPSU - ', '', $head->campus) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_head_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional: Assign a teacher as program head</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="total_years" class="form-label">Total Years <span class="text-danger">*</span></label>
                                <select class="form-select @error('total_years') is-invalid @enderror" 
                                        id="total_years" name="total_years" required>
                                    <option value="">Select Years</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('total_years') == $i ? 'selected' : '' }}>
                                            {{ $i }} Year{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('total_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Brief description of the course program...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Provide a brief description of the course</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Course Creation Guide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Course Code</h6>
                        <p class="small text-muted mb-2">
                            Use standard abbreviations (e.g., BSIT, BSCS, BSBA). Keep it short and memorable.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Program Head</h6>
                        <p class="small text-muted mb-2">
                            @if($adminCampus)
                                Only teachers from your campus ({{ $adminCampus }}) are available for selection.
                            @else
                                You can assign any teacher as program head.
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">Course Duration</h6>
                        <p class="small text-muted mb-2">
                            Most bachelor's degrees are 4 years, master's are 2 years, and doctoral programs are 3-5 years.
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tip:</strong> After creating the course, you can manage subjects and assign them to specific year levels.
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card shadow mt-4" id="previewCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-eye"></i> Course Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-info me-2" id="previewCode">CODE</span>
                        <strong id="previewName">Course Name</strong>
                    </div>
                    <div class="small text-muted mb-2">
                        <i class="fas fa-building"></i> <span id="previewDepartment">Department</span>
                    </div>
                    <div class="small text-muted mb-2">
                        <i class="fas fa-user"></i> <span id="previewHead">No Program Head</span>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-calendar"></i> <span id="previewYears">Duration</span> •
                        <span class="badge bg-success" id="previewStatus">Status</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TomSelect for program head dropdown
    const programHeadSelect = new TomSelect('#program_head_id', {
        placeholder: 'Search and select program head...',
        allowEmptyOption: true,
        create: false,
        render: {
            option: function(data, escape) {
                const campus = data.dataset?.campus ? `(${data.dataset.campus.replace('CPSU - ', '')})` : '';
                return `<div>
                    <div class="fw-bold">${escape(data.text)}</div>
                    <div class="small text-muted">${escape(data.dataset?.email || '')} ${campus}</div>
                </div>`;
            }
        }
    });

    // Form validation and preview
    const form = document.getElementById('courseForm');
    const previewCard = document.getElementById('previewCard');
    
    // Form fields
    const fields = {
        code: document.getElementById('program_code'),
        name: document.getElementById('program_name'),
        department: document.getElementById('department_id'),
        head: document.getElementById('program_head_id'),
        years: document.getElementById('total_years'),
        status: document.getElementById('status')
    };

    // Preview elements
    const preview = {
        code: document.getElementById('previewCode'),
        name: document.getElementById('previewName'),
        department: document.getElementById('previewDepartment'),
        head: document.getElementById('previewHead'),
        years: document.getElementById('previewYears'),
        status: document.getElementById('previewStatus')
    };

    // Update preview
    function updatePreview() {
        const hasContent = fields.code.value || fields.name.value;
        
        if (hasContent) {
            previewCard.style.display = 'block';
            
            preview.code.textContent = fields.code.value || 'CODE';
            preview.name.textContent = fields.name.value || 'Course Name';
            
            const deptOption = fields.department.options[fields.department.selectedIndex];
            preview.department.textContent = deptOption?.text || 'Department';
            
            const headOption = fields.head.options[fields.head.selectedIndex];
            preview.head.textContent = headOption?.text || 'No Program Head';
            
            const yearsText = fields.years.value ? `${fields.years.value} Year${fields.years.value > 1 ? 's' : ''}` : 'Duration';
            preview.years.textContent = yearsText;
            
            preview.status.textContent = fields.status.value || 'Status';
            preview.status.className = `badge ${fields.status.value === 'Active' ? 'bg-success' : 'bg-secondary'}`;
        } else {
            previewCard.style.display = 'none';
        }
    }

    // Add event listeners
    Object.values(fields).forEach(field => {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['program_code', 'program_name', 'department_id', 'total_years', 'status'];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Auto-generate course code from name
    fields.name.addEventListener('input', function() {
        if (!fields.code.value) {
            const words = this.value.split(' ');
            let code = '';
            
            words.forEach(word => {
                if (word.length > 0 && ['of', 'in', 'and', 'the'].indexOf(word.toLowerCase()) === -1) {
                    code += word.charAt(0).toUpperCase();
                }
            });
            
            if (code.length > 10) {
                code = code.substring(0, 10);
            }
            
            fields.code.value = code;
            updatePreview();
        }
    });
});
</script>

<style>
.ts-control {
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
}

.ts-control.focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

#previewCard {
    transition: all 0.3s ease;
}
</style>
@endsection