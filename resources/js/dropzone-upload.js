/**
 * Generic Dropzone Upload Handler
 * Complete reusable dropzone functionality for image uploads with drag & drop, preview, validation, and upload
 *
 * Usage Examples:
 *
 * Profile Photo Upload:
 * DropzoneUpload.init({
 *   dropzoneSelector: '.profile-photo-dropzone',
 *   fileInputSelector: '#profile-photo-file-upload',
 *   previewSelector: '#profile-photo-preview-img',
 *   uploadUrl: '/profile/upload-photo',
 *   formDataFieldName: 'files',
 *   minWidth: 200,
 *   minHeight: 200,
 *   maxSize: 5 * 1024 * 1024,
 *   allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
 *   modalSelector: '#profilePhotoModal',
 *   saveButtonSelector: '.modal-save-profile-photo',
 *   onUploadSuccess: function(response) { console.log('Success', response); },
 *   onUploadError: function(message) { console.error('Error', message); }
 * });
 *
 * Artist Photo Upload:
 * DropzoneUpload.init({
 *   dropzoneSelector: '.artist-photo-dropzone',
 *   fileInputSelector: '#file-upload',
 *   previewSelector: '#artist-photo-preview',
 *   uploadUrl: '/artist/upload-photo',
 *   formDataFieldName: 'photo',
 *   minWidth: 400,
 *   minHeight: 400,
 *   maxSize: 5 * 1024 * 1024,
 *   allowedTypes: ['image/jpeg', 'image/png', 'image/jpg']
 * });
 */

(function() {
    'use strict';

    const DropzoneUpload = {
        config: {},
        selectedFile: null,

        /**
         * Initialize dropzone with configuration
         */
        init: function(options) {
            // Merge default config with provided options
            this.config = Object.assign({
                dropzoneSelector: '.dropzone',
                fileInputSelector: '#file-upload',
                previewSelector: null,
                minWidth: 200,
                minHeight: 200,
                maxSize: 5 * 1024 * 1024, // 5MB
                allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
                onFileSelect: null, // Callback when file is selected
                onError: null, // Callback for errors
                dragOverClass: 'drag-over',
                // Upload configuration
                uploadUrl: null, // Will be set from data attribute or config
                formDataFieldName: 'file', // Field name for FormData
                modalSelector: null, // Optional modal selector
                saveButtonSelector: null, // Optional save button selector
                cancelButtonSelector: null, // Optional cancel button selector
                closeButtonSelector: null, // Optional close button selector
                triggerButtonSelector: null, // Button to open modal/trigger upload
                onUploadSuccess: null, // Callback when upload succeeds
                onUploadError: null, // Callback when upload fails
                additionalFormData: {} // Additional form data to send
            }, options);

            const dropzone = document.querySelector(this.config.dropzoneSelector);
            const fileInput = document.querySelector(this.config.fileInputSelector);

            if (!dropzone || !fileInput) {
                console.warn('Dropzone or file input not found', {
                    dropzone: this.config.dropzoneSelector,
                    fileInput: this.config.fileInputSelector
                });
                return;
            }

            // Make dropzone clickable to open file dialog
            // If dropzone is a label with 'for' attribute, it will work automatically
            // Otherwise, add click handler
            if (dropzone.tagName === 'LABEL' && dropzone.getAttribute('for')) {
                // Label with 'for' attribute will automatically trigger the input
                // Just ensure the input ID matches
                if (fileInput.id !== dropzone.getAttribute('for')) {
                    dropzone.setAttribute('for', fileInput.id);
                }
            } else {
                // For non-label dropzones, add click handler
                dropzone.addEventListener('click', function(e) {
                    // Don't trigger if clicking on the file input itself
                    if (e.target !== fileInput && !fileInput.contains(e.target)) {
                        e.preventDefault();
                        e.stopPropagation();
                        fileInput.click();
                    }
                });
            }

            // File input change handler
            fileInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files.length > 0) {
                    this.handleFileSelect(e.target.files[0]);
                }
            });

            // Setup drag and drop
            this.setupDragAndDrop(dropzone, fileInput);

            // Setup upload functionality if URL is provided
            if (this.config.uploadUrl || fileInput.dataset.uploadUrl) {
                this.config.uploadUrl = this.config.uploadUrl || fileInput.dataset.uploadUrl;
                this.setupUpload();
            }

            // Setup modal handlers if modal is configured
            if (this.config.modalSelector) {
                this.setupModal();
            }
        },

        /**
         * Setup drag and drop functionality
         */
        setupDragAndDrop: function(dropzone, fileInput) {
            const dragEvents = ['dragenter', 'dragover', 'dragleave', 'drop'];

            // Prevent default behavior for all drag events
            dragEvents.forEach(eventName => {
                dropzone.addEventListener(eventName, this.preventDefaults, false);
            });

            // Add drag-over class on dragenter/dragover
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add(this.config.dragOverClass);
                }, false);
            });

            // Remove drag-over class on dragleave/drop
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove(this.config.dragOverClass);
                }, false);
            });

            // Handle file drop
            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files && files.length > 0) {
                    // Update file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    fileInput.files = dataTransfer.files;

                    // Trigger change event
                    const changeEvent = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(changeEvent);
                }
            }, false);
        },

        /**
         * Prevent default drag and drop behavior
         */
        preventDefaults: function(e) {
            e.preventDefault();
            e.stopPropagation();
        },

        /**
         * Handle file selection
         */
        handleFileSelect: function(file) {
            // Validate file type
            if (!this.config.allowedTypes.includes(file.type)) {
                const errorMsg = `Please select a valid image file (${this.config.allowedTypes.map(t => t.split('/')[1].toUpperCase()).join(' or ')})`;
                this.showError(errorMsg);
                return;
            }

            // Validate file size
            if (file.size > this.config.maxSize) {
                const maxSizeMB = (this.config.maxSize / (1024 * 1024)).toFixed(0);
                this.showError(`Image size should be less than ${maxSizeMB}MB`);
                return;
            }

            // Validate dimensions
            const img = new Image();
            img.onload = () => {
                if (img.width < this.config.minWidth || img.height < this.config.minHeight) {
                    this.showError(`Image must be at least ${this.config.minWidth}px on the longer side`);
                    return;
                }

                // Show preview
                this.showPreview(file, img);

                // Store selected file
                this.selectedFile = file;

                // Call custom callback if provided
                if (this.config.onFileSelect && typeof this.config.onFileSelect === 'function') {
                    this.config.onFileSelect(file, img);
                }
            };

            img.onerror = () => {
                this.showError('Invalid image file');
            };

            img.src = URL.createObjectURL(file);
        },

        /**
         * Show preview of selected image
         */
        showPreview: function(file, img) {
            if (!this.config.previewSelector) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const previewElement = document.querySelector(this.config.previewSelector);
                if (previewElement) {
                    if (previewElement.tagName === 'IMG') {
                        previewElement.src = e.target.result;
                    } else {
                        previewElement.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                    }
                }
            };
            reader.readAsDataURL(file);
        },

        /**
         * Show error message
         */
        showError: function(message) {
            if (this.config.onError && typeof this.config.onError === 'function') {
                this.config.onError(message);
            } else if (typeof Lobibox !== 'undefined') {
                Lobibox.notify("error", {
                    rounded: false,
                    delay: 3000,
                    delayIndicator: true,
                    position: "top right",
                    msg: message,
                });
            } else {
                alert(message);
            }
        },

        /**
         * Get selected file
         */
        getSelectedFile: function() {
            return this.selectedFile;
        },

        /**
         * Clear selection
         */
        clearSelection: function() {
            this.selectedFile = null;
            const fileInput = document.querySelector(this.config.fileInputSelector);
            if (fileInput) {
                fileInput.value = '';
            }
        },

        /**
         * Setup upload functionality
         */
        setupUpload: function() {
            const saveButton = this.config.saveButtonSelector
                ? document.querySelector(this.config.saveButtonSelector)
                : null;

            if (saveButton) {
                saveButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.uploadFile();
                });
            }
        },

        /**
         * Setup modal functionality
         */
        setupModal: function() {
            const modal = document.querySelector(this.config.modalSelector);
            if (!modal) return;

            // Open modal when trigger button is clicked
            if (this.config.triggerButtonSelector) {
                const triggerButton = document.querySelector(this.config.triggerButtonSelector);
                if (triggerButton) {
                    triggerButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openModal();
                    });
                }
            }

            // Close modal handlers
            if (this.config.closeButtonSelector) {
                const closeButton = document.querySelector(this.config.closeButtonSelector);
                if (closeButton) {
                    closeButton.addEventListener('click', () => this.closeModal());
                }
            }

            if (this.config.cancelButtonSelector) {
                const cancelButton = document.querySelector(this.config.cancelButtonSelector);
                if (cancelButton) {
                    cancelButton.addEventListener('click', () => this.closeModal());
                }
            }

            // Close modal on overlay click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal();
                }
            });
        },

        /**
         * Open modal
         */
        openModal: function() {
            const modal = document.querySelector(this.config.modalSelector);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                this.clearSelection();
            }
        },

        /**
         * Close modal
         */
        closeModal: function() {
            const modal = document.querySelector(this.config.modalSelector);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                this.clearSelection();
            }
        },

        /**
         * Upload file
         */
        uploadFile: function() {
            if (!this.selectedFile) {
                this.showError('Please select an image file first.');
                return;
            }

            if (!this.config.uploadUrl) {
                this.showError('Upload URL is not configured.');
                return;
            }

            const formData = new FormData();
            formData.append(this.config.formDataFieldName, this.selectedFile);
            formData.append('_token', this.getCsrfToken());

            // Add additional form data
            Object.keys(this.config.additionalFormData).forEach(key => {
                formData.append(key, this.config.additionalFormData[key]);
            });

            // Get user ID from file input if available
            const fileInput = document.querySelector(this.config.fileInputSelector);
            if (fileInput && fileInput.dataset.userid) {
                formData.append('user_id', fileInput.dataset.userid);
            }

            const saveButton = this.config.saveButtonSelector
                ? document.querySelector(this.config.saveButtonSelector)
                : null;
            const originalButtonText = saveButton ? saveButton.innerHTML : '';

            // Disable save button and show loading
            if (saveButton) {
                saveButton.disabled = true;
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
            }

            // Show progress if available
            if (typeof Lobibox !== 'undefined') {
                Lobibox.progress({
                    title: 'Please wait',
                    label: 'Uploading image...',
                    progressTpl: '<div class="progress"><div class="progress-bar progress-bar-danger progress-bar-striped lobibox-progress-element myprogress" role="progressbar" style="width:0%">0%</div></div>',
                });
            }

            // Create XMLHttpRequest for upload progress
            const xhr = new XMLHttpRequest();

            // Track upload progress
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.querySelector('.myprogress');
                    if (progressBar) {
                        progressBar.style.width = percentComplete + '%';
                        progressBar.textContent = percentComplete + '%';
                    }
                }
            });

            // Handle response
            xhr.onload = () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        this.handleUploadSuccess(response);
                    } catch (e) {
                        this.handleUploadError('Invalid response from server.');
                    }
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        this.handleUploadError(response.message || 'Upload failed.');
                    } catch (e) {
                        this.handleUploadError('Upload failed. Please try again.');
                    }
                }

                // Re-enable button
                if (saveButton) {
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalButtonText;
                }

                // Close progress
                if (typeof Lobibox !== 'undefined') {
                    const closeBtn = document.querySelector('.lobibox-close');
                    if (closeBtn) closeBtn.click();
                }
            };

            xhr.onerror = () => {
                this.handleUploadError('Network error. Please check your connection and try again.');
                if (saveButton) {
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalButtonText;
                }
                if (typeof Lobibox !== 'undefined') {
                    const closeBtn = document.querySelector('.lobibox-close');
                    if (closeBtn) closeBtn.click();
                }
            };

            // Send request
            xhr.open('POST', this.config.uploadUrl);
            xhr.send(formData);
        },

        /**
         * Handle successful upload
         */
        handleUploadSuccess: function(response) {
            if (this.config.onUploadSuccess && typeof this.config.onUploadSuccess === 'function') {
                this.config.onUploadSuccess(response);
            } else {
                // Default success handler
                if (typeof Lobibox !== 'undefined') {
                    Lobibox.notify('success', {
                        position: 'top right',
                        rounded: false,
                        delay: 3000,
                        delayIndicator: true,
                        msg: response.message || 'Photo uploaded successfully.',
                    });
                }

                // Close modal if open
                if (this.config.modalSelector) {
                    this.closeModal();
                }

                // Reload if specified
                if (response.reload) {
                    setTimeout(() => location.reload(), 1000);
                }
            }
        },

        /**
         * Handle upload error
         */
        handleUploadError: function(message) {
            if (this.config.onUploadError && typeof this.config.onUploadError === 'function') {
                this.config.onUploadError(message);
            } else {
                this.showError(message);
            }
        },

        /**
         * Get CSRF token
         */
        getCsrfToken: function() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }
    };

    // Make it globally available
    window.DropzoneUpload = DropzoneUpload;
})();

