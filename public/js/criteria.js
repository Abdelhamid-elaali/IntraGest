document.addEventListener('DOMContentLoaded', function() {
    const criteriaContainer = document.getElementById('criteria-container');
    const addButton = document.getElementById('add-criteria');
    let criteriaCount = document.querySelectorAll('.criteria-row').length;

    // Add new criteria row
    addButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'criteria-row border-b border-gray-200 pb-6 mb-6';
        newRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Criterion Name</label>
                    <input type="text" 
                           name="criteria[${criteriaCount}][name]" 
                           placeholder="Enter criterion name" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="criteria[${criteriaCount}][category]" class="w-full category-select rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Category</option>
                        <option value="geographical">Geographical</option>
                        <option value="social">Social</option>
                        <option value="academic">Academic</option>
                        <option value="physical">Physical</option>
                        <option value="family">Family</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Score Points</label>
                        <div class="flex">
                            <input type="number" 
                                   name="criteria[${criteriaCount}][score]" 
                                   min="1" 
                                   max="100" 
                                   placeholder="Points (1-100)" 
                                   class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                   required>
                            <span class="inline-flex items-center px-3 rounded-r-md bg-gray-50 text-gray-500 text-sm">
                                %
                            </span>
                            <button type="button" class="remove-criteria ml-2 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                <textarea name="criteria[${criteriaCount}][description]" 
                          rows="2" 
                          placeholder="Enter criterion description" 
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
            </div>
        `;

        criteriaContainer.appendChild(newRow);
        criteriaCount++;

        // Add event listener to the new remove button
        const removeButton = newRow.querySelector('.remove-criteria');
        removeButton.addEventListener('click', function() {
            if (document.querySelectorAll('.criteria-row').length > 1) {
                newRow.remove();
                renumberCriteria();
            } else {
                alert('At least one criterion is required.');
            }
        });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-criteria').forEach(button => {
        button.addEventListener('click', function() {
            if (document.querySelectorAll('.criteria-row').length > 1) {
                this.closest('.criteria-row').remove();
                renumberCriteria();
            } else {
                alert('At least one criterion is required.');
            }
        });
    });

    // Renumber criteria indices
    function renumberCriteria() {
        document.querySelectorAll('.criteria-row').forEach((row, index) => {
            // Update name attribute for all inputs and selects
            row.querySelectorAll('input, select, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
    }

    // Form validation
    const form = document.getElementById('criteria-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const criteriaRows = document.querySelectorAll('.criteria-row');
            let totalScore = 0;
            let isValid = true;

            criteriaRows.forEach((row, index) => {
                const scoreInput = row.querySelector('input[type="number"]');
                const score = parseInt(scoreInput.value) || 0;
                totalScore += score;

                if (score < 1 || score > 100) {
                    isValid = false;
                    scoreInput.classList.add('border-red-500');
                } else {
                    scoreInput.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please ensure all score points are between 1 and 100.');
            } else if (totalScore > 100) {
                e.preventDefault();
                alert('The total score points cannot exceed 100. Current total: ' + totalScore);
            }
        });
    }
});
