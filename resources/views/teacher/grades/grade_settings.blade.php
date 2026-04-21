@extends('layouts.teacher')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="mb-1">⚙️ Grade Settings & Configuration</h2>
                        <p class="text-muted mb-0">{{ $class->class_name }} - {{ ucfirst($term) }} Term</p>
                    </div>
                    <a href="{{ route('teacher.grades.entry', $class->id) }}?term={{ $term }}"
                        class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i>Go to Grade Entry
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- KSA Percentage Settings -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">📊 KSA Weighting & Percentage</h5>
                    </div>
                    <div class="card-body">
                        @if ($settings->is_locked)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Settings Locked</strong> - Cannot modify during grading period
                            </div>
                        @endif

                        <form id="percentageForm">
                            <div class="mb-4">
                                <label class="form-label">
                                    <strong>📚 Knowledge Percentage</strong>
                                    <small class="text-muted">(40% default)</small>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="knowledgePerc"
                                        value="{{ $settings->knowledge_percentage }}" min="0" max="100"
                                        step="0.01" {{ $settings->is_locked ? 'disabled' : '' }}>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="form-text text-muted">Proportion of final grade from Knowledge
                                    components</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <strong>🎯 Skills Percentage</strong>
                                    <small class="text-muted">(50% default)</small>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="skillsPerc"
                                        value="{{ $settings->skills_percentage }}" min="0" max="100"
                                        step="0.01" {{ $settings->is_locked ? 'disabled' : '' }}>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="form-text text-muted">Proportion of final grade from Skills components</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <strong>😊 Attitude Percentage</strong>
                                    <small class="text-muted">(10% default)</small>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="attitudePerc"
                                        value="{{ $settings->attitude_percentage }}" min="0" max="100"
                                        step="0.01" {{ $settings->is_locked ? 'disabled' : '' }}>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="form-text text-muted">Proportion of final grade from Attitude
                                    components</small>
                            </div>

                            <!-- Progress Bar showing percentages -->
                            <div class="mb-4">
                                <label class="form-label"><strong>Total Progress</strong></label>
                                <div class="progress" style="height: 30px;">
                                    <div class="progress-bar bg-primary" id="knowledgeBar" style="width: 40%;"
                                        data-bs-toggle="tooltip" title="Knowledge"></div>
                                    <div class="progress-bar bg-success" id="skillsBar" style="width: 50%;"
                                        data-bs-toggle="tooltip" title="Skills"></div>
                                    <div class="progress-bar bg-info" id="attitudeBar" style="width: 10%;"
                                        data-bs-toggle="tooltip" title="Attitude"></div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Total: <span id="totalPerc">100</span>%
                                    <span id="totalStatus" class="badge badge-success ms-2">Valid</span>
                                </small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tip:</strong> Percentages must equal 100% to save. Adjust the sliders to
                                redistribute weights.
                            </div>

                            @if (!$settings->is_locked)
                                <button type="button" class="btn btn-primary w-100" id="btnSavePercentages">
                                    <i class="fas fa-save me-2"></i>Save KSA Settings
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Assessment Components Configuration -->
            <div class="col-lg-7 mb-4">
                <!-- Component Weight Automation Mode Selector -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">⚡ Component Weight Automation Mode</h5>
                    </div>
                    <div class="card-body">
                        @if ($settings->is_locked)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Settings Locked</strong> - Cannot modify automation mode during grading
                            </div>
                        @endif

                        <!-- Mode Selector -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label"><strong>Select Weight Distribution Mode</strong></label>

                                <div class="mb-3">
                                    @php
                                        $currentMode = $settings->component_weight_mode ?? 'semi-auto';
                                    @endphp

                                    <!-- Manual Mode -->
                                    <div class="form-check mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                        <input class="form-check-input" type="radio" name="componentWeightMode"
                                            id="modeManual" value="manual"
                                            {{ $currentMode === 'manual' ? 'checked' : '' }}
                                            {{ $settings->is_locked ? 'disabled' : '' }} onchange="updateModeInfo()">
                                        <label class="form-check-label" for="modeManual">
                                            <strong>🎯 Manual Mode</strong>
                                            <br>
                                            <small class="text-muted">
                                                No auto-distribution. You can set weights freely (total must = 100%).
                                                Perfect for custom weight configurations.
                                            </small>
                                        </label>
                                    </div>

                                    <!-- Semi-Auto Mode (Default) -->
                                    <div class="form-check mb-3 p-3 border rounded" style="background-color: #f0f8ff;">
                                        <input class="form-check-input" type="radio" name="componentWeightMode"
                                            id="modeSemiAuto" value="semi-auto"
                                            {{ $currentMode === 'semi-auto' ? 'checked' : '' }}
                                            {{ $settings->is_locked ? 'disabled' : '' }} onchange="updateModeInfo()">
                                        <label class="form-check-label" for="modeSemiAuto">
                                            <strong>🔄 Semi-Auto Mode (Recommended)</strong>
                                            <small class="badge bg-success ms-2">ACTIVE</small>
                                            <br>
                                            <small class="text-muted">
                                                Change Quiz 1 to 20% → Other quizzes auto-adjust to share remaining 80%.
                                                Smart distribution ensures total always = 100%. <strong>Requires 2+
                                                    components.</strong>
                                            </small>
                                        </label>
                                    </div>

                                    <!-- Auto Mode -->
                                    <div class="form-check p-3 border rounded" style="background-color: #fff8f0;">
                                        <input class="form-check-input" type="radio" name="componentWeightMode"
                                            id="modeAuto" value="auto" {{ $currentMode === 'auto' ? 'checked' : '' }}
                                            {{ $settings->is_locked ? 'disabled' : '' }} onchange="updateModeInfo()">
                                        <label class="form-check-label" for="modeAuto">
                                            <strong>🤖 Fully Auto Mode</strong>
                                            <br>
                                            <small class="text-muted">
                                                Change Quiz 1 to 20% → ALL quizzes become 20%. Perfect for equal-weight
                                                components. <strong>Requires exactly matching component counts per
                                                    category.</strong>
                                            </small>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mode Info Alert -->
                                <div id="modeInfoAlert" class="alert mt-3" role="alert" style="display:none;">
                                    <strong id="modeInfoTitle">Mode Information</strong>
                                    <p id="modeInfoText" class="mb-0"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Save Mode Button -->
                        @if (!$settings->is_locked)
                            <button type="button" class="btn btn-info w-100 mt-3" id="btnSaveMode">
                                <i class="fas fa-save me-2"></i>Save Automation Mode
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">📋 Assessment Components & Max Scores</h5>
                    </div>
                    <div class="card-body">
                        @if ($settings->is_locked)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-lock me-2"></i>
                                <strong>Settings Locked</strong> - Cannot modify components during grading period
                            </div>
                        @endif

                        <!-- Components by Category -->
                        @foreach (['Knowledge', 'Skills', 'Attitude'] as $category)
                            <div class="component-category mb-4" data-category="{{ $category }}">
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-0">
                                        @if ($category === 'Knowledge')
                                            <span class="badge bg-primary">📚</span>
                                        @elseif($category === 'Skills')
                                            <span class="badge bg-success">🎯</span>
                                        @else
                                            <span class="badge bg-info">😊</span>
                                        @endif
                                        {{ $category }} Components
                                    </h6>
                                    @if (!$settings->is_locked)
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-auto"
                                            data-bs-toggle="modal" data-bs-target="#addComponentModal"
                                            onclick="setCategory('{{ $category }}')">
                                            <i class="fas fa-plus me-1"></i>Add
                                        </button>
                                    @endif
                                </div>

                                <div class="component-list" id="components-{{ $category }}">
                                    @if (isset($components[$category]))
                                        @foreach ($components[$category] as $comp)
                                            <div class="component-item p-3 mb-2 border rounded d-flex justify-content-between align-items-center"
                                                data-component-id="{{ $comp->id }}"
                                                data-weight="{{ $comp->weight }}">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $comp->name }}</div>
                                                    <small class="text-muted">
                                                        Max: <strong>{{ $comp->max_score }} pts</strong> |
                                                        Weight: <strong>{{ $comp->weight }}%</strong>
                                                    </small>
                                                </div>
                                                <div class="flex-shrink-0 ms-3">
                                                    @if (!$settings->is_locked)
                                                        <button type="button" class="btn btn-sm btn-outline-warning me-2"
                                                            onclick="editComponent({{ $comp->id }}, '{{ $comp->name }}', '{{ $category }}', {{ $comp->max_score }}, {{ $comp->weight }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteComponent({{ $comp->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <span class="badge bg-secondary">Locked</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted"><em>No components yet</em></p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Settings Lock -->
                @if (!$settings->is_locked)
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-secondary w-100" id="btnLockSettings">
                            <i class="fas fa-lock me-2"></i>Lock Settings (Prevent Changes During Grading)
                        </button>
                    </div>
                @else
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-warning w-100" id="btnUnlockSettings">
                            <i class="fas fa-unlock me-2"></i>Unlock Settings
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add/Edit Component Modal -->
    <div class="modal fade" id="addComponentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Component</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="componentForm">
                        <div class="mb-3">
                            <label class="form-label">Component Name</label>
                            <input type="text" class="form-control" id="componentName" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="componentCategory" required
                                onchange="updateAvailableWeight()">
                                <option value="">-- Select Category --</option>
                                <option value="Knowledge">📚 Knowledge</option>
                                <option value="Skills">🎯 Skills</option>
                                <option value="Attitude">😊 Attitude</option>
                            </select>
                        </div>

                        <!-- Available Weight Display -->
                        <div class="alert alert-info d-none" id="availableWeightAlert" role="alert">
                            <small>
                                <strong>📊 Available weight for <span id="selectedCategoryDisplay">this
                                        category</span>:</strong>
                                <br>
                                <span id="availableWeightValue">100</span>% remaining
                                <br>
                                <small class="text-muted" id="existingWeightInfo"></small>
                            </small>
                        </div>

                        <!-- Weight Validation Alert -->
                        <div class="alert alert-warning d-none" id="weightWarning" role="alert">
                            <strong>⚠️ Weight Warning:</strong>
                            <span id="weightWarningText"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Max Score</label>
                            <input type="number" class="form-control" id="componentMaxScore" min="1"
                                max="1000" value="100" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight in Category (%)
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="componentWeight" min="0"
                                    max="100" value="10" step="0.01" required onchange="validateWeight()"
                                    oninput="validateWeight()">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted" id="weightHelpText">
                                Must not exceed available weight remaining in this category
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnSaveComponent">Save Component</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const classId = {{ $class->id }};
        const term = '{{ $term }}';
        let editingComponentId = null;
        let selectedCategory = null;

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Tooltip(document.body, {
                selector: '[data-bs-toggle="tooltip"]'
            });
            updateProgressBar();
        });

        // Update progress bar when percentages change
        document.getElementById('knowledgePerc').addEventListener('change', updateProgressBar);
        document.getElementById('skillsPerc').addEventListener('change', updateProgressBar);
        document.getElementById('attitudePerc').addEventListener('change', updateProgressBar);

        document.getElementById('knowledgePerc').addEventListener('input', updateProgressBar);
        document.getElementById('skillsPerc').addEventListener('input', updateProgressBar);
        document.getElementById('attitudePerc').addEventListener('input', updateProgressBar);

        function updateProgressBar() {
            const k = parseFloat(document.getElementById('knowledgePerc').value) || 0;
            const s = parseFloat(document.getElementById('skillsPerc').value) || 0;
            const a = parseFloat(document.getElementById('attitudePerc').value) || 0;
            const total = k + s + a;

            document.getElementById('knowledgeBar').style.width = k + '%';
            document.getElementById('skillsBar').style.width = s + '%';
            document.getElementById('attitudeBar').style.width = a + '%';

            document.getElementById('totalPerc').textContent = total.toFixed(2);

            const statusBadge = document.getElementById('totalStatus');
            if (Math.abs(total - 100) < 0.01) {
                statusBadge.className = 'badge bg-success ms-2';
                statusBadge.textContent = '✓ Valid';
            } else {
                statusBadge.className = 'badge bg-danger ms-2';
                statusBadge.textContent = '✗ Invalid';
            }

            // Enable/disable save button
            const saveBtn = document.getElementById('btnSavePercentages');
            saveBtn.disabled = Math.abs(total - 100) > 0.01;
        }

        // Save KSA Percentages
        document.getElementById('btnSavePercentages')?.addEventListener('click', async function() {
            const k = parseFloat(document.getElementById('knowledgePerc').value);
            const s = parseFloat(document.getElementById('skillsPerc').value);
            const a = parseFloat(document.getElementById('attitudePerc').value);

            try {
                const response = await fetch(`/teacher/grade-settings/${classId}/${term}/percentages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        knowledge_percentage: k,
                        skills_percentage: s,
                        attitude_percentage: a
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('✅ KSA percentages saved successfully!', 'success');
                } else {
                    showNotification('❌ ' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Error saving percentages:', error);
                showNotification('❌ Error saving percentages', 'danger');
            }
        });

        // Set category for add component
        function setCategory(category) {
            selectedCategory = category;
            document.getElementById('componentCategory').value = category;
            editingComponentId = null;
            document.getElementById('modalTitle').textContent = 'Add ' + category + ' Component';
            updateAvailableWeight();
        }

        // Update available weight display based on selected category
        function updateAvailableWeight() {
            const category = document.getElementById('componentCategory').value;
            const availableAlert = document.getElementById('availableWeightAlert');

            if (!category) {
                availableAlert.classList.add('d-none');
                return;
            }

            // Calculate existing weight in this category
            const categorySection = document.querySelector(`[data-category="${category}"] .component-list`);
            let existingWeight = 0;

            if (categorySection) {
                const components = categorySection.querySelectorAll('[data-weight]');
                components.forEach(comp => {
                    const weight = parseFloat(comp.getAttribute('data-weight')) || 0;
                    const id = comp.getAttribute('data-component-id');
                    // Don't count if we're editing this component
                    if (!editingComponentId || id !== editingComponentId) {
                        existingWeight += weight;
                    }
                });
            }

            const availableWeight = 100 - existingWeight;

            // Update the display
            document.getElementById('selectedCategoryDisplay').textContent = category;
            document.getElementById('availableWeightValue').textContent = availableWeight.toFixed(2);
            document.getElementById('existingWeightInfo').textContent =
                existingWeight > 0 ? `(${existingWeight.toFixed(2)}% already assigned)` : '(No components yet)';

            availableAlert.classList.remove('d-none');

            // Validate current weight
            validateWeight();
        }

        // Validate weight input
        function validateWeight() {
            const category = document.getElementById('componentCategory').value;
            const weight = parseFloat(document.getElementById('componentWeight').value) || 0;
            const warningAlert = document.getElementById('weightWarning');

            if (!category) {
                warningAlert.classList.add('d-none');
                return;
            }

            // Calculate available weight
            const categorySection = document.querySelector(`[data-category="${category}"] .component-list`);
            let existingWeight = 0;

            if (categorySection) {
                const components = categorySection.querySelectorAll('[data-weight]');
                components.forEach(comp => {
                    const compWeight = parseFloat(comp.getAttribute('data-weight')) || 0;
                    const id = comp.getAttribute('data-component-id');
                    if (!editingComponentId || id !== editingComponentId) {
                        existingWeight += compWeight;
                    }
                });
            }

            const availableWeight = 100 - existingWeight;
            let hasWarning = false;
            let warningText = '';

            // Check for issues
            if (weight > availableWeight) {
                hasWarning = true;
                warningText =
                    `Weight ${weight}% exceeds available ${availableWeight.toFixed(2)}%. Maximum allowed is ${availableWeight.toFixed(2)}%`;
            } else if (weight === 100 && existingWeight > 0) {
                hasWarning = true;
                warningText =
                    `Cannot set weight to 100% when other components exist (already using ${existingWeight.toFixed(2)}%).`;
            } else if (weight === 0) {
                hasWarning = true;
                warningText = `Weight should be greater than 0%.`;
            }

            if (hasWarning) {
                document.getElementById('weightWarningText').textContent = warningText;
                warningAlert.classList.remove('d-none');
            } else {
                warningAlert.classList.add('d-none');
            }
        }

        // Edit component
        function editComponent(id, name, category, maxScore, weight) {
            editingComponentId = id;
            document.getElementById('componentName').value = name;
            document.getElementById('componentCategory').value = category;
            document.getElementById('componentMaxScore').value = maxScore;
            document.getElementById('componentWeight').value = weight;
            document.getElementById('modalTitle').textContent = 'Edit Component: ' + name;

            // Update available weight display for edit mode
            updateAvailableWeight();

            const modal = new bootstrap.Modal(document.getElementById('addComponentModal'));
            modal.show();
        }

        // Save component
        document.getElementById('btnSaveComponent')?.addEventListener('click', async function() {
            const name = document.getElementById('componentName').value;
            const category = document.getElementById('componentCategory').value;
            const maxScore = document.getElementById('componentMaxScore').value;
            const weight = parseFloat(document.getElementById('componentWeight').value);

            if (!name || !category || !maxScore) {
                showNotification('❌ Please fill all required fields', 'danger');
                return;
            }

            // Frontend validation before submit
            if (weight <= 0) {
                showNotification('❌ Weight must be greater than 0%', 'danger');
                return;
            }

            // Calculate available weight
            const categorySection = document.querySelector(`[data-category="${category}"] .component-list`);
            let existingWeight = 0;

            if (categorySection) {
                const components = categorySection.querySelectorAll('[data-weight]');
                components.forEach(comp => {
                    const compWeight = parseFloat(comp.getAttribute('data-weight')) || 0;
                    const id = comp.getAttribute('data-component-id');
                    if (!editingComponentId || id !== editingComponentId) {
                        existingWeight += compWeight;
                    }
                });
            }

            const availableWeight = 100 - existingWeight;

            if (weight > availableWeight) {
                showNotification(
                    `❌ Cannot add component!\n\n` +
                    `Requested weight: ${weight}%\n` +
                    `Available weight: ${availableWeight.toFixed(2)}%\n\n` +
                    `Please reduce the weight to ${availableWeight.toFixed(2)}% or less.`,
                    'danger'
                );
                return;
            }

            if (!editingComponentId && weight === 100 && existingWeight > 0) {
                showNotification(
                    `❌ Cannot set weight to 100%!\n\n` +
                    `Other components need space in the ${category} category.\n` +
                    `Currently used: ${existingWeight.toFixed(2)}%\n` +
                    `Available: ${availableWeight.toFixed(2)}%`,
                    'danger'
                );
                return;
            }

            try {
                let response;
                if (editingComponentId) {
                    response = await fetch(
                        `/teacher/grade-settings/${classId}/components/${editingComponentId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                name: name,
                                max_score: maxScore,
                                weight: weight
                            })
                        });
                } else {
                    response = await fetch(`/teacher/grade-settings/${classId}/components`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: name,
                            category: category,
                            subcategory: name,
                            max_score: maxScore,
                            weight: weight
                        })
                    });
                }

                const data = await response.json();
                if (data.success) {
                    showNotification(
                        editingComponentId ? '✅ Component updated! All weights redistributed.' :
                        '✅ Component added! Weights redistributed.',
                        'success'
                    );
                    bootstrap.Modal.getInstance(document.getElementById('addComponentModal')).hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    // Format multiline error message
                    const errorMsg = data.message ? data.message.replace(/\n/g, '<br>') : 'Unknown error';
                    showNotification('❌ ' + errorMsg, 'danger');
                }
            } catch (error) {
                console.error('Error saving component:', error);
                showNotification('❌ Error saving component: ' + error.message, 'danger');
            }
        });

        // Delete component
        function deleteComponent(id) {
            if (confirm('Delete this component? All grades for this component will be removed.')) {
                fetch(`/teacher/grade-settings/${classId}/components/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('✅ Component deleted', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('❌ ' + data.message, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting component:', error);
                        showNotification('❌ Error deleting component', 'danger');
                    });
            }
        }

        // Lock/Unlock settings
        document.getElementById('btnLockSettings')?.addEventListener('click', async function() {
            try {
                const response = await fetch(`/teacher/grade-settings/${classId}/${term}/toggle-lock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('🔒 ' + data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        document.getElementById('btnUnlockSettings')?.addEventListener('click', async function() {
            if (confirm('Unlock settings? Teachers will be able to modify components again.')) {
                try {
                    const response = await fetch(`/teacher/grade-settings/${classId}/${term}/toggle-lock`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        showNotification('🔓 ' + data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });

        // Update Mode Selection Display
        function updateModeInfo() {
            const selectedMode = document.querySelector('input[name="componentWeightMode"]:checked').value;
            const infoAlert = document.getElementById('modeInfoAlert');
            const infoTitle = document.getElementById('modeInfoTitle');
            const infoText = document.getElementById('modeInfoText');

            let info = {};

            if (selectedMode === 'manual') {
                infoAlert.style.backgroundColor = '#fff3cd';
                infoAlert.style.borderColor = '#ffc107';
                infoTitle.textContent = '🎯 Manual Mode';
                infoText.innerHTML = `
                    No automatic weight adjustment. You have full control.
                    <br><small><strong>Use when:</strong> You want custom weight distributions or non-equal component weights.</small>
                    <br><small class="text-danger"><strong>Validation:</strong> Total weight per category must = 100% to save.</small>
                `;
            } else if (selectedMode === 'semi-auto') {
                infoAlert.style.backgroundColor = '#d1ecf1';
                infoAlert.style.borderColor = '#17a2b8';
                infoTitle.textContent = '🔄 Semi-Auto Mode (Recommended)';
                infoText.innerHTML = `
                    When you change one component weight, others auto-adjust proportionally.
                    <br><strong>Example:</strong> If you have [Quiz 1: 30%, Quiz 2: 70%] and change Quiz 1 to 20%, 
                    Quiz 2 auto-adjusts to 80% (maintaining 100% total).
                    <br><small class="text-success"><strong>Requires:</strong> 2 or more components in the category.</small>
                `;
            } else if (selectedMode === 'auto') {
                infoAlert.style.backgroundColor = '#fff3cd';
                infoAlert.style.borderColor = '#ff8800';
                infoTitle.textContent = '🤖 Fully Auto Mode';
                infoText.innerHTML = `
                    When you change one component, ALL components in that category get the SAME weight.
                    <br><strong>Example:</strong> If you have 3 quizzes and set any quiz to 25%, all 3 become 25%.
                    <br><small class="text-warning"><strong>Requires:</strong> 2+ components AND all get equal weights (100% ÷ count).</small>
                    <br><small class="text-info"><strong>Validation:</strong> Component count is checked to prevent errors.</small>
                `;
            }

            infoAlert.style.display = 'block';
        }

        // Save Component Weight Mode
        document.getElementById('btnSaveMode')?.addEventListener('click', async function() {
            const selectedMode = document.querySelector('input[name="componentWeightMode"]:checked').value;

            try {
                const response = await fetch(`/teacher/grade-settings/${classId}/${term}/weight-mode`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        component_weight_mode: selectedMode
                    })
                });

                const data = await response.json();
                if (data.success) {
                    const modeNames = {
                        'manual': '🎯 Manual Mode',
                        'semi-auto': '🔄 Semi-Auto Mode',
                        'auto': '🤖 Fully Auto Mode'
                    };
                    showNotification(
                        `✅ Component weight automation mode changed to: ${modeNames[selectedMode]}`,
                        'success'
                    );
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('❌ ' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Error saving mode:', error);
                showNotification('❌ Error saving automation mode', 'danger');
            }
        });

        function showNotification(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(alertDiv);

            setTimeout(() => alertDiv.remove(), 5000);
        }

        // Initialize mode info on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateModeInfo();
        });
    </script>

    <style>
        .component-item {
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .component-item:hover {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .progress {
            background-color: #e9ecef;
        }

        .progress-bar {
            font-size: 0.75rem;
            line-height: 30px;
            color: white;
            font-weight: bold;
        }

        .component-category {
            border-left: 4px solid #ddd;
            padding-left: 15px;
        }
    </style>
@endsection
