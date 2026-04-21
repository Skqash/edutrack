@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">Digital E-Signature</h1>
                <p class="text-muted mb-0">Create or update your digital signature for attendance</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-pen-fancy me-2 text-primary"></i>
                            E-Signature Management
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($student->hasESignature())
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                You already have a registered signature. Create a new one below to update it.
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-2">Current E-Signature:</h6>
                                <div class="border rounded p-3 bg-light" style="max-width: 400px;">
                                    <img src="{{ $student->e_signature }}" alt="Current E-Signature"
                                        style="max-height: 120px; max-width: 100%; border-radius: 0.25rem;">
                                </div>
                            </div>

                            <hr>
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                You don't have a registered signature yet. Please create one below to enable signature-based
                                attendance.
                            </div>
                        @endif

                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid #dee2e6;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="draw-btn" data-bs-toggle="tab"
                                    data-bs-target="#draw-tab" type="button" role="tab" aria-controls="draw-tab"
                                    aria-selected="true" style="border: none; border-bottom: 3px solid #0d6efd;">
                                    <i class="fas fa-pen-fancy me-2"></i> Draw Signature
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="upload-btn" data-bs-toggle="tab" data-bs-target="#upload-tab"
                                    type="button" role="tab" aria-controls="upload-tab" aria-selected="false"
                                    style="border: none;">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Upload Image
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Draw Tab -->
                            <div class="tab-pane fade show active" id="draw-tab" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label"><strong>Draw Your Signature</strong></label>
                                    <p class="text-muted small mb-2">Use your mouse or touch to draw your signature:</p>

                                    <canvas id="signatureCanvas"
                                        style="display: block; cursor: crosshair; background: white; width: 100%; height: 250px; border: 1px solid #dee2e6; border-radius: 0.375rem; max-width: 100%;">
                                    </canvas>

                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Draw naturally and keep your signature consistent.
                                    </small>
                                </div>

                                <div class="d-flex gap-2 mb-4">
                                    <button type="button" class="btn btn-outline-secondary" id="clearButton">
                                        <i class="fas fa-trash me-1"></i> Clear
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="undoButton">
                                        <i class="fas fa-undo me-1"></i> Undo
                                    </button>
                                    <div class="flex-grow-1"></div>
                                    <button type="button" class="btn btn-primary" id="saveDrawButton">
                                        <i class="fas fa-save me-1"></i> Save Signature
                                    </button>
                                </div>

                                <div class="alert alert-light border">
                                    <h6 class="mb-2"><i class="fas fa-lightbulb me-1 text-warning"></i> Tips:</h6>
                                    <ul class="mb-0 small">
                                        <li>Write naturally and avoid rushing</li>
                                        <li>Keep your signature consistent</li>
                                        <li>Make sure it's clearly visible</li>
                                        <li>Avoid writing too small or too large</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Upload Tab -->
                            <div class="tab-pane fade" id="upload-tab" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label"><strong>Upload Signature Image</strong></label>
                                    <p class="text-muted small mb-3">Upload a clear image of your signature (PNG, JPG, or
                                        WebP):</p>

                                    <input type="file" id="signatureFile" accept=".png,.jpg,.jpeg,.webp"
                                        style="display: none;">

                                    <div class="border border-dashed rounded p-4 text-center" id="uploadArea"
                                        style="cursor: pointer; transition: all 0.3s ease; background-color: #f8f9fa;">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 2.5rem; color: #0d6efd; display: block; margin-bottom: 10px;"></i>
                                        <p class="mb-1 fw-bold">Drop image here or click to browse</p>
                                        <small class="text-muted d-block">PNG, JPG, WebP • Max 5MB</small>
                                    </div>
                                </div>

                                <!-- Upload Preview -->
                                <div id="uploadPreviewContainer" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Preview:</label>
                                        <div class="border rounded p-3 bg-light text-center">
                                            <img id="uploadPreviewImg" src="" alt="Preview"
                                                style="max-height: 150px; max-width: 100%; border-radius: 0.25rem;">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mb-4">
                                        <button type="button" class="btn btn-outline-secondary" id="cancelUploadButton">
                                            <i class="fas fa-times me-1"></i> Choose Different
                                        </button>
                                        <div class="flex-grow-1"></div>
                                        <button type="button" class="btn btn-primary" id="saveUploadButton">
                                            <i class="fas fa-save me-1"></i> Save Signature
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-light border">
                                    <h6 class="mb-2"><i class="fas fa-lightbulb me-1 text-warning"></i> Tips:</h6>
                                    <ul class="mb-0 small">
                                        <li>Upload a clear, high-quality image</li>
                                        <li>Use PNG format for best results</li>
                                        <li>Use transparent background if possible</li>
                                        <li>Avoid blurry or low-resolution images</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="savingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Saving your signature...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Signature.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/3.0.0-beta.4/signature_pad.min.js"></script>

    <script>
        // ===== DRAWING FUNCTIONALITY =====
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');

        // Resize canvas to match container
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Initialize SignaturePad
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 2,
            maxWidth: 4,
            throttle: 16,
            velocityFilterWeight: 0.7,
            dotSize: 0
        });

        // Clear button
        document.getElementById('clearButton').addEventListener('click', () => {
            signaturePad.clear();
        });

        // Undo button
        document.getElementById('undoButton').addEventListener('click', () => {
            const data = signaturePad.toData();
            if (data.length > 0) {
                data.pop();
                signaturePad.fromData(data);
            }
        });

        // Save draw button
        document.getElementById('saveDrawButton').addEventListener('click', () => {
            if (signaturePad.isEmpty()) {
                alert('Please draw your signature first!');
                return;
            }

            if (confirm('Save this signature?')) {
                const dataUrl = signaturePad.toDataURL('image/png');
                saveSignature(dataUrl);
            }
        });

        // Prevent navigation with unsaved signature
        window.addEventListener('beforeunload', (e) => {
            if (!signaturePad.isEmpty()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // ===== FILE UPLOAD FUNCTIONALITY =====
        let selectedFileData = null;

        const uploadArea = document.getElementById('uploadArea');
        const signatureFile = document.getElementById('signatureFile');
        const uploadPreviewContainer = document.getElementById('uploadPreviewContainer');
        const uploadPreviewImg = document.getElementById('uploadPreviewImg');
        const cancelUploadButton = document.getElementById('cancelUploadButton');
        const saveUploadButton = document.getElementById('saveUploadButton');

        // Click to browse
        uploadArea.addEventListener('click', () => signatureFile.click());

        // Drag and drop styling
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#0d6efd';
            uploadArea.style.backgroundColor = '#e7f1ff';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = '#f8f9fa';
        });

        // Drop files
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = '#f8f9fa';
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        // File input change
        signatureFile.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            // Validate type
            const validTypes = ['image/png', 'image/jpeg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload PNG, JPG, or WebP image.');
                return;
            }

            // Validate size
            if (file.size > 5 * 1024 * 1024) {
                alert('File too large. Maximum size is 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                selectedFileData = e.target.result;
                uploadPreviewImg.src = selectedFileData;
                uploadPreviewContainer.style.display = 'block';

                // Scroll to preview
                uploadPreviewContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            };
            reader.readAsDataURL(file);
        }

        // Cancel upload
        cancelUploadButton.addEventListener('click', () => {
            selectedFileData = null;
            signatureFile.value = '';
            uploadPreviewContainer.style.display = 'none';
        });

        // Save uploaded signature
        saveUploadButton.addEventListener('click', () => {
            if (!selectedFileData) {
                alert('Please select a signature image first!');
                return;
            }

            if (confirm('Save this signature?')) {
                saveSignature(selectedFileData);
            }
        });

        // ===== SAVE FUNCTION (Both methods) =====
        function saveSignature(dataUrl) {
            const modal = new bootstrap.Modal(document.getElementById('savingModal'));
            modal.show();

            fetch('{{ route('student.signature.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        signature_data: dataUrl
                    })
                })
                .then(response => response.json())
                .then(data => {
                    modal.hide();
                    if (data.success) {
                        alert('Signature saved successfully!');
                        window.location.href = '{{ route('student.dashboard') }}';
                    } else {
                        alert('Error: ' + (data.message || 'Failed to save signature'));
                    }
                })
                .catch(error => {
                    modal.hide();
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }
    </script>
@endsection
