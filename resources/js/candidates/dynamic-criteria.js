// Dynamic criteria handling for candidate edit page
document.addEventListener('DOMContentLoaded', function() {
    const criteriaContainer = document.getElementById('criteria-container');
    const addMoreButton = document.getElementById('add-more-criteria');
    let criteriaCount = 0;

    // Function to create a new criteria group
    function createCriteriaGroup(initialData = null) {
        const groupId = `criteria-group-${criteriaCount++}`;
        const groupDiv = document.createElement('div');
        groupDiv.className = 'criteria-group border rounded-lg p-4 bg-gray-50 relative';
        groupDiv.id = groupId;

        groupDiv.innerHTML = `
            <button type="button" class="remove-criteria absolute top-2 right-2 text-gray-400 hover:text-red-500" onclick="removeCriteriaGroup('${groupId}')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Criteria Category</label>
                    <select name="criteria[${criteriaCount}][category]" class="criteria-category w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select a category</option>
                        <option value="geographical">Geographical</option>
                        <option value="social">Social</option>
                        <option value="academic">Academic</option>
                        <option value="physical">Physical</option>
                        <option value="family">Family</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Criterion Type</label>
                    <select name="criteria[${criteriaCount}][criteria_id]" class="criteria-type w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required disabled>
                        <option value="">Select a category first</option>
                    </select>
                </div>
                <input type="hidden" name="criteria[${criteriaCount}][score]" value="0">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="criteria[${criteriaCount}][note]" class="criteria-notes w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" rows="2" placeholder="Add any notes about this criterion">${initialData?.note || ''}</textarea>
                </div>
            </div>
        `;

        // Initialize Select2 for the new selects
        const categorySelect = groupDiv.querySelector('.criteria-category');
        const typeSelect = groupDiv.querySelector('.criteria-type');

        $(categorySelect).select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: categorySelect.parentElement
        });

        $(typeSelect).select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: typeSelect.parentElement
        });

        // Add change event listener for category select
        $(categorySelect).on('change', function() {
            const category = $(this).val();
            const typeSelect = $(this).closest('.criteria-group').find('.criteria-type');
            
            if (!category) {
                typeSelect.prop('disabled', true).empty().append('<option value="">Select a category first</option>').trigger('change');
                return;
            }

            // Show loading state
            typeSelect.prop('disabled', true).empty().append('<option value="">Loading criteria...</option>').trigger('change');

            // Fetch criteria types for the selected category
            $.ajax({
                url: '/api/criteria',
                method: 'GET',
                data: { category: category },
                dataType: 'json',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                success: function(response) {
                    typeSelect.empty().append('<option value="">Select a criterion type</option>');
                    
                    if (response && response.data && response.data.length > 0) {
                        response.data.forEach(function(criterion) {
                            typeSelect.append(new Option(criterion.text || criterion.name, criterion.id));
                        });
                    } else {
                        typeSelect.append(new Option('No criteria found for this category', ''));
                    }
                    
                    typeSelect.prop('disabled', false).trigger('change');

                    // If there's an initial selected value, set it after options are loaded
                    const selectedVal = typeSelect.data('selected-value');
                    if (selectedVal) {
                        typeSelect.val(selectedVal).trigger('change');
                        typeSelect.data('selected-value', null); // Clear after setting
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading criteria types:', error);
                    typeSelect.empty()
                        .append('<option value="">Error loading criteria</option>')
                        .prop('disabled', true)
                        .trigger('change');
                }
            });
        });

        // Set initial values if data is provided (for edit page)
        if (initialData) {
            // Set category first
            $(categorySelect).val(initialData.category).trigger('change');
            
            // Store the criteria ID to set after options are loaded
            $(typeSelect).data('selected-value', initialData.criteria_id);
            
            // Set score and notes immediately since they don't depend on AJAX
            const scoreInput = groupDiv.querySelector('.criteria-score');
            const notesTextarea = groupDiv.querySelector('.criteria-notes');
            if (scoreInput && initialData.score !== undefined) {
                scoreInput.value = initialData.score;
            }
            if (notesTextarea && initialData.note !== undefined) {
                notesTextarea.value = initialData.note;
            }
        }

        return groupDiv;
    }

    // Function to remove a criteria group
    window.removeCriteriaGroup = function(groupId) {
        const group = document.getElementById(groupId);
        if (group) {
            // Destroy Select2 instances before removing
            $(group).find('.criteria-category, .criteria-type').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
            group.remove();
            // Re-index remaining groups if needed for correct array naming
            criteriaContainer.querySelectorAll('.criteria-group').forEach((grp, idx) => {
                grp.id = `criteria-group-${idx}`;
                grp.querySelector('h4').textContent = `Criterion #${idx + 1}`;
                grp.querySelectorAll('select[name^="criteria["]').forEach(el => {
                    const oldName = el.name;
                    let newName = oldName.replace(/criteria\[\d+\]\[(category|criteria_id)\]/, `criteria[${idx}][$1]`);
                    el.name = newName;
                    el.id = newName.replace(/[\[\]]/g, '_'); // Update ID to match new name for consistency
                });
                grp.querySelectorAll('input[name^="criteria["], textarea[name^="criteria["]').forEach(el => {
                    const oldName = el.name;
                    let newName = oldName.replace(/criteria\[\d+\]\[(score|note)\]/, `criteria[${idx}][$1]`);
                    el.name = newName;
                });
            });
            criteriaCount = criteriaContainer.children.length;
        }
    };

    // Add click event listener to "Add Another Criteria" button
    if (addMoreButton) {
        addMoreButton.addEventListener('click', function() {
            const newGroup = createCriteriaGroup();
            criteriaContainer.appendChild(newGroup);
        });
    }

    // Check if on the Create or Edit page and load initial criteria
    const isCreatePage = window.location.pathname.includes('/candidates/create');
    const isEditPage = window.location.pathname.match(/\/candidates\/\d+\/edit/);

    if (isCreatePage) {
        // On create page, add one empty criteria group by default
        const defaultGroup = createCriteriaGroup();
        criteriaContainer.appendChild(defaultGroup);
    } else if (isEditPage) {
        // On edit page, load existing criteria
        // The existingCriteria data will be passed from the Blade template
        if (window.candidateExistingCriteria && window.candidateExistingCriteria.length > 0) {
            window.candidateExistingCriteria.forEach(function(criterion) {
                const group = createCriteriaGroup(criterion);
                criteriaContainer.appendChild(group);

                // Set actual selected value after options are loaded
                const typeSelect = group.querySelector('.criteria-type');
                $(typeSelect).on('select2:open', function() { // Use select2:open to ensure options are loaded
                    const selectedVal = $(this).data('selected-value');
                    if (selectedVal) {
                        $(this).val(selectedVal).trigger('change');
                        $(this).data('selected-value', null); // Clear after setting
                    }
                });
            });
            criteriaCount = window.candidateExistingCriteria.length; // Set criteriaCount to existing number
        } else {
            // If no existing criteria on edit page, add one empty group
            const defaultGroup = createCriteriaGroup();
            criteriaContainer.appendChild(defaultGroup);
        }
    }
}); 