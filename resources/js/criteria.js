document.addEventListener('DOMContentLoaded', function() {
    // Handle delete form submissions
    document.querySelectorAll('form[data-delete-form]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this criterion?')) {
                // Ensure we have the correct method and token
                const formData = new FormData(this);
                const method = formData.get('_method') || 'POST';
                const token = formData.get('_token') || '';
                
                fetch(this.action, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json().then(data => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.reload();
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the criterion. Please try again.');
                });
            }
        });
    });
});
