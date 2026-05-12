/**
 * Profile Photo Upload with Dropzone Functionality
 * Handles image preview, drag & drop, and upload
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        minWidth: 200,
        minHeight: 200,
        maxSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
        uploadUrl: null, // Will be set from data attribute
        previewSelector: '#profile-photo-preview',
        fileInputSelector: '#profile-photo-file-upload',
        dropzoneSelector: '.profile-photo-dropzone',
        modalSelector: '#profilePhotoModal',
        saveButtonSelector: '.modal-save-profile-photo',
        cancelButtonSelector: '.modal-cancel-profile-photo',
        closeButtonSelector: '#closeProfilePhotoModal',
        changePhotoButtonSelector: '#changeProfilePhotoBtn'
    };

    let selectedFile = null;
    let previewImageUrl = null;

    /**
     * Initialize the profile photo upload functionality
     */
    function init() {
        const fileInput = document.querySelector(config.fileInputSelector);
        const dropzone = document.querySelector(config.dropzoneSelector);
        const modal = document.querySelector(config.modalSelector);
        const changePhotoBtn = document.querySelector(config.changePhotoButtonSelector);
        const saveButton = document.querySelector(config.saveButtonSelector);
        const cancelButton = document.querySelector(config.cancelButtonSelector);
        const closeButton = document.querySelector(config.closeButtonSelector);

        if (!fileInput || !dropzone || !modal) {
            console.warn('Profile photo upload elements not found');
            return;
        }

        // Get upload URL from data attribute or set default
        config.uploadUrl = fileInput.dataset.uploadUrl || '/profile/upload-photo';

        // Open modal when change photo button is clicked
        if (changePhotoBtn) {
            changePhotoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal();
            });
        }

        // Close modal handlers
        if (cancelButton) {
            cancelButton.addEventListener('click', closeModal);
        }
        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }

        // Close modal on overlay click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop handlers
        setupDragAndDrop(dropzone, fileInput);

        // Save button handler
        if (saveButton) {
            saveButton.addEventListener('click', function(e) {
                e.preventDefault();
                uploadPhoto();
            });
        }
    }

    /**
     * Open the modal
     */
    function openModal() {
        const modal = document.querySelector(config.modalSelector);
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // Reset state
            resetUploadState();
        }
    }

    /**
     * Close the modal
     */
    function closeModal() {
        const modal = document.querySelector(config.modalSelector);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            // Reset state
            resetUploadState();
        }
    }

    /**
     * Reset upload state
     */
    function resetUploadState() {
        selectedFile = null;
        previewImageUrl = null;
        const fileInput = document.querySelector(config.fileInputSelector);
        if (fileInput) {
            fileInput.value = '';
        }
        // Reset preview to original image
        resetPreview();
    }

    /**
     * Setup drag and drop functionality
     */
    function setupDragAndDrop(dropzone, fileInput) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
                // Update file input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
            }
        }, false);

        // Click to select file
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
    }

    /**
     * Prevent default drag behaviors
     */
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    /**
     * Highlight dropzone
     */
    function highlight(e) {
        const dropzone = document.querySelector(config.dropzoneSelector);
        if (dropzone) {
            dropzone.classList.add('drag-over');
        }
    }

    /**
     * Unhighlight dropzone
     */
    function unhighlight(e) {
        const dropzone = document.querySelector(config.dropzoneSelector);
        if (dropzone) {
            dropzone.classList.remove('drag-over');
        }
    }

    /**
     * Handle file selection
     */
    function handleFileSelect(file) {
        if (!file) {
            return;
        }

        // Validate file type
        if (!config.allowedTypes.includes(file.type)) {
            showError('Please select a valid image file (JPG or PNG only).');
            return;
        }

        // Validate file size
        if (file.size > config.maxSize) {
            showError('File size must be less than 5MB.');
            return;
        }

        // Validate image dimensions
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const width = img.width;
                const height = img.height;
                const longerSide = Math.max(width, height);

                if (longerSide < config.minWidth) {
                    showError(`Image resolution must be at least ${config.minWidth}px on the longer side. Current: ${longerSide}px`);
                    return;
                }

                // File is valid, show preview
                selectedFile = file;
                previewImageUrl = e.target.result;
                showPreview(previewImageUrl);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show image preview
     */
    function showPreview(imageUrl) {
        const preview = document.querySelector(config.previewSelector);
        if (preview) {
            const img = preview.querySelector('#profile-photo-preview-img') || preview.querySelector('img');
            if (img) {
                img.src = imageUrl;
                img.style.display = 'block';
            } else {
                // Create img element if it doesn't exist
                const container = preview.querySelector('#profile-photo-preview-container') || preview.querySelector('div');
                if (container) {
                    const newImg = document.createElement('img');
                    newImg.id = 'profile-photo-preview-img';
                    newImg.src = imageUrl;
                    newImg.className = 'w-[210px] h-[210px] rounded-full object-cover';
                    newImg.alt = 'Profile photo preview';
                    container.appendChild(newImg);
                }
            }
        }
    }

    /**
     * Reset preview to original image
     */
    function resetPreview() {
        const preview = document.querySelector(config.previewSelector);
        if (preview) {
            const img = preview.querySelector('#profile-photo-preview-img') || preview.querySelector('img');
            if (img && img.dataset.originalSrc) {
                img.src = img.dataset.originalSrc;
            }
        }
    }

    /**
     * Upload the photo
     */
    function uploadPhoto() {
        if (!selectedFile) {
            showError('Please select an image file first.');
            return;
        }

        const formData = new FormData();
        formData.append('files', selectedFile);
        formData.append('_token', getCsrfToken());
        formData.append('user_id', document.querySelector(config.fileInputSelector).dataset.userid || '');

        const saveButton = document.querySelector(config.saveButtonSelector);
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
        xhr.upload.addEventListener('progress', function(e) {
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
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    handleUploadSuccess(response);
                } catch (e) {
                    handleUploadError('Invalid response from server.');
                }
            } else {
                try {
                    const response = JSON.parse(xhr.responseText);
                    handleUploadError(response.message || 'Upload failed.');
                } catch (e) {
                    handleUploadError('Upload failed. Please try again.');
                }
            }

            // Re-enable button
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.innerHTML = originalButtonText;
            }

            // Close progress
            if (typeof Lobibox !== 'undefined') {
                $('.lobibox-close').click();
            }
        };

        xhr.onerror = function() {
            handleUploadError('Network error. Please check your connection and try again.');
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.innerHTML = originalButtonText;
            }
            if (typeof Lobibox !== 'undefined') {
                $('.lobibox-close').click();
            }
        };

        // Send request
        xhr.open('POST', config.uploadUrl);
        xhr.send(formData);
    }

    /**
     * Handle successful upload
     */
    function handleUploadSuccess(response) {
        if (typeof Lobibox !== 'undefined') {
            Lobibox.notify('success', {
                position: 'top right',
                rounded: false,
                delay: 3000,
                delayIndicator: true,
                msg: response.message || 'Photo uploaded successfully.',
            });
        }

        // Update main image preview if exists
        const mainImage = document.getElementById('imagePreview') || document.querySelector('.relative.w-16.h-16.rounded-full img');
        if (mainImage && response.s3FullPath) {
            if (mainImage.tagName === 'IMG') {
                mainImage.src = response.s3FullPath;
            } else {
                // If it's a div, replace with img
                const parent = mainImage.parentElement;
                const newImg = document.createElement('img');
                newImg.id = 'imagePreview';
                newImg.src = response.s3FullPath;
                newImg.className = 'w-full h-full object-cover';
                newImg.alt = 'Profile Photo';
                parent.replaceChild(newImg, mainImage);
            }
        }

        // Update hidden input if exists
        const hiddenInput = document.querySelector('#f_profile_photo');
        if (hiddenInput && response.filename) {
            hiddenInput.value = response.filename;
        }

        // Show delete button if it doesn't exist
        const deleteBtn = document.getElementById('deletePhotoBtn');
        if (!deleteBtn && response.filename) {
            const photoContainer = document.querySelector('.flex.items-center.gap-3.relative.flex-1.grow');
            if (photoContainer) {
                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.id = 'deletePhotoBtn';
                deleteButton.className = 'all-[unset] box-border inline-flex items-center justify-center gap-2 relative flex-[0_0_auto] rounded-[999px] cursor-pointer';
                deleteButton.innerHTML = '<span class="relative flex items-center justify-center w-fit mt-[-1.00px] [font-family:\'BR_Hendrix-Medium\',Helvetica] font-medium text-[#1c1b1b] text-sm text-center tracking-[0] leading-5 underline whitespace-nowrap">Delete Photo</span>';
                photoContainer.appendChild(deleteButton);
            }
        }

        // Close modal
        closeModal();

        // Reload if specified
        if (response.reload) {
            setTimeout(function() {
                location.reload();
            }, 1000);
        }
    }

    /**
     * Handle upload error
     */
    function handleUploadError(message) {
        if (typeof Lobibox !== 'undefined') {
            Lobibox.notify('error', {
                position: 'top right',
                rounded: false,
                delay: 5000,
                delayIndicator: true,
                msg: message,
            });
        } else {
            alert(message);
        }
    }

    /**
     * Show error message
     */
    function showError(message) {
        if (typeof Lobibox !== 'undefined') {
            Lobibox.notify('error', {
                position: 'top right',
                rounded: false,
                delay: 5000,
                delayIndicator: true,
                msg: message,
            });
        } else {
            alert(message);
        }
    }

    /**
     * Get CSRF token
     */
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

