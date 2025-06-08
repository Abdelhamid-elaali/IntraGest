document.addEventListener('DOMContentLoaded', function() {
    console.log('Criteria delete script loaded');
    
    // Handle delete form submission
    const deleteForms = document.querySelectorAll('form[action*="criteria/"]');
    console.log(`Found ${deleteForms.length} delete forms`);
    
    deleteForms.forEach((form, index) => {
        console.log(`Setting up form ${index + 1}:`, form.action);
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Delete form submitted:', this.action);
            
            if (confirm('Are you sure you want to delete this criterion?')) {
                console.log('User confirmed deletion');
                
                // Create a hidden input for the _method if it doesn't exist
                if (!this.querySelector('input[name="_method"]')) {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    this.appendChild(methodInput);
                }
                
                // Create a hidden input for the CSRF token if it doesn't exist
                if (!this.querySelector('input[name="_token"]')) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (token) {
                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = token;
                        this.appendChild(tokenInput);
                    } else {
                        console.error('CSRF token not found');
                        alert('Error: Could not verify request. Please refresh the page and try again.');
                        return;
                    }
                }
                
                console.log('Submitting form...');
                this.submit();
            } else {
                console.log('User cancelled deletion');
            }
        });
    });
});
