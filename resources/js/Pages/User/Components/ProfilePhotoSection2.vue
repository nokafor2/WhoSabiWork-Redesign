<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Profile Photo:</dt>
                    <dd class="col-sm-9 mb-0">
                        <span v-if="isEditing" class="badge bg-info">Editing</span>
                        <span v-else class="text-muted">Click to upload and edit</span>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">
                
                <!-- Current Profile Photo Display -->
                <div class="d-flex align-items-center mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle border me-3" 
                             style="height: 120px; width: 120px; object-fit: cover;" 
                             :src="currentProfilePhoto || imagePath(0)"
                             alt="Current Profile Photo">
                        <div class="position-absolute bottom-0 end-0 me-3">
                            <span class="badge bg-primary rounded-pill">
                                <i class="fas fa-camera"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1">Profile Photo</h6>
                        <small class="text-muted">
                            Upload and crop your profile picture.<br>
                            Best results: Square images, 400x400px or larger
                        </small>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-upload"></i> Select New Photo
                    </label>
                    <input 
                        type="file" 
                        class="form-control" 
                        @change="onFileChange" 
                        accept="image/*"
                        :disabled="isUploading"
                        ref="fileInput"
                    >
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i>
                        Supported: JPG, PNG, GIF, WebP • Max size: 5MB
                    </div>
                </div>

                <!-- Vue-Cropper Section -->
                <div v-if="imageSrc && isEditing" class="cropper-section">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-crop"></i> Edit Your Photo
                            </h6>
                            <small class="text-muted">
                                Drag to reposition • Scroll to zoom • Use controls below
                            </small>
                        </div>
                        <div class="card-body p-0">
                            <!-- Cropper Container -->
                            <div class="cropper-container" :key="cropperKey">
                                <VueCropperjs
                                    ref="cropper"
                                    :src="imageSrc"
                                    alt="Source Image"
                                    :containerStyle="{ 
                                        width: '100%', 
                                        height: '400px',
                                        position: 'relative',
                                        overflow: 'hidden'
                                    }"
                                    :options="{
                                        aspectRatio: 1,
                                        autoCropArea: 0.8,
                                        viewMode: 1,
                                        dragMode: 'move',
                                        scalable: true,
                                        zoomable: true,
                                        rotatable: true,
                                        movable: true,
                                        cropBoxMovable: true,
                                        cropBoxResizable: false,
                                        toggleDragModeOnDblclick: false,
                                        guides: true,
                                        center: true,
                                        highlight: true,
                                        background: true,
                                        responsive: true,
                                        restore: true,
                                        checkCrossOrigin: true,
                                        checkOrientation: true,
                                        modal: true,
                                        zoomOnTouch: true,
                                        zoomOnWheel: true,
                                        wheelZoomRatio: 0.1
                                    }"
                                    @ready="onCropperReady"
                                    @zoom="onCropperZoom"
                                    @crop="onCropperCrop"
                                />
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Crop Controls -->
                            <div class="row g-2 mb-3">
                                <!-- Rotation Controls -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Rotation</label>
                                    <div class="btn-group w-100" role="group">
                                        <button @click="rotateLeft" class="btn btn-outline-primary btn-sm" :disabled="!cropperReady">
                                            <i class="fas fa-undo"></i> 90° Left
                                        </button>
                                        <button @click="rotateRight" class="btn btn-outline-primary btn-sm" :disabled="!cropperReady">
                                            <i class="fas fa-redo"></i> 90° Right
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Zoom Controls -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Zoom</label>
                                    <div class="btn-group w-100" role="group">
                                        <button @click="zoomOut" class="btn btn-outline-secondary btn-sm" :disabled="!cropperReady">
                                            <i class="fas fa-search-minus"></i> Out
                                        </button>
                                        <button @click="zoomIn" class="btn btn-outline-secondary btn-sm" :disabled="!cropperReady">
                                            <i class="fas fa-search-plus"></i> In
                                        </button>
                                        <button @click="resetCrop" class="btn btn-outline-warning btn-sm" :disabled="!cropperReady">
                                            <i class="fas fa-refresh"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Zoom Slider -->
                            <div class="mb-3">
                                <label class="form-label small">Fine Zoom Control</label>
                                <input 
                                    type="range" 
                                    class="form-range" 
                                    :min="minZoom" 
                                    :max="maxZoom" 
                                    step="0.1" 
                                    v-model="zoomLevel"
                                    @input="applyZoom"
                                    :disabled="!cropperReady"
                                >
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">50%</small>
                                    <small class="text-primary fw-bold">{{ Math.round(zoomLevel * 100) }}%</small>
                                    <small class="text-muted">300%</small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button @click="cancelEdit" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button @click="previewCrop" class="btn btn-info" :disabled="!cropperReady">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button @click="cropAndUpload" class="btn btn-success" :disabled="!cropperReady || isUploading">
                                    <i class="fas fa-crop"></i> 
                                    {{ isUploading ? 'Updating...' : 'Update Profile' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crop Preview Section -->
                <div v-if="croppedPreview" class="preview-section mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-eye"></i> Crop Preview
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <img 
                                :src="croppedPreview" 
                                class="rounded-circle border shadow-sm" 
                                style="width: 150px; height: 150px; object-fit: cover;"
                                alt="Cropped Preview"
                            >
                            <div class="mt-2">
                                <small class="text-muted">This is how your profile photo will look</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Progress -->
                <div v-if="uploadProgress.show" class="mt-3">
                    <div class="d-flex align-items-center">
                        <div class="progress flex-grow-1 me-3" style="height: 6px;">
                            <div 
                                class="progress-bar bg-success" 
                                role="progressbar" 
                                :style="{ width: uploadProgress.percent + '%' }"
                                :aria-valuenow="uploadProgress.percent" 
                                aria-valuemin="0" 
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="text-muted">{{ uploadProgress.message }}</small>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="showSuccess" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <strong>Success!</strong> Your profile photo has been updated.
                    <button type="button" class="btn-close" @click="showSuccess = false"></button>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error!</strong> {{ errorMessage }}
                    <button type="button" class="btn-close" @click="errorMessage = ''"></button>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
import { useForm, usePage } from '@inertiajs/vue3';
import VueCropperjs from 'vue-cropperjs';

export default {
    components: {
        VueCropperjs,
    },
    props: {
        user: {
            type: Object,
            default: () => ({})
        }
    },
    emits: ['photoUpdated'],
    data() {
        return {
            // Image data
            adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            
            // Cropper state
            imageSrc: '',
            croppedPreview: '',
            currentProfilePhoto: null,
            isEditing: false,
            isUploading: false,
            cropperReady: false,
            cropperKey: 0, // Force re-render when needed
            
            // Cropper configuration
            zoomLevel: 1,
            minZoom: 0.5,
            maxZoom: 3,
            
            // UI state
            uploadProgress: {
                show: false,
                percent: 0,
                message: ''
            },
            showSuccess: false,
            errorMessage: '',
            
            // Form for upload
            form: useForm({
                croppedImage: null,
                photoType: 'profile'
            }),
            
            page: usePage()
        }
    },
    computed: {
        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }
    },
    methods: {
        imagePath(index) {
            const imageName = this.adImages[index];
            const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;
            return imageUrl;
        },
        
        onFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                this.errorMessage = 'Please select a valid image file.';
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                this.errorMessage = 'File size must be less than 5MB.';
                return;
            }
            
            // Clear any previous errors
            this.errorMessage = '';
            this.showSuccess = false;
            this.croppedPreview = '';
            
            // Read file as base64
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.isEditing = true;
                this.zoomLevel = 1;
                
                // Reset cropper after image loads and force re-render
                this.cropperKey += 1;
                this.cropperReady = false;
                
                this.$nextTick(() => {
                    // Wait a bit longer for cropper to fully initialize
                    setTimeout(() => {
                        if (this.cropperReady) {
                            this.resetCrop();
                        }
                    }, 500);
                });
            };
            reader.onerror = () => {
                this.errorMessage = 'Failed to read the selected file.';
            };
            reader.readAsDataURL(file);
        },
        
        onCropperReady() {
            console.log('Cropper is ready');
            this.cropperReady = true;
            
            // Initialize cropper with proper settings
            this.$nextTick(() => {
                if (this.$refs.cropper && this.$refs.cropper.cropper) {
                    // Get initial zoom level
                    const imageData = this.$refs.cropper.cropper.getImageData();
                    if (imageData) {
                        this.zoomLevel = imageData.ratio || 1;
                    }
                }
            });
        },
        
        onCropperZoom(event) {
            // Update zoom level when cropper zoom changes
            if (event && event.detail && event.detail.ratio) {
                this.zoomLevel = parseFloat(event.detail.ratio.toFixed(2));
            }
        },
        
        onCropperCrop(event) {
            // Handle crop events if needed
            console.log('Crop event:', event);
        },
        
        // Rotation methods
        rotateLeft() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                console.warn('Cropper not ready for rotation');
                return;
            }
            
            try {
                this.$refs.cropper.cropper.rotate(-90);
                console.log('Rotated left 90 degrees');
            } catch (error) {
                console.error('Error rotating left:', error);
                this.errorMessage = 'Failed to rotate image. Please try again.';
            }
        },
        
        rotateRight() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                console.warn('Cropper not ready for rotation');
                return;
            }
            
            try {
                this.$refs.cropper.cropper.rotate(90);
                console.log('Rotated right 90 degrees');
            } catch (error) {
                console.error('Error rotating right:', error);
                this.errorMessage = 'Failed to rotate image. Please try again.';
            }
        },
        
        // Zoom methods
        zoomIn() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                console.warn('Cropper not ready for zoom');
                return;
            }
            
            try {
                const currentZoom = this.zoomLevel;
                const newZoom = Math.min(currentZoom + 0.1, this.maxZoom);
                this.$refs.cropper.cropper.zoomTo(newZoom);
                this.zoomLevel = newZoom;
                console.log(`Zoomed in to ${newZoom}`);
            } catch (error) {
                console.error('Error zooming in:', error);
                this.errorMessage = 'Failed to zoom in. Please try again.';
            }
        },
        
        zoomOut() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                console.warn('Cropper not ready for zoom');
                return;
            }
            
            try {
                const currentZoom = this.zoomLevel;
                const newZoom = Math.max(currentZoom - 0.1, this.minZoom);
                this.$refs.cropper.cropper.zoomTo(newZoom);
                this.zoomLevel = newZoom;
                console.log(`Zoomed out to ${newZoom}`);
            } catch (error) {
                console.error('Error zooming out:', error);
                this.errorMessage = 'Failed to zoom out. Please try again.';
            }
        },
        
        applyZoom() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                return;
            }
            
            try {
                // Clamp zoom level to valid range
                const clampedZoom = Math.max(this.minZoom, Math.min(this.maxZoom, this.zoomLevel));
                if (clampedZoom !== this.zoomLevel) {
                    this.zoomLevel = clampedZoom;
                }
                
                this.$refs.cropper.cropper.zoomTo(this.zoomLevel);
                console.log(`Applied zoom: ${this.zoomLevel}`);
            } catch (error) {
                console.error('Error applying zoom:', error);
                this.errorMessage = 'Failed to apply zoom. Please try again.';
            }
        },
        
        resetCrop() {
            if (!this.cropperReady || !this.$refs.cropper || !this.$refs.cropper.cropper) {
                console.warn('Cropper not ready for reset');
                return;
            }
            
            try {
                this.$refs.cropper.cropper.reset();
                this.zoomLevel = 1;
                console.log('Cropper reset to default');
            } catch (error) {
                console.error('Error resetting crop:', error);
                this.errorMessage = 'Failed to reset crop. Please try again.';
            }
        },
        
        previewCrop() {
            if (!this.$refs.cropper || !this.$refs.cropper.cropper) {
                this.errorMessage = 'Cropper not ready. Please try again.';
                return;
            }
            
            const canvas = this.$refs.cropper.cropper.getCroppedCanvas({
                width: 300,
                height: 300,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            if (canvas) {
                this.croppedPreview = canvas.toDataURL('image/jpeg', 0.9);
            }
        },
        
        cropAndUpload() {
            if (!this.$refs.cropper || !this.$refs.cropper.cropper) {
                this.errorMessage = 'Cropper not ready. Please try again.';
                return;
            }
            
            this.isUploading = true;
            this.uploadProgress.show = true;
            this.uploadProgress.percent = 25;
            this.uploadProgress.message = 'Preparing image...';
            
            // Get cropped canvas
            const canvas = this.$refs.cropper.cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            if (!canvas) {
                this.errorMessage = 'Failed to crop image. Please try again.';
                this.isUploading = false;
                this.uploadProgress.show = false;
                return;
            }
            
            this.uploadProgress.percent = 50;
            this.uploadProgress.message = 'Processing...';
            
            // Convert canvas to blob
            canvas.toBlob((blob) => {
                if (!blob) {
                    this.errorMessage = 'Failed to process image. Please try again.';
                    this.isUploading = false;
                    this.uploadProgress.show = false;
                    return;
                }
                
                this.uploadProgress.percent = 75;
                this.uploadProgress.message = 'Uploading...';
                
                // Create FormData
                const formData = new FormData();
                formData.append('image', blob, 'profile-photo.jpg');
                formData.append('photoType', 'profile');
                
                // Upload using fetch
                this.uploadImage(formData);
            }, 'image/jpeg', 0.9);
        },
        
        dataURLtoBlob(dataURL, callback) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            callback(new Blob([u8arr], { type: mime }));
        },
        
        async uploadImage(formData) {
            try {
                if (!this.csrfToken) {
                    throw new Error('CSRF token not found');
                }
                
                const response = await fetch(route('photograph.store'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                this.uploadProgress.percent = 100;
                this.uploadProgress.message = 'Complete!';
                
                if (response.ok) {
                    const result = await response.json();
                    
                    // Success
                    this.showSuccess = true;
                    this.isEditing = false;
                    this.imageSrc = '';
                    this.croppedPreview = '';
                    this.currentProfilePhoto = this.croppedPreview; // Update current photo display
                    
                    // Clear file input
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                    
                    // Emit update event
                    this.$emit('photoUpdated');
                    
                    // Hide progress after delay
                    setTimeout(() => {
                        this.uploadProgress.show = false;
                    }, 2000);
                    
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Upload failed');
                }
                
            } catch (error) {
                console.error('Upload error:', error);
                this.errorMessage = error.message || 'Failed to upload photo. Please try again.';
                this.uploadProgress.show = false;
            } finally {
                this.isUploading = false;
            }
        },
        
        cancelEdit() {
            this.isEditing = false;
            this.imageSrc = '';
            this.croppedPreview = '';
            this.errorMessage = '';
            this.zoomLevel = 1;
            this.cropperReady = false;
            
            // Force cropper re-initialization on next use
            this.cropperKey += 1;
            
            // Clear file input
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        
        loadCurrentProfilePhoto() {
            // Load user's current profile photo if available
            if (this.user && this.user.profile_photo_url) {
                this.currentProfilePhoto = this.user.profile_photo_url;
            }
        }
    },
    
    mounted() {
        this.loadCurrentProfilePhoto();
        
        // Initialize CSRF token validation
        if (!this.csrfToken) {
            console.error('CSRF token not found. Please refresh the page.');
        }
    }
}
</script>

<style scoped>
.cropper-container {
    width: 100%;
    height: 400px;
    overflow: hidden;
    border-radius: 8px;
}

.preview-section {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom range slider styling */
.form-range::-webkit-slider-thumb {
    background: #0d6efd;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.form-range::-webkit-slider-thumb:hover {
    transform: scale(1.1);
}

/* Button group styling */
.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

/* Cropper specific styling */
:deep(.cropper-container) {
    direction: ltr;
    position: relative !important;
    width: 100% !important;
    height: 400px !important;
    overflow: hidden !important;
    background: #f8f9fa;
    z-index: 1;
}

:deep(.cropper-wrap-box) {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
}

:deep(.cropper-canvas) {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
}

:deep(.cropper-drag-box) {
    position: absolute !important;
    width: 100% !important;
    height: 100% !important;
    cursor: move !important;
}

:deep(.cropper-crop-box) {
    position: absolute !important;
    z-index: 2 !important;
}

:deep(.cropper-bg) {
    background-image: repeating-conic-gradient(#f8f9fa 0% 25%, #e9ecef 0% 50%);
    background-size: 20px 20px;
}

:deep(.cropper-modal) {
    background-color: rgba(0, 0, 0, 0.5) !important;
}

/* Fix for image overlapping issues */
:deep(.cropper-container canvas) {
    max-width: none !important;
    max-height: none !important;
}

/* Prevent scrolling when cropper is active */
.cropper-section {
    position: relative;
    z-index: 1;
}

.cropper-section .card-body {
    position: relative;
    overflow: hidden;
}
</style>