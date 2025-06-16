// File upload handling for candidate documents (Optimized)
(function() {
    // Constants
    const MAX_FILES = 5;
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx', 'csv', 'zip'];
    const ALLOWED_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'application/zip'
    ];

    // Cache DOM elements
    const fileInput = document.getElementById('supporting_documents');
    const dropArea = document.getElementById('document-drop-area');
    const uploadPrompt = document.getElementById('document-upload-prompt');
    const previewContainer = document.getElementById('document-preview-container');
    const previewsGrid = document.getElementById('document-previews');
    const fileCountEl = document.getElementById('file-count');

    // State
    let files = [];
    let existingFiles = [];
    let isDragging = false;

    // File type icons mapping
    const fileIcons = {
        pdf: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-red-500' },
        doc: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-blue-500' },
        docx: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-blue-500' },
        xls: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-green-500' },
        xlsx: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-green-500' },
        csv: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-green-500' },
        jpg: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
        jpeg: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
        png: { icon: 'M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z', color: 'text-purple-500' },
        zip: { icon: 'M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2h8v2H6V6zm8 4H6v2h8v-2zm0 4H6v2h8v-2z', color: 'text-yellow-500' },
        default: { icon: 'M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z', color: 'text-gray-500' }
    };

    // Helper functions
    const formatFileSize = (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.min(Math.floor(Math.log(bytes) / Math.log(k)), sizes.length - 1);
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`;
    };

    const getFileExtension = (filename) => {
        const match = /\.([0-9a-z]+)$/i.exec(filename);
        return match ? match[1].toLowerCase() : '';
    };

    const isFileTypeAllowed = (file) => {
        const ext = getFileExtension(file.name);
        return ALLOWED_EXTENSIONS.includes(ext) || ALLOWED_TYPES.includes(file.type);
    };

    // UI Update functions
    const updateFileCount = () => {
        const totalFiles = files.length + existingFiles.length;
        if (fileCountEl) {
            fileCountEl.textContent = `${totalFiles}/${MAX_FILES} files`;
            fileCountEl.className = totalFiles >= MAX_FILES ? 
                'text-xs font-medium text-amber-600' : 'text-xs text-gray-500';
        }
    };

    const toggleDropAreaState = (hasFiles) => {
        if (hasFiles) {
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
            dropArea.classList.remove('h-32');
            dropArea.classList.add('min-h-32');
        } else {
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropArea.classList.add('h-32');
            dropArea.classList.remove('min-h-32');
        }
    };

    // File handling
    const createFilePreview = (file, index) => {
        const fileSize = formatFileSize(file.size);
        const fileExt = getFileExtension(file.name);
        const fileType = fileIcons[fileExt] || fileIcons.default;
        
        const preview = document.createElement('div');
        preview.className = 'bg-white rounded-lg shadow-sm border border-gray-200 p-3 flex items-start transition-colors duration-200 hover:bg-gray-50';
        preview.dataset.index = index;
        
        preview.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                <svg class="w-8 h-8 ${fileType.color}" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="${fileType.icon}" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="flex-grow min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate" title="${file.name.replace(/"/g, '&quot;')}">${file.name}</p>
                <p class="text-xs text-gray-500">${fileExt.toUpperCase()} · ${fileSize}</p>
            </div>
            <div class="flex-shrink-0 ml-2 flex space-x-1">
                <button type="button" class="text-gray-400 hover:text-blue-500 transition-colors" data-action="preview" data-index="${index}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
                <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" data-action="remove" data-index="${index}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        
        return preview;
    };

    const updatePreviews = () => {
        previewsGrid.innerHTML = '';
        
        // Add existing files first
        existingFiles.forEach(doc => {
            const preview = document.createElement('div');
            preview.className = 'document-preview-item flex items-center justify-between p-2 bg-white border rounded-md';
            preview.setAttribute('data-document-id', doc.id);
            
            const icon = fileIcons[getFileExtension(doc.original_filename).toLowerCase()] || fileIcons.default;
            
            preview.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 ${icon.color} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm text-gray-700 truncate max-w-xs">${doc.original_filename}</span>
                </div>
                <div class="flex items-center">
                    <a href="${doc.url}" class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3" download>Download</a>
                    <button type="button" class="text-red-600 hover:text-red-800 text-xs font-medium remove-existing-document" data-document-id="${doc.id}">
                        Remove
                    </button>
                    <input type="hidden" name="existing_documents[]" value="${doc.id}">
                </div>
            `;
            
            previewsGrid.appendChild(preview);
        });
        
        // Add new files
        files.forEach((file, index) => {
            const preview = createFilePreview(file, index);
            previewsGrid.appendChild(preview);
        });
        
        const hasFiles = files.length > 0 || existingFiles.length > 0;
        previewContainer.classList.toggle('hidden', !hasFiles);
        uploadPrompt.classList.toggle('hidden', hasFiles);
    };

    // Event handlers
    const handleFileSelect = (event) => {
        const newFiles = Array.from(event.target.files);
        const totalFiles = files.length + existingFiles.length + newFiles.length;
        
        // Filter out files that exceed max size or have invalid types
        const validFiles = newFiles.filter(file => {
            const isValid = file.size <= MAX_FILE_SIZE && isFileTypeAllowed(file);
            if (!isValid) {
                alert(`File ${file.name} was not added. Please ensure files are under ${MAX_FILE_SIZE / (1024 * 1024)}MB and have a valid file type.`);
            }
            return isValid;
        });

        // Check if adding these files would exceed max files
        if (files.length + existingFiles.length + validFiles.length > MAX_FILES) {
            alert(`You can only upload up to ${MAX_FILES} files.`);
            return;
        }

        // Add files to the files array
        files = [...files, ...validFiles];
        
        // Update the file input with the new files
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        // Manually trigger a change event on the file input
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
        
        updatePreviews();
        updateFileCount();
        
        // Reset the file input to allow selecting the same file again
        fileInput.value = '';
    };

    const handleRemoveFile = (index) => {
        files = files.filter((_, i) => i !== index);
        updatePreviews();
        updateFileCount();
    };

    const handlePreviewFile = (index) => {
        const file = files[index];
        if (!file) return;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="relative bg-white rounded-lg max-w-3xl max-h-[90vh] overflow-auto p-4">
                        <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" data-action="close-modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="mt-4">
                            <img src="${e.target.result}" alt="${file.name}" class="max-w-full max-h-[70vh] mx-auto">
                            <p class="mt-2 text-center text-sm text-gray-600">${file.name}</p>
                        </div>
                    </div>
                `;
                
                modal.querySelector('[data-action="close-modal"]').addEventListener('click', () => {
                    modal.remove();
                });
                
                document.body.appendChild(modal);
            };
            
            reader.onerror = () => {
                alert('Error loading image preview.');
            };
            
            reader.readAsDataURL(file);
        } else {
            alert('File preview is only available for images.');
        }
    };

    // Event delegation for preview and remove buttons
    const handlePreviewClick = (e) => {
        if (e.target.classList.contains('remove-file')) {
            const index = parseInt(e.target.getAttribute('data-index'));
            if (!isNaN(index)) {
                handleRemoveFile(index);
            }
        } else if (e.target.classList.contains('remove-existing-document')) {
            e.preventDefault();
            const docId = e.target.getAttribute('data-document-id');
            if (docId) {
                if (window.removeExistingDocument) {
                    window.removeExistingDocument(docId);
                    // Remove from existingFiles array
                    existingFiles = existingFiles.filter(doc => doc.id != docId);
                    updatePreviews();
                    updateFileCount();
                }
            }
        } else if (e.target.classList.contains('preview-file')) {
            const index = parseInt(e.target.getAttribute('data-index'));
            if (!isNaN(index)) {
                handlePreviewFile(index);
            }
        }
    };

    // Drag and drop handlers
    const preventDefaults = (e) => {
        e.preventDefault();
        e.stopPropagation();
    };

    const highlight = () => {
        dropArea.classList.add('border-blue-500', 'bg-blue-50');
    };

    const unhighlight = () => {
        dropArea.classList.remove('border-blue-500', 'bg-blue-50');
    };

    const handleDrop = (e) => {
        preventDefaults(e);
        unhighlight();
        
        const dt = e.dataTransfer;
        const droppedFiles = dt.files;
        
        if (droppedFiles.length) {
            fileInput.files = droppedFiles;
            handleFileSelect({ target: { files: droppedFiles } });
        }
    };

    // Initialize existing documents from the window object
    function initializeExistingDocuments() {
        if (window.existingDocuments && window.existingDocuments.length > 0) {
            existingFiles = [...window.existingDocuments];
            updatePreviews();
            updateFileCount();
        }
    }
    
    // Initialize
    function init() {
        // Event listeners for file input
        fileInput.addEventListener('change', handleFileSelect);
        
        // Drag and drop event listeners
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        dropArea.addEventListener('drop', handleDrop, false);
        dropArea.addEventListener('click', () => fileInput.click());
        
        // Event delegation for preview and remove buttons
        document.addEventListener('click', handlePreviewClick);
        
        // Initialize UI
        updateFileCount();
        
        // Initialize existing documents
        initializeExistingDocuments();
    }

    // Start the application
    init();
})();