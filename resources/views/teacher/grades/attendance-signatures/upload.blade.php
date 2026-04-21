@extends('layouts.teacher')

@section('content')
    <div class="container-fluid py-4" style="margin-top: 80px;">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-penpal me-2"></i>
                            E-Signature Management
                        </h4>
                        <p class="mb-0 opacity-75">{{ $class->class_name }} | {{ ucfirst($term) }} Term</p>
                    </div>
                    <div>
                        <a href="{{ route('teacher.grades') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-primary" id="totalCount">0</h3>
                        <p class="text-muted mb-0">Total Signatures</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success" id="approvedCount">0</h3>
                        <p class="text-muted mb-0">Approved</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning" id="pendingCount">0</h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-info" id="coveragePercentage">0%</h3>
                        <p class="text-muted mb-0">Coverage</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Upload Form -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>
                        Upload E-Signature
                    </h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#bulkUploadModal">
                        <i class="fas fa-file-upload me-1"></i> Bulk Upload
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="signatureUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Student *</strong></label>
                            <select name="student_id" id="studentSelect" class="form-select" required>
                                <option value="">-- Select Student --</option>
                                @foreach ($studentsWithoutSignatures as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Signature Type *</strong></label>
                            <select name="signature_type" id="signatureType" class="form-select" required>
                                <option value="upload">File Upload (PNG/JPG/PDF)</option>
                                <option value="digital">Digital Draw</option>
                                <option value="pen-based">Pen-based (Tablet)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Signed Date *</strong></label>
                            <input type="date" name="signed_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div id="uploadSection" class="mb-3">
                        <label class="form-label"><strong>Signature File *</strong></label>
                        <input type="file" name="signature_file" id="signatureFile" class="form-control"
                            accept="image/png,image/jpeg,application/pdf" required>
                        <small class="text-muted">Accepted formats: PNG, JPG, PDF (max 5MB)</small>
                    </div>

                    <!-- Digital Canvas Section -->
                    <div id="digitalSection" style="display: none;" class="mb-3">
                        <label class="form-label"><strong>Draw Signature *</strong></label>
                        <div class="border bg-light p-3" style="border-radius: 5px;">
                            <canvas id="signatureCanvas" width="600" height="200"
                                style="border: 2px solid #dee2e6; cursor: crosshair; display: block; margin: 0 auto;"></canvas>
                            <div class="text-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearCanvas()">
                                    <i class="fas fa-eraser me-1"></i> Clear
                                </button>
                            </div>
                            <input type="hidden" name="signature_data" id="signatureData">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="2"
                            placeholder="Any additional notes about this signature..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Upload Signature
                    </button>
                </form>
            </div>
        </div>

        <!-- Signatures List -->
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Signatures
                    </h5>
                    <div>
                        <select id="filterStatus" class="form-select form-select-sm"
                            style="width: auto; display: inline-block;">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="signaturesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Signed Date</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="signaturesBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Loading signatures...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Signature Modal -->
    <div class="modal fade" id="viewSignatureModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="signaturePreview"></div>
                    <div id="signatureMetadata" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Signatures</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Upload a ZIP file containing signature images for multiple students.</p>
                    <p class="text-muted"><small>File naming format: <code>studentid_firstname_lastname.png</code></small>
                    </p>
                    <input type="file" id="bulkUploadFile" class="form-control" accept=".zip">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processBulkUpload()">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #signatureCanvas {
            background: white;
            touch-action: none;
        }
    </style>

    <script src="{{ asset('js/teacher/signature-management.js') }}"></script>

    <script>
        // Canvas drawing setup
        let isDrawing = false;
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas?.getContext('2d');

        if (canvas) {
            canvas.addEventListener('mousedown', (e) => {
                isDrawing = true;
                startDrawing(e);
            });
            canvas.addEventListener('mousemove', (e) => {
                if (isDrawing) draw(e);
            });
            canvas.addEventListener('mouseup', () => {
                isDrawing = false;
            });
            canvas.addEventListener('mouseout', () => {
                isDrawing = false;
            });
        }

        function startDrawing(e) {
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            const rect = canvas.getBoundingClientRect();
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
        }

        function clearCanvas() {
            if (ctx) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signatureData').value = '';
            }
        }

        // Signature type change handler
        document.getElementById('signatureType')?.addEventListener('change', function() {
            document.getElementById('uploadSection').style.display = this.value === 'upload' ? 'block' : 'none';
            document.getElementById('digitalSection').style.display = this.value === 'digital' ? 'block' : 'none';

            if (this.value === 'digital') {
                clearCanvas();
            }
        });

        // Form submission
        document.getElementById('signatureUploadForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            const signatureType = document.getElementById('signatureType').value;

            formData.append('student_id', document.getElementById('studentSelect').value);
            formData.append('term', '{{ $term }}');
            formData.append('signature_type', signatureType);
            formData.append('signed_date', document.querySelector('input[name="signed_date"]').value);
            formData.append('remarks', document.querySelector('textarea[name="remarks"]').value);

            if (signatureType === 'upload') {
                formData.append('signature_file', document.getElementById('signatureFile').files[0]);
            } else if (signatureType === 'digital') {
                formData.append('signature_data', canvas.toDataURL('image/png'));
            }

            try {
                const response = await fetch('{{ route('teacher.attendance-signatures.store', $class->id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Signature uploaded successfully!');
                    document.getElementById('signatureUploadForm').reset();
                    loadSignatures();
                } else {
                    alert('Error: ' + (data.message || data.error));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error uploading signature');
            }
        });

        // Load signatures
        async function loadSignatures() {
            try {
                const response = await fetch(
                    '{{ route('teacher.attendance-signatures.index', $class->id) }}?term={{ $term }}&ajax=1'
                    );
                const html = await response.text();
                document.getElementById('signaturesBody').innerHTML = html;

                // Update statistics
                updateStatistics();
            } catch (error) {
                console.error('Error loading signatures:', error);
            }
        }

        // Update statistics
        async function updateStatistics() {
            try {
                const response = await fetch(
                    '{{ route('teacher.attendance-signatures.statistics', $class->id) }}?term={{ $term }}'
                    );
                const stats = await response.json();

                document.getElementById('totalCount').textContent = stats.total;
                document.getElementById('approvedCount').textContent = stats.approved;
                document.getElementById('pendingCount').textContent = stats.pending;
                document.getElementById('coveragePercentage').textContent = stats.coverage_percentage + '%';
            } catch (error) {
                console.error('Error updating statistics:', error);
            }
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSignatures();
        });
    </script>
@endsection
