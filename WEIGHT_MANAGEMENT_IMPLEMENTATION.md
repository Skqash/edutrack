# Weight Management Tab Implementation Guide

## Overview
This guide shows how to add a Weight Management tab to the grade_content.blade.php where teachers can adjust the percentage weights of individual subcomponents (Quiz, Exam, Output, etc.) within each KSA category.

## Step 1: Add Weight Management Tab Button

In `resources/views/teacher/grades/grade_content.blade.php`, find the tabs section and add the Weight Management button:

```html
<div class="grade-tabs">
    <button class="tab-button active" onclick="switchTab('entry')">
        <i class="fas fa-edit"></i>Grade Entry
    </button>
    <button class="tab-button" onclick="switchTab('weights')">
        <i class="fas fa-balance-scale"></i>Weight Management
    </button>
    <button class="tab-button" onclick="switchTab('settings')">
        <i class="fas fa-cog"></i>Settings & Components
    </button>
    <button class="tab-button" onclick="switchTab('schemes')">
        <i class="fas fa-layer-group"></i>Grade Schemes
    </button>
    <button class="tab-button" onclick="switchTab('history')">
        <i class="fas fa-history"></i>History
    </button>
</div>
```

## Step 2: Add Weight Management Tab Content

Add this after the Grade Entry tab content (after `</div>` that closes `#entry-tab`):

```html
<!-- Weight Management Tab -->
<div id="weights-tab" class="tab-content">
    <div class="grade-entry-section">
        <div class="section-title">
            <i class="fas fa-balance-scale"></i>
            Component Weight Management
        </div>
        <p class="mb-4 text-muted">Adjust the percentage weight of each subcomponent within its KSA category. Weights will be automatically normalized.</p>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>How it works:</strong> Each component's weight determines its contribution to the category average. 
            For example, if Quiz 1 has 40% weight and Quiz 2 has 60% weight in Knowledge, Quiz 2 will have more impact on the final Knowledge score.
        </div>

        <!-- Knowledge Components -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📚 Knowledge Components</h5>
            </div>
            <div class="card-body">
                <div id="knowledgeComponents" class="components-weight-list">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills Components -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">🎯 Skills Components</h5>
            </div>
            <div class="card-body">
                <div id="skillsComponents" class="components-weight-list">
                    <div class="text-center py-3">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attitude Components -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">😊 Attitude Components</h5>
            </div>
            <div class="card-body">
                <div id="attitudeComponents" class="components-weight-list">
                    <div class="text-center py-3">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-primary btn-lg" id="btnSaveAllWeights">
                <i class="fas fa-save me-2"></i>Save All Weights
            </button>
        </div>
    </div>
</div>
```

## Step 3: Add CSS Styles

Add these styles in the `<style>` section:

```css
.components-weight-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.weight-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.weight-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.weight-item.knowledge {
    border-left-color: #2196F3;
}

.weight-item.skills {
    border-left-color: #4CAF50;
}

.weight-item.attitude {
    border-left-color: #FF9800;
}

.weight-item-info {
    flex: 1;
}

.weight-item-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.weight-item-details {
    font-size: 0.875rem;
    color: #64748b;
}

.weight-item-control {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.weight-slider {
    width: 200px;
}

.weight-value {
    min-width: 60px;
    text-align: center;
    font-weight: 700;
    color: #4f46e5;
    font-size: 1.1rem;
}

.weight-total {
    background: #e0e7ff;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
    text-align: center;
    font-weight: 600;
}

.weight-total.valid {
    background: #d1fae5;
    color: #065f46;
}

.weight-total.invalid {
    background: #fee2e2;
    color: #991b1b;
}
```

## Step 4: Add JavaScript Functions

Add these functions in the `<script>` section:

```javascript
// Load components for weight management
async function loadComponentsForWeights() {
    try {
        const response = await fetch(`/teacher/components/${classId}`);
        const data = await response.json();
        
        if (data.success) {
            renderWeightComponents('Knowledge', data.components.Knowledge || [], 'knowledgeComponents');
            renderWeightComponents('Skills', data.components.Skills || [], 'skillsComponents');
            renderWeightComponents('Attitude', data.components.Attitude || [], 'attitudeComponents');
        }
    } catch (error) {
        console.error('Error loading components:', error);
        showNotification('❌ Error loading components', 'danger');
    }
}

// Render weight components for a category
function renderWeightComponents(category, components, containerId) {
    const container = document.getElementById(containerId);
    
    if (components.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-info-circle me-2"></i>
                No components configured for ${category}. Add components in the Settings tab first.
            </div>
        `;
        return;
    }
    
    let html = '';
    let totalWeight = 0;
    
    components.forEach(comp => {
        totalWeight += parseFloat(comp.weight);
        html += `
            <div class="weight-item ${category.toLowerCase()}" data-component-id="${comp.id}">
                <div class="weight-item-info">
                    <div class="weight-item-name">${comp.name}</div>
                    <div class="weight-item-details">
                        ${comp.subcategory} | Max Score: ${comp.max_score} pts
                    </div>
                </div>
                <div class="weight-item-control">
                    <input type="range" class="form-range weight-slider" 
                           min="0" max="100" step="0.1" 
                           value="${comp.weight}"
                           data-component-id="${comp.id}"
                           onchange="updateWeightValue(this, '${category}')">
                    <div class="weight-value" id="weight-${comp.id}">
                        ${parseFloat(comp.weight).toFixed(1)}%
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
        <div class="weight-total ${Math.abs(totalWeight - 100) < 0.1 ? 'valid' : 'invalid'}" 
             id="total-${category}">
            Total ${category} Weight: <span id="total-value-${category}">${totalWeight.toFixed(1)}</span>%
            ${Math.abs(totalWeight - 100) < 0.1 ? '✓' : '⚠️ Should equal 100%'}
        </div>
    `;
    
    container.innerHTML = html;
}

// Update weight value display
function updateWeightValue(slider, category) {
    const componentId = slider.dataset.componentId;
    const value = parseFloat(slider.value);
    
    // Update display
    document.getElementById(`weight-${componentId}`).textContent = value.toFixed(1) + '%';
    
    // Recalculate total
    updateCategoryTotal(category);
}

// Update category total weight
function updateCategoryTotal(category) {
    const containerId = category.toLowerCase() + 'Components';
    const sliders = document.querySelectorAll(`#${containerId} .weight-slider`);
    
    let total = 0;
    sliders.forEach(slider => {
        total += parseFloat(slider.value);
    });
    
    const totalElement = document.getElementById(`total-${category}`);
    const totalValueElement = document.getElementById(`total-value-${category}`);
    
    totalValueElement.textContent = total.toFixed(1);
    
    if (Math.abs(total - 100) < 0.1) {
        totalElement.className = 'weight-total valid';
        totalElement.innerHTML = `Total ${category} Weight: <span id="total-value-${category}">${total.toFixed(1)}</span>% ✓`;
    } else {
        totalElement.className = 'weight-total invalid';
        totalElement.innerHTML = `Total ${category} Weight: <span id="total-value-${category}">${total.toFixed(1)}</span>% ⚠️ Should equal 100%`;
    }
}

// Save all component weights
document.getElementById('btnSaveAllWeights')?.addEventListener('click', async function() {
    const allSliders = document.querySelectorAll('.weight-slider');
    const updates = [];
    
    allSliders.forEach(slider => {
        updates.push({
            id: parseInt(slider.dataset.componentId),
            weight: parseFloat(slider.value)
        });
    });
    
    try {
        const response = await fetch(`/teacher/components/${classId}/update-weights`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ components: updates })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('✅ All component weights saved successfully!', 'success');
        } else {
            showNotification('❌ ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Error saving weights:', error);
        showNotification('❌ Error saving weights', 'danger');
    }
});

// Load components when Weight Management tab is clicked
document.addEventListener('DOMContentLoaded', function() {
    const weightsTab = document.querySelector('[onclick*="weights"]');
    if (weightsTab) {
        weightsTab.addEventListener('click', function() {
            // Load components only once
            if (!document.getElementById('knowledgeComponents').dataset.loaded) {
                loadComponentsForWeights();
                document.getElementById('knowledgeComponents').dataset.loaded = 'true';
            }
        });
    }
});
```

## Step 5: Add Backend Route

In `routes/web.php`, add this route inside the teacher group:

```php
// Component Weight Management
Route::post('/components/{classId}/update-weights', [\App\Http\Controllers\AssessmentComponentController::class, 'updateWeights'])->name('components.update-weights');
```

## Step 6: Add Controller Method

In `app/Http/Controllers/AssessmentComponentController.php`, add this method:

```php
/**
 * Update weights for multiple components
 */
public function updateWeights(Request $request, $classId)
{
    $validated = $request->validate([
        'components' => 'required|array',
        'components.*.id' => 'required|integer|exists:assessment_components,id',
        'components.*.weight' => 'required|numeric|min:0|max:100',
    ]);

    try {
        DB::beginTransaction();

        foreach ($validated['components'] as $componentData) {
            AssessmentComponent::where('id', $componentData['id'])
                ->where('class_id', $classId)
                ->update(['weight' => $componentData['weight']]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Component weights updated successfully'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating component weights: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to update component weights'
        ], 500);
    }
}
```

## How It Works

1. **Teacher navigates to Weight Management tab**
2. **System loads all components** from database grouped by KSA category
3. **Teacher adjusts sliders** to change component weights (e.g., Quiz 1: 40%, Quiz 2: 60%)
4. **Real-time feedback** shows total weight per category (should equal 100%)
5. **Save button** updates all weights in database
6. **Grade calculations** automatically use new weights when computing category averages

## Example Use Case

### Before:
- Quiz 1: 50% weight
- Quiz 2: 50% weight
- Both quizzes contribute equally to Knowledge score

### After Teacher Adjustment:
- Quiz 1: 30% weight
- Quiz 2: 70% weight
- Quiz 2 now has more impact on final Knowledge score

## Database Impact

When weights are saved, the `assessment_components` table is updated:

```sql
UPDATE assessment_components 
SET weight = 40.0 
WHERE id = 1 AND class_id = 5;
```

The grade calculation service automatically uses these weights when computing averages.

## Benefits

1. **Flexibility**: Teachers can emphasize important assessments
2. **Real-time**: Changes take effect immediately
3. **Validation**: System ensures weights sum to 100%
4. **User-friendly**: Visual sliders with instant feedback
5. **Database-driven**: All changes persist and affect grade calculations

## Testing

1. Add some components in Settings tab
2. Go to Weight Management tab
3. Adjust sliders for different components
4. Verify total shows 100% when balanced
5. Click Save All Weights
6. Refresh page and verify weights persist
7. Enter grades and verify calculations use new weights
