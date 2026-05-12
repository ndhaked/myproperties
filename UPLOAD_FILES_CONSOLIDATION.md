# Image Upload Files Consolidation Guide

## Summary

We have consolidated all image upload functionality into a **single generic file**: `dropzone-upload.js`

## Files Status

### ✅ **USE THIS: `dropzone-upload.js`** 
- **Generic and complete** - Handles all image upload scenarios
- **Features included:**
  - Drag & drop functionality
  - File validation (type, size, dimensions)
  - Image preview
  - Upload with progress tracking
  - Modal support
  - Success/error handling
  - Fully configurable

### ❌ **NO LONGER NEEDED:**
- `profile-photo-upload.js` - Duplicate code, can be deleted
- `artist-photo-upload.js` - Duplicate code, can be deleted

## Migration Guide

### Profile Photo Upload Example

Replace any inline code with:

```javascript
DropzoneUpload.init({
    dropzoneSelector: '.profile-photo-dropzone',
    fileInputSelector: '#profile-photo-file-upload',
    previewSelector: '#profile-photo-preview-img',
    uploadUrl: '/profile/upload-photo',
    formDataFieldName: 'files',
    minWidth: 200,
    minHeight: 200,
    maxSize: 5 * 1024 * 1024, // 5MB
    allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
    modalSelector: '#profilePhotoModal',
    saveButtonSelector: '.modal-save-profile-photo',
    cancelButtonSelector: '.modal-cancel-profile-photo',
    closeButtonSelector: '#closeProfilePhotoModal',
    triggerButtonSelector: '#changeProfilePhotoBtn',
    additionalFormData: {
        user_id: document.getElementById('profile-photo-file-upload').dataset.userid || ''
    },
    onUploadSuccess: (response) => {
        // Update main image preview
        const mainImage = document.getElementById('imagePreview');
        if (mainImage && response.s3FullPath) {
            mainImage.src = response.s3FullPath;
        }
        
        // Update hidden input
        const hiddenInput = document.querySelector('#f_profile_photo');
        if (hiddenInput && response.filename) {
            hiddenInput.value = response.filename;
        }
        
        // Reload if needed
        if (response.reload) {
            setTimeout(() => location.reload(), 1000);
        }
    }
});
```

### Artist Photo Upload Example

```javascript
DropzoneUpload.init({
    dropzoneSelector: '.artist-photo-dropzone',
    fileInputSelector: '#file-upload',
    previewSelector: '#artist-photo-preview',
    uploadUrl: '/artist/upload-photo',
    formDataFieldName: 'photo',
    minWidth: 400,
    minHeight: 400,
    maxSize: 5 * 1024 * 1024, // 5MB
    allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
    modalSelector: '#modalOverlay',
    saveButtonSelector: '.modal-save-photo',
    cancelButtonSelector: '.modal-cancel',
    closeButtonSelector: '#closeModal',
    triggerButtonSelector: '#editImageButton',
    onUploadSuccess: (response) => {
        // Update main image
        const mainImage = document.getElementById('main-artist-photo');
        if (mainImage && response.imageUrl) {
            mainImage.src = response.imageUrl;
        }
        
        // Update hidden input
        const hiddenInput = document.querySelector('input[name="artist_photo"]');
        if (hiddenInput && response.filename) {
            hiddenInput.value = response.filename;
        }
        
        // Reload if needed
        if (response.reload) {
            setTimeout(() => location.reload(), 1000);
        }
    }
});
```

## Configuration Options

All options available in `dropzone-upload.js`:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `dropzoneSelector` | string | `.dropzone` | CSS selector for dropzone element |
| `fileInputSelector` | string | `#file-upload` | CSS selector for file input |
| `previewSelector` | string | `null` | CSS selector for preview element |
| `minWidth` | number | `200` | Minimum image width in pixels |
| `minHeight` | number | `200` | Minimum image height in pixels |
| `maxSize` | number | `5242880` | Maximum file size in bytes (5MB) |
| `allowedTypes` | array | `['image/jpeg', 'image/png', 'image/jpg']` | Allowed MIME types |
| `uploadUrl` | string | `null` | Upload endpoint URL (can also use `data-upload-url` on input) |
| `formDataFieldName` | string | `'file'` | FormData field name for file |
| `modalSelector` | string | `null` | CSS selector for modal (optional) |
| `saveButtonSelector` | string | `null` | CSS selector for save/upload button |
| `cancelButtonSelector` | string | `null` | CSS selector for cancel button |
| `closeButtonSelector` | string | `null` | CSS selector for close button |
| `triggerButtonSelector` | string | `null` | CSS selector for button that opens modal |
| `onFileSelect` | function | `null` | Callback when file is selected |
| `onUploadSuccess` | function | `null` | Callback when upload succeeds |
| `onUploadError` | function | `null` | Callback when upload fails |
| `onError` | function | `null` | Callback for validation errors |
| `additionalFormData` | object | `{}` | Additional data to send with upload |
| `dragOverClass` | string | `'drag-over'` | CSS class added on drag over |

## Benefits of Consolidation

1. **Single source of truth** - One file to maintain
2. **No code duplication** - All upload scenarios use same code
3. **Easier to update** - Fix bugs once, works everywhere
4. **More flexible** - Fully configurable for any use case
5. **Smaller bundle size** - Less JavaScript to load

## Next Steps

1. ✅ Enhanced `dropzone-upload.js` with upload functionality
2. ⏳ Update existing pages to use the generic handler
3. ⏳ Delete `profile-photo-upload.js` (after migration)
4. ⏳ Delete `artist-photo-upload.js` (after migration)



