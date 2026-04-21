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

        @if (!$student)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error: Student profile not found. Please contact support.
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
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

                                <div class="mb-4 p-3 bg-light rounded border" style="max-width: 350px; margin: 0 auto;">
                                    <p class="text-muted small mb-2 text-center">Current E-Signature:</p>
                                    @if (strpos($student->e_signature, 'data:') === 0)
                                        <img src="{{ $student->e_signature }}" alt="Current E-Signature"
                                            style="max-height: 100px; max-width: 100%; display: block; margin: 0 auto; border-radius: 0.25rem;">
                                    @else
                                        <div
                                            style="max-height: 100px; max-width: 100%; display: flex; align-items: center; justify-content: center; margin: 0 auto; border-radius: 0.25rem; background: white; border: 1px solid #dee2e6;">
                                            <small class="text-muted">Signature data format issue</small>
                                        </div>
                                    @endif
                                </div>

                                <hr>
                            @else
                                <div class="alert alert-warning mb-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    You don't have a registered signature yet. Please create one below to enable
                                    signature-based attendance.
                                </div>
                            @endif

                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="draw-tab-btn" data-bs-toggle="tab"
                                        data-bs-target="#draw-tab" type="button">
                                        <i class="fas fa-pen-fancy me-2"></i> Draw Signature
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="upload-tab-btn" data-bs-toggle="tab"
                                        data-bs-target="#upload-tab" type="button">
                                        <i class="fas fa-cloud-upload-alt me-2"></i> Upload Image
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Draw Tab -->
                                <div class="tab-pane fade show active" id="draw-tab">
                                    <h6 class="mb-2"><strong>Draw Your Signature</strong></h6>
                                    <p class="text-muted small mb-3">Use your mouse or touch to draw your signature:</p>

                                    <div id="canvasContainer" style="width: 100%; max-width: 800px; margin-bottom: 1.5rem;">
                                        <canvas id="signatureCanvas"
                                            style="display: block; width: 100%; border: 2px solid #dee2e6; border-radius: 0.375rem; background: white; cursor: crosshair; touch-action: none;">
                                        </canvas>
                                    </div>

                                    <div class="d-flex gap-2 mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearBtn">
                                            <i class="fas fa-trash me-1"></i> Clear
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" id="undoBtn">
                                            <i class="fas fa-undo me-1"></i> Undo
                                        </button>
                                        <div class="flex-grow-1"></div>
                                        <button type="button" class="btn btn-primary btn-sm" id="saveDrawBtn">
                                            <i class="fas fa-save me-1"></i> Save Signature
                                        </button>
                                    </div>

                                    <div class="alert alert-light border-start border-info ps-3 mb-0">
                                        <small>
                                            <i class="fas fa-lightbulb me-1 text-warning"></i>
                                            <strong>Tips:</strong> Write naturally, keep it consistent, and make sure it's
                                            clearly visible.
                                        </small>
                                    </div>
                                </div>

                                <!-- Upload Tab -->
                                <div class="tab-pane fade" id="upload-tab">
                                    <h6 class="mb-2"><strong>Upload Signature Image</strong></h6>
                                    <p class="text-muted small mb-3">Choose a clear image of your signature (PNG, JPG, or
                                        WebP - Max 5MB):</p>

                                    <input type="file" id="signatureFile" accept=".png,.jpg,.jpeg,.webp"
                                        style="display: none;">

                                    <div id="uploadArea" class="border border-dashed rounded p-4 mb-3 text-center"
                                        style="cursor: pointer; background-color: #f8f9fa; transition: all 0.3s ease;">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 2rem; color: #0d6efd; display: block; margin-bottom: 0.5rem;"></i>
                                        <p class="mb-1 fw-bold">Drop image here or click to browse</p>
                                        <small class="text-muted">PNG, JPG, WebP • Max 5MB</small>
                                    </div>

                                    <!-- Upload Preview -->
                                    <div id="uploadPreviewContainer" style="display: none; margin-bottom: 1.5rem;">
                                        <p class="form-label mb-2">Preview:</p>
                                        <div class="border rounded p-3 bg-light text-center">
                                            <img id="uploadPreviewImg" src="" alt="Preview"
                                                style="max-height: 150px; max-width: 100%; border-radius: 0.25rem;">
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                id="cancelUploadBtn">
                                                <i class="fas fa-times me-1"></i> Choose Different
                                            </button>
                                            <div class="flex-grow-1"></div>
                                            <button type="button" class="btn btn-primary btn-sm" id="saveUploadBtn">
                                                <i class="fas fa-save me-1"></i> Save Signature
                                            </button>
                                        </div>
                                    </div>

                                    <div class="alert alert-light border-start border-info ps-3 mb-0">
                                        <small>
                                            <i class="fas fa-lightbulb me-1 text-warning"></i>
                                            <strong>Tips:</strong> Use PNG format for best results with transparent
                                            background.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="savingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-body text-center py-4">
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
    <!-- Signature Pad Library from unpkg CDN -->
    <script src="https://unpkg.com/signature_pad@3.0.0-beta.4/dist/signature_pad.umd.js"></script>

    <script>
        let signaturePad = null;
        let selectedFileData = null;

        // Wait for SignaturePad library to load
        function waitForSignaturePad(callback) {
            if (typeof SignaturePad !== 'undefined') {
                callback();
            } else {
                console.log('Waiting for SignaturePad to load...');
                setTimeout(() => waitForSignaturePad(callback), 100);
            }
        }

        // Initialize when DOM and SignaturePad library are ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            waitForSignaturePad(function() {
                console.log('SignaturePad library loaded successfully');
                setupSignaturePad();
                setupUploadHandlers();
            });
        });

        // ===== SIGNATURE PAD SETUP =====
        function setupSignaturePad() {
            try {
                const canvas = document.getElementById('signatureCanvas');
                const container = document.getElementById('canvasContainer');

                if (!canvas || !container) {
                    console.error('Canvas or container not found!');
                    return;
                }

                // Set canvas size to match container's actual display size
                const resizeCanvas = () => {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const rect = container.getBoundingClientRect();

                    // Set internal resolution
                    canvas.width = rect.width * ratio;
                    canvas.height = 280 * ratio;

                    // Scale the drawing context
                    const ctx = canvas.getContext('2d');
                    ctx.scale(ratio, ratio);

                    console.log('Canvas resized:', canvas.width, 'x', canvas.height, 'ratio:', ratio);
                };

                // Initial canvas setup
                resizeCanvas();

                // Re-initialize SignaturePad after sizing
                if (signaturePad) {
                    signaturePad.clear();
                }

                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    penColor: 'rgb(0, 0, 0)',
                    minWidth: 0.5,
                    maxWidth: 2.5,
                    throttle: 16,
                    velocityFilterWeight: 0.7,
                    dotSize: false
                });

                console.log('SignaturePad initialized successfully');

                // Binding buttons
                const clearBtn = document.getElementById('clearBtn');
                const undoBtn = document.getElementById('undoBtn');
                const saveDrawBtn = document.getElementById('saveDrawBtn');

                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        signaturePad.clear();
                        console.log('Signature cleared');
                    });
                }

                if (undoBtn) {
                    undoBtn.addEventListener('click', undoSignature);
                }

                if (saveDrawBtn) {
                    saveDrawBtn.addEventListener('click', saveDrawSignature);
                }

                // Handle window resize
                window.addEventListener('resize', () => {
                    const data = signaturePad.toData();
                    resizeCanvas();
                    signaturePad.fromData(data);
                });

            } catch (error) {
                console.error('Error initializing SignaturePad:', error);
                alert('Error initializing signature: ' + error.message);
            }
        }

        function undoSignature() {
            if (!signaturePad) return;
            const data = signaturePad.toData();
            if (data.length > 0) {
                data.pop();
                signaturePad.fromData(data);
                console.log('Undo applied');
            }
        }

        function saveDrawSignature() {
            if (!signaturePad) {
                alert('Error: Signature pad not initialized!');
                return;
            }
            if (signaturePad.isEmpty()) {
                alert('Please draw your signature first!');
                return;
            }

            if (confirm('Save this signature?')) {
                const dataUrl = signaturePad.toDataURL('image/png');
                console.log('Saving signature...');
                saveSignature(dataUrl);
            }
        }

        // ===== FILE UPLOAD SETUP =====
        function setupUploadHandlers() {
            const uploadArea = document.getElementById('uploadArea');
            const signatureFile = document.getElementById('signatureFile');
            const uploadPreviewContainer = document.getElementById('uploadPreviewContainer');
            const uploadPreviewImg = document.getElementById('uploadPreviewImg');
            const cancelUploadBtn = document.getElementById('cancelUploadBtn');
            const saveUploadBtn = document.getElementById('saveUploadBtn');

            if (!uploadArea) return;

            // Click handler
            uploadArea.addEventListener('click', () => signatureFile.click());

            // Drag handlers
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.style.backgroundColor = '#e7f1ff';
                uploadArea.style.borderColor = '#0d6efd';
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.style.backgroundColor = '#f8f9fa';
                uploadArea.style.borderColor = '#dee2e6';
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.style.backgroundColor = '#f8f9fa';
                uploadArea.style.borderColor = '#dee2e6';
                if (e.dataTransfer.files.length) {
                    processUploadFile(e.dataTransfer.files[0]);
                }
            });

            // File input change
            signatureFile.addEventListener('change', (e) => {
                if (e.target.files.length) {
                    processUploadFile(e.target.files[0]);
                }
            });

            // Cancel button
            if (cancelUploadBtn) {
                cancelUploadBtn.addEventListener('click', () => {
                    selectedFileData = null;
                    signatureFile.value = '';
                    uploadPreviewContainer.style.display = 'none';
                });
            }

            // Save button
            if (saveUploadBtn) {
                saveUploadBtn.addEventListener('click', () => {
                    if (!selectedFileData) {
                        alert('Please select an image first!');
                        return;
                    }
                    if (confirm('Save this signature?')) {
                        saveSignature(selectedFileData);
                    }
                });
            }

            function processUploadFile(file) {
                const validTypes = ['image/png', 'image/jpeg', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Use PNG, JPG, or WebP.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('File too large (max 5MB).');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    selectedFileData = e.target.result;
                    uploadPreviewImg.src = selectedFileData;
                    uploadPreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // ===== SAVE SIGNATURE =====
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
                .then(res => res.json())
                .then(data => {
                    modal.hide();
                    if (data.success) {
                        alert('Signature saved successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route('student.dashboard') }}';
                        }, 500);
                    } else {
                        alert('Error: ' + (data.message || 'Failed to save signature'));
                    }
                })
                .catch(err => {
                    modal.hide();
                    console.error('Error:', err);
                    alert('An error occurred. Please try again.');
                });
        }
    </script>
@endsection
