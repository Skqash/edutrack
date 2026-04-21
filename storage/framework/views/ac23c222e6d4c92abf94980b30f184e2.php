

<?php $__env->startSection('content'); ?>
    <style>
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            border-radius: 8px 8px 0 0;
            padding: 15px 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .mode-options {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .mode-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .mode-option:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .mode-option.selected {
            border-color: #007bff;
            background-color: #e7f3ff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        }

        .mode-option input[type="radio"] {
            margin-top: 10px;
            cursor: pointer;
        }

        .mode-option h6 {
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .mode-option small {
            display: block;
            margin-bottom: 10px;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2>⚙️ Grade Settings</h2>
                        <p class="text-muted mb-0"><?php echo e($class->class_name); ?> - <?php echo e(ucfirst($term)); ?> Term</p>
                    </div>
                    <div>
                        <span id="settingsModeIndicator" class="badge fs-5 px-3 py-2 <?php echo e(($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'manual' ? 'bg-primary' : 
                            (($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'auto' ? 'bg-warning text-dark' : 'bg-success')); ?>">
                            <?php echo e(($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'manual' ? '🎯 Manual Mode' : 
                                (($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'auto' ? '🤖 Auto Mode' : '🔄 Semi-Auto Mode')); ?>

                        </span>
                    </div>
                </div>
                <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- KSA Distribution -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-percentage me-2"></i>KSA Distribution</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('teacher.grades.settings.update-ksa', $class->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="term" value="<?php echo e($term); ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Knowledge (K)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" name="knowledge_weight" class="form-range" min="0"
                                    max="100" value="<?php echo e($ksaSettings->knowledge_weight ?? 40); ?>">
                                <span class="badge bg-primary"
                                    id="k-display"><?php echo e($ksaSettings->knowledge_weight ?? 40); ?>%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Skills (S)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" name="skills_weight" class="form-range" min="0" max="100"
                                    value="<?php echo e($ksaSettings->skills_weight ?? 50); ?>">
                                <span class="badge bg-success"
                                    id="s-display"><?php echo e($ksaSettings->skills_weight ?? 50); ?>%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Attitude (A)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="range" name="attitude_weight" class="form-range" min="0"
                                    max="100" value="<?php echo e($ksaSettings->attitude_weight ?? 10); ?>">
                                <span class="badge bg-danger"
                                    id="a-display"><?php echo e($ksaSettings->attitude_weight ?? 10); ?>%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <span class="badge bg-secondary fs-6">Total: <span id="total-display">100</span>%</span>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save KSA Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Weight Mode -->
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-wand-magic-sparkles me-2"></i>Component Weight Automation Mode</h5>
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modeInfoModal" title="Learn about modes">
                    <i class="fas fa-question-circle"></i> Info
                </button>
            </div>
            <div class="card-body">
                <div class="mode-options">
                    <div class="mode-option" data-mode="manual" onclick="selectMode('manual')">
                        <h6>🎯 Manual</h6>
                        <small class="text-muted">Full control</small>
                        <div>
                            <input type="radio" name="mode" value="manual" id="mode-manual"
                                <?php echo e(($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'manual' ? 'checked' : ''); ?>>
                        </div>
                    </div>

                    <div class="mode-option" data-mode="semi-auto" onclick="selectMode('semi-auto')">
                        <h6>🔄 Semi-Auto</h6>
                        <small class="text-muted">Recommended</small>
                        <div>
                            <input type="radio" name="mode" value="semi-auto" id="mode-semi"
                                <?php echo e(($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'semi-auto' ? 'checked' : ''); ?>>
                        </div>
                    </div>

                    <div class="mode-option" data-mode="auto" onclick="selectMode('auto')">
                        <h6>🤖 Auto</h6>
                        <small class="text-muted">Equal weights</small>
                        <div>
                            <input type="radio" name="mode" value="auto" id="mode-auto"
                                <?php echo e(($gradingScaleSettings->component_weight_mode ?? 'semi-auto') === 'auto' ? 'checked' : ''); ?>>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-primary" onclick="saveWeightMode()">
                        <i class="fas fa-save me-2"></i>Save Mode
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Settings -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Attendance Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('teacher.grades.settings.update-attendance', $class->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="term" value="<?php echo e($term); ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Meetings</label>
                            <input type="number" name="total_meetings" class="form-control"
                                value="<?php echo e($ksaSettings->total_meetings ?? 20); ?>" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Attendance Weight (%)</label>
                            <input type="number" name="attendance_weight" class="form-control"
                                value="<?php echo e($ksaSettings->attendance_weight ?? 10); ?>" min="0" max="100"
                                step="0.1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attendance Affects:</label>
                        <div class="form-check">
                            <input type="radio" name="attendance_category" value="knowledge" id="att-k"
                                class="form-check-input"
                                <?php echo e(($ksaSettings->attendance_category ?? 'skills') === 'knowledge' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="att-k">Knowledge</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="attendance_category" value="skills" id="att-s"
                                class="form-check-input"
                                <?php echo e(($ksaSettings->attendance_category ?? 'skills') === 'skills' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="att-s">Skills</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="attendance_category" value="attitude" id="att-a"
                                class="form-check-input"
                                <?php echo e(($ksaSettings->attendance_category ?? 'skills') === 'attitude' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="att-a">Attitude</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="enable_attendance_ksa" id="enable-att" class="form-check-input"
                                <?php echo e(($ksaSettings->enable_attendance_ksa ?? true) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="enable-att">
                                Enable Attendance in KSA Calculation
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Attendance Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mode Information Modal -->
    <div class="modal fade" id="modeInfoModal" tabindex="-1" aria-labelledby="modeInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modeInfoModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Component Weight Automation Modes Explained
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Manual Mode -->
                    <div class="mode-info-section mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mode-icon me-3">
                                <span style="font-size: 2rem;">🎯</span>
                            </div>
                            <div>
                                <h5 class="mb-1 text-primary">Manual Mode</h5>
                                <span class="badge bg-primary">Full Control</span>
                            </div>
                        </div>
                        <div class="ps-5">
                            <h6 class="fw-bold">How it works:</h6>
                            <ul class="mb-3">
                                <li>You manually set the weight percentage for <strong>each component</strong></li>
                                <li>Total weights within each KSA category must equal <strong>100%</strong></li>
                                <li>System validates but does not auto-adjust weights</li>
                                <li>You have complete control over the distribution</li>
                            </ul>
                            
                            <h6 class="fw-bold">Example:</h6>
                            <div class="alert alert-light border">
                                <strong>Knowledge Components:</strong><br>
                                • Midterm Exam: <span class="text-primary">60%</span> (you set)<br>
                                • Quiz 1: <span class="text-primary">10%</span> (you set)<br>
                                • Quiz 2: <span class="text-primary">10%</span> (you set)<br>
                                • Quiz 3: <span class="text-primary">20%</span> (you set)<br>
                                <strong>Total: 100%</strong> ✓
                            </div>
                            
                            <h6 class="fw-bold">Best for:</h6>
                            <ul class="mb-0">
                                <li>Teachers with specific grading requirements</li>
                                <li>Custom grading schemes (e.g., major exam worth 70%)</li>
                                <li>When you need precise control over each component</li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <!-- Semi-Auto Mode -->
                    <div class="mode-info-section mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mode-icon me-3">
                                <span style="font-size: 2rem;">🔄</span>
                            </div>
                            <div>
                                <h5 class="mb-1 text-success">Semi-Auto Mode</h5>
                                <span class="badge bg-success">Recommended - Subcategory-Level</span>
                            </div>
                        </div>
                        <div class="ps-5">
                            <h6 class="fw-bold">How it works:</h6>
                            <ul class="mb-3">
                                <li>System <strong>suggests equal distribution</strong> within each <strong>subcategory</strong></li>
                                <li>You can <strong>override and adjust</strong> any component weight</li>
                                <li>When you change one weight, <strong>only the same subcategory</strong> recalculates proportionally</li>
                                <li>Different subcategories remain independent (like Auto Mode)</li>
                                <li>Always maintains 100% total automatically</li>
                            </ul>
                            
                            <h6 class="fw-bold">Example:</h6>
                            <div class="alert alert-light border">
                                <strong>Knowledge Category:</strong><br>
                                <span class="text-primary">📝 Exam:</span> 60%<br>
                                <span class="text-primary">📋 Quizzes (3 components):</span> 13.33% each = 40% total<br>
                                <br>
                                <strong>You change Quiz 1 to 20%:</strong><br>
                                <span class="text-success">✓ Exam stays: 60%</span> (not affected)<br>
                                <span class="text-success">✓ Quiz 1: 20%</span> (your override)<br>
                                <span class="text-success">✓ Quiz 2 & 3: 10% each</span> (proportionally adjusted)<br>
                                <strong>Total: 100%</strong> ✓
                            </div>
                            
                            <h6 class="fw-bold">Best for:</h6>
                            <ul class="mb-0">
                                <li><strong>Most teachers</strong> - perfect balance of control and automation</li>
                                <li>When you want flexibility within logical groupings</li>
                                <li>Standard grading with customization per assessment type</li>
                                <li>Saves time while maintaining precise control</li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <!-- Auto Mode -->
                    <div class="mode-info-section mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="mode-icon me-3">
                                <span style="font-size: 2rem;">🤖</span>
                            </div>
                            <div>
                                <h5 class="mb-1 text-warning">Auto Mode</h5>
                                <span class="badge bg-warning text-dark">Fully Automated (Subcategory-Level)</span>
                            </div>
                        </div>
                        <div class="ps-5">
                            <h6 class="fw-bold">How it works:</h6>
                            <ul class="mb-3">
                                <li>System <strong>automatically distributes weights equally</strong> within each <strong>subcategory</strong></li>
                                <li>Weights are <strong>locked</strong> - you cannot manually adjust them</li>
                                <li>When you add/remove components, <strong>only the same subcategory</strong> recalculates</li>
                                <li>Different subcategories (Exam vs Quiz, Output vs Activity) remain independent</li>
                                <li>Zero manual weight management required</li>
                            </ul>
                            
                            <h6 class="fw-bold">Example:</h6>
                            <div class="alert alert-light border">
                                <strong>Knowledge Category:</strong><br>
                                <span class="text-primary">📝 Exam (1 component):</span> 60%<br>
                                <span class="text-primary">📋 Quizzes (3 components):</span> 13.33% each = 40% total<br>
                                <br>
                                <strong>Delete Quiz 1:</strong><br>
                                <span class="text-success">✓ Exam stays: 60%</span> (not affected)<br>
                                <span class="text-success">✓ Remaining 2 Quizzes: 20% each</span> (auto-adjusted)<br>
                                <strong>Total: 100%</strong> ✓
                            </div>
                            
                            <h6 class="fw-bold">Best for:</h6>
                            <ul class="mb-0">
                                <li>Structured grading with distinct assessment types</li>
                                <li>When components within same type have equal importance</li>
                                <li>Teachers who want minimal setup with logical grouping</li>
                                <li>Standardized assessment with subcategory flexibility</li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <!-- Comparison Table -->
                    <div class="mode-info-section">
                        <h5 class="mb-3 text-center">Quick Comparison</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Feature</th>
                                        <th class="text-center">🎯 Manual</th>
                                        <th class="text-center">🔄 Semi-Auto</th>
                                        <th class="text-center">🤖 Auto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Control Level</strong></td>
                                        <td class="text-center">100%</td>
                                        <td class="text-center">75%</td>
                                        <td class="text-center">0%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Setup Time</strong></td>
                                        <td class="text-center">High</td>
                                        <td class="text-center">Medium</td>
                                        <td class="text-center">Low</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Flexibility</strong></td>
                                        <td class="text-center">Maximum</td>
                                        <td class="text-center">High</td>
                                        <td class="text-center">None</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Auto-Adjustment</strong></td>
                                        <td class="text-center">❌ No</td>
                                        <td class="text-center">✅ Proportional</td>
                                        <td class="text-center">✅ Equal</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Manual Override</strong></td>
                                        <td class="text-center">✅ Yes</td>
                                        <td class="text-center">✅ Yes</td>
                                        <td class="text-center">❌ No</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Recommended For</strong></td>
                                        <td class="text-center">Advanced</td>
                                        <td class="text-center">Most Users</td>
                                        <td class="text-center">Beginners</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="alert alert-info mt-4">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Pro Tips:</h6>
                        <ul class="mb-0">
                            <li><strong>Start with Semi-Auto</strong> - It's the best balance for most teachers</li>
                            <li><strong>Switch anytime</strong> - You can change modes at any point</li>
                            <li><strong>Per term</strong> - Midterm and Final can use different modes</li>
                            <li><strong>Test first</strong> - Try different modes to see what works best for you</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastChangedSlider = null;

        function updateKSADisplay() {
            const kSlider = document.querySelector('input[name="knowledge_weight"]');
            const sSlider = document.querySelector('input[name="skills_weight"]');
            const aSlider = document.querySelector('input[name="attitude_weight"]');

            let k = parseInt(kSlider.value);
            let s = parseInt(sSlider.value);
            let a = parseInt(aSlider.value);
            let total = k + s + a;

            // Auto-adjust to maintain 100% total
            if (total > 100) {
                const diff = total - 100;

                // Determine which slider changed (wasn't changed by auto-adjust)
                if (lastChangedSlider === 'knowledge') {
                    // Reduce skills and attitude proportionally
                    const sRatio = s / (s + a) || 0.5;
                    s -= Math.round(diff * sRatio);
                    a -= diff - Math.round(diff * sRatio);
                } else if (lastChangedSlider === 'skills') {
                    // Reduce knowledge and attitude proportionally
                    const kRatio = k / (k + a) || 0.5;
                    k -= Math.round(diff * kRatio);
                    a -= diff - Math.round(diff * kRatio);
                } else {
                    // Reduce knowledge and skills proportionally
                    const kRatio = k / (k + s) || 0.5;
                    k -= Math.round(diff * kRatio);
                    s -= diff - Math.round(diff * kRatio);
                }

                // Ensure no negative values
                k = Math.max(0, k);
                s = Math.max(0, s);
                a = Math.max(0, a);

                // Update sliders
                kSlider.value = k;
                sSlider.value = s;
                aSlider.value = a;
            }

            // Update displays
            document.getElementById('k-display').textContent = k + '%';
            document.getElementById('s-display').textContent = s + '%';
            document.getElementById('a-display').textContent = a + '%';
            document.getElementById('total-display').textContent = (k + s + a);

            lastChangedSlider = null;
        }

        // Function to select mode when card is clicked
        function selectMode(mode) {
            // Remove selected class from all cards
            document.querySelectorAll('.mode-option').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            const clickedCard = document.querySelector(`.mode-option[data-mode="${mode}"]`);
            if (clickedCard) {
                clickedCard.classList.add('selected');
            }

            // Check the corresponding radio button
            const radio = document.getElementById(`mode-${mode}`);
            if (radio) {
                radio.checked = true;
            }
        }

        // Function to update card selection based on checked radio
        function updateCardSelection() {
            const checkedRadio = document.querySelector('input[name="mode"]:checked');
            if (checkedRadio) {
                const mode = checkedRadio.value;
                document.querySelectorAll('.mode-option').forEach(card => {
                    card.classList.remove('selected');
                });
                const selectedCard = document.querySelector(`.mode-option[data-mode="${mode}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }
            }
        }

        // Track which slider is being changed
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize KSA sliders
            document.querySelector('input[name="knowledge_weight"]').addEventListener('input', function() {
                lastChangedSlider = 'knowledge';
                updateKSADisplay();
            });
            document.querySelector('input[name="skills_weight"]').addEventListener('input', function() {
                lastChangedSlider = 'skills';
                updateKSADisplay();
            });
            document.querySelector('input[name="attitude_weight"]').addEventListener('input', function() {
                lastChangedSlider = 'attitude';
                updateKSADisplay();
            });

            // Initialize card selection on page load
            updateCardSelection();

            // Add event listeners to radio buttons to update card selection
            document.querySelectorAll('input[name="mode"]').forEach(radio => {
                radio.addEventListener('change', updateCardSelection);
            });
        });

        function saveWeightMode() {
            const checkedRadio = document.querySelector('input[name="mode"]:checked');
            if (!checkedRadio) {
                alert('⚠️ Please select a mode first');
                return;
            }

            const mode = checkedRadio.value;
            const classId = '<?php echo e($class->id); ?>';
            const term = '<?php echo e($term); ?>';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            if (!classId || !term) {
                alert('❌ Error: Missing class ID or term');
                return;
            }

            console.log('Saving mode:', mode, 'for class:', classId, 'term:', term);

            fetch(`/teacher/grade-settings/${classId}/${term}/weight-mode`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        component_weight_mode: mode
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Update the mode indicator badge
                        const indicator = document.getElementById('settingsModeIndicator');
                        if (indicator) {
                            let badgeClass = '';
                            let badgeText = '';
                            
                            switch(mode) {
                                case 'manual':
                                    badgeClass = 'bg-primary';
                                    badgeText = '🎯 Manual Mode';
                                    break;
                                case 'semi-auto':
                                    badgeClass = 'bg-success';
                                    badgeText = '🔄 Semi-Auto Mode';
                                    break;
                                case 'auto':
                                    badgeClass = 'bg-warning text-dark';
                                    badgeText = '🤖 Auto Mode';
                                    break;
                            }
                            
                            indicator.className = `badge fs-5 px-3 py-2 ${badgeClass}`;
                            indicator.textContent = badgeText;
                        }
                        
                        alert('✅ Mode saved successfully: ' + mode.toUpperCase() + '\n\nThe component management will now follow ' + mode + ' mode rules.');
                        // Don't reload, just update the UI
                    } else {
                        alert('❌ Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error saving mode: ' + error.message);
                });
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/settings.blade.php ENDPATH**/ ?>