<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'id' => '',
    'placeholder' => 'Search and select...',
    'options' => [],
    'selected' => '',
    'required' => false,
    'multiple' => false,
    'searchable' => true,
    'clearable' => true,
    'createNew' => false,
    'createNewText' => 'Create New',
    'createNewValue' => 'new',
    'apiUrl' => null,
    'displayKey' => 'name',
    'valueKey' => 'id',
    'searchKey' => 'name',
    'class' => '',
    'disabled' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => '',
    'id' => '',
    'placeholder' => 'Search and select...',
    'options' => [],
    'selected' => '',
    'required' => false,
    'multiple' => false,
    'searchable' => true,
    'clearable' => true,
    'createNew' => false,
    'createNewText' => 'Create New',
    'createNewValue' => 'new',
    'apiUrl' => null,
    'displayKey' => 'name',
    'valueKey' => 'id',
    'searchKey' => 'name',
    'class' => '',
    'disabled' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="searchable-dropdown-container" data-dropdown-id="<?php echo e($id); ?>">
    <div class="searchable-dropdown-fixed <?php echo e($class); ?>" 
         data-name="<?php echo e($name); ?>" 
         data-multiple="<?php echo e($multiple ? 'true' : 'false'); ?>"
         data-api-url="<?php echo e($apiUrl); ?>"
         data-display-key="<?php echo e($displayKey); ?>"
         data-value-key="<?php echo e($valueKey); ?>"
         data-search-key="<?php echo e($searchKey); ?>">
        
        <!-- Hidden Input for Form Submission -->
        <input type="hidden" 
               name="<?php echo e($name); ?>" 
               id="<?php echo e($id); ?>_hidden" 
               value="<?php echo e($selected); ?>"
               <?php echo e($required ? 'required' : ''); ?>>
        
        <!-- Dropdown Display -->
        <div class="dropdown-display-fixed" 
             tabindex="0" 
             <?php echo e($disabled ? 'disabled' : ''); ?>>
            <div class="dropdown-selected-fixed">
                <span class="selected-text-fixed placeholder"><?php echo e($placeholder); ?></span>
                <?php if($clearable && !$disabled): ?>
                    <button type="button" class="clear-btn-fixed" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="dropdown-arrow-fixed">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <!-- Dropdown Menu -->
        <div class="dropdown-menu-fixed">
            <?php if($searchable): ?>
                <div class="dropdown-search-fixed">
                    <input type="text" 
                           class="search-input-fixed" 
                           placeholder="Type to search..."
                           autocomplete="off">
                    <i class="fas fa-search search-icon-fixed"></i>
                </div>
            <?php endif; ?>
            
            <div class="dropdown-options-fixed">
                <?php if($createNew): ?>
                    <div class="dropdown-option-fixed create-new-fixed" data-value="<?php echo e($createNewValue); ?>">
                        <i class="fas fa-plus"></i>
                        <span><?php echo e($createNewText); ?></span>
                    </div>
                    <div class="dropdown-divider-fixed"></div>
                <?php endif; ?>
                
                <?php if($apiUrl): ?>
                    <div class="loading-state-fixed">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading...</span>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $value = is_array($option) ? $option[$valueKey] : $option->$valueKey;
                            $display = is_array($option) ? $option[$displayKey] : $option->$displayKey;
                            $isSelected = $multiple ? 
                                (is_array($selected) && in_array($value, $selected)) : 
                                ($selected == $value);
                        ?>
                        <div class="dropdown-option-fixed <?php echo e($isSelected ? 'selected' : ''); ?>" 
                             data-value="<?php echo e($value); ?>"
                             data-display="<?php echo e($display); ?>">
                            <?php if($multiple): ?>
                                <input type="checkbox" <?php echo e($isSelected ? 'checked' : ''); ?>>
                            <?php endif; ?>
                            <span><?php echo e($display); ?></span>
                            <?php if(isset($option['description']) || (is_object($option) && isset($option->description))): ?>
                                <small class="option-description-fixed">
                                    <?php echo e(is_array($option) ? $option['description'] : $option->description); ?>

                                </small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
                <div class="no-results-fixed" style="display: none;">
                    <i class="fas fa-search"></i>
                    <span>No results found</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.searchable-dropdown-container {
    position: relative;
    width: 100%;
}

.searchable-dropdown-fixed {
    position: relative;
    width: 100%;
}

.dropdown-display-fixed {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 48px;
}

.dropdown-display-fixed:hover {
    border-color: #cbd5e1;
}

.dropdown-display-fixed:focus,
.dropdown-display-fixed.active {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.dropdown-display-fixed[disabled] {
    background: #f8fafc;
    cursor: not-allowed;
    opacity: 0.6;
}

.dropdown-selected-fixed {
    display: flex;
    align-items: center;
    flex: 1;
    gap: 0.5rem;
}

.selected-text-fixed {
    color: #374151;
    flex: 1;
}

.selected-text-fixed.placeholder {
    color: #9ca3af;
}

.clear-btn-fixed {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.clear-btn-fixed:hover {
    color: #ef4444;
    background: #fef2f2;
}

.dropdown-arrow-fixed {
    color: #9ca3af;
    transition: transform 0.2s ease;
}

.dropdown-display-fixed.active .dropdown-arrow-fixed {
    transform: rotate(180deg);
}

.dropdown-menu-fixed {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    z-index: 1050;
    max-height: 300px;
    overflow: hidden;
    display: none;
    margin-top: 4px;
}

.dropdown-menu-fixed.show {
    display: block;
}

.dropdown-search-fixed {
    position: relative;
    padding: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.search-input-fixed {
    width: 100%;
    padding: 0.5rem 2.5rem 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    outline: none;
}

.search-input-fixed:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.search-icon-fixed {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 0.875rem;
}

.dropdown-options-fixed {
    max-height: 200px;
    overflow-y: auto;
}

.dropdown-option-fixed {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
    user-select: none;
}

.dropdown-option-fixed:last-child {
    border-bottom: none;
}

.dropdown-option-fixed:hover {
    background: #f9fafb;
}

.dropdown-option-fixed.selected {
    background: #eff6ff;
    color: #1d4ed8;
}

.dropdown-option-fixed.create-new-fixed {
    color: #059669;
    font-weight: 500;
}

.dropdown-option-fixed.create-new-fixed:hover {
    background: #ecfdf5;
}

.option-description-fixed {
    display: block;
    color: #6b7280;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.dropdown-divider-fixed {
    height: 1px;
    background: #e5e7eb;
    margin: 0.5rem 0;
}

.loading-state-fixed,
.no-results-fixed {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.loading-state-fixed i {
    color: #4f46e5;
}

/* Responsive */
@media (max-width: 768px) {
    .dropdown-menu-fixed {
        max-height: 250px;
    }
    
    .dropdown-options-fixed {
        max-height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeFixedDropdowns();
});

function initializeFixedDropdowns() {
    const dropdowns = document.querySelectorAll('.searchable-dropdown-fixed');
    
    dropdowns.forEach(dropdown => {
        const display = dropdown.querySelector('.dropdown-display-fixed');
        const menu = dropdown.querySelector('.dropdown-menu-fixed');
        const searchInput = dropdown.querySelector('.search-input-fixed');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const clearBtn = dropdown.querySelector('.clear-btn-fixed');
        const selectedText = dropdown.querySelector('.selected-text-fixed');
        const optionsContainer = dropdown.querySelector('.dropdown-options-fixed');
        
        const isMultiple = dropdown.dataset.multiple === 'true';
        const apiUrl = dropdown.dataset.apiUrl;
        
        let currentOptions = [];
        
        // Initialize
        if (apiUrl) {
            loadOptionsFromAPI();
        } else {
            currentOptions = Array.from(dropdown.querySelectorAll('.dropdown-option-fixed:not(.create-new-fixed)'));
            initializeSelected();
        }
        
        // Toggle dropdown
        display.addEventListener('click', function(e) {
            if (display.hasAttribute('disabled')) return;
            e.stopPropagation();
            toggleDropdown();
        });
        
        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                filterOptions(query);
            });
        }
        
        // Option selection - Use event delegation
        optionsContainer.addEventListener('click', function(e) {
            const option = e.target.closest('.dropdown-option-fixed');
            if (!option) return;
            
            e.stopPropagation();
            
            if (option.classList.contains('create-new-fixed')) {
                handleCreateNew(option);
                return;
            }
            
            selectOption(option);
        });
        
        // Clear button
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                clearSelection();
            });
        }
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                closeDropdown();
            }
        });
        
        function toggleDropdown() {
            const isOpen = menu.classList.contains('show');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu-fixed.show').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('show');
                    m.closest('.searchable-dropdown-fixed').querySelector('.dropdown-display-fixed').classList.remove('active');
                }
            });
            
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }
        
        function openDropdown() {
            menu.classList.add('show');
            display.classList.add('active');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }
        
        function closeDropdown() {
            menu.classList.remove('show');
            display.classList.remove('active');
            if (searchInput) {
                searchInput.value = '';
                filterOptions('');
            }
        }
        
        function selectOption(option) {
            const value = option.dataset.value;
            const displayText = option.dataset.display || option.textContent.trim();
            
            if (!value) return;
            
            if (isMultiple) {
                selectMultipleOption(option, value, displayText);
            } else {
                selectSingleOption(option, value, displayText);
                closeDropdown();
            }
            
            // Trigger change event
            const changeEvent = new Event('change', { bubbles: true });
            hiddenInput.dispatchEvent(changeEvent);
        }
        
        function selectSingleOption(option, value, displayText) {
            // Update hidden input
            hiddenInput.value = value;
            
            // Update display
            selectedText.textContent = displayText;
            selectedText.classList.remove('placeholder');
            
            // Update option states
            currentOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            
            // Show clear button
            if (clearBtn) {
                clearBtn.style.display = 'block';
            }
        }
        
        function selectMultipleOption(option, value, displayText) {
            const checkbox = option.querySelector('input[type="checkbox"]');
            const isSelected = option.classList.contains('selected');
            
            if (isSelected) {
                // Deselect
                option.classList.remove('selected');
                if (checkbox) checkbox.checked = false;
                removeFromSelection(value);
            } else {
                // Select
                option.classList.add('selected');
                if (checkbox) checkbox.checked = true;
                addToSelection(value, displayText);
            }
            
            updateMultipleDisplay();
        }
        
        function addToSelection(value, displayText) {
            const currentValues = hiddenInput.value ? hiddenInput.value.split(',') : [];
            if (!currentValues.includes(value)) {
                currentValues.push(value);
                hiddenInput.value = currentValues.join(',');
            }
        }
        
        function removeFromSelection(value) {
            const currentValues = hiddenInput.value ? hiddenInput.value.split(',') : [];
            const newValues = currentValues.filter(v => v !== value);
            hiddenInput.value = newValues.join(',');
        }
        
        function updateMultipleDisplay() {
            const selectedOptions = dropdown.querySelectorAll('.dropdown-option-fixed.selected:not(.create-new-fixed)');
            
            if (selectedOptions.length === 0) {
                selectedText.textContent = dropdown.closest('.searchable-dropdown-container').querySelector('.dropdown-display-fixed').getAttribute('data-placeholder') || 'Select options...';
                selectedText.classList.add('placeholder');
                if (clearBtn) clearBtn.style.display = 'none';
            } else {
                const count = selectedOptions.length;
                selectedText.textContent = `${count} item${count > 1 ? 's' : ''} selected`;
                selectedText.classList.remove('placeholder');
                if (clearBtn) clearBtn.style.display = 'block';
            }
        }
        
        function clearSelection() {
            hiddenInput.value = '';
            
            if (isMultiple) {
                currentOptions.forEach(option => {
                    option.classList.remove('selected');
                    const checkbox = option.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = false;
                });
                updateMultipleDisplay();
            } else {
                selectedText.textContent = dropdown.closest('.searchable-dropdown-container').querySelector('.dropdown-display-fixed').getAttribute('data-placeholder') || 'Select an option...';
                selectedText.classList.add('placeholder');
                currentOptions.forEach(opt => opt.classList.remove('selected'));
            }
            
            if (clearBtn) clearBtn.style.display = 'none';
            
            // Trigger change event
            const changeEvent = new Event('change', { bubbles: true });
            hiddenInput.dispatchEvent(changeEvent);
        }
        
        function filterOptions(query) {
            let visibleCount = 0;
            
            currentOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                const matches = text.includes(query);
                
                option.style.display = matches ? 'flex' : 'none';
                if (matches) visibleCount++;
            });
            
            // Show/hide no results
            const noResults = dropdown.querySelector('.no-results-fixed');
            if (noResults) {
                noResults.style.display = visibleCount === 0 && query ? 'flex' : 'none';
            }
        }
        
        function initializeSelected() {
            const initialValue = hiddenInput.value;
            if (!initialValue) return;
            
            if (isMultiple) {
                const values = initialValue.split(',');
                values.forEach(value => {
                    const option = dropdown.querySelector(`[data-value="${value}"]`);
                    if (option) {
                        option.classList.add('selected');
                        const checkbox = option.querySelector('input[type="checkbox"]');
                        if (checkbox) checkbox.checked = true;
                    }
                });
                updateMultipleDisplay();
            } else {
                const option = dropdown.querySelector(`[data-value="${initialValue}"]`);
                if (option) {
                    option.classList.add('selected');
                    selectedText.textContent = option.dataset.display || option.textContent.trim();
                    selectedText.classList.remove('placeholder');
                    if (clearBtn) clearBtn.style.display = 'block';
                }
            }
        }
        
        function handleCreateNew(option) {
            const value = option.dataset.value;
            hiddenInput.value = value;
            selectedText.textContent = option.textContent.trim();
            selectedText.classList.remove('placeholder');
            
            if (clearBtn) clearBtn.style.display = 'block';
            
            closeDropdown();
            
            // Trigger change event
            const changeEvent = new Event('change', { bubbles: true });
            hiddenInput.dispatchEvent(changeEvent);
            
            // Trigger custom event for create new
            const createEvent = new CustomEvent('createNew', {
                detail: { dropdown: dropdown, value: value },
                bubbles: true
            });
            dropdown.dispatchEvent(createEvent);
        }
        
        function loadOptionsFromAPI() {
            const loadingState = dropdown.querySelector('.loading-state-fixed');
            
            if (loadingState) loadingState.style.display = 'flex';
            
            fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (loadingState) loadingState.style.display = 'none';
                    
                    // Clear existing options
                    const existingOptions = optionsContainer.querySelectorAll('.dropdown-option-fixed:not(.create-new-fixed)');
                    existingOptions.forEach(opt => opt.remove());
                    
                    // Add new options
                    currentOptions = [];
                    data.forEach(item => {
                        const option = createOptionElement(item);
                        optionsContainer.appendChild(option);
                        currentOptions.push(option);
                    });
                    
                    initializeSelected();
                })
                .catch(error => {
                    console.error('Error loading options:', error);
                    if (loadingState) {
                        loadingState.innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>Error loading options</span>';
                    }
                });
        }
        
        function createOptionElement(item) {
            const option = document.createElement('div');
            option.className = 'dropdown-option-fixed';
            option.dataset.value = item[dropdown.dataset.valueKey];
            option.dataset.display = item[dropdown.dataset.displayKey];
            
            let content = `<span>${item[dropdown.dataset.displayKey]}</span>`;
            
            if (item.description) {
                content += `<small class="option-description-fixed">${item.description}</small>`;
            }
            
            if (isMultiple) {
                content = `<input type="checkbox"> ${content}`;
            }
            
            option.innerHTML = content;
            return option;
        }
    });
}
</script><?php /**PATH C:\laragon\www\edutrack\resources\views/components/searchable-dropdown.blade.php ENDPATH**/ ?>