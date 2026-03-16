<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Settings & Preferences</h1>
            <small class="text-muted">Manage your account and application preferences</small>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Account Settings -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i> Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> <?php echo e($user->name); ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo e($user->email); ?></p>
                    <p class="mb-1"><strong>Phone:</strong> <?php echo e($user->phone ?? 'Not provided'); ?></p>
                    <p class="mb-0"><strong>Role:</strong> <span
                            class="badge bg-primary"><?php echo e(ucfirst($user->role)); ?></span></p>
                </div>
            </div>
            <!-- Grading Scheme -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Grading Scheme
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('teacher.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label"><strong>Default Grading Scheme</strong></label>
                            <select name="grading_scheme" class="form-select mb-2" id="settingsSchemeSelect">
                                <option value="">-- Use system default --</option>
                                <?php $__currentLoopData = ($schemes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e((isset($userScheme) && $userScheme === $key) ? 'selected' : ''); ?>><?php echo e($s['label'] ?? $key); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="custom" <?php echo e((isset($userScheme) && $userScheme === 'custom') ? 'selected' : ''); ?>>Custom</option>
                            </select>
                        </div>

                        <div id="settingsCustomWeights" style="display: none;">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label">Knowledge %</label>
                                    <input type="number" name="grading_weights[knowledge]" class="form-control" min="0" max="100" value="<?php echo e($userWeights['knowledge'] ?? 40); ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Skills %</label>
                                    <input type="number" name="grading_weights[skills]" class="form-control" min="0" max="100" value="<?php echo e($userWeights['skills'] ?? 50); ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Attitude %</label>
                                    <input type="number" name="grading_weights[attitude]" class="form-control" min="0" max="100" value="<?php echo e($userWeights['attitude'] ?? 10); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">Save Scheme</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Theme Selection -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-palette me-2"></i> Appearance - Theme Selection
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('teacher.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label class="form-label mb-3"><strong>Select Theme:</strong></label>
                            <div class="row g-3">
                                <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $themeKey => $themeName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check theme-card">
                                            <input class="form-check-input" type="radio" name="theme"
                                                id="theme_<?php echo e($themeKey); ?>" value="<?php echo e($themeKey); ?>"
                                                <?php echo e($user->theme === $themeKey ? 'checked' : ''); ?>>
                                            <label class="form-check-label d-block" for="theme_<?php echo e($themeKey); ?>">
                                                <div class="theme-preview theme-<?php echo e($themeKey); ?>" id="preview_<?php echo e($themeKey); ?>">
                                                    <div class="theme-applied">✓ Applied</div>
                                                    <div class="theme-content">
                                                        <div class="theme-name"><?php echo e($themeName); ?></div>
                                                        <div class="theme-colors">
                                                            <div class="theme-color-box" style="background: var(--theme-primary, #2196F3);"></div>
                                                            <div class="theme-color-box" style="background: var(--theme-secondary, #6c757d);"></div>
                                                            <div class="theme-color-box" style="background: var(--theme-success, #4caf50);"></div>
                                                            <div class="theme-color-box" style="background: var(--theme-warning, #ff9800);"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Available Themes:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Light:</strong> Clean, bright default theme</li>
                            <li><strong>Dark:</strong> Easy on the eyes, dark theme</li>
                            <li><strong>Ocean Blue:</strong> Professional blue-themed interface</li>
                            <li><strong>Forest Green:</strong> Natural green-themed interface</li>
                            <li><strong>Sunset Orange:</strong> Warm orange-themed interface</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i> Theme Tips
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small mb-3">
                        <strong>Personalize your experience</strong> by selecting your preferred theme. Your choice will be
                        saved and applied across all pages.
                    </p>
                    <ul class="small list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #0d6efd; font-size: 8px;"></i>
                            <strong>Light</strong> - Perfect for daytime use
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #1a1a2e; font-size: 8px;"></i>
                            <strong>Dark</strong> - Reduces eye strain in low light
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #0066cc; font-size: 8px;"></i>
                            <strong>Ocean</strong> - Calming blue palette
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-circle" style="color: #1b5e20; font-size: 8px;"></i>
                            <strong>Forest</strong> - Natural green tones
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-circle" style="color: #e65100; font-size: 8px;"></i>
                            <strong>Sunset</strong> - Warm, energetic orange
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i> Privacy
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small mb-0">
                        Your theme preference is stored securely in your account. It will be automatically applied when you
                        log in from any device.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .theme-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .theme-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .theme-preview {
            min-height: 120px;
            border-radius: 0.75rem;
            border: 3px solid #e3e6f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .theme-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 40%;
            z-index: 1;
        }

        .theme-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            z-index: 1;
        }

        .theme-content {
            position: relative;
            z-index: 2;
            padding: 1rem;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .theme-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .theme-colors {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .theme-color-box {
            width: 24px;
            height: 24px;
            border-radius: 0.375rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .form-check-input:checked~label .theme-preview {
            border-color: #2196F3 !important;
            box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.2);
            transform: scale(1.02);
        }

        .form-check-input {
            margin-top: 0.75rem;
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Theme-specific styles */
        .theme-light .theme-preview::before { background: #ffffff; }
        .theme-light .theme-preview::after { background: #f8f9fa; }
        .theme-light .theme-content { color: #333; text-shadow: none; }

        .theme-dark .theme-preview::before { background: #1a1a2e; }
        .theme-dark .theme-preview::after { background: #16213e; }

        .theme-ocean .theme-preview::before { background: #0066cc; }
        .theme-ocean .theme-preview::after { background: #004080; }

        .theme-forest .theme-preview::before { background: #1b5e20; }
        .theme-forest .theme-preview::after { background: #2e7d32; }

        .theme-sunset .theme-preview::before { background: #e65100; }
        .theme-sunset .theme-preview::after { background: #bf360c; }

        /* Applied theme indicator */
        .theme-applied {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #4CAF50;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 3;
            display: none;
        }

        .form-check-input:checked~label .theme-applied {
            display: block;
        }

        /* Theme transition styles */
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark theme styles */
        body.dark-theme {
            background-color: #1a1a2e !important;
            color: #ffffff !important;
        }

        body.dark-theme .card {
            background-color: #16213e !important;
            border-color: #0f3460 !important;
            color: #ffffff !important;
        }

        body.dark-theme .card-header {
            background-color: #0f3460 !important;
            border-color: #16213e !important;
            color: #ffffff !important;
        }

        body.dark-theme .form-control,
        body.dark-theme .form-select {
            background-color: #16213e !important;
            border-color: #0f3460 !important;
            color: #ffffff !important;
        }

        body.dark-theme .table {
            color: #ffffff !important;
        }

        body.dark-theme .table th {
            background-color: #0f3460 !important;
            color: #ffffff !important;
        }

        /* Ocean theme styles */
        body.ocean-theme {
            background: linear-gradient(135deg, #0066cc 0%, #004080 100%) !important;
        }

        body.ocean-theme .card {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-color: #0066cc !important;
        }

        body.ocean-theme .card-header {
            background: linear-gradient(135deg, #0066cc 0%, #004080 100%) !important;
            color: white !important;
        }

        body.ocean-theme .btn-primary {
            background: linear-gradient(135deg, #0066cc 0%, #004080 100%) !important;
            border-color: #0066cc !important;
        }

        /* Forest theme styles */
        body.forest-theme {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%) !important;
        }

        body.forest-theme .card {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-color: #1b5e20 !important;
        }

        body.forest-theme .card-header {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%) !important;
            color: white !important;
        }

        body.forest-theme .btn-primary {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%) !important;
            border-color: #1b5e20 !important;
        }

        /* Sunset theme styles */
        body.sunset-theme {
            background: linear-gradient(135deg, #e65100 0%, #bf360c 100%) !important;
        }

        body.sunset-theme .card {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-color: #e65100 !important;
        }

        body.sunset-theme .card-header {
            background: linear-gradient(135deg, #e65100 0%, #bf360c 100%) !important;
            color: white !important;
        }

        body.sunset-theme .btn-primary {
            background: linear-gradient(135deg, #e65100 0%, #bf360c 100%) !important;
            border-color: #e65100 !important;
        }

        @media (max-width: 768px) {
            .theme-preview {
                min-height: 100px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            // Grading scheme toggle
            const select = document.getElementById('settingsSchemeSelect');
            const custom = document.getElementById('settingsCustomWeights');
            function toggle(){
                if(!select) return;
                if(select.value === 'custom') custom.style.display = 'block'; else custom.style.display = 'none';
            }
            if(select){ select.addEventListener('change', toggle); toggle(); }

            // Theme switching functionality
            const themeRadios = document.querySelectorAll('input[name="theme"]');
            const body = document.body;
            const themeForm = document.getElementById('themeForm');

            // Apply saved theme on page load
            function applyTheme(theme) {
                // Remove all theme classes
                body.classList.remove('light-theme', 'dark-theme', 'ocean-theme', 'forest-theme', 'sunset-theme');
                
                // Apply new theme
                if (theme && theme !== 'light') {
                    body.classList.add(`${theme}-theme`);
                }
                
                // Save to localStorage for persistence
                localStorage.setItem('selectedTheme', theme);
                
                // Update theme previews
                updateThemePreviews(theme);
                
                console.log(`🎨 Theme applied: ${theme}`);
            }

            // Update theme preview indicators
            function updateThemePreviews(selectedTheme) {
                themeRadios.forEach(radio => {
                    const preview = radio.nextElementSibling.querySelector('.theme-preview');
                    const appliedIndicator = radio.nextElementSibling.querySelector('.theme-applied');
                    
                    if (radio.value === selectedTheme) {
                        preview.style.borderColor = '#2196F3';
                        preview.style.boxShadow = '0 0 0 4px rgba(33, 150, 243, 0.2)';
                        preview.style.transform = 'scale(1.02)';
                        if (appliedIndicator) {
                            appliedIndicator.style.display = 'block';
                        }
                    } else {
                        preview.style.borderColor = '#e3e6f0';
                        preview.style.boxShadow = 'none';
                        preview.style.transform = 'scale(1)';
                        if (appliedIndicator) {
                            appliedIndicator.style.display = 'none';
                        }
                    }
                });
            }

            // Theme change event listeners
            themeRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const theme = radio.value;
                    applyTheme(theme);
                    
                    // Show feedback
                    showThemeFeedback(theme);
                });
            });

            // Show theme change feedback
            function showThemeFeedback(theme) {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '1050';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <i class="fas fa-palette me-2"></i>
                            <strong class="me-auto">Theme Changed</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            ${theme.charAt(0).toUpperCase() + theme.slice(1)} theme has been applied successfully!
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }

            // Load saved theme on page load
            const savedTheme = localStorage.getItem('selectedTheme') || '<?php echo e($user->theme ?? "light"); ?>';
            applyTheme(savedTheme);

            // Handle form submission for theme
            if (themeForm) {
                themeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const theme = formData.get('theme');
                    
                    // Apply theme immediately
                    applyTheme(theme);
                    
                    // Submit form via AJAX for better UX
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Show success feedback
                        showThemeFeedback(theme);
                        console.log('✅ Theme saved to database');
                    })
                    .catch(error => {
                        console.error('❌ Error saving theme:', error);
                        // Still apply theme locally even if server save fails
                        showThemeFeedback(theme);
                    });
                });
            }

            // Enhanced theme preview interactions
            const themeCards = document.querySelectorAll('.theme-card');
            themeCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const preview = this.querySelector('.theme-preview');
                    if (preview && !this.querySelector('input').checked) {
                        preview.style.transform = 'scale(1.05)';
                        preview.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
                    }
                });

                card.addEventListener('mouseleave', function() {
                    const preview = this.querySelector('.theme-preview');
                    const radio = this.querySelector('input');
                    if (preview && !radio.checked) {
                        preview.style.transform = 'scale(1)';
                        preview.style.boxShadow = 'none';
                    }
                });

                // Click to select theme
                card.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        const radio = this.querySelector('input');
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Keyboard navigation for themes
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    const checkedRadio = document.querySelector('input[name="theme"]:checked');
                    const allRadios = Array.from(document.querySelectorAll('input[name="theme"]'));
                    const currentIndex = allRadios.indexOf(checkedRadio);
                    
                    let newIndex;
                    if (e.key === 'ArrowLeft') {
                        newIndex = currentIndex > 0 ? currentIndex - 1 : allRadios.length - 1;
                    } else {
                        newIndex = currentIndex < allRadios.length - 1 ? currentIndex + 1 : 0;
                    }
                    
                    allRadios[newIndex].checked = true;
                    allRadios[newIndex].dispatchEvent(new Event('change'));
                    allRadios[newIndex].focus();
                }
            });

            console.log('🎨 Enhanced Theme System Loaded');
            console.log('✅ Theme switching functional');
            console.log('✅ Local storage persistence active');
            console.log('✅ Keyboard navigation enabled');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\settings\index.blade.php ENDPATH**/ ?>