<?php $__env->startSection('content'); ?>
<style>
    /* Grade Content Platform Styles */
    :root {
        --primary-color: #4f46e5;
        --knowledge-color: #2196F3;
        --skills-color: #4CAF50;
        --attitude-color: #FF9800;
    }

    .grade-management-center {
        position: fixed;
        top: var(--topbar-height, 60px);
        left: var(--sidebar-width, 250px);
        right: 0;
        background: white;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem 2rem;
        z-index: 100;
        transition: left 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .sidebar-collapsed .grade-management-center {
        left: 60px;
    }

    .grade-content-wrapper {
        margin-top: calc(var(--topbar-height, 60px) + 80px);
        padding: 2rem;
    }

    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #64748b;
        padding: 1rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
        background: none;
    }

    .scheme-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }

    .scheme-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        transform: translateY(-2px);
    }

    .scheme-card.active {
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.05);
    }

    .ksa-progress-bar {
        height: 30px;
        border-radius: 15px;
        overflow: hidden;
        display: flex;
        margin: 1rem 0;
    }

    .ksa-segment {
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .ksa-segment.knowledge { background: var(--knowledge-color); }
    .ksa-segment.skills { background: var(--skills-color); }
    .ksa-segment.attitude { background: var(--attitude-color); }

    /* ===================================
       MOBILE RESPONSIVE STYLES
       =================================== */
    
    @media (max-width: 768px) {
        /* Remove fixed positioning on mobile */
        .grade-management-center {
            position: static !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            padding: 0.75rem !important;
            margin: 0 !important;
            border-radius: 0;
        }

        .grade-management-center .d-flex {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }

        .grade-management-center h4 {
            font-size: 1rem !important;
        }

        .grade-management-center small {
            font-size: 0.75rem !important;
        }

        .grade-management-center .d-flex.gap-2 {
            width: 100%;
            flex-direction: row !important;
            justify-content: space-between;
        }

        .grade-management-center .btn {
            flex: 1;
            font-size: 0.75rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        .grade-content-wrapper {
            margin-top: 0 !important;
            padding: 0 !important;
        }

        /* Card adjustments */
        .card {
            border-radius: 0 !important;
            margin: 0 !important;
        }

        .card-body {
            padding: 0 !important;
        }

        /* Tabs */
        .nav-tabs {
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            border-bottom: 2px solid #e2e8f0;
            margin: 0 !important;
            padding: 0 0.75rem !important;
            background: white;
        }

        .nav-tabs::-webkit-scrollbar {
            height: 2px;
        }

        .nav-tabs::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .nav-tabs .nav-link {
            white-space: nowrap;
            padding: 0.75rem 1rem !important;
            font-size: 0.8125rem !important;
            border: none !important;
        }

        .nav-tabs .nav-link i {
            font-size: 0.875rem;
        }

        /* Tab content */
        .tab-content {
            padding: 0 !important;
        }

        .tab-pane {
            padding: 0.75rem !important;
        }

        /* Scheme cards */
        .scheme-card {
            padding: 0.875rem !important;
            margin-bottom: 0.875rem !important;
        }

        .scheme-card h5 {
            font-size: 1rem !important;
        }

        .scheme-card p {
            font-size: 0.8125rem !important;
        }

        .scheme-card .fa-2x {
            font-size: 1.5rem !important;
        }

        /* KSA progress bar */
        .ksa-progress-bar {
            height: 20px !important;
            font-size: 0.625rem !important;
            margin: 0.75rem 0 !important;
        }

        .ksa-segment {
            font-size: 0.625rem !important;
        }

        /* Buttons */
        .btn-group {
            flex-direction: column !important;
            width: 100% !important;
        }

        .btn-group .btn {
            width: 100% !important;
            margin-bottom: 0.5rem !important;
        }

        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }

        .d-flex.gap-2.flex-wrap .btn {
            flex: 1 1 calc(50% - 0.25rem);
            font-size: 0.8125rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        /* Form controls */
        .form-select,
        .form-control {
            font-size: 16px !important; /* Prevents zoom on iOS */
            padding: 0.5rem 0.75rem !important;
        }

        /* Headers */
        h1, .h1 {
            font-size: 1.25rem !important;
        }

        h2, .h2 {
            font-size: 1.125rem !important;
        }

        h3, .h3 {
            font-size: 1rem !important;
        }

        h4, .h4 {
            font-size: 0.9375rem !important;
        }

        h5, .h5 {
            font-size: 0.875rem !important;
        }

        h6, .h6 {
            font-size: 0.8125rem !important;
        }

        /* Alerts */
        .alert {
            font-size: 0.8125rem !important;
            padding: 0.625rem !important;
            margin-bottom: 0.75rem !important;
        }

        /* Row adjustments */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .row > * {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Component cards */
        .component-category-card {
            padding: 0.875rem !important;
            margin-bottom: 0.875rem !important;
        }

        .component-list-item {
            padding: 0.75rem !important;
            margin-bottom: 0.5rem !important;
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        /* Stat boxes */
        .stat-box {
            padding: 0.75rem !important;
        }

        .stat-value {
            font-size: 1.5rem !important;
        }

        .stat-label {
            font-size: 0.75rem !important;
        }

        /* Badges */
        .badge {
            font-size: 0.6875rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        .term-badge {
            padding: 0.375rem 0.75rem !important;
            font-size: 0.75rem !important;
        }

        /* Modal adjustments */
        .modal-dialog {
            margin: 0.5rem !important;
            max-width: calc(100% - 1rem) !important;
        }

        .modal-body {
            padding: 1rem !important;
        }

        .modal-header {
            padding: 0.875rem !important;
        }

        .modal-title {
            font-size: 1rem !important;
        }

        .modal-footer {
            padding: 0.75rem !important;
            flex-direction: column !important;
        }

        .modal-footer .btn {
            width: 100% !important;
            margin: 0.25rem 0 !important;
        }
    }

    @media (max-width: 480px) {
        .grade-management-center {
            padding: 0.625rem !important;
        }

        .grade-management-center h4 {
            font-size: 0.9375rem !important;
        }

        .grade-management-center .btn {
            font-size: 0.6875rem !important;
            padding: 0.375rem 0.5rem !important;
        }

        .nav-tabs {
            padding: 0 0.5rem !important;
        }

        .nav-tabs .nav-link {
            padding: 0.625rem 0.75rem !important;
            font-size: 0.75rem !important;
        }

        .nav-tabs .nav-link i {
            display: none; /* Hide icons on very small screens */
        }

        .tab-pane {
            padding: 0.5rem !important;
        }

        .scheme-card {
            padding: 0.75rem !important;
        }

        .ksa-progress-bar {
            height: 18px !important;
            font-size: 0.5625rem !important;
        }

        .btn {
            font-size: 0.8125rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        .btn-sm {
            font-size: 0.75rem !important;
            padding: 0.375rem 0.5rem !important;
        }

        .d-flex.gap-2.flex-wrap .btn {
            flex: 1 1 100%;
        }

        h1, .h1 {
            font-size: 1.125rem !important;
        }

        h2, .h2 {
            font-size: 1rem !important;
        }

        h3, .h3 {
            font-size: 0.9375rem !important;
        }
    }

    /* Landscape mobile */
    @media (max-width: 768px) and (orientation: landscape) {
        .grade-management-center {
            padding: 0.5rem !important;
        }

        .grade-management-center h4 {
            font-size: 0.875rem !important;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem !important;
        }
    }

    /* Touch-friendly improvements */
    @media (max-width: 768px) {
        /* Larger tap targets */
        .btn,
        a.btn,
        button,
        .nav-link {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Better spacing */
        .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .mb-4 {
            margin-bottom: 1rem !important;
        }

        /* Prevent text selection on buttons */
        .btn,
        button {
            -webkit-user-select: none;
            user-select: none;
        }
    }

    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }

    /* Stat Boxes */
    .stat-box {
        padding: 1rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Component Cards */
    .component-category-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .component-category-card.knowledge {
        border-left: 4px solid var(--knowledge-color);
    }

    .component-category-card.skills {
        border-left: 4px solid var(--skills-color);
    }

    .component-category-card.attitude {
        border-left: 4px solid var(--attitude-color);
    }

    .component-list-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }

    .component-list-item:hover {
        background: #e2e8f0;
        transform: translateX(5px);
    }

    /* Grading Config Modal */
    .grading-config-modal .modal-dialog {
        max-width: 900px;
    }

    .term-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .term-badge.midterm {
        background: rgba(79, 70, 229, 0.1);
        color: var(--primary-color);
    }

    .term-badge.final {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }
</style>

<!-- Grade Management Center Header -->
<div class="grade-management-center">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-graduation-cap me-2"></i>
                <?php echo e($class->class_name); ?> - <?php echo e(ucfirst($term)); ?> Term
            </h4>
            <small class="text-muted"><?php echo e($class->course->program_name ?? 'N/A'); ?></small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
            <button class="btn btn-outline-success btn-sm" onclick="exportGrades()">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grade-content-wrapper">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs px-3 pt-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="entry-tab" data-bs-toggle="tab" data-bs-target="#entry-pane" 
                            type="button" role="tab">
                        <i class="fas fa-edit me-2"></i>Grade Entry
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" 
                            type="button" role="tab" onclick="loadComponentsForSettings()">
                        <i class="fas fa-cog me-2"></i>Settings & Components
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="schemes-tab" data-bs-toggle="tab" data-bs-target="#schemes-pane" 
                            type="button" role="tab">
                        <i class="fas fa-th-large me-2"></i>Grade Schemes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-pane" 
                            type="button" role="tab">
                        <i class="fas fa-history me-2"></i>History
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content p-0">
                <!-- TAB 1: Grade Entry (Embedded Advanced Grade Entry) -->
                <div class="tab-pane fade show active" id="entry-pane" role="tabpanel">
                    <?php echo $__env->make('teacher.grades.advanced_grade_entry_embedded', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <!-- TAB 2: Settings & Components -->
                <div class="tab-pane fade" id="settings-pane" role="tabpanel">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5><i class="fas fa-cog me-2"></i>Component Management</h5>
                                <p class="text-muted mb-0">Add, edit, and organize assessment components for each KSA category</p>
                            </div>
                            <div>
                                <?php
                                    $gradingSettings = \App\Models\GradingScaleSetting::where('class_id', $class->id)
                                        ->where('term', $term)
                                        ->first();
                                    $currentMode = $gradingSettings->component_weight_mode ?? 'semi-auto';
                                    $badgeClass = $currentMode === 'manual' ? 'bg-primary' : ($currentMode === 'auto' ? 'bg-warning text-dark' : 'bg-success');
                                    $badgeText = $currentMode === 'manual' ? '🎯 Manual Mode' : ($currentMode === 'auto' ? '🤖 Auto Mode' : '🔄 Semi-Auto Mode');
                                ?>
                                <span id="currentModeIndicator" class="badge <?php echo e($badgeClass); ?> fs-6 px-3 py-2">
                                    <?php echo e($badgeText); ?>

                                </span>
                            </div>
                        </div>
                        
                        <!-- Mode Status Alert -->
                        <div id="modeStatusAlert" class="alert alert-dismissible fade show <?php echo e($currentMode === 'manual' ? 'alert-primary border-primary' : ($currentMode === 'auto' ? 'alert-warning border-warning' : 'alert-success border-success')); ?>" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1" id="modeStatusTitle">
                                        <?php if($currentMode === 'manual'): ?>
                                            <i class="fas fa-hand-paper me-2"></i>Manual Mode Active
                                        <?php elseif($currentMode === 'auto'): ?>
                                            <i class="fas fa-robot me-2"></i>Auto Mode Active
                                        <?php else: ?>
                                            <i class="fas fa-magic me-2"></i>Semi-Auto Mode Active (Recommended)
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-0" id="modeStatusDescription">
                                        <?php if($currentMode === 'manual'): ?>
                                            You have <strong>full control</strong> over component weights. Set each weight manually and ensure they sum to 100% per category.
                                        <?php elseif($currentMode === 'auto'): ?>
                                            <strong>Weights are automatically managed</strong> within each subcategory and distributed equally. Quizzes adjust independently from Exams, Outputs from Activities, etc.
                                        <?php else: ?>
                                            System suggests equal weights within each subcategory, but you can <strong>override any component</strong>. Other weights in the same subcategory adjust proportionally to maintain 100%.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Component Update Notice -->
                    <div class="alert alert-info border-info mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-lightbulb fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">
                                    <i class="fas fa-sync-alt me-2"></i>Component Updates
                                </h6>
                                <p class="mb-0 small">
                                    When you <strong>add, edit, or delete</strong> components, the page will automatically reload to update the Grade Entry table headers with your changes. 
                                    This ensures max scores, passing scores, and weights are always current.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input to store current mode for JavaScript -->
                    <input type="hidden" id="currentComponentMode" value="<?php echo e($currentMode); ?>">

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#componentManagerModal">
                                    <i class="fas fa-plus me-2"></i>Add New Component
                                </button>
                                <button class="btn btn-success" onclick="applyDefaultTemplate()">
                                    <i class="fas fa-magic me-2"></i>Apply Default Template
                                </button>
                                <button class="btn btn-info" onclick="loadComponentsForSettings()">
                                    <i class="fas fa-sync me-2"></i>Refresh Components
                                </button>
                                <a href="<?php echo e(route('teacher.grades.settings.index', $class->id)); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-cog me-2"></i>Advanced Settings
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- KSA Weight Distribution -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-balance-scale me-2"></i>KSA Weight Distribution</h6>
                            <div class="row mb-3">
                                <div class="col-md-8 mx-auto">
                                    <?php
                                        $kPct = $ksaSettings->knowledge_percentage ?? 40;
                                        $sPct = $ksaSettings->skills_percentage ?? 50;
                                        $aPct = $ksaSettings->attitude_percentage ?? 10;
                                    ?>
                                    <div class="ksa-progress-bar">
                                        <div class="ksa-segment knowledge" style="width: <?php echo e($kPct); ?>%">Knowledge <?php echo e($kPct); ?>%</div>
                                        <div class="ksa-segment skills" style="width: <?php echo e($sPct); ?>%">Skills <?php echo e($sPct); ?>%</div>
                                        <div class="ksa-segment attitude" style="width: <?php echo e($aPct); ?>%">Attitude <?php echo e($aPct); ?>%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    To modify KSA percentages, use Advanced Settings
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Components List by Category -->
                    <div id="componentsListSettings">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading components...</p>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: Grade Schemes -->
                <div class="tab-pane fade" id="schemes-pane" role="tabpanel">
                    <div class="mb-4">
                        <h5><i class="fas fa-th-large me-2"></i>Available Grade Entry Methods</h5>
                        <p class="text-muted">Choose your preferred grading method</p>
                    </div>

                    <div class="row g-4">
                        <!-- Advanced KSA Entry -->
                        <div class="col-md-6">
                            <div class="scheme-card active">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-star fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Advanced KSA Entry</h5>
                                        <span class="badge bg-success">Currently Active</span>
                                    </div>
                                </div>
                                <p class="text-muted">
                                    Modern dynamic grading system with customizable assessment components and real-time calculations.
                                </p>
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-check text-success me-2"></i>Dynamic components</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Real-time calculations</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Flexible weights</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Classic Grade Entry -->
                        <div class="col-md-6">
                            <div class="scheme-card">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-list fa-2x text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Classic Grade Entry</h5>
                                        <span class="badge bg-secondary">Legacy</span>
                                    </div>
                                </div>
                                <p class="text-muted">
                                    Traditional simple interface for quick grade entry with fixed components.
                                </p>
                                <a href="<?php echo e(route('teacher.grades.entry.old', $class->id)); ?>" class="btn btn-outline-secondary btn-sm">
                                    Use Classic Entry
                                </a>
                            </div>
                        </div>



                        <!-- Points-Based Entry -->
                        <div class="col-md-6">
                            <div class="scheme-card">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calculator fa-2x text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Points-Based Entry</h5>
                                        <span class="badge bg-warning">Coming Soon</span>
                                    </div>
                                </div>
                                <p class="text-muted">
                                    Pure points accumulation system without percentage calculations.
                                </p>
                                <button class="btn btn-outline-warning btn-sm" disabled>
                                    Under Development
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: History -->
                <div class="tab-pane fade" id="history-pane" role="tabpanel">
                    <div class="mb-4">
                        <h5><i class="fas fa-history me-2"></i>Grade History & Activity Log</h5>
                        <p class="text-muted">Recent grade submissions and modifications</p>
                    </div>

                    <div class="timeline">
                        <div class="alert alert-light">
                            <i class="fas fa-info-circle me-2"></i>
                            Grade history tracking will be available soon. This will show all grade submissions, modifications, and exports.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Component Manager Modal -->
<?php echo $__env->make('teacher.grades.components.component-manager-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Grading Configuration Modal -->
<div class="modal fade grading-config-modal" id="gradingConfigModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div>
                    <h5 class="modal-title">
                        <i class="fas fa-cog me-2"></i>Grading Scheme Configuration
                    </h5>
                    <small class="d-block mt-1">
                        <span class="term-badge" id="configTermBadge">Midterm</span>
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Configure assessment components and their weights for <strong id="configTermText">Midterm</strong> term.
                </div>

                <!-- Component Configuration -->
                <div id="configComponentsList">
                    <!-- Will be populated dynamically -->
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-primary" onclick="saveGradingConfig()">
                        <i class="fas fa-save me-2"></i>Save Configuration
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mode-Aware Component Management Script -->
<script src="<?php echo e(asset('js/mode-aware-component-management.js')); ?>"></script>

<script>
let componentsData = {};
const classId = <?php echo e($class->id); ?>;
const term = '<?php echo e($term); ?>';

// Export grades
function exportGrades() {
    window.location.href = `/teacher/grades/export/${classId}?term=${term}`;
}

// Show notification
function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
    const iconClass = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle');
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <i class="fas fa-${iconClass} me-2"></i>
        ${message}
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

// ========== COMPONENT MANAGER MODAL FUNCTIONS ==========

const subcategoryTemplates = {
    'Knowledge': ['Quiz', 'Exam', 'Test', 'Pre-test'],
    'Skills': ['Output', 'Project', 'Assignment', 'Activity', 'Participation', 'Presentation'],
    'Attitude': ['Behavior', 'Attendance', 'Awareness', 'Collaboration', 'Punctuality']
};

// Cache for components to avoid repeated API calls
let componentsCache = null;
let componentsCacheTime = null;
const CACHE_DURATION = 30000; // 30 seconds

// Update subcategory dropdown based on selected category
function updateSubcategoryOptions() {
    const categorySelect = document.getElementById('componentCategory');
    const subcategorySelect = document.getElementById('componentSubcategory');
    const category = categorySelect.value;
    
    subcategorySelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
    
    if (category && subcategoryTemplates[category]) {
        subcategoryTemplates[category].forEach(sub => {
            const option = document.createElement('option');
            option.value = sub;
            option.textContent = sub;
            subcategorySelect.appendChild(option);
        });
        subcategorySelect.disabled = false;
    } else {
        subcategorySelect.disabled = true;
    }
}

// Handle add component form submission
document.addEventListener('DOMContentLoaded', function() {
    console.log('[DOMContentLoaded] Initializing...');
    
    // Initialize mode-aware system
    const classId = <?php echo e($class->id); ?>;
    const term = '<?php echo e($term); ?>';
    if (typeof initializeModeAwareSystem === 'function') {
        initializeModeAwareSystem(classId, term);
    }
    
    // Initialize category dropdown change handler
    const categorySelect = document.getElementById('componentCategory');
    if (categorySelect) {
        categorySelect.addEventListener('change', updateSubcategoryOptions);
        console.log('[DOMContentLoaded] Category dropdown handler attached');
    }
    
    const addComponentForm = document.getElementById('addComponentForm');
    if (addComponentForm) {
        addComponentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submitted'); // Debug
            
            const formData = {
                name: document.getElementById('componentName').value,
                category: document.getElementById('componentCategory').value,
                subcategory: document.getElementById('componentSubcategory').value,
                max_score: document.getElementById('componentMaxScore').value,
                weight: document.getElementById('componentWeight').value,
                passing_score: document.getElementById('componentPassingScore').value || null
            };
            
            console.log('Form data:', formData); // Debug
            
            // Validate required fields
            if (!formData.name || !formData.category || !formData.subcategory || !formData.max_score || !formData.weight) {
                showNotification('Please fill in all required fields', 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            
            // Check if we're editing or adding
            const editId = this.dataset.editId;
            const isEdit = editId && editId !== '';
            
            const url = isEdit 
                ? `/teacher/components/${classId}/${editId}`
                : `/teacher/components/${classId}`;
            const method = isEdit ? 'PUT' : 'POST';
            
            console.log('Sending request to:', url, 'Method:', method); // Debug
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status); // Debug
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Debug
                if (data.success) {
                    // Show success message with reload notice
                    showNotification(data.message + ' - Refreshing page to show changes...', 'success');
                    
                    // Show loading overlay
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; align-items: center; justify-content: center;';
                    loadingOverlay.innerHTML = `
                        <div class="text-center text-white">
                            <div class="spinner-border mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5>Updating Grade Entry Table...</h5>
                            <p>Please wait while we refresh the component data</p>
                        </div>
                    `;
                    document.body.appendChild(loadingOverlay);
                    
                    addComponentForm.reset();
                    delete addComponentForm.dataset.editId; // Clear edit mode
                    
                    // Reset button text
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Component';
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('componentManagerModal'));
                    if (modal) modal.hide();
                    
                    // Force hard reload with cache bypass to ensure updated data is loaded
                    // Increased delay to ensure database transaction is committed
                    setTimeout(() => {
                        // Use location.reload(true) to force cache bypass
                        window.location.reload(true);
                    }, 1200);
                } else {
                    showNotification(data.message || 'Failed to save component', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error saving component:', error);
                showNotification('Error saving component: ' + error.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // Initialize subcategory dropdown when modal opens
    const componentModal = document.getElementById('componentManagerModal');
    if (componentModal) {
        componentModal.addEventListener('shown.bs.modal', function() {
            console.log('Modal opened'); // Debug
            
            // Check if we're in edit mode
            const form = document.getElementById('addComponentForm');
            const isEditMode = form && form.dataset.editId;
            
            // Only reset if NOT in edit mode
            if (!isEditMode) {
                // Reset form
                if (form) {
                    form.reset();
                    delete form.dataset.editId; // Clear edit mode
                    
                    // Reset button text
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Component';
                    }
                    
                    // Reset modal title
                    const modalTitle = document.getElementById('componentManagerLabel');
                    if (modalTitle) {
                        modalTitle.innerHTML = '<i class="fas fa-cogs"></i> Component Manager';
                    }
                }
                
                // Clear subcategory dropdown
                const subcategorySelect = document.getElementById('componentSubcategory');
                if (subcategorySelect) {
                    subcategorySelect.innerHTML = '<option value="">-- Select Category First --</option>';
                    subcategorySelect.disabled = true;
                }
            }
        });
    }
    
    // Load components when manage tab is clicked
    const manageTab = document.getElementById('manageComponentTab');
    if (manageTab) {
        manageTab.addEventListener('click', loadComponentsForManagement);
    }
});

// Load components for management tab
function loadComponentsForManagement() {
    const componentsList = document.getElementById('componentsList');
    if (!componentsList) return;
    
    componentsList.innerHTML = '<div class="text-center py-3"><div class="spinner-border"></div></div>';
    
    fetch(`/teacher/components/${classId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderComponentsList(data.components);
            } else {
                componentsList.innerHTML = '<div class="alert alert-warning">No components found</div>';
            }
        })
        .catch(error => {
            console.error('Error loading components:', error);
            componentsList.innerHTML = '<div class="alert alert-danger">Failed to load components</div>';
        });
}

// Render components list
function renderComponentsList(components) {
    const componentsList = document.getElementById('componentsList');
    if (!componentsList) return;
    
    let html = '';
    
    ['knowledge', 'skills', 'attitude'].forEach(category => {
        const categoryComps = components[category] || [];
        if (categoryComps.length > 0) {
            const categoryName = category.charAt(0).toUpperCase() + category.slice(1);
            html += `<h6 class="mt-3 mb-2">${categoryName}</h6>`;
            
            categoryComps.forEach(comp => {
                html += `
                    <div class="component-item ${category}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${comp.name}</strong>
                                <small class="text-muted d-block">${comp.subcategory} | Weight: ${comp.weight}% | Max: ${comp.max_score} | Passing: ${comp.passing_score || 75}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="editComponent(${comp.id}, '${comp.name}', '${comp.subcategory}', ${comp.weight}, ${comp.max_score}, ${comp.passing_score || 75})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteComponent(${comp.id}, '${comp.name}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
    });
    
    if (html === '') {
        html = '<div class="alert alert-info">No components configured yet. Add components using the "Add Component" tab.</div>';
    }
    
    componentsList.innerHTML = html;
}

// Delete component
function deleteComponent(componentId, componentName) {
    if (!confirm(`Are you sure you want to delete "${componentName}"?`)) {
        return;
    }
    
    // Show loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    loadingOverlay.innerHTML = `
        <div class="text-center text-white">
            <div class="spinner-border mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Deleting Component...</h5>
            <p>Please wait</p>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
    
    fetch(`/teacher/components/${classId}/${componentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message + ' - Refreshing page to show changes...', 'success');
            // Update loading message
            loadingOverlay.querySelector('h5').textContent = 'Updating Grade Entry Table...';
            loadingOverlay.querySelector('p').textContent = 'Please wait while we refresh the component data';
            // Force hard reload with increased delay
            setTimeout(() => {
                window.location.reload(true);
            }, 1200);
        } else {
            document.body.removeChild(loadingOverlay);
            showNotification(data.message || 'Failed to delete component', 'error');
        }
    })
    .catch(error => {
        document.body.removeChild(loadingOverlay);
        console.error('Error deleting component:', error);
        showNotification('Error deleting component', 'error');
    });
}

// Apply template
function applyTemplate(template, category) {
    if (!confirm(`Apply ${category} template? This will add multiple components.`)) {
        return;
    }
    
    fetch(`/teacher/components/${classId}/apply-template`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ template: template, category: category })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            loadComponentsForManagement();
            loadComponentsForSettings();
            // Reload the grade entry page to show new components
            location.reload();
        } else {
            showNotification(data.message || 'Failed to apply template', 'error');
        }
    })
    .catch(error => {
        console.error('Error applying template:', error);
        showNotification('Error applying template', 'error');
    });
}

// Load components for settings tab
function loadComponentsForSettings() {
    const container = document.getElementById('componentsListSettings');
    if (!container) return;
    
    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Loading components...</p></div>';
    
    fetch(`/teacher/components/${classId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            renderComponentsForSettings(data.components);
        } else {
            container.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No components found. Click "Add New Component" to get started.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading components:', error);
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Failed to load components. Please refresh the page.
                <br><small class="text-muted">${error.message}</small>
            </div>
        `;
    });
}

// Render components for settings tab
function renderComponentsForSettings(components) {
    const container = document.getElementById('componentsListSettings');
    if (!container) return;
    
    let html = '';
    
    const categories = [
        { key: 'knowledge', name: 'Knowledge', icon: 'brain', color: 'var(--knowledge-color)' },
        { key: 'skills', name: 'Skills', icon: 'tools', color: 'var(--skills-color)' },
        { key: 'attitude', name: 'Attitude', icon: 'heart', color: 'var(--attitude-color)' }
    ];
    
    categories.forEach(cat => {
        const categoryComps = components[cat.key] || [];
        
        html += `
            <div class="component-category-card ${cat.key}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fas fa-${cat.icon} me-2" style="color: ${cat.color};"></i>
                        ${cat.name} Components
                        <span class="badge bg-secondary ms-2">${categoryComps.length}</span>
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="addComponentQuick('${cat.key}')">
                        <i class="fas fa-plus me-1"></i>Add
                    </button>
                </div>
        `;
        
        if (categoryComps.length > 0) {
            categoryComps.forEach(comp => {
                html += `
                    <div class="component-list-item">
                        <div>
                            <strong>${comp.name}</strong>
                            <small class="text-muted d-block">
                                ${comp.subcategory} | Weight: ${comp.weight}% | Max: ${comp.max_score} | Passing: ${comp.passing_score || 75}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="editComponent(${comp.id}, '${comp.name}', '${comp.subcategory}', ${comp.weight}, ${comp.max_score}, ${comp.passing_score || 75})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteComponent(${comp.id}, '${comp.name}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="alert alert-light mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    No components added yet. Click "Add" to create one.
                </div>
            `;
        }
        
        html += `</div>`;
    });
    
    container.innerHTML = html;
}

// Edit component function
function editComponent(id, name, subcategory, weight, maxScore, passingScore) {
    // Check if modal is already open
    const modalElement = document.getElementById('componentManagerModal');
    const existingModal = bootstrap.Modal.getInstance(modalElement);
    
    // If modal is open, close it first
    if (existingModal) {
        existingModal.hide();
        // Wait for modal to close before reopening
        setTimeout(() => {
            openEditModal(id, name, subcategory, weight, maxScore, passingScore);
        }, 300);
    } else {
        openEditModal(id, name, subcategory, weight, maxScore, passingScore);
    }
}

// Helper function to open edit modal
function openEditModal(id, name, subcategory, weight, maxScore, passingScore) {
    // Change form to edit mode FIRST (before modal opens)
    const form = document.getElementById('addComponentForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Store the component ID for update
    form.dataset.editId = id;
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Component';
    
    // Change modal title
    const modalTitle = document.getElementById('componentManagerLabel');
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Component';
    }
    
    // Populate the modal with existing data
    document.getElementById('componentName').value = name;
    document.getElementById('componentWeight').value = weight;
    document.getElementById('componentMaxScore').value = maxScore;
    document.getElementById('componentPassingScore').value = passingScore;
    
    // Determine category from subcategory
    let category = '';
    if (['Quiz', 'Exam', 'Test', 'Pre-test'].includes(subcategory)) {
        category = 'Knowledge';
    } else if (['Output', 'Project', 'Assignment', 'Activity', 'Participation', 'Presentation'].includes(subcategory)) {
        category = 'Skills';
    } else if (['Behavior', 'Attendance', 'Awareness', 'Collaboration', 'Punctuality'].includes(subcategory)) {
        category = 'Attitude';
    }
    
    document.getElementById('componentCategory').value = category;
    updateSubcategoryOptions();
    document.getElementById('componentSubcategory').value = subcategory;
    
    // Switch to Add Component tab
    const addTab = document.getElementById('addComponentTab');
    if (addTab) {
        addTab.click();
    }
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('componentManagerModal'));
    modal.show();
}

// Add component quick (opens modal with pre-selected category)
function addComponentQuick(category) {
    const categorySelect = document.getElementById('componentCategory');
    if (categorySelect) {
        const categoryMap = {
            'knowledge': 'Knowledge',
            'skills': 'Skills',
            'attitude': 'Attitude'
        };
        categorySelect.value = categoryMap[category];
        updateSubcategoryOptions();
    }
    
    const modal = new bootstrap.Modal(document.getElementById('componentManagerModal'));
    modal.show();
}

// Apply default template
function applyDefaultTemplate() {
    if (!confirm('Apply default KSA template? This will add standard components for all categories.')) {
        return;
    }
    
    showNotification('Applying templates...', 'info');
    
    Promise.all([
        fetch(`/teacher/components/${classId}/apply-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ template: 'knowledge', category: 'Knowledge' })
        }),
        fetch(`/teacher/components/${classId}/apply-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ template: 'skills', category: 'Skills' })
        }),
        fetch(`/teacher/components/${classId}/apply-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ template: 'attitude', category: 'Attitude' })
        })
    ])
    .then(() => {
        showNotification('Default templates applied successfully!', 'success');
        loadComponentsForSettings();
        // Reload the grade entry page to show new components
        location.reload();
    })
    .catch(error => {
        console.error('Error applying templates:', error);
        showNotification('Error applying templates', 'error');
    });
}

// Open grading configuration modal
let currentConfigTerm = 'midterm';

function openGradingConfig(term) {
    currentConfigTerm = term;
    
    const badge = document.getElementById('configTermBadge');
    const text = document.getElementById('configTermText');
    
    if (badge && text) {
        badge.textContent = term.charAt(0).toUpperCase() + term.slice(1);
        badge.className = `term-badge ${term}`;
        text.textContent = term.charAt(0).toUpperCase() + term.slice(1);
    }
    
    loadGradingConfig(term);
    
    const modal = new bootstrap.Modal(document.getElementById('gradingConfigModal'));
    modal.show();
}

// Load grading configuration
function loadGradingConfig(term) {
    const container = document.getElementById('configComponentsList');
    if (!container) return;
    
    container.innerHTML = '<div class="text-center py-3"><div class="spinner-border"></div></div>';
    
    fetch(`/teacher/components/${classId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderGradingConfig(data.components, term);
            } else {
                container.innerHTML = '<div class="alert alert-warning">No components configured</div>';
            }
        })
        .catch(error => {
            console.error('Error loading config:', error);
            container.innerHTML = '<div class="alert alert-danger">Failed to load configuration</div>';
        });
}

// Render grading configuration
function renderGradingConfig(components, term) {
    const container = document.getElementById('configComponentsList');
    if (!container) return;
    
    let html = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            Configuration for <strong>${term}</strong> term is managed through the component system.
            All components you add will be used for both midterm and final calculations.
        </div>
        <p class="text-muted">
            To customize components, use the "Settings & Components" tab or click "Add New Component" button.
        </p>
    `;
    
    container.innerHTML = html;
}

// Save grading configuration
function saveGradingConfig() {
    showNotification('Configuration saved successfully!', 'success');
    const modal = bootstrap.Modal.getInstance(document.getElementById('gradingConfigModal'));
    if (modal) modal.hide();
}

// Load components when settings tab is clicked
document.addEventListener('DOMContentLoaded', function() {
    const settingsTab = document.getElementById('settings-tab');
    if (settingsTab) {
        // Load components when tab is clicked
        settingsTab.addEventListener('click', function() {
            // Small delay to ensure tab is visible
            setTimeout(loadComponentsForSettings, 100);
        });
    }
    
    // Auto-activate settings tab if ?tab=settings is in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'settings' && settingsTab) {
        const tabInstance = new bootstrap.Tab(settingsTab);
        tabInstance.show();
        setTimeout(loadComponentsForSettings, 150);
    }
    
    // Also load on page load if Settings tab is active
    const settingsPane = document.getElementById('settings-pane');
    if (settingsPane && settingsPane.classList.contains('active')) {
        loadComponentsForSettings();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/grades/grade_content.blade.php ENDPATH**/ ?>