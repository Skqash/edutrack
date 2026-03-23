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
                    <a href="{{ route('teacher.grades.entry', $class->id) }}?term={{ $term }}" class="btn btn-primary">
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
                            <div class="component-category mb-4">
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
                                                data-component-id="{{ $comp->id }}">
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
                                                            onclick="editComponent({{ $comp->id }}, '{{ $comp->name }}', {{ $comp->max_score }}, {{ $comp->weight }})">
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
                            <select class="form-select" id="componentCategory" required>
                                <option value="">-- Select Category --</option>
                                <option value="Knowledge">📚 Knowledge</option>
                                <option value="Skills">🎯 Skills</option>
                                <option value="Attitude">😊 Attitude</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Max Score</label>
                            <input type="number" class="form-control" id="componentMaxScore" min="1"
                                max="1000" value="100" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Weight in Category (%)</label>
                            <input type="number" class="form-control" id="componentWeight" min="0"
                                max="100" value="10" step="0.01" required>
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
        }

        // Edit component
        function editComponent(id, name, maxScore, weight) {
            editingComponentId = id;
            document.getElementById('componentName').value = name;
            document.getElementById('componentMaxScore').value = maxScore;
            document.getElementById('componentWeight').value = weight;
            document.getElementById('modalTitle').textContent = 'Edit Component';

            const modal = new bootstrap.Modal(document.getElementById('addComponentModal'));
            modal.show();
        }

        // Save component
        document.getElementById('btnSaveComponent')?.addEventListener('click', async function() {
            const name = document.getElementById('componentName').value;
            const category = document.getElementById('componentCategory').value;
            const maxScore = document.getElementById('componentMaxScore').value;
            const weight = document.getElementById('componentWeight').value;

            if (!name || !category || !maxScore) {
                showNotification('❌ Please fill all required fields', 'danger');
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
                    showNotification(editingComponentId ? '✅ Component updated!' : '✅ Component added!',
                        'success');
                    bootstrap.Modal.getInstance(document.getElementById('addComponentModal')).hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('❌ ' + data.message, 'danger');
                }
            } catch (error) {
                console.error('Error saving component:', error);
                showNotification('❌ Error saving component', 'danger');
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

        function showNotification(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(alertDiv);

            setTimeout(() => alertDiv.remove(), 5000);
        }
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
