<template>
    <div>
        <div id="app">
            <file-pond
                name="image"
                ref="pond"
                class-name="my-pond"
                allow-multiple="true"
                accepted-file-types="image/jpeg, image/png, image/webp"
                :server="{
                    url: '',
                    process: {
                        url: '/upload',
                        method: 'POST',
                        onload: handleFilePondLoad
                    },
                    revert: handleFilePondRevert,
                    headers: {
                        'X-CSRF-TOKEN': $page.props.csrf_token
                    },
                }"
                v-on:init="handleFilePondInit"
                v-on:addfile="handleFileAdd"
                v-on:removefile="handleFileRemove"
                v-on:processfile="handleFileProcess"
                v-on:error="handleFilePondError"
            />
        </div>

        <!-- Caption Input Section - Positioned right under FilePond -->
        <div v-if="form.tempImages.length > 0" class="row justify-content-center mt-4">
            <div class="col-md-10">
                <!-- Compact Caption Interface -->
                <div class="caption-interface p-3 bg-light border rounded">
                    <!-- Image Selection Info -->
                    <div v-if="!selectedImageId" class="text-center text-muted py-2">
                        <p class="mb-1">
                            <i class="fas fa-mouse-pointer me-2"></i>
                            Click on any image above to add a caption
                        </p>
                        <small>{{ form.tempImages.length }} image{{ form.tempImages.length > 1 ? 's' : '' }} ready for captioning</small>
                    </div>
                    
                    <!-- Selected Image Caption Input -->
                    <div v-else>
                        <div class="mb-2">
                            <small class="text-muted fw-bold">
                                <i class="fas fa-image me-1"></i>
                                Caption for Image {{ getImageIndex(selectedImageId) }} of {{ form.tempImages.length }}
                            </small>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-comment"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Enter a caption for this image..." 
                                v-model="currentCaption"
                                @input="updateCurrentCaption($event.target.value)"
                                maxlength="255"
                            >
                            <button 
                                class="btn btn-outline-secondary" 
                                type="button" 
                                @click="selectedImageId = null; currentCaption = ''"
                                title="Clear selection"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                {{ currentCaption.length }}/255 characters
                            </small>
                            <small class="text-success" v-if="currentCaption.trim()">
                                <i class="fas fa-check me-1"></i>Caption saved
                            </small>
                        </div>
                    </div>
                    
                    <!-- Caption Summary for Multiple Images -->
                    <div v-if="form.tempImages.length > 1" class="mt-2 pt-2 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <small class="text-muted me-2">Caption Status:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <span 
                                    v-for="(imageObj, index) in form.tempImages" 
                                    :key="imageObj.id"
                                    class="badge badge-sm"
                                    :class="imageObj.caption && imageObj.caption.trim() ? 'bg-success' : 'bg-secondary'"
                                    style="cursor: pointer; font-size: 0.7rem;"
                                    @click="selectImageForCaptioning(imageObj.id)"
                                    :title="imageObj.caption ? imageObj.caption : 'No caption'"
                                >
                                    {{ index + 1 }}
                                    <i class="fas fa-check ms-1" v-if="imageObj.caption && imageObj.caption.trim()"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compression Statistics Display -->
        <div v-if="compressionStats.originalSize > 0" class="row justify-content-center mt-2">
            <div class="col-auto">
                <small class="text-muted">
                    Original: {{ formatFileSize(compressionStats.originalSize) }} 
                    <span v-if="compressionStats.compressionRatio > 0">
                        → Compressed: {{ formatFileSize(compressionStats.compressedSize) }} 
                        ({{ compressionStats.compressionRatio }}% saved)
                    </span>
                </small>
            </div>
        </div>

        <!-- Upload Progress Bar -->
        <div v-if="uploadProgress.show" class="row justify-content-center mt-3">
            <div class="col-12">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div 
                        class="progress-bar bg-success" 
                        role="progressbar" 
                        :style="{ width: uploadProgress.percent + '%' }"
                        :aria-valuenow="uploadProgress.percent" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">{{ uploadProgress.message }}</small>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <button @click="uploadPhotos" type="button" class="btn btn-danger w-25" :class="{ 'opacity-25' : form.processing }" :disabled="form.processing || form.tempImages.length === 0">
                <span v-if="form.processing">Uploading...</span>
                <span v-else-if="form.tempImages.length > 0">Upload {{ form.tempImages.length }} Image{{ form.tempImages.length > 1 ? 's' : '' }}</span>
                <span v-else>Upload</span>
            </button>
        </div>
        
        <!-- Upload Instructions -->
        <div class="row justify-content-center mt-2">
            <div class="col-auto">
                <small class="text-muted text-center d-block">
                    <strong>Step 1:</strong> Drop images above for preview and compression<br>
                    <strong>Step 2:</strong> Click on any image to add a caption (optional)<br>
                    <strong>Step 3:</strong> Click "Upload" button to save to your gallery<br>
                    <em>Images compressed to 85% quality, original dimensions preserved • Max: 5MB per image, 50MB total</em>
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    // Import FilePond
    import vueFilePond, { setOptions } from 'vue-filepond';
    import { useForm, router, usePage } from '@inertiajs/vue3';
    import axios from 'axios';

    // Import plugins
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';
    import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
    import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
    import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
    import FilePondPluginImageResize from 'filepond-plugin-image-resize'; // Used for future resizing (currently disabled)
    import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';
    import FilePondPluginImageEdit from 'filepond-plugin-image-edit';

    // Import styles
    import 'filepond/dist/filepond.min.css';
    import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
    import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
 
    // Create FilePond component
    const FilePond = vueFilePond(
        FilePondPluginFileValidateType, 
        FilePondPluginImagePreview,
        FilePondPluginImageTransform,
        FilePondPluginImageCrop,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize, // absent
        FilePondPluginImageResize,
        FilePondPluginImageValidateSize, // absent
        FilePondPluginImageEdit,
    );

    // Set options before creating the components
    setOptions({
        labelIdle: 'Drag & Drop your picture or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 250, // Increased for better preview of actual dimensions
        // imageCropAspectRatio: '1:1', // DISABLED - Show actual image dimensions in preview
        
        // Image Resizing Configuration (DISABLED - keeping for future use)
        // imageResizeTargetWidth: 1200,
        // imageResizeTargetHeight: 1200,
        // imageResizeMode: 'contain', // 'cover', 'contain', 'force'
        // imageResizeUpscale: false, // Don't upscale smaller images
        
        // Image Transform & Compression
        imageTransformOutputQuality: 85, // JPEG quality (0-100)
        imageTransformOutputFormat: 'jpeg', // 'jpeg', 'png', 'webp'
        imageTransformOutputMimeType: 'image/jpeg',
        imageTransformOutputStripImageHead: true, // Remove EXIF data for privacy
        
        // Image Validation
        imageValidateSizeMinWidth: 100,
        imageValidateSizeMinHeight: 100,
        imageValidateSizeMaxWidth: 4000,
        imageValidateSizeMaxHeight: 4000,
        
        // File Size Validation (after compression)
        maxFileSize: '5MB',
        maxTotalFileSize: '50MB',
        
        // UI Styling
        stylePanelLayout: 'null', // compact, circle
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        
        // General Settings
        maxFiles: 10,
        allowReorder: true,
        allowMultiple: true,
        instantUpload: true, // Auto upload to temp storage for preview
        credits: false,
        
        // Labels for better UX
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}',
        labelMaxTotalFileSizeExceeded: 'Maximum total size exceeded',
        labelMaxTotalFileSize: 'Maximum total file size is {filesize}',
        labelFileTypeNotAllowed: 'File type not allowed',
        labelFileProcessing: 'Preparing preview...',
        labelFileProcessingComplete: 'Ready for upload',
        labelFileProcessingAborted: 'Preview cancelled',
        labelFileProcessingRevertError: 'Error removing preview',
        labelFileProcessingAborted: 'Upload cancelled',
        labelFileProcessingError: 'Error during upload',
    });

    export default {
        name: 'app',
        data: function () {
            return { 
                form: useForm({
                    tempImages: [] // Will hold image objects: [{id: "image-abc", caption: "My caption"}, ...]
                }),
                page: usePage(),
                compressionStats: {
                    originalSize: 0,
                    compressedSize: 0,
                    compressionRatio: 0
                },
                uploadProgress: {
                    show: false,
                    percent: 0,
                    message: ''
                },
                selectedImageId: null, // Currently selected image for captioning
                currentCaption: '' // Caption for currently selected image
            };
        },
        methods: {
            handleFilePondInit: function () {
                // console.log('FilePond has initialized');

                // example of instance method call on pond reference
                // this.$refs.pond.getFiles();
                // this.$refs.pond.insertAfter('<input type="text" />');
            },
            uploadPhotos(event) {
                console.log('uploadPhotos function called!');
                
                // Check if we have temporary images to upload
                if (this.form.tempImages.length === 0) {
                    alert('Please select at least one image to upload.');
                    return;
                }

                // Transform object structure to backend format
                const tempImageIds = this.form.tempImages.map(img => img.id);
                const captions = {};
                this.form.tempImages.forEach(img => {
                    captions[img.id] = img.caption || '';
                });

                console.log('=== FRONTEND UPLOAD DEBUG ===');
                console.log('Temporary images to process:', tempImageIds);
                console.log('Captions to send:', captions);
                console.log('Image objects:', this.form.tempImages);
                console.log('Caption keys:', Object.keys(captions));
                console.log('Caption values:', Object.values(captions));
                console.log('Non-empty captions:', Object.entries(captions).filter(([key, value]) => value.trim() !== ''));
                console.log('===========================');
                
                // Debug CSRF token
                const csrfToken = this.page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token being used:', csrfToken?.substring(0, 10) + '...');

                // Show progress bar and start upload
                this.uploadProgress.show = true;
                this.uploadProgress.percent = 0;
                this.uploadProgress.message = 'Preparing upload...';

                // Post with temporary image IDs - ensure CSRF token is included
                this.form
                .transform((data) => ({
                    tempImages: tempImageIds,
                    captions: captions
                }))
                .post(route('photograph.store'), {
                        preserveState: true,
                        preserveScroll: true,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        onStart: () => {
                            this.uploadProgress.percent = 25;
                            const imageCount = this.form.tempImages.length;
                            const captionCount = this.form.tempImages.filter(img => img.caption && img.caption.trim() !== '').length;
                            this.uploadProgress.message = `Uploading ${imageCount} image${imageCount > 1 ? 's' : ''} with ${captionCount} caption${captionCount > 1 ? 's' : ''}...`;
                        },
                        onProgress: (progress) => {
                            // Map Inertia progress (0-1) to percentage (25-90)
                            this.uploadProgress.percent = 25 + Math.round(progress.percentage * 65);
                            this.uploadProgress.message = `Processing images... ${this.uploadProgress.percent}%`;
                        },
                        onSuccess: (page) => {
                            console.log('Upload successful:', page);
                            
                            // Complete progress
                            this.uploadProgress.percent = 100;
                            this.uploadProgress.message = 'Upload completed successfully!';

                            // Update the image state
                            this.$store.dispatch('updateImages', { value: page.props.images });
                            
                            // Clear the FilePond preview after successful upload
                            this.$refs.pond.removeFiles();
                            // Reset the form data
                            this.form.tempImages = [];
                            // Reset caption selection
                            this.selectedImageId = null;
                            this.currentCaption = '';
                            // Clean up all click handlers
                            this.removeAllClickHandlers();
                            // Reset compression statistics
                            this.resetCompressionStats();
                            
                            // Hide progress bar after 2 seconds
                            setTimeout(() => {
                                this.uploadProgress.show = false;
                                this.uploadProgress.percent = 0;
                                this.uploadProgress.message = '';
                            }, 2000);
                        },
                        onError: (errors) => {
                            console.error('Upload failed:', errors);
                            this.uploadProgress.percent = 0;
                            this.uploadProgress.message = 'Upload failed. Please try again.';
                            
                            // Hide progress bar after 3 seconds
                            setTimeout(() => {
                                this.uploadProgress.show = false;
                                this.uploadProgress.percent = 0;
                                this.uploadProgress.message = '';
                            }, 3000);
                        }
                    });
            },
            handleFilePondLoad(response) {
                // The response from /upload is the temporary folder ID
                // Create image object with ID and empty caption
                const imageObject = {
                    id: response,
                    caption: ''
                };
                this.form.tempImages.push(imageObject);
                console.log('File uploaded to temp storage:', response, 'Total images:', this.form.tempImages.length);
                return response;
            },
            async handleFilePondRevert(uniqueId, load, error) {
                // Remove the image object from our array
                this.form.tempImages = this.form.tempImages.filter((imageObj) => imageObj.id !== uniqueId);
                
                // Reset selection if this was the selected image
                if (this.selectedImageId === uniqueId) {
                    this.selectedImageId = null;
                    this.currentCaption = '';
                }
                
                // Get CSRF token for deletion request
                const csrfToken = this.page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                try {
                    // Use Axios for deletion (better for API-style requests)
                    const response = await axios.post(route('deleteimage'), {
                        uniqueId: uniqueId
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    console.log('Temporary file deleted:', uniqueId);
                    // Call load to complete the revert
                    load();
                    
                    // Refresh click handlers after DOM updates
                    this.$nextTick(() => {
                        this.refreshImageClickHandlers();
                    });
                    
                } catch (error) {
                    console.error('Error deleting temp file:', error);
                    // Re-add the image object back if deletion failed
                    const imageObject = {
                        id: uniqueId,
                        caption: ''
                    };
                    this.form.tempImages.push(imageObject);
                    // Call error callback to let FilePond know deletion failed
                    error('Failed to delete temporary file');
                }
            },
            handleFileAdd(error, file) {
                if (error) {
                    console.error('File add error:', error);
                    return;
                }
                
                // Track original file size for compression stats
                this.compressionStats.originalSize += file.fileSize;
                console.log(`Added file: ${file.filename} (${this.formatFileSize(file.fileSize)})`);
            },
            handleFileRemove(error, file) {
                if (error) {
                    console.error('File remove error:', error);
                    return;
                }
                
                console.log('File removed from FilePond:', file.filename);
                
                // Refresh click handlers after DOM updates to ensure sync
                this.$nextTick(() => {
                    this.refreshImageClickHandlers();
                });
            },
            handleFileProcess(error, file) {
                if (error) {
                    console.error('File process error:', error);
                    return;
                }
                
                // File has been processed and uploaded to temp storage
                console.log(`File processed: ${file.filename}`);
                
                // Refresh click handlers to ensure proper synchronization
                this.$nextTick(() => {
                    this.refreshImageClickHandlers();
                });
            },
            handleFilePondError(error, file, status) {
                console.error('FilePond error:', error, status);
                
                // Provide user-friendly error messages
                if (status.main === 'File too large') {
                    alert('The selected image is too large. Please choose an image smaller than 5MB.');
                } else if (status.main === 'Invalid file type') {
                    alert('Please select a valid image file (JPEG, PNG, or WebP).');
                } else {
                    alert(`Upload error: ${status.main}`);
                }
            },
            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },
            resetCompressionStats() {
                this.compressionStats = {
                    originalSize: 0,
                    compressedSize: 0,
                    compressionRatio: 0
                };
            },
            selectImageForCaptioning(imageId) {
                // Save current caption if there was a previously selected image
                if (this.selectedImageId && this.currentCaption !== this.getCurrentImageCaption(this.selectedImageId)) {
                    this.updateImageCaption(this.selectedImageId, this.currentCaption);
                }
                
                // Select the new image and load its caption
                this.selectedImageId = imageId;
                this.currentCaption = this.getCurrentImageCaption(imageId);
                
                // Update visual selection
                this.$nextTick(() => {
                    this.updateImageSelection();
                });
                
                console.log('Selected image for captioning:', imageId, 'Caption:', this.currentCaption);
            },
            updateCurrentCaption(caption) {
                this.currentCaption = caption;
                if (this.selectedImageId) {
                    this.updateImageCaption(this.selectedImageId, caption);
                }
            },
            getCurrentImageCaption(imageId) {
                const imageObj = this.form.tempImages.find(img => img.id === imageId);
                return imageObj ? imageObj.caption : '';
            },
            updateImageCaption(imageId, caption) {
                const imageObj = this.form.tempImages.find(img => img.id === imageId);
                if (imageObj) {
                    imageObj.caption = caption;
                }
            },
            getImageIndex(imageId) {
                const index = this.form.tempImages.findIndex(img => img.id === imageId);
                return index !== -1 ? index + 1 : 0;
            },
            addImageClickHandlers() {
                // Add click handlers to FilePond image previews
                const filePondItems = document.querySelectorAll('.filepond--item');
                const filePondFiles = this.$refs.pond ? this.$refs.pond.getFiles() : [];
                
                console.log('Adding click handlers:', {
                    domItemsCount: filePondItems.length,
                    filePondFilesCount: filePondFiles.length,
                    tempImagesCount: this.form.tempImages.length
                });
                
                filePondItems.forEach((item, domIndex) => {
                    // Remove existing click handlers to avoid duplicates
                    const existingHandler = item.getAttribute('data-caption-handler');
                    if (existingHandler) return;
                    
                    // Mark as having a handler
                    item.setAttribute('data-caption-handler', 'true');
                    
                    // Add visual styling for clickable state
                    item.style.cursor = 'pointer';
                    item.style.transition = 'all 0.2s ease';
                    
                    const imagePreview = item.querySelector('.filepond--image-preview');
                    if (imagePreview) {
                        imagePreview.style.border = '2px solid transparent';
                        imagePreview.style.borderRadius = '6px';
                        imagePreview.style.transition = 'all 0.2s ease';
                    }
                    
                    // Store the server ID directly on the DOM element for reliable identification
                    const filePondFile = filePondFiles[domIndex];
                    if (filePondFile && filePondFile.serverId) {
                        item.setAttribute('data-server-id', filePondFile.serverId);
                    }
                    
                    // Add click event listener
                    const clickHandler = (e) => {
                        // Prevent FilePond's default handling
                        e.stopPropagation();
                        
                        // Find the matching FilePond file by comparing DOM element properties
                        const currentFiles = this.$refs.pond ? this.$refs.pond.getFiles() : [];
                        
                        // Try to find file by checking if the clicked element belongs to this file
                        let matchedFile = null;
                        let actualIndex = -1;
                        
                        // Method 1: Check by filename in DOM (most reliable)
                        const filenameElement = item.querySelector('.filepond--file-info-main');
                        if (filenameElement && filenameElement.textContent) {
                            const displayedFilename = filenameElement.textContent.trim();
                            matchedFile = currentFiles.find((file, index) => {
                                const match = file.filename === displayedFilename || file.file.name === displayedFilename;
                                if (match) actualIndex = index;
                                return match;
                            });
                        }
                        
                        // Method 2: Fallback to DOM index if filename matching fails
                        if (!matchedFile && domIndex < currentFiles.length) {
                            matchedFile = currentFiles[domIndex];
                            actualIndex = domIndex;
                        }
                        
                        if (!matchedFile || !matchedFile.serverId) {
                            console.warn('No valid FilePond file found for clicked item:', {
                                domIndex: domIndex,
                                actualIndex: actualIndex,
                                currentFilesCount: currentFiles.length,
                                displayedFilename: filenameElement ? filenameElement.textContent : 'unknown',
                                availableFiles: currentFiles.map(f => f.filename),
                                availableServerIds: currentFiles.map(f => f.serverId)
                            });
                            return;
                        }
                        
                        console.log('Image clicked (reliable matching):', {
                            serverId: matchedFile.serverId,
                            domIndex: domIndex,
                            actualIndex: actualIndex,
                            filename: matchedFile.filename,
                            currentSelection: this.selectedImageId,
                            matchMethod: filenameElement ? 'filename' : 'index'
                        });
                        
                        // Select image using server ID
                        this.selectImageForCaptioning(matchedFile.serverId);
                        this.updateImageSelection();
                    };
                    
                    item.addEventListener('click', clickHandler);
                    
                    // Store handler reference for cleanup
                    item._captionClickHandler = clickHandler;
                });
            },
            refreshImageClickHandlers() {
                // Clean up all existing click handlers first
                this.removeAllClickHandlers();
                
                // Wait longer for DOM to stabilize (FilePond needs more time for deletions)
                setTimeout(() => {
                    this.addImageClickHandlers();
                    // Update visual selection state
                    this.updateImageSelection();
                }, 300); // Increased from 100ms to 300ms
            },
            removeAllClickHandlers() {
                // Remove all existing click handlers and styling
                const filePondItems = document.querySelectorAll('.filepond--item');
                
                filePondItems.forEach((item) => {
                    // Remove click handler if it exists
                    if (item._captionClickHandler) {
                        item.removeEventListener('click', item._captionClickHandler);
                        delete item._captionClickHandler;
                    }
                    
                    // Remove our custom attributes and styles
                    item.removeAttribute('data-caption-handler');
                    item.removeAttribute('data-server-id'); // Clean up server ID too
                    item.style.cursor = '';
                    item.style.transition = '';
                    
                    // Reset image preview styling
                    const imagePreview = item.querySelector('.filepond--image-preview');
                    if (imagePreview) {
                        imagePreview.style.border = '';
                        imagePreview.style.borderRadius = '';
                        imagePreview.style.transition = '';
                        imagePreview.style.boxShadow = '';
                    }
                });
            },
            updateImageSelection() {
                // Update visual selection state of all images
                const filePondItems = document.querySelectorAll('.filepond--item');
                const currentFiles = this.$refs.pond ? this.$refs.pond.getFiles() : [];
                
                filePondItems.forEach((item, domIndex) => {
                    const imagePreview = item.querySelector('.filepond--image-preview');
                    if (!imagePreview) return;
                    
                    // Find the matching FilePond file using same reliable method as click handler
                    let matchedFile = null;
                    
                    // Method 1: Check by filename in DOM (most reliable)
                    const filenameElement = item.querySelector('.filepond--file-info-main');
                    if (filenameElement && filenameElement.textContent) {
                        const displayedFilename = filenameElement.textContent.trim();
                        matchedFile = currentFiles.find((file) => {
                            return file.filename === displayedFilename || file.file.name === displayedFilename;
                        });
                    }
                    
                    // Method 2: Fallback to DOM index if filename matching fails
                    if (!matchedFile && domIndex < currentFiles.length) {
                        matchedFile = currentFiles[domIndex];
                    }
                    
                    if (!matchedFile || !matchedFile.serverId) {
                        // Reset styling if no valid file found
                        imagePreview.style.border = '2px solid transparent';
                        imagePreview.style.boxShadow = 'none';
                        return;
                    }
                    
                    // Check if this file is currently selected
                    if (matchedFile.serverId === this.selectedImageId) {
                        // Selected state
                        imagePreview.style.border = '2px solid #007bff';
                        imagePreview.style.boxShadow = '0 0 0 2px rgba(0, 123, 255, 0.25)';
                        console.log('Visual selection applied to:', matchedFile.serverId);
                    } else {
                        // Unselected state
                        imagePreview.style.border = '2px solid transparent';
                        imagePreview.style.boxShadow = 'none';
                    }
                });
            }
        },
        components: {
            FilePond,
        },
    };
</script>

<style scoped>
/* FilePond Vertical Layout with 400px Height */
:deep(.filepond--root) {
    font-family: inherit;
    min-height: 200px; /* Ensure minimum height for visibility */
    max-height: 400px; /* Increased height to 400px */
    overflow: hidden;
}

:deep(.filepond--list) {
    min-height: 200px !important; /* Ensure minimum height for single image visibility */
    max-height: 400px !important;
    overflow-x: hidden !important;   /* No horizontal scroll */
    overflow-y: auto !important;     /* Vertical scroll for overflow */
    padding: 5px !important;
    scrollbar-width: thin !important;
    scrollbar-color: #007bff #f1f1f1 !important;
}

/* Custom vertical scrollbar styling */
:deep(.filepond--list::-webkit-scrollbar) {
    width: 6px !important;
}

:deep(.filepond--list::-webkit-scrollbar-track) {
    background: #f1f1f1 !important;
    border-radius: 3px !important;
}

:deep(.filepond--list::-webkit-scrollbar-thumb) {
    background: #007bff !important;
    border-radius: 3px !important;
}

:deep(.filepond--list::-webkit-scrollbar-thumb:hover) {
    background: #0056b3 !important;
}

/* Ensure image items are properly sized and visible */
:deep(.filepond--item) {
    min-height: 180px !important; /* Minimum height for single image visibility */
    margin-bottom: 0.5rem !important;
}

/* Ensure image preview is properly sized */
:deep(.filepond--image-preview-wrapper) {
    min-height: 150px !important;
}

/* When no files are present, show the drop area properly */
:deep(.filepond--drop-label) {
    min-height: 180px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Caption section animations and spacing */
.caption-interface {
    animation: slideInUp 0.3s ease-out;
    transition: all 0.2s ease;
    margin-top: 1rem;
    clear: both;
    position: relative;
    z-index: 1;
}

.caption-interface:hover {
    border-color: #007bff !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge hover effects */
.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Input group styling */
.input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 6px;
    overflow: hidden;
}

.input-group .form-control:focus {
    box-shadow: none;
    border-color: #007bff;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .col-md-8 {
        padding: 0 10px;
    }
    
    .card-body {
        padding: 1rem 0.75rem;
    }
    
    .badge {
        font-size: 0.7rem;
        margin: 2px;
    }
}
</style>