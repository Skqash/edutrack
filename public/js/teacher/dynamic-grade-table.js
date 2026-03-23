/**
 * Dynamic Grade Entry Table Generator
 * Creates responsive, flexible grade entry tables based on KSA components
 * COMPLETELY RESPONSIVE - adjusts dynamically when components change
 */

const DynamicGradeTable = {
    classId: null,
    components: [],
    students: [],
    entries: {},
    ksaSettings: {},

    /**
     * Initialize dynamic table generator
     */
    init: function(classId, ksaSettings = {}) {
        this.classId = classId;
        this.ksaSettings = ksaSettings || {
            knowledge_percentage: 40,
            skills_percentage: 50,
            attitude_percentage: 10
        };
        console.log('🎯 DynamicGradeTable initialized for class:', classId);
        console.log('⚖️ KSA Weights:', this.ksaSettings);

        this.loadComponentsAndStudents();
    },

    /**
     * Load components and students data
     */
    loadComponentsAndStudents: async function() {
        try {
            console.log('📥 Loading components and students...');

            // Load components
            const compResponse = await fetch(`/teacher/components/${this.classId}`);
            if (!compResponse.ok) throw new Error('Failed to load components');
            const compData = await compResponse.json();

            // Flatten components if grouped
            if (compData.components) {
                if (Array.isArray(compData.components)) {
                    this.components = compData.components;
                } else {
                    this.components = [];
                    Object.values(compData.components).forEach(cats => {
                        this.components = this.components.concat(cats);
                    });
                }
            }

            console.log(`✅ Loaded ${this.components.length} components`);
            console.log('Components:', this.components);

            // Load students
            const studResponse = await fetch(`/teacher/classes/${this.classId}/students`);
            if (!studResponse.ok) throw new Error('Failed to load students');
            const studData = await studResponse.json();
            this.students = studData.data || studData.students || [];

            console.log(`✅ Loaded ${this.students.length} students`);

            // Load existing grades if any
            this.loadExistingGrades();

            // Generate the table
            this.generateDynamicTable();

        } catch (error) {
            console.error('❌ Error loading data:', error);
            alert(`Error loading data: ${error.message}`);
        }
    },

    /**
     * Load existing grades from database
     */
    loadExistingGrades: async function() {
        try {
            const response = await fetch(`/teacher/grades/dynamic/${this.classId}/entries`);
            if (!response.ok) return; // No entries yet is OK

            const data = await response.json();
            this.entries = data.entries || {};
            console.log('📊 Loaded existing grades:', this.entries);
        } catch (error) {
            console.warn('⚠️ Could not load existing grades:', error);
        }
    },

    /**
     * Generate the complete dynamic grade table
     */
    generateDynamicTable: function() {
        console.log('🎨 Generating dynamic grade table...');

        if (this.components.length === 0) {
            console.warn('⚠️ No components available to display');
            return this.showEmptyState();
        }

        // Group components by category
        const grouped = {};
        this.components.forEach(comp => {
            if (!grouped[comp.category]) {
                grouped[comp.category] = [];
            }
            grouped[comp.category].push(comp);
        });

        // Generate HTML
        const tableHTML = this.buildTableHTML(grouped);
        const container = document.getElementById('dynamicGradeTable');

        if (container) {
            container.innerHTML = tableHTML;
            this.attachEventListeners();
            console.log('✅ Dynamic table generated successfully');
        } else {
            console.error('❌ Container #dynamicGradeTable not found');
        }
    },

    /**
     * Build the complete table HTML
     */
    buildTableHTML: function(grouped) {
        let html = '<div class="table-responsive" style="margin-top: 2rem;">';
        html += '<table class="table table-bordered table-sm text-center">';

        // Build thead
        html += this.buildTableHeader(grouped);

        // Build tbody
        html += this.buildTableBody(grouped);

        html += '</table></div>';
        return html;
    },

    /**
     * Build table header dynamically
     */
    buildTableHeader: function(grouped) {
        let html = '<thead class="table-light">';

        // First header row - category headers
        html += '<tr>';
        html += '<th colspan="2" class="bg-secondary text-white">Student Info</th>';

        Object.entries(grouped).forEach(([category, comps]) => {
            const color = this.getCategoryColor(category);
            html += `<th colspan="${comps.length + 1}" style="background-color: ${color}; color: white; font-weight: 600;">
                ${category} (${comps.length} items)
            </th>`;
        });

        html += '<th colspan="2" class="bg-warning text-dark fw-bold">Final</th>';
        html += '</tr>';

        // Second header row - component names
        html += '<tr>';
        html += '<th style="width: 80px;">ID</th>';
        html += '<th style="width: 200px;">Name</th>';

        Object.entries(grouped).forEach(([category, comps]) => {
            comps.forEach(comp => {
                const bgColor = this.getCategoryLightColor(category);
                html += `<th style="background-color: ${bgColor}; font-size: 0.85rem; padding: 0.5rem; max-width: 100px; word-break: break-word;">
                    <small title="${comp.name}"><strong>${comp.name.substring(0, 15)}</strong></small>
                </th>`;
            });

            // Add average column for this category
            const bgColor = this.getCategoryLightColor(category);
            html += `<th style="background-color: ${bgColor}; font-weight: bold; font-size: 0.85rem;">AVE</th>`;
        });

        html += '<th style="background-color: #FFF9E6; font-weight: bold;">Grade</th>';
        html += '<th style="background-color: #FFF9E6; font-weight: bold;">Grade (5.0)</th>';
        html += '</tr></thead>';

        return html;
    },

    /**
     * Build table body with student rows
     */
    buildTableBody: function(grouped) {
        let html = '<tbody>';

        // Max scores row
        html += '<tr class="table-warning fw-bold">';
        html += '<td colspan="2" class="text-center bg-warning bg-opacity-50">Max Scores</td>';

        Object.entries(grouped).forEach(([category, comps]) => {
            comps.forEach(comp => {
                html += `<td style="background-color: #fff3cd; font-weight: bold;">${comp.max_score}</td>`;
            });
            html += `<td style="background-color: #fff3cd; font-weight: bold;">100</td>`;
        });

        html += '<td style="background-color: #fff3cd; font-weight: bold;">100.00</td>';
        html += '<td style="background-color: #fff3cd; font-weight: bold;">5.00</td>';
        html += '</tr>';

        // Student rows
        this.students.forEach(student => {
            html += this.buildStudentRow(student, grouped);
        });

        html += '</tbody>';
        return html;
    },

    /**
     * Build a single student row
     */
    buildStudentRow: function(student, grouped) {
        const studentId = student.id;
        let html = `<tr data-student-id="${studentId}" class="student-row">`;

        // Student info
        html += `<td class="fw-semibold text-muted">${student.student_id}</td>`;
        html += `<td class="fw-semibold text-start">${student.user?.name || student.name}</td>`;

        // Component inputs and calculation
        let knowledgeScore = 0, skillsScore = 0, attitudeScore = 0;
        let knowledgeTotal = 0, skillsTotal = 0, attitudeTotal = 0;

        Object.entries(grouped).forEach(([category, comps]) => {
            let categoryTotal = 0;
            let categoryMaxTotal = 0;

            comps.forEach(comp => {
                const existingEntry = this.entries[studentId]?.[comp.id] || {};
                const score = existingEntry.raw_score || 0;

                html += `<td>
                    <input type="number" 
                        class="form-control form-control-sm score-input" 
                        data-student-id="${studentId}"
                        data-component-id="${comp.id}"
                        data-max="${comp.max_score}"
                        data-category="${category}"
                        min="0" max="${comp.max_score}"
                        value="${score || ''}"
                        placeholder="0">
                </td>`;

                categoryTotal += score;
                categoryMaxTotal += comp.max_score;
            });

            // Category average
            const categoryPercent = categoryMaxTotal > 0 ? (categoryTotal / categoryMaxTotal) * 100 : 0;
            html += `<td class="category-average fw-bold" data-category="${category}" style="background-color: ${this.getCategoryLightColor(category)};">
                ${categoryPercent > 0 ? categoryPercent.toFixed(2) : '-'}
            </td>`;

            // Store for final calculation
            if (category === 'Knowledge') {
                knowledgeScore = categoryPercent;
                knowledgeTotal = categoryMaxTotal;
            } else if (category === 'Skills') {
                skillsScore = categoryPercent;
                skillsTotal = categoryMaxTotal;
            } else if (category === 'Attitude') {
                attitudeScore = categoryPercent;
                attitudeTotal = categoryMaxTotal;
            }
        });

        // Final grade calculation
        const finalGrade = (
            (knowledgeScore * this.ksaSettings.knowledge_percentage) +
            (skillsScore * this.ksaSettings.skills_percentage) +
            (attitudeScore * this.ksaSettings.attitude_percentage)
        ) / 100;

        const decimalGrade = this.convertToDecimalGrade(finalGrade);

        html += `<td class="final-grade fw-bold" style="background-color: #FFF9E6; color: #F57F17;">
            ${finalGrade > 0 ? finalGrade.toFixed(2) : '-'}
        </td>`;
        html += `<td class="decimal-grade fw-bold" style="background-color: #FFF9E6; color: #F57F17;">
            ${decimalGrade > 0 ? decimalGrade.toFixed(2) : '-'}
        </td>`;

        html += '</tr>';
        return html;
    },

    /**
     * Attach event listeners to score inputs
     */
    attachEventListeners: function() {
        document.querySelectorAll('.score-input').forEach(input => {
            input.addEventListener('input', (e) => {
                this.validateInput(e.target);
                this.calculateRowGrades(e.target.closest('tr'));
                this.autoSaveScore(e.target);
            });

            input.addEventListener('blur', (e) => {
                this.autoSaveScore(e.target);
            });

            input.addEventListener('focus', (e) => {
                e.target.select();
            });
        });

        console.log('✅ Event listeners attached to all score inputs');
    },

    /**
     * Validate score input
     */
    validateInput: function(input) {
        const max = parseFloat(input.getAttribute('data-max')) || 100;
        let value = parseFloat(input.value) || 0;

        if (value < 0) value = 0;
        if (value > max) value = max;

        input.value = value > 0 ? value : '';

        if (input.value && value > max) {
            input.classList.add('is-invalid');
            input.style.backgroundColor = '#ffe5e5';
        } else {
            input.classList.remove('is-invalid');
            input.style.backgroundColor = '';
        }
    },

    /**
     * Calculate grades for a student row
     */
    calculateRowGrades: function(row) {
        if (!row || !row.classList.contains('student-row')) return;

        const grouped = {};
        document.querySelectorAll('.score-input[data-student-id]').forEach(input => {
            const category = input.getAttribute('data-category');
            if (!grouped[category]) grouped[category] = [];
            grouped[category].push(input);
        });

        // Calculate category averages
        let kScore = 0, sScore = 0, aScore = 0;

        Object.entries(grouped).forEach(([category, inputs]) => {
            let total = 0, maxTotal = 0;

            inputs.forEach(inp => {
                if (parseInt(inp.getAttribute('data-student-id')) === parseInt(row.getAttribute('data-student-id'))) {
                    const value = parseFloat(inp.value) || 0;
                    const max = parseFloat(inp.getAttribute('data-max')) || 100;
                    total += value;
                    maxTotal += max;
                }
            });

            const percent = maxTotal > 0 ? (total / maxTotal) * 100 : 0;
            const avgCell = row.querySelector(`.category-average[data-category="${category}"]`);
            if (avgCell) {
                avgCell.textContent = percent > 0 ? percent.toFixed(2) : '-';
            }

            if (category === 'Knowledge') kScore = percent;
            else if (category === 'Skills') sScore = percent;
            else if (category === 'Attitude') aScore = percent;
        });

        // Calculate final grade
        const finalGrade = (
            (kScore * this.ksaSettings.knowledge_percentage) +
            (sScore * this.ksaSettings.skills_percentage) +
            (aScore * this.ksaSettings.attitude_percentage)
        ) / 100;

        const decimalGrade = this.convertToDecimalGrade(finalGrade);

        const finalCell = row.querySelector('.final-grade');
        if (finalCell) {
            finalCell.textContent = finalGrade > 0 ? finalGrade.toFixed(2) : '-';
        }

        const decimalCell = row.querySelector('.decimal-grade');
        if (decimalCell) {
            decimalCell.textContent = decimalGrade > 0 ? decimalGrade.toFixed(2) : '-';
        }
    },

    /**
     * Convert percentage to 4-point or 5-point decimal grade
     */
    convertToDecimalGrade: function(percentage) {
        if (percentage >= 98) return 1.0;
        if (percentage >= 95) return 1.25;
        if (percentage >= 92) return 1.50;
        if (percentage >= 89) return 1.75;
        if (percentage >= 86) return 2.00;
        if (percentage >= 83) return 2.25;
        if (percentage >= 80) return 2.50;
        if (percentage >= 77) return 2.75;
        if (percentage >= 74) return 3.00;
        if (percentage >= 71) return 3.25;
        if (percentage >= 70) return 3.50;
        return 5.0;
    },

    /**
     * Auto-save score to server
     */
    autoSaveScore: async function(input) {
        const studentId = input.getAttribute('data-student-id');
        const componentId = input.getAttribute('data-component-id');
        const value = parseFloat(input.value) || null;

        if (!studentId || !componentId || value === null) return;

        try {
            await fetch(`/teacher/grades/dynamic/${this.classId}/entries`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    term: 'midterm',
                    entries: {
                        [studentId]: {
                            [componentId]: value
                        }
                    }
                })
            });
        } catch (error) {
            console.warn('⚠️ Could not save score:', error);
        }
    },

    /**
     * Get category color
     */
    getCategoryColor: function(category) {
        const colors = {
            'Knowledge': '#2196F3',
            'Skills': '#4CAF50',
            'Attitude': '#9C27B0'
        };
        return colors[category] || '#666';
    },

    /**
     * Get category light color
     */
    getCategoryLightColor: function(category) {
        const colors = {
            'Knowledge': '#E3F2FD',
            'Skills': '#E8F5E9',
            'Attitude': '#F3E5F5'
        };
        return colors[category] || '#f5f5f5';
    },

    /**
     * Show empty state when no components
     */
    showEmptyState: function() {
        const container = document.getElementById('dynamicGradeTable');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle"></i> <strong>No components</strong> to display.
                    Click "Manage Components" to add assessment components for this class.
                </div>
            `;
        }
    },

    /**
     * Refresh table when components change
     */
    refresh: function() {
        console.log('🔄 Refreshing dynamic grade table...');
        this.loadComponentsAndStudents();
    }
};

// Auto-initialize if on grade entry page
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Will be initialized via inline script in the view
    });
} else {
    // DOM already loaded
}
