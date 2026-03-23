// Complete Grade System JavaScript - Working Version
// Place this at the bottom of grade_content.blade.php

<script>
// ==================== GLOBAL VARIABLES ====================
let componentsData = {};
let componentsCache = null;
let componentsCacheTime = null;
const CACHE_DURATION = 30000; // 30 seconds
const classId = {{ $class->id }};
const term = '{{ $term }}';

// ==================== UTILITY FUNCTIONS ====================

function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    const iconClass = type === 'success' ? 'check-circle' : 
                     type === 'warning' ? 'exclamation-triangle' : 
                     type === 'error' ? 'exclamation-circle' : 'info-circle';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} position-fixed top-0 end-0 m-3 alert-dismissible fade show`;
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <i class="fas fa-${iconClass} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function exportGrades() {
    window.location.href = `/teacher/grades/export/${classId}?term=${term}`;
}

// ==================== COMPONENT LOADING ====================

function loadComponentsForSettings() {
    console.log('[loadComponentsForSettings] Starting...');
    const container = document.getElementById('componentsListSettings');
    if (!container) {
        console.error('[loadComponentsForSettings] Container not found');
        return;
    }
    
    // Check cache
    const now = Date.now();
    if (componentsCache && componentsCacheTime && (now - componentsCacheTime < CACHE_DURATION)) {
        console.log('[loadComponentsForSettings] Using cache');
        renderComponentsForSettings(componentsCache);
        return;
    }
    
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading components...</p>
        </div>
    `;
    
    const url = `/teacher/components/${classId}`;
    console.log('[loadComponentsForSettings] Fetching from:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('[loadComponentsForSettings] Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('[loadComponentsForSettings] Data received:', data);
        if (data.success) {
            componentsCache = data.components;
            componentsCacheTime = Date.now();
            renderComponentsForSettings(data.components);
        } else {
            throw new Error(data.message || 'Failed to load components');
        }
    })
    .catch(error => {
        console.error('[loadComponentsForSettings] Error:', error);
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Error loading components</strong><br>
                <small>${error.message}</small><br>
                <button class="btn btn-sm btn-primary mt-2" onclick="loadComponentsForSettings()">
                    <i class="fas fa-sync me-1"></i>Retry
                </button>
            </div>
        `;
        showNotification('Failed to load components: ' + error.message, 'error');
    });
}

function renderComponentsForSettings(components) {
    console.log('[renderComponentsForSettings] Rendering:', components);
    const container = document.getElementById('componentsListSettings');
    if (!container) return;
    
    const categories = [
        { key: 'knowledge', name: 'Knowledge', icon: 'brain', color: '#2196F3' },
        { key: 'skills', name: 'Skills', icon: 'tools', color: '#4CAF50' },
        { key: 'attitude', name: 'Attitude', icon: 'heart', color: '#FF9800' }
    ];
    
    let html = '';
    
    categories.forEach(cat => {
        const categoryComps = components[cat.key] || [];
        
        html += `
            <div class="component-category-card ${cat.key} mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fas fa-${cat.icon} me-2" style="color: ${cat.color};"></i>
                        ${cat.name} Components
                        <span class="badge bg-secondary ms-2">${categoryComps.length}</span>
                    </h6>
                    <button class="btn btn-sm btn-primary" onclick="openAddComponentModal('${cat.key}')">
                        <i class="fas fa-plus me-1"></i>Add
                    </button>
                </div>
        `;
        
        if (categoryComps.length > 0) {
            categoryComps.forEach(comp => {
                html += `
                    <div class="component-list-item mb-2">
                        <div class="flex-grow-1">
                            <strong>${comp.name}</strong>
                            <small class="text-muted d-block">
                                ${comp.subcategory} | Weight: ${comp.weight}% | Max: ${comp.max_score}
                                ${comp.passing_score ? ` | Pass: ${comp.passing_score}` : ''}
                            </small>
                        </div>
                        <div class="d-flex gap-2">
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

// ==================== COMPONENT CRUD ====================

function openAddComponentModal(category = '') {
    const modal = new bootstrap.Modal(document.getElementById('componentManagerModal'));
    modal.show();
    
    // Pre-select category if provided
    if (category) {
        setTimeout(() => {
            const categorySelect = document.getElementById('componentCategory');
            const categoryMap = {
                'knowledge': 'Knowledge',
                'skills': 'Skills',
                'attitude': 'Attitude'
            };
            if (categorySelect) {
                categorySelect.value = categoryMap[category] || '';
                categorySelect.dispatchEvent(new Event('change'));
            }
        }, 100);
    }
}

function deleteComponent(componentId, componentName) {
    if (!confirm(`Are you sure you want to delete "${componentName}"?`)) {
        return;
    }
    
    const url = `/teacher/components/${classId}/${componentId}`;
    console.log('[deleteComponent] Deleting:', url);
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Clear cache and reload
            componentsCache = null;
            setTimeout(() => location.reload(), 500);
        } else {
            throw new Error(data.message || 'Failed to delete component');
        }
    })
    .catch(error => {
        console.error('[deleteComponent] Error:', error);
        showNotification('Error deleting component: ' + error.message, 'error');
    });
}

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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ template: 'knowledge', category: 'Knowledge' })
        }),
        fetch(`/teacher/components/${classId}/apply-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ template: 'skills', category: 'Skills' })
        }),
        fetch(`/teacher/components/${classId}/apply-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ template: 'attitude', category: 'Attitude' })
        })
    ])
    .then(() => {
        showNotification('Default templates applied successfully!', 'success');
        setTimeout(() => location.reload(), 1000);
    })
    .catch(error => {
        console.error('[applyDefaultTemplate] Error:', error);
        showNotification('Error applying templates', 'error');
    });
}

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', function() {
    console.log('[DOMContentLoaded] Initializing grade system...');
    console.log('[DOMContentLoaded] Class ID:', classId);
    console.log('[DOMContentLoaded] Term:', term);
    
    // Settings tab click handler
    const settingsTab = document.getElementById('settings-tab');
    if (settingsTab) {
        settingsTab.addEventListener('click', function() {
            console.log('[settingsTab] Clicked');
            setTimeout(loadComponentsForSettings, 100);
        });
    }
    
    // Check if settings pane is already active
    const settingsPane = document.getElementById('settings-pane');
    if (settingsPane && settingsPane.classList.contains('active')) {
        console.log('[DOMContentLoaded] Settings pane is active, loading components');
        loadComponentsForSettings();
    }
    
    console.log('[DOMContentLoaded] Initialization complete');
});
</script>
