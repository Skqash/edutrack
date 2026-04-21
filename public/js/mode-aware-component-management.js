/**
 * Mode-Aware Component Management System
 * 
 * This script handles different behaviors for component management
 * based on the selected automation mode (Manual, Semi-Auto, Auto)
 */

// Global state
window.componentManagementMode = {
    currentMode: 'semi-auto', // Default
    classId: null,
    term: null,
    initialized: false
};

/**
 * Initialize mode-aware system
 */
function initializeModeAwareSystem(classId, term) {
    console.log('[Mode-Aware] Initializing for class:', classId, 'term:', term);
    
    window.componentManagementMode.classId = classId;
    window.componentManagementMode.term = term;
    
    // Get mode from hidden input (server-rendered)
    const modeInput = document.getElementById('currentComponentMode');
    if (modeInput && modeInput.value) {
        const mode = modeInput.value;
        window.componentManagementMode.currentMode = mode;
        console.log('[Mode-Aware] Mode loaded from page:', mode);
        
        // Apply mode restrictions immediately
        applyModeRestrictions(mode);
    } else {
        // Fallback: Fetch from server
        console.log('[Mode-Aware] Mode input not found, fetching from server...');
        fetchCurrentMode(classId, term);
    }
    
    // Set up event listeners
    setupModeAwareListeners();
    
    window.componentManagementMode.initialized = true;
}

/**
 * Fetch current automation mode from server
 */
function fetchCurrentMode(classId, term) {
    console.log('[Mode-Aware] Fetching mode for class:', classId, 'term:', term);
    console.log('[Mode-Aware] URL:', `/teacher/grade-settings/${classId}/${term}/settings`);
    
    fetch(`/teacher/grade-settings/${classId}/${term}/settings`)
        .then(response => {
            console.log('[Mode-Aware] Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('[Mode-Aware] Response data:', data);
            const mode = data.gradingScaleSettings?.component_weight_mode || 'semi-auto';
            window.componentManagementMode.currentMode = mode;
            console.log('[Mode-Aware] Current mode:', mode);
            
            // Update UI based on mode
            updateModeStatusAlert(mode);
            applyModeRestrictions(mode);
        })
        .catch(error => {
            console.error('[Mode-Aware] Error fetching mode:', error);
            console.error('[Mode-Aware] Error details:', error.message);
            // Default to semi-auto on error
            window.componentManagementMode.currentMode = 'semi-auto';
            updateModeStatusAlert('semi-auto');
            
            // Show error to user
            const indicator = document.getElementById('currentModeIndicator');
            if (indicator) {
                indicator.className = 'badge bg-danger fs-6 px-3 py-2';
                indicator.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Error Loading Mode';
                indicator.title = 'Using Semi-Auto as default. Error: ' + error.message;
            }
        });
}

/**
 * Update the mode status alert
 */
function updateModeStatusAlert(mode) {
    const alert = document.getElementById('modeStatusAlert');
    const title = document.getElementById('modeStatusTitle');
    const description = document.getElementById('modeStatusDescription');
    const indicator = document.getElementById('currentModeIndicator');
    
    if (!alert || !title || !description) return;
    
    // Remove existing classes
    alert.className = 'alert alert-dismissible fade show';
    
    let modeIcon = '';
    let modeName = '';
    let indicatorClass = '';
    
    switch(mode) {
        case 'manual':
            alert.classList.add('alert-primary', 'border-primary');
            modeIcon = '<i class="fas fa-hand-paper me-2"></i>';
            modeName = 'Manual Mode Active';
            title.innerHTML = modeIcon + modeName;
            description.innerHTML = 'You have <strong>full control</strong> over component weights. Set each weight manually and ensure they sum to 100% per category.';
            indicatorClass = 'bg-primary';
            if (indicator) {
                indicator.className = `badge ${indicatorClass} fs-6 px-3 py-2`;
                indicator.innerHTML = '🎯 Manual Mode';
            }
            break;
            
        case 'semi-auto':
            alert.classList.add('alert-success', 'border-success');
            modeIcon = '<i class="fas fa-magic me-2"></i>';
            modeName = 'Semi-Auto Mode Active (Recommended)';
            title.innerHTML = modeIcon + modeName;
            description.innerHTML = 'System suggests equal weights, but you can <strong>override any component</strong>. Other weights adjust proportionally to maintain 100%.';
            indicatorClass = 'bg-success';
            if (indicator) {
                indicator.className = `badge ${indicatorClass} fs-6 px-3 py-2`;
                indicator.innerHTML = '🔄 Semi-Auto Mode';
            }
            break;
            
        case 'auto':
            alert.classList.add('alert-warning', 'border-warning');
            modeIcon = '<i class="fas fa-robot me-2"></i>';
            modeName = 'Auto Mode Active';
            title.innerHTML = modeIcon + modeName;
            description.innerHTML = '<strong>Weights are automatically managed</strong> and distributed equally. You cannot manually adjust weights in this mode.';
            indicatorClass = 'bg-warning text-dark';
            if (indicator) {
                indicator.className = `badge ${indicatorClass} fs-6 px-3 py-2`;
                indicator.innerHTML = '🤖 Auto Mode';
            }
            break;
    }
}

/**
 * Apply mode-specific restrictions to the UI
 */
function applyModeRestrictions(mode) {
    const weightInput = document.getElementById('componentWeight');
    const weightLabel = document.querySelector('label[for="componentWeight"]');
    
    if (!weightInput) return;
    
    switch(mode) {
        case 'manual':
            // Enable weight input, require manual entry
            weightInput.disabled = false;
            weightInput.required = true;
            weightInput.placeholder = 'Enter weight %';
            if (weightLabel) {
                weightLabel.innerHTML = '<i class="fas fa-percentage"></i> Weight (%) <span class="text-danger">*Required</span>';
            }
            break;
            
        case 'semi-auto':
            // Enable weight input, but provide suggestion
            weightInput.disabled = false;
            weightInput.required = false;
            weightInput.placeholder = 'Auto-suggested (can override)';
            if (weightLabel) {
                weightLabel.innerHTML = '<i class="fas fa-percentage"></i> Weight (%) <span class="text-muted">(Optional - Auto-suggested)</span>';
            }
            break;
            
        case 'auto':
            // Disable weight input completely
            weightInput.disabled = true;
            weightInput.required = false;
            weightInput.value = '';
            weightInput.placeholder = 'Auto-calculated';
            if (weightLabel) {
                weightLabel.innerHTML = '<i class="fas fa-percentage"></i> Weight (%) <span class="badge bg-warning text-dark">Auto-Managed</span>';
            }
            break;
    }
}

/**
 * Setup mode-aware event listeners
 */
function setupModeAwareListeners() {
    // Listen for component manager modal opening
    const modal = document.getElementById('componentManagerModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            const mode = window.componentManagementMode.currentMode;
            console.log('[Mode-Aware] Modal opening, applying restrictions for mode:', mode);
            applyModeRestrictions(mode);
            showModeNotice(mode);
        });
    }
    
    // Listen for form submission
    const form = document.getElementById('addComponentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const mode = window.componentManagementMode.currentMode;
            if (!validateComponentSubmission(mode, e)) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Show mode-specific notice when opening component manager
 */
function showModeNotice(mode) {
    let message = '';
    let icon = '';
    let alertClass = '';
    
    switch(mode) {
        case 'manual':
            icon = '🎯';
            alertClass = 'alert-primary';
            message = '<strong>Manual Mode:</strong> You must set the weight for each component. Ensure all weights in each category sum to 100%.';
            break;
            
        case 'semi-auto':
            icon = '🔄';
            alertClass = 'alert-success';
            message = '<strong>Semi-Auto Mode:</strong> Leave weight empty for equal distribution, or enter a custom weight. Other components will adjust automatically.';
            break;
            
        case 'auto':
            icon = '🤖';
            alertClass = 'alert-warning';
            message = '<strong>Auto Mode:</strong> Weights are automatically calculated and distributed equally. You cannot set custom weights.';
            break;
    }
    
    // Check if notice already exists
    let notice = document.getElementById('modeNoticeInModal');
    if (!notice) {
        // Create notice element
        notice = document.createElement('div');
        notice.id = 'modeNoticeInModal';
        notice.className = `alert ${alertClass} alert-dismissible fade show`;
        notice.innerHTML = `
            <div class="d-flex align-items-center">
                <span style="font-size: 1.5rem; margin-right: 10px;">${icon}</span>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the modal body
        const modalBody = document.querySelector('#componentManagerModal .modal-body');
        if (modalBody) {
            modalBody.insertBefore(notice, modalBody.firstChild);
        }
    } else {
        // Update existing notice
        notice.className = `alert ${alertClass} alert-dismissible fade show`;
        notice.innerHTML = `
            <div class="d-flex align-items-center">
                <span style="font-size: 1.5rem; margin-right: 10px;">${icon}</span>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    }
}

/**
 * Validate component submission based on mode
 */
function validateComponentSubmission(mode, event) {
    const weightInput = document.getElementById('componentWeight');
    const weight = weightInput ? weightInput.value : '';
    
    switch(mode) {
        case 'manual':
            // Weight is required in manual mode
            if (!weight || weight === '' || parseFloat(weight) <= 0) {
                showValidationError('Manual Mode requires you to set a weight percentage for each component.');
                return false;
            }
            
            // Show confirmation
            if (!confirm(`Manual Mode: You are setting this component weight to ${weight}%.\n\nMake sure all weights in this category sum to 100%.`)) {
                return false;
            }
            break;
            
        case 'semi-auto':
            // Weight is optional, show different confirmations
            if (weight && weight !== '') {
                // User is overriding - this is ALLOWED in semi-auto
                if (!confirm(`Semi-Auto Mode: You are overriding the auto-suggested weight with ${weight}%.\n\nOther components will adjust proportionally to maintain 100%.\n\nContinue?`)) {
                    return false;
                }
            } else {
                // Using auto-suggestion
                if (!confirm('Semi-Auto Mode: Weight will be auto-suggested based on equal distribution.\n\nContinue?')) {
                    return false;
                }
            }
            break;
            
        case 'auto':
            // Weight should not be set in auto mode
            if (weight && weight !== '') {
                showValidationError('Auto Mode: Weights are automatically managed. You cannot set custom weights.');
                weightInput.value = '';
                return false;
            }
            
            // Show info confirmation
            if (!confirm('Auto Mode: This component will be added with auto-calculated equal weight.\n\nAll existing components will be recalculated.\n\nContinue?')) {
                return false;
            }
            break;
    }
    
    return true;
}

/**
 * Show validation error
 */
function showValidationError(message) {
    // Create or update error alert
    let errorAlert = document.getElementById('componentValidationError');
    if (!errorAlert) {
        errorAlert = document.createElement('div');
        errorAlert.id = 'componentValidationError';
        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="errorMessage"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const form = document.getElementById('addComponentForm');
        if (form) {
            form.insertBefore(errorAlert, form.firstChild);
        }
    }
    
    document.getElementById('errorMessage').textContent = message;
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (errorAlert) {
            errorAlert.remove();
        }
    }, 5000);
}

/**
 * Show mode-specific confirmation for component deletion
 */
function confirmComponentDeletion(componentName, mode) {
    let message = '';
    
    switch(mode) {
        case 'manual':
            message = `Manual Mode: Delete "${componentName}"?\n\nYou will need to manually adjust remaining component weights to sum to 100%.`;
            break;
            
        case 'semi-auto':
            message = `Semi-Auto Mode: Delete "${componentName}"?\n\nRemaining components will be recalculated proportionally to maintain 100%.`;
            break;
            
        case 'auto':
            message = `Auto Mode: Delete "${componentName}"?\n\nAll remaining components will be recalculated with equal weights.`;
            break;
    }
    
    return confirm(message);
}

/**
 * Show mode-specific confirmation for component editing
 */
function confirmComponentEdit(componentName, mode, newWeight) {
    let message = '';
    
    switch(mode) {
        case 'manual':
            message = `Manual Mode: Update "${componentName}" weight to ${newWeight}%?\n\nEnsure all weights in this category sum to 100%.`;
            break;
            
        case 'semi-auto':
            message = `Semi-Auto Mode: Update "${componentName}" weight to ${newWeight}%?\n\nOther components will adjust proportionally.`;
            break;
            
        case 'auto':
            message = `Auto Mode: Weights are automatically managed.\n\nYou cannot manually edit component weights in Auto Mode.`;
            return false; // Don't allow editing in auto mode
    }
    
    return confirm(message);
}

// Export functions for global use
window.initializeModeAwareSystem = initializeModeAwareSystem;
window.confirmComponentDeletion = confirmComponentDeletion;
window.confirmComponentEdit = confirmComponentEdit;
window.fetchCurrentMode = fetchCurrentMode;
