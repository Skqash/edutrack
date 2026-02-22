<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white border-0"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title fw-bold" id="addStudentModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add Student to Class
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs border-bottom" id="addStudentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="manual-tab" data-bs-toggle="tab"
                            data-bs-target="#manual" type="button" role="tab" aria-controls="manual"
                            aria-selected="true">
                            <i class="fas fa-keyboard me-2"></i> Manual Entry
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel"
                            type="button" role="tab" aria-controls="excel" aria-selected="false">
                            <i class="fas fa-file-excel me-2"></i> Excel Import
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="addStudentTabContent">
                    <!-- Manual Entry Tab -->
                    <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                        <form id="manualStudentForm" method="POST" action="{{ route('teacher.students.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="manual_class_id" class="form-label fw-bold">Class <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg @error('class_id') is-invalid @enderror"
                                    id="manual_class_id" name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach ($myClasses as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class_name }} ({{ $class->class_level }}) -
                                            {{ $class->students->count() }}/{{ $class->capacity }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manual_name" class="form-label fw-bold">Student Name <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    id="manual_name" name="name" placeholder="e.g., John Doe" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="manual_email" class="form-label fw-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    id="manual_email" name="email" placeholder="e.g., john@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="manual_year" class="form-label fw-bold">Year <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg @error('year') is-invalid @enderror"
                                        id="manual_year" name="year" required>
                                        <option value="">-- Select Year --</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="manual_section" class="form-label fw-bold">Section <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg @error('section') is-invalid @enderror"
                                        id="manual_section" name="section" required>
                                        <option value="">-- Select Section --</option>
                                        <option value="A">Section A</option>
                                        <option value="B">Section B</option>
                                        <option value="C">Section C</option>
                                        <option value="D">Section D</option>
                                        <option value="E">Section E</option>
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="alert alert-info border-0 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Password:</strong> A temporary password will be generated automatically. Student
                                can reset it on first login.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg fw-bold text-white"
                                    style="background-color: #667eea; border: none;">
                                    <i class="fas fa-save me-2"></i> Add Student
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Excel Import Tab -->
                    <div class="tab-pane fade" id="excel" role="tabpanel" aria-labelledby="excel-tab">
                        <form id="excelImportForm" method="POST" action="{{ route('teacher.students.import') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="excel_class_id" class="form-label fw-bold">Class <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg @error('class_id') is-invalid @enderror"
                                    id="excel_class_id" name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach ($myClasses as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class_name }} ({{ $class->class_level }}) -
                                            {{ $class->students->count() }}/{{ $class->capacity }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="excel_file" class="form-label fw-bold">Excel File (.xlsx) <span
                                        class="text-danger">*</span></label>
                                <input type="file"
                                    class="form-control form-control-lg @error('excel_file') is-invalid @enderror"
                                    id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                @error('excel_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Format Instructions -->
                            <div class="alert alert-info border-0 mb-3">
                                <h6 class="alert-heading fw-bold">
                                    <i class="fas fa-file-excel me-2"></i> Excel File Format
                                </h6>
                                <hr class="my-2">
                                <p class="mb-2 small"><strong>Required Columns:</strong></p>
                                <ul class="small mb-0">
                                    <li><strong>Column A - Name:</strong> Student full name (required)</li>
                                    <li><strong>Column B - Email:</strong> Valid email address (required)</li>
                                    <li><strong>Column C - Year:</strong> Academic year 1-4 (required)</li>
                                    <li><strong>Column D - Section:</strong> Section A-E (required)</li>
                                </ul>
                            </div>

                            <!-- Example Data -->
                            <div class="alert alert-light border mb-3">
                                <h6 class="fw-bold mb-2">Example:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Year</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>John Doe</td>
                                                <td>john@example.com</td>
                                                <td>1</td>
                                                <td>A</td>
                                            </tr>
                                            <tr>
                                                <td>Jane Smith</td>
                                                <td>jane@example.com</td>
                                                <td>1</td>
                                                <td>B</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ asset('templates/students_import_template.xlsx') }}"
                                    class="btn btn-outline-secondary" download>
                                    <i class="fas fa-download me-2"></i> Download Template
                                </a>
                                <button type="submit" class="btn btn-lg fw-bold text-white"
                                    style="background-color: #667eea; border: none;">
                                    <i class="fas fa-upload me-2"></i> Import Students
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sync class selection between tabs
        const manualClassSelect = document.getElementById('manual_class_id');
        const excelClassSelect = document.getElementById('excel_class_id');

        if (manualClassSelect) {
            manualClassSelect.addEventListener('change', function() {
                if (excelClassSelect) {
                    excelClassSelect.value = this.value;
                }
            });
        }

        if (excelClassSelect) {
            excelClassSelect.addEventListener('change', function() {
                if (manualClassSelect) {
                    manualClassSelect.value = this.value;
                }
            });
        }

        // Form validation feedback
        const forms = document.querySelectorAll('#manualStudentForm, #excelImportForm');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            }, false);
        });
    });
</script>
