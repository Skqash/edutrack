<!-- Professional Component Manager Modal -->
<div id="componentManagerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="componentManagerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <!-- Header -->
            <div class="modal-header bg-gradient text-white border-0">
                <div>
                    <h5 class="modal-title" id="componentManagerLabel">
                        <i class="fas fa-cogs"></i> Component Manager
                    </h5>
                    <small class="text-light">Add, edit, and manage KSA subcomponents</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <!-- Tabs: Add New / Manage Existing -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="addComponentTab" data-bs-toggle="tab"
                            data-bs-target="#addComponentPane" type="button" role="tab"
                            aria-controls="addComponentPane" aria-selected="true">
                            <i class="fas fa-plus-circle"></i> Add Component
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="manageComponentTab" data-bs-toggle="tab"
                            data-bs-target="#manageComponentPane" type="button" role="tab"
                            aria-controls="manageComponentPane" aria-selected="false">
                            <i class="fas fa-edit"></i> Manage Components
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="templatesTab" data-bs-toggle="tab" data-bs-target="#templatesPane"
                            type="button" role="tab" aria-controls="templatesPane" aria-selected="false">
                            <i class="fas fa-layer-group"></i> Templates
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- TAB 1: Add Component -->
                    <div class="tab-pane fade show active" id="addComponentPane" role="tabpanel"
                        aria-labelledby="addComponentTab">
                        <form id="addComponentForm" class="component-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentCategory" class="form-label">
                                            <i class="fas fa-layer-group"></i> KSA Category
                                        </label>
                                        <select class="form-select form-select-lg" id="componentCategory"
                                            name="category" required>
                                            <option value="">-- Select Category --</option>
                                            <option value="Knowledge">📚 Knowledge</option>
                                            <option value="Skills">🎯 Skills</option>
                                            <option value="Attitude">⭐ Attitude</option>
                                        </select>
                                        <small class="text-muted">Select the KSA category for this component</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentSubcategory" class="form-label">
                                            <i class="fas fa-tag"></i> Subcategory
                                        </label>
                                        <select class="form-select form-select-lg" id="componentSubcategory"
                                            name="subcategory" required>
                                            <option value="">-- Select Category First --</option>
                                        </select>
                                        <small class="text-muted">Choose component type</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentName" class="form-label">
                                            <i class="fas fa-heading"></i> Component Name
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="componentName"
                                            name="name" placeholder="e.g., Quiz 1, Output 1" required>
                                        <small class="text-muted">Display name for this component</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentMaxScore" class="form-label">
                                            <i class="fas fa-star"></i> Max Score
                                        </label>
                                        <input type="number" class="form-control form-control-lg"
                                            id="componentMaxScore" name="max_score" placeholder="100" min="1"
                                            max="500" value="100" required>
                                        <small class="text-muted">Maximum possible score</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentWeight" class="form-label">
                                            <i class="fas fa-percentage"></i> Weight (%)
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" class="form-control" id="componentWeight" name="weight"
                                                placeholder="0" min="0" max="100" step="0.01" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="text-muted">Weight percentage within the category</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="componentPassingScore" class="form-label">
                                            <i class="fas fa-check-circle"></i> Passing Score
                                        </label>
                                        <input type="number" class="form-control form-control-lg"
                                            id="componentPassingScore" name="passing_score" placeholder="75" min="0"
                                            max="100" value="75">
                                        <small class="text-muted">Minimum score to pass (optional)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info alert-dismissible">
                                <i class="fas fa-info-circle"></i> <strong>Tip:</strong> Passing score will highlight grades in red (failed) or green (passed) for easy identification.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Add Component
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: Manage Components -->
                    <div class="tab-pane fade" id="manageComponentPane" role="tabpanel"
                        aria-labelledby="manageComponentTab">
                        <div id="componentsList" class="component-list">
                            <div class="text-center py-5">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Loading components...</p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Templates -->
                    <div class="tab-pane fade" id="templatesPane" role="tabpanel" aria-labelledby="templatesTab">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-primary h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <i class="fas fa-brain"></i> Knowledge Template
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Includes: Quizzes & Exams</p>
                                        <button type="button" class="btn btn-primary w-100"
                                            onclick="applyTemplate('knowledge', 'Knowledge')">
                                            Apply Template
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-success h-100 shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-tasks"></i> Skills Template
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Includes: Outputs, Activities & Assignments</p>
                                        <button type="button" class="btn btn-success w-100"
                                            onclick="applyTemplate('skills', 'Skills')">
                                            Apply Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-warning h-100 shadow-sm">
                                    <div class="card-header bg-warning text-dark">
                                        <i class="fas fa-heart"></i> Attitude Template
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Includes: Behavior & Attendance</p>
                                        <button type="button" class="btn btn-warning w-100"
                                            onclick="applyTemplate('attitude', 'Attitude')">
                                            Apply Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    #componentManagerModal .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .component-form input,
    .component-form select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .component-form input:focus,
    .component-form select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .component-item {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .component-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .component-item.knowledge {
        border-left-color: #2196F3;
    }

    .component-item.skills {
        border-left-color: #4CAF50;
    }

    .component-item.attitude {
        border-left-color: #FF9800;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .nav-tabs .nav-link {
        color: #667eea;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #667eea;
        color: #764ba2;
    }

    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: none;
    }
</style>
