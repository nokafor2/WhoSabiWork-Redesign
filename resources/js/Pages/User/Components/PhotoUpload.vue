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
            <!-- <button @click="console.log('Test button clicked!')" type="button" class="btn btn-secondary w-25 ms-2">Test</button> -->
        </div>
        
        <!-- Upload Instructions -->
        <div class="row justify-content-center mt-2">
            <div class="col-auto">
                <small class="text-muted text-center d-block">
                    <strong>Step 1:</strong> Drop images above for preview and compression<br>
                    <strong>Step 2:</strong> Click "Upload" button to save to your gallery<br>
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
                    tempImages: [] // Will hold temporary image IDs for upload
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
                }
            };
        },
        methods: {
            handleFilePondInit: function () {
                console.log('FilePond has initialized');

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

                console.log('Temporary images to process:', this.form.tempImages);
                console.log('Form data being sent:', this.form);
                
                // Debug CSRF token
                const csrfToken = this.page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token being used:', csrfToken?.substring(0, 10) + '...');

                // Show progress bar and start upload
                this.uploadProgress.show = true;
                this.uploadProgress.percent = 0;
                this.uploadProgress.message = 'Preparing upload...';

                // Post with temporary image IDs - ensure CSRF token is included
                this.form.post(route('photograph.store'), {
                        preserveState: true,
                        preserveScroll: true,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        onStart: () => {
                            this.uploadProgress.percent = 25;
                            this.uploadProgress.message = `Uploading ${this.form.tempImages.length} image${this.form.tempImages.length > 1 ? 's' : ''}...`;
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
                            
                            // Clear the FilePond preview after successful upload
                            this.$refs.pond.removeFiles();
                            // Reset the form data
                            this.form.tempImages = [];
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
                this.form.tempImages.push(response);
                console.log('File uploaded to temp storage:', response);
                return response;
            },
            async handleFilePondRevert(uniqueId, load, error) {
                // Remove the temporary file from server and our array
                this.form.tempImages = this.form.tempImages.filter((id) => id !== uniqueId);
                
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
                    
                } catch (error) {
                    console.error('Error deleting temp file:', error);
                    // Re-add the image ID back if deletion failed
                    this.form.tempImages.push(uniqueId);
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
            handleFileProcess(error, file) {
                if (error) {
                    console.error('File process error:', error);
                    return;
                }
                
                // File has been processed and uploaded to temp storage
                console.log(`File processed: ${file.filename}`);
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
            }
        },
        components: {
            FilePond,
        },
    };
</script>