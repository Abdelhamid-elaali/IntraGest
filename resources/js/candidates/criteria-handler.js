// Make the function globally available for initialization from blade templates
window.initializeCriteriaHandler = function() {
    console.log('Initializing criteria handler...');
    
    // Wait for jQuery to be loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded. Make sure jQuery is included before this script.');
        return;
    }
    
    const $criteriaCategorySelect = $('#criteria_category');
    const $criteriaTypeSelect = $('#criteria_type');
    
    if ($criteriaCategorySelect.length === 0 || $criteriaTypeSelect.length === 0) {
        console.error('Required select elements not found');
        return;
    }
    
    // Initialize Select2 on criteria type select if not already initialized
    if (!$criteriaTypeSelect.hasClass('select2-hidden-accessible')) {
        $criteriaTypeSelect.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $criteriaTypeSelect.parent(),
            placeholder: 'Select a criterion type'
        });
    }
    
    // Get the selected value from the form or data attribute
    const selectedValue = $criteriaTypeSelect.data('selected') || '';
    
    // Function to load criteria types
    function loadCriteriaTypes(category, selectedVal = '') {
        console.log('Loading criteria for category:', category, 'Selected value:', selectedVal);
        
        if (!category) {
            console.log('No category selected, disabling criteria type select');
            // Clear and disable the select2
            $criteriaTypeSelect.empty().prop('disabled', true).trigger('change');
            $criteriaTypeSelect.append(new Option('Select a category first', '', true, true));
            return;
        }
        
        // Show loading state
        console.log('Setting loading state for criteria type select');
        $criteriaTypeSelect.empty().prop('disabled', true).trigger('change');
        $criteriaTypeSelect.html('<option value="">Loading criteria...</option>');
        
        // Log the request details
        console.log('Making API request to /api/criteria with:', {
            category: category,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        // Fetch criteria types for the selected category from the API
        $.ajax({
            url: '/api/criteria',
            method: 'GET',
            data: { category: category },
            dataType: 'json',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            beforeSend: function(xhr) {
                console.log('Request headers:', xhr.getAllResponseHeaders());
            },
            success: function(response) {
                console.log('API Response:', response);
                
                // Destroy Select2 before manipulating options to ensure proper re-initialization
                if ($criteriaTypeSelect.data('select2')) {
                    $criteriaTypeSelect.select2('destroy');
                }
                
                // Clear existing options and add default
                $criteriaTypeSelect.empty().append('<option value="">Select a criterion type</option>');
                
                if (response && response.data && response.data.length > 0) {
                    console.log('Adding criteria options:', response.data);
                    // Add criteria options
                    response.data.forEach(function(criterion) {
                        const isSelected = (selectedVal && 
                                         (criterion.id == selectedVal || 
                                          criterion.id.toString() === selectedVal.toString()));
                        
                        console.log('Adding option:', {
                            text: criterion.text || criterion.name,
                            value: criterion.id,
                            isSelected: isSelected
                        });
                        
                        $criteriaTypeSelect.append(new Option(
                            criterion.text || criterion.name, 
                            criterion.id, 
                            false, 
                            isSelected
                        ));
                    });
                    
                    // Enable the select
                    console.log('Enabling criteria type select');
                    $criteriaTypeSelect.prop('disabled', false);
                } else {
                    console.log('No criteria found for category:', category);
                    // No criteria found for this category
                    $criteriaTypeSelect.append(new Option('No criteria found for this category', '', true, true));
                    $criteriaTypeSelect.prop('disabled', true);
                }
                
                // Re-initialize Select2 after options are loaded and select is enabled/disabled
                $criteriaTypeSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $criteriaTypeSelect.parent(),
                    placeholder: 'Select a criterion type'
                });
                
                // Set selected value if any, and trigger change for Select2 to update its display
                if (selectedVal) {
                    $criteriaTypeSelect.val(selectedVal).trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading criteria types:', {
                    status: status,
                    statusText: xhr.statusText,
                    error: error,
                    response: xhr.responseText,
                    headers: xhr.getAllResponseHeaders()
                });
                
                let errorMessage = 'Error loading criteria. Please try again.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                }
                
                // Show error message
                $criteriaTypeSelect.empty()
                    .append(`<option value="">${errorMessage}</option>`)
                    .prop('disabled', true)
                    .trigger('change');
                
                // Show error in console for debugging
                console.error('AJAX Error Details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    headers: xhr.getAllResponseHeaders(),
                    readyState: xhr.readyState
                });
            }
        });
    }
    
    // Load criteria types when the category changes
    $criteriaCategorySelect.on('change', function() {
        const category = $(this).val();
        loadCriteriaTypes(category, selectedValue);
    });
    
    // Load criteria types immediately if a category is already selected
    if ($criteriaCategorySelect.val()) {
        loadCriteriaTypes($criteriaCategorySelect.val(), selectedValue);
    }
};

// Auto-initialize if the DOM is already loaded
// if (document.readyState !== 'loading') {
//     window.initializeCriteriaHandler();
// } else {
//     document.addEventListener('DOMContentLoaded', window.initializeCriteriaHandler);
// }
