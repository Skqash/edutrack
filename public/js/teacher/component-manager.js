/**
 * Component Manager Module - FIXED & ENHANCED
 * Professional component management interface for KSA grading system
 * NOW FULLY CONNECTED TO BACKEND WITH PROPER ERROR HANDLING
 */

const ComponentManager = {
    classId: null,
    baseUrl: '/teacher/components',
    currentComponents: [],
    editingComponentId: null, // Track if we're editing
    subcategoryOptions: {
        Knowledge: ['Quiz', 'Exam', 'Test', 'Pre-test'],
        Skills: ['Output', 'Project', 'Assignment', 'Activity', 'Participation', 'Presentation'],
        Attitude: ['Behavior', 'Attendance', 'Awareness', 'Collaboration', 'Punctuality']
    },

    /**
     * Initialize ComponentManager with class ID
     */
    init: function (classId) {
        this.classId = classId;
        console.log('🔧 Initializing ComponentManager for class:', classId);

        this.setupEventListeners();
        this.loadComponents(); // Load immediately on init

        console.log('✅ Component Manager initialized successfully');
    },

    /**
     * Setup event listeners for component manager
     */
    setupEventListeners: function () {
        console.log('📌 Setting up event listeners...');

        // Add Component Form Submit
        const addForm = document.getElementById('addComponentForm');
        if (addForm) {
            addForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddComponent();
            });
            console.log('✓ Add form listener attached');
        } else {
            console.warn('⚠️ Add form not found');
        }

        // Category change - update subcategories
        const categorySelect = document.getElementById('componentCategory');
        if (categorySelect) {
            categorySelect.addEventListener('change', (e) => this.updateSubcategoryOptions(e.target.value));
            console.log('✓ Category select listener attached');
        }

        // Manage tab listener
        const manageTab = document.getElementById('manageComponentTab');
        if (manageTab) {
            manageTab.addEventListener('click', () => {
                console.log('📂 Manage tab clicked - loading components');
                this.loadComponents();
            });
            console.log('✓ Manage tab listener attached');
        }

        // Modal reset on close
        const modal = document.getElementById('componentManagerModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                console.log('🔄 Modal closed - resetting form');
                this.resetForm();
            });
            console.log('✓ Modal close listener attached');
        }

        // Template buttons
        const templateBtns = document.querySelectorAll('.apply-template-btn');
        templateBtns.forEach(btn => {
            btn.addEventListener('click', (e) => this.applyTemplate(e));
        });
        console.log(`✓ ${templateBtns.length} template buttons attached`);
    },

    /**
     * Update subcategory options based on selected category
     */
    updateSubcategoryOptions: function (category) {
        const subcategorySelect = document.getElementById('componentSubcategory');
        if (!subcategorySelect) return;

        const options = this.subcategoryOptions[category] || [];
        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

        options.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option;
            opt.textContent = option;
            subcategorySelect.appendChild(opt);
        });

        console.log('Updated subcategories for', category, ':', options);
    },

    /**
     * Load all components from server
     */
    loadComponents: async function () {
        try {
            console.log(`🔄 Loading components for class: ${this.classId}`);

            const response = await fetch(`${this.baseUrl}/${this.classId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                }
            });

            console.log(`Response status: ${response.status}`);

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
            }

            const data = await response.json();
            console.log('📦 Raw response data:', data);

            // Handle both flat array and grouped object responses
            if (data.components) {
                if (Array.isArray(data.components)) {
                    this.currentComponents = data.components;
                } else {
                    // It's a grouped object, flatten it
                    this.currentComponents = [];
                    Object.values(data.components).forEach(categoryComps => {
                        this.currentComponents = this.currentComponents.concat(categoryComps);
                    });
                }
            } else if (data.data && Array.isArray(data.data)) {
                this.currentComponents = data.data;
            } else if (Array.isArray(data)) {
                this.currentComponents = data;
            } else {
                console.warn('Unexpected data format:', data);
                this.currentComponents = [];
            }

            console.log(`✅ Loaded ${this.currentComponents.length} components`);
            this.renderComponentsList();

            // Also trigger grade table update if available
            if (typeof updateGradeTable === 'function') {
                console.log('🔄 Updating grade table with new components...');
                updateGradeTable();
            }

        } catch (error) {
            console.error('❌ Error loading components:', error);
            this.showToast(`Failed to load components: ${error.message}`, 'danger');
        }
    },

    /**
     * Render components list in the Manage tab
     */
    renderComponentsList: function () {
        const container = document.getElementById('componentsListContainer');
        if (!container) return;

        if (!this.currentComponents || this.currentComponents.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No components yet. Add one to get started!</div>';
            return;
        }

        // Group by category
        const grouped = {};
        this.currentComponents.forEach(comp => {
            if (!grouped[comp.category]) grouped[comp.category] = [];
            grouped[comp.category].push(comp);
        });

        let html = '';
        Object.entries(grouped).forEach(([category, comps]) => {
            html += `<div class="mb-4"><h6 class="text-uppercase fw-bold">${category}</h6>`;
            comps.forEach(comp => {
                html += this.renderComponentItem(comp);
            });
            html += '</div>';
        });

        container.innerHTML = html;

        // Attach action listeners
        document.querySelectorAll('.edit-component-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.editComponent(e.target.closest('[data-comp-id]').getAttribute('data-comp-id')));
        });

        document.querySelectorAll('.delete-component-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.deleteComponent(e.target.closest('[data-comp-id]').getAttribute('data-comp-id')));
        });

        document.querySelectorAll('.duplicate-component-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.duplicateComponent(e.target.closest('[data-comp-id]').getAttribute('data-comp-id')));
        });
    },

    /**
     * Render individual component item HTML
     */
    renderComponentItem: function (comp) {
        const categoryColor = {
            Knowledge: '#2196F3',
            Skills: '#4CAF50',
            Attitude: '#9C27B0'
        }[comp.category] || '#666';

        return `
            <div class="card mb-2 shadow-sm" data-comp-id="${comp.id}" style="border-left: 4px solid ${categoryColor};">
                <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${comp.name}</strong>
                        <div class="small text-muted">
                            ${comp.subcategory} • Max: ${comp.max_score}pts • Weight: ${comp.weight}%
                        </div>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary edit-component-btn" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success duplicate-component-btn" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger delete-component-btn" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Handle add component form submission
     */
    handleAddComponent: async function () {
        console.log('➕ Adding new component...');

        const formData = {
            name: document.getElementById('componentName')?.value?.trim(),
            category: document.getElementById('componentCategory')?.value,
            subcategory: document.getElementById('componentSubcategory')?.value,
            max_score: parseInt(document.getElementById('componentMaxScore')?.value) || 100,
            weight: parseFloat(document.getElementById('componentWeight')?.value) || 10,
            auto_redistribute: true, // Enable automatic redistribution
        };

        console.log('📝 Form data:', formData);

        // Validation
        if (!formData.name || formData.name.length < 3) {
            this.showToast('⚠️ Component name must be at least 3 characters', 'warning');
            return;
        }

        if (!formData.category) {
            this.showToast('⚠️ Please select a category', 'warning');
            return;
        }

        if (!formData.subcategory) {
            this.showToast('⚠️ Please select a subcategory', 'warning');
            return;
        }

        if (formData.max_score < 1 || formData.max_score > 500) {
            this.showToast('⚠️ Max score must be between 1 and 500', 'warning');
            return;
        }

        // Check if weight would exceed 100% for the category
        if (!this.editingComponentId) {
            const categoryComponents = this.currentComponents.filter(c => c.category === formData.category);
            const currentTotalWeight = categoryComponents.reduce((sum, c) => sum + parseFloat(c.weight), 0);
            const newTotalWeight = currentTotalWeight + formData.weight;

            if (newTotalWeight > 100) {
                this.showToast(`❌ Cannot add component. Total weight would exceed 100% (Current: ${currentTotalWeight}%, Adding: ${formData.weight}%, Total: ${newTotalWeight}%). The system will automatically redistribute weights.`, 'warning');
                // Allow the addition but let the backend handle redistribution
            }
        } else {
            // For updates, validate against other components
            const categoryComponents = this.currentComponents.filter(c => c.category === formData.category && c.id != this.editingComponentId);
            const otherTotalWeight = categoryComponents.reduce((sum, c) => sum + parseFloat(c.weight), 0);
            const newTotalWeight = otherTotalWeight + formData.weight;

            if (newTotalWeight > 100) {
                this.showToast(`❌ Cannot update weight. Total would exceed 100% (Other components: ${otherTotalWeight}%, New weight: ${formData.weight}%, Total: ${newTotalWeight}%). Please adjust other weights first.`, 'error');
                return;
            }
        }

        try {
            let url, method;
            if (this.editingComponentId) {
                // Update existing component
                url = `${this.baseUrl}/${this.classId}/${this.editingComponentId}`;
                method = 'PUT';
            } else {
                // Add new component
                url = `${this.baseUrl}/${this.classId}`;
                method = 'POST';
            }

            console.log(`🚀 ${method} to: ${url}`);

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify(formData)
            });

            console.log(`Response status: ${response.status}`);
            const data = await response.json();
            console.log('Response data:', data);

            if (response.ok) {
                const action = this.editingComponentId ? 'updated' : 'added';
                this.showToast(`✅ Component "${formData.name}" ${action} successfully! ${!this.editingComponentId ? 'Weights redistributed automatically.' : ''}`, 'success');
                this.resetForm();
                this.loadComponents(); // Refresh the list

                // Trigger grade table update if in grade entry context
                if (typeof updateGradeTable === 'function') {
                    updateGradeTable();
                }
            } else {
                this.showToast(data.message || `Error ${this.editingComponentId ? 'updating' : 'adding'} component`, 'danger');
                console.error('Server errors:', data.errors || data);
            }
        } catch (error) {
            console.error('❌ Error adding component:', error);
            this.showToast(`Network error: ${error.message}`, 'danger');
        }
    },

    /**
     * Edit component
     */
    editComponent: function (componentId) {
        const comp = this.findComponent(componentId);
        if (!comp) {
            this.showToast('Component not found', 'danger');
            return;
        }

        // Set editing flag
        this.editingComponentId = componentId;

        // Populate form with component data
        document.getElementById('componentName').value = comp.name;
        document.getElementById('componentCategory').value = comp.category;
        this.updateSubcategoryOptions(comp.category);
        document.getElementById('componentSubcategory').value = comp.subcategory;
        document.getElementById('componentMaxScore').value = comp.max_score;
        document.getElementById('componentWeight').value = comp.weight;
        document.getElementById('componentPassingScore').value = comp.passing_score || 75;

        // Update form button text
        const submitBtn = document.querySelector('#addComponentForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Component';
        }

        // Switch to Add tab
        const addTab = document.getElementById('addComponentTab');
        if (addTab) {
            addTab.click();
        }

        this.showToast(`Editing "${comp.name}" - weights will be validated automatically`, 'info');
    },

    /**
     * Delete component with confirmation
     */
    deleteComponent: async function (componentId) {
        const comp = this.findComponent(componentId);
        if (!comp) {
            this.showToast('Component not found', 'danger');
            return;
        }

        if (!confirm(`Delete component "${comp.name}"? This will delete all associated scores and automatically redistribute weights among remaining components.`)) {
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/${this.classId}/${componentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.showToast(`✅ Component "${comp.name}" deleted! Weights redistributed automatically.`, 'success');
                this.loadComponents();

                // Trigger grade table update if in grade entry context
                if (typeof updateGradeTable === 'function') {
                    updateGradeTable();
                }
            } else {
                const data = await response.json();
                this.showToast(data.message || 'Error deleting component', 'danger');
            }
        } catch (error) {
            console.error('❌ Error deleting component:', error);
            this.showToast('Network error while deleting component', 'danger');
        }
    },

    /**
     * Duplicate component
     */
    duplicateComponent: async function (componentId) {
        const comp = this.findComponent(componentId);
        if (!comp) {
            this.showToast('Component not found', 'danger');
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/${this.classId}/${componentId}/duplicate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (response.ok) {
                this.showToast(`✅ Component duplicated!`, 'success');
                this.loadComponents();
            } else {
                this.showToast(data.message || 'Error duplicating component', 'danger');
            }
        } catch (error) {
            console.error('❌ Error duplicating component:', error);
            this.showToast('Network error while duplicating component', 'danger');
        }
    },

    /**
     * Apply pre-built template
     */
    applyTemplate: async function (e) {
        const templateName = e.target.closest('[data-template]')?.getAttribute('data-template');
        if (!templateName) return;

        if (!confirm(`Apply ${templateName} template? This will create multiple components.`)) {
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/${this.classId}/apply-template`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({ template: templateName })
            });

            const data = await response.json();

            if (response.ok) {
                this.showToast(`✅ ${templateName} template applied!`, 'success');
                this.loadComponents();
            } else {
                this.showToast(data.message || 'Error applying template', 'danger');
            }
        } catch (error) {
            console.error('❌ Error applying template:', error);
            this.showToast('Network error while applying template', 'danger');
        }
    },

    /**
     * Find component by ID
     */
    findComponent: function (componentId) {
        return this.currentComponents.find(c => c.id == componentId);
    },

    /**
     * Reset form to initial state
     */
    resetForm: function () {
        const form = document.getElementById('addComponentForm');
        if (form) form.reset();

        document.getElementById('componentCategory').value = '';
        document.getElementById('componentSubcategory').innerHTML = '<option value="">Select Subcategory</option>';

        // Reset editing state
        this.editingComponentId = null;

        // Reset button text
        const submitBtn = document.querySelector('#addComponentForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Add Component';
        }
    },

    /**
     * Show toast notification
     */
    showToast: function (message, type = 'info') {
        const toastDiv = document.createElement('div');
        toastDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toastDiv.setAttribute('role', 'alert');
        toastDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        toastDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(toastDiv);

        // Auto-dismiss after 4 seconds
        setTimeout(() => {
            toastDiv.remove();
        }, 4000);
    },

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken: function () {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }
};

// Auto-initialize if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
        // Will be initialized via inline script in the view
    });
}
