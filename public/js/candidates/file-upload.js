/**
 * File Upload Handling for Candidate Supporting Documents
 * 
 * This script handles the file upload functionality for candidate supporting documents,
 * including drag-and-drop, file previews, file count enforcement, and styled alerts.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const fileInput = document.getElementById('supporting_documents');
    const dropArea = document.getElementById('document-drop-area');
    const uploadPrompt = document.getElementById('document-upload-prompt');
    const previewContainer = document.getElementById('document-preview-container');
    const previewsGrid = document.getElementById('document-previews');
    const fileCountEl = document.getElementById('file-count');

    // Only initialize if we have the necessary elements
    if (!fileInput || !dropArea) return;

    // Format file size to human-readable format
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Get file type from file name
    function getFileType(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        return ext;
    }

    // Get appropriate icon for file type
    function getFileIcon(fileType) {
        let icon = '';
        
        switch(fileType) {
            case 'pdf':
                icon = '<svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
                break;
            case 'doc':
            case 'docx':
                icon = '<svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
                break;
            case 'xls':
            case 'xlsx':
            case 'csv':
                icon = '<svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
                icon = '<svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>';
                break;
            case 'zip':
                icon = '<svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2h8v2H6V6zm8 4H6v2h8v-2zm0 4H6v2h8v-2z" clip-rule="evenodd"></path></svg>';
                break;
            default:
                icon = '<svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
        }
        
        return icon;
    }

    // Create preview element for a file
    function createFilePreview(file, index) {
        const fileSize = formatFileSize(file.size);
        const fileType = getFileType(file.name);
        const fileIcon = getFileIcon(fileType);
        
        // Create preview element
        const preview = document.createElement('div');
        preview.className = 'bg-white rounded-lg shadow-sm border border-gray-200 p-3 flex items-start';
        preview.dataset.index = index;
        
        // Create preview content
        preview.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                ${fileIcon}
            </div>
            <div class="flex-grow min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate" title="${file.name}">${file.name}</p>
                <p class="text-xs text-gray-500">${fileType.toUpperCase()} · ${fileSize}</p>
            </div>
            <div class="flex-shrink-0 ml-2 flex space-x-1">
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="previewFile(${index})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
                <button type="button" class="text-gray-400 hover:text-red-600" onclick="removeFile(${index})">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        
        return preview;
    }

    // Function to handle file selection
    function handleFileSelect() {
        // Check if files are selected
        if (fileInput.files.length > 0) {
            // Enforce maximum of 5 files
            if (fileInput.files.length > 5) {
                // Use x-alert component instead of basic alert
                showXAlert('warning', 'File Limit Exceeded', 'You can upload a maximum of 5 files. Only the first 5 files will be used.');
                
                // Create a new FileList with only the first 5 files
                const dt = new DataTransfer();
                for (let i = 0; i < Math.min(5, fileInput.files.length); i++) {
                    dt.items.add(fileInput.files[i]);
                }
                fileInput.files = dt.files;
            }
            
            // Update file count display
            fileCountEl.textContent = `${fileInput.files.length}/5 files`;
            fileCountEl.className = fileInput.files.length === 5 ? 
                'text-xs font-medium text-amber-600' : 'text-xs text-gray-500';
            
            // Show preview container and hide upload prompt if there are files
            if (fileInput.files.length > 0) {
                previewContainer.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
                
                // Adjust drop area height for better display
                dropArea.classList.remove('h-32');
                dropArea.classList.add('min-h-32');
            } else {
                previewContainer.classList.add('hidden');
                uploadPrompt.classList.remove('hidden');
                dropArea.classList.add('h-32');
                dropArea.classList.remove('min-h-32');
            }
            
            // Clear previous previews
            previewsGrid.innerHTML = '';
            
            // Create previews for each file
            Array.from(fileInput.files).forEach((file, index) => {
                const filePreview = createFilePreview(file, index);
                previewsGrid.appendChild(filePreview);
            });
        } else {
            // Hide preview container and show upload prompt
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropArea.classList.add('h-32');
            dropArea.classList.remove('min-h-32');
            
            // Update file count display
            fileCountEl.textContent = '0/5 files';
            fileCountEl.className = 'text-xs text-gray-500';
        }
    }

    // Handle file selection
    fileInput.addEventListener('change', handleFileSelect);
    
    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Highlight drop area when dragging over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('bg-blue-50');
        dropArea.classList.add('border-blue-500');
    }
    
    function unhighlight() {
        dropArea.classList.remove('bg-blue-50');
        dropArea.classList.remove('border-blue-500');
    }
    
    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        // Update file input with dropped files
        if (files.length > 0) {
            // Create a new FileList with the dropped files (max 5)
            const newDt = new DataTransfer();
            for (let i = 0; i < Math.min(5, files.length); i++) {
                newDt.items.add(files[i]);
            }
            
            fileInput.files = newDt.files;
            handleFileSelect();
        }
    }
    
    // Function to remove a file from the input
    window.removeFile = function(index) {
        if (fileInput.files.length > 0) {
            const dt = new DataTransfer();
            
            // Add all files except the one to remove
            Array.from(fileInput.files)
                .filter((_, i) => i !== index)
                .forEach(file => dt.items.add(file));
            
            // Update the file input
            fileInput.files = dt.files;
            
            // Update the UI
            handleFileSelect();
            
            // Show success message
            showXAlert('success', 'File Removed', 'The file has been removed from the upload list.');
        }
    };
    
    // Function to preview a file
    window.previewFile = function(index) {
        const file = fileInput.files[index];
        if (!file) return;
        
        // For images, create a preview in a modal
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Create modal with image preview
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="relative bg-white rounded-lg max-w-3xl max-h-[90vh] overflow-auto p-4">
                        <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="mt-4">
                            <img src="${e.target.result}" alt="${file.name}" class="max-w-full max-h-[70vh] mx-auto">
                            <p class="mt-2 text-center text-sm text-gray-600">${file.name}</p>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Close modal when clicking outside
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });
            };
            
            reader.readAsDataURL(file);
        } else {
            // For non-images, just show info
            showXAlert('info', 'File Preview', `Preview not available for ${file.name}. You can view this file after uploading.`);
        }
    };
    
    // Function to show styled alerts
    window.showXAlert = function(type, title, message) {
        // Generate a unique ID for the alert
        const alertId = 'alert-' + Date.now();
        
        // Create the alert element
        const alertElement = document.createElement('div');
        alertElement.id = alertId;
        alertElement.className = 'fixed bottom-4 right-4 z-50 max-w-md transition-opacity duration-500';
        
        // Get the appropriate icon for the alert type
        let icon = '';
        let colorClass = '';
        
        switch(type) {
            case 'success':
                icon = '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                colorClass = 'text-green-800 bg-green-50 border-green-200';
                break;
            case 'warning':
                icon = '<svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                colorClass = 'text-yellow-800 bg-yellow-50 border-yellow-200';
                break;
            case 'error':
                icon = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                colorClass = 'text-red-800 bg-red-50 border-red-200';
                break;
            case 'info':
            default:
                icon = '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                colorClass = 'text-blue-800 bg-blue-50 border-blue-200';
        }
        
        // Set the alert content
        alertElement.innerHTML = `
            <div class="flex items-start p-4 border rounded-lg shadow-md ${colorClass}">
                <div class="flex-shrink-0 mr-3">
                    ${icon}
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium">${title}</h3>
                    <div class="mt-1 text-sm opacity-90">${message}</div>
                </div>
                <button type="button" class="ml-4 text-gray-400 hover:text-gray-600" onclick="dismissAlert('${alertId}')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add the alert to the document
        document.body.appendChild(alertElement);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            dismissAlert(alertId);
        }, 5000);
    };
    
    // Function to dismiss an alert
    window.dismissAlert = function(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            // Add fade-out animation
            alertElement.style.transition = 'opacity 0.5s';
            alertElement.style.opacity = '0';
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                alertElement.remove();
            }, 500);
        }
    };
    
    // Helper function to show document download alert
    window.showDocumentDownloadAlert = function(filename) {
        showXAlert('info', 'File Preview', `Previewing ${filename}`);
    };
    
    // Check file input on change to enforce the 5-file limit
    fileInput.addEventListener('change', function() {
        if (this.files.length === 5) {
            // Show warning when limit is reached
            showXAlert('warning', 'Maximum Files Reached', 'You have reached the maximum of 5 files. Remove a file before adding more.');
            
            // Disable the file input visually
            dropArea.classList.add('opacity-50');
            const uploadText = dropArea.querySelector('p span.font-semibold');
            if (uploadText) {
                uploadText.textContent = 'Maximum files reached';
            }
        } else {
            // Re-enable the file input visually
            dropArea.classList.remove('opacity-50');
            const uploadText = dropArea.querySelector('p span.font-semibold');
            if (uploadText) {
                uploadText.textContent = 'Click to upload';
            }
        }
    });
});
