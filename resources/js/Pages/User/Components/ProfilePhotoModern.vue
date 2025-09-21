<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingProfilePhoto">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfilePhoto" aria-expanded="true" aria-controls="collapseProfilePhoto">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Avatar:</dt>
                    <dd class="col-sm-9 mb-0">
                        <span v-if="hasAvatar" class="text-success">
                            <i class="fas fa-check-circle"></i> Avatar set
                        </span>
                        <span v-else class="text-muted">
                            <i class="fas fa-user-circle"></i> No avatar uploaded
                        </span>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseProfilePhoto" class="accordion-collapse collapse show" aria-labelledby="headingProfilePhoto">
            <div class="accordion-body">
                
                <!-- Current Photo Display -->
                <div class="current-photo-section mb-4">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            <!-- Avatar Image (when user has uploaded one) -->
                            <img 
                                v-if="hasAvatar"
                                class="rounded-circle border shadow-sm" 
                                style="width: 80px; height: 80px; object-fit: cover;" 
                                :src="currentProfilePhotoUrl"
                                alt="Current avatar"
                            >
                            <!-- Default Avatar Icon (when no avatar uploaded) -->
                            <div 
                                v-else
                                class="rounded-circle border shadow-sm avatar-placeholder d-flex align-items-center justify-content-center" 
                                style="width: 80px; height: 80px;"
                            >
                                <i class="fas fa-user fa-2x text-muted"></i>
                            </div>
                            <!-- Remove Button (only show when avatar exists) -->
                            <div v-if="hasAvatar" class="position-absolute top-0 end-0">
                                <button 
                                    @click="removeCurrentPhoto" 
                                    class="btn btn-sm btn-danger rounded-circle p-1"
                                    style="width: 22px; height: 22px; line-height: 1;"
                                    title="Remove current avatar"
                                >
                                    <i class="fas fa-times" style="font-size: 9px;"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">Current Avatar</h6>
                            <small class="text-muted">{{ hasAvatar ? 'Click the X to remove' : 'Upload a new avatar below' }}</small>
                        </div>
                    </div>
                </div>

                <!-- File Upload Input -->
                <div v-if="!imageSrc" class="upload-section">
                    <div class="upload-zone" @click="triggerFileInput" @dragover.prevent @drop.prevent="handleDrop">
                        <input 
                            ref="fileInput"
                            type="file" 
                            class="d-none" 
                            @change="handleFileSelect" 
                            accept="image/*"
                        >
                        <div class="upload-content">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                            </div>
                            <h5 class="mt-3">Choose your avatar</h5>
                            <p class="text-muted">
                                Click to browse or drag & drop an image here<br>
                                <small>Supports: JPG, PNG, GIF, WebP • Max: 5MB</small>
                            </p>
                            <button type="button" class="btn btn-outline-primary">
                                <i class="fas fa-plus"></i> Select Avatar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modern Cropper Interface -->
                <div v-if="imageSrc" class="cropper-section">
                    <div class="cropper-card">
                        <!-- Cropper Header -->
                        <div class="cropper-header">
                            <h6 class="mb-0">
                                <i class="fas fa-crop"></i> Crop Your Avatar
                            </h6>
                            <div class="cropper-instructions">
                                <small class="text-muted">
                                    Drag to reposition • Scroll to zoom • Perfect circular crop
                                </small>
                            </div>
                        </div>
                        
                        <!-- Modern Cropper Container -->
                        <div class="modern-cropper-container">
                            <Cropper
                                ref="cropper"
                                :src="imageSrc"
                                :stencil-props="{
                                    aspectRatio: 1,
                                    resizable: false,
                                    movable: true,
                                    handlers: {}
                                }"
                                :canvas="{
                                    maxWidth: 400,
                                    maxHeight: 400,
                                    fillColor: '#ffffff'
                                }"
                                :auto-zoom="true"
                                :transitions="true"
                                :wheel-resize="true"
                                image-restriction="stencil"
                                @change="onChange"
                                @ready="onReady"
                                class="modern-cropper"
                            />
                            
                            <!-- Overlay Controls -->
                            <div class="cropper-overlay-controls">
                                <!-- Zoom Controls -->
                                <div class="zoom-controls">
                                    <button @click="zoomIn" class="control-btn" title="Zoom In">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button @click="zoomOut" class="control-btn" title="Zoom Out">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                
                                <!-- Rotation Controls -->
                                <div class="rotation-controls">
                                    <button @click="rotateLeft" class="control-btn" title="Rotate Left">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button @click="rotateRight" class="control-btn" title="Rotate Right">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                
                                <!-- Reset Control -->
                                <div class="reset-controls">
                                    <button @click="resetCrop" class="control-btn" title="Reset">
                                        <i class="fas fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="cropper-actions">
                            <button @click="cancelCrop" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button @click="cropAndUpload" class="btn btn-primary" :disabled="isUploading">
                                <span v-if="isUploading">
                                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                                </span>
                                <span v-else>
                                    <i class="fas fa-check"></i> Update Avatar
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Upload Progress -->
                <div v-if="uploadProgress.show" class="upload-progress mt-3">
                    <div class="progress-container">
                        <div class="progress">
                            <div 
                                class="progress-bar bg-primary" 
                                role="progressbar" 
                                :style="{ width: uploadProgress.percent + '%' }"
                                :aria-valuenow="uploadProgress.percent"
                                aria-valuemin="0" 
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="progress-text">{{ uploadProgress.message }}</small>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="showSuccess" class="alert alert-success mt-3" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <strong>Success!</strong> {{ successMessage || 'Your avatar has been updated.' }}
                    <button type="button" class="btn-close float-end" @click="showSuccess = false"></button>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="alert alert-danger mt-3" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error!</strong> {{ errorMessage }}
                    <button type="button" class="btn-close float-end" @click="errorMessage = ''"></button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { useForm, usePage } from '@inertiajs/vue3';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import axios from 'axios';

export default {
    components: {
        Cropper,
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
            // Image state
            imageSrc: '',
            currentProfilePhoto: null,
            cropperReady: false,
            
            // Upload state
            isUploading: false,
            uploadProgress: {
                show: false,
                percent: 0,
                message: ''
            },
            
            // UI state
            showSuccess: false,
            successMessage: '',
            errorMessage: '',
            
            // Form for upload
            form: useForm({
                croppedImage: null,
                photoType: 'profile'
            }),
            
            // Default placeholder images
            adImages: ['photoSample', 'photoSample1', 'photoSample2'],
            
            page: usePage()
        }
    },
    computed: {
        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        },
        currentProfilePhotoUrl() {
            if (this.currentProfilePhoto && this.currentProfilePhoto.url) {
                return this.currentProfilePhoto.url;
            }
            if (this.currentProfilePhoto && this.currentProfilePhoto.path) {
                return `/storage/${this.currentProfilePhoto.path}`;
            }
            return null; // Return null when no avatar - we'll show icon instead
        },
        hasAvatar() {
            return this.currentProfilePhoto && (this.currentProfilePhoto.url || this.currentProfilePhoto.path);
        }
    },
    methods: {
        // File handling
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.loadImage(file);
            }
        },
        
        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.loadImage(file);
            }
        },
        
        loadImage(file) {
            // Validate file
            if (!file.type.startsWith('image/')) {
                this.errorMessage = 'Please select a valid image file.';
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                this.errorMessage = 'File size must be less than 5MB.';
                return;
            }
            
            // Clear previous state
            this.errorMessage = '';
            this.showSuccess = false;
            this.cropperReady = false;
            
            // Load image
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.clearFileInput();
            };
            reader.onerror = () => {
                this.errorMessage = 'Failed to read the selected file.';
            };
            reader.readAsDataURL(file);
        },
        
        clearFileInput() {
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        
        // Cropper events
        onReady() {
            this.cropperReady = true;
            console.log('Modern cropper ready');
        },
        
        onChange({ coordinates, canvas }) {
            // Handle crop changes if needed
            console.log('Crop changed:', { coordinates, canvas });
        },
        
        // Cropper controls
        zoomIn() {
            if (this.$refs.cropper && this.cropperReady) {
                this.$refs.cropper.zoom(1.1);
            }
        },
        
        zoomOut() {
            if (this.$refs.cropper && this.cropperReady) {
                this.$refs.cropper.zoom(0.9);
            }
        },
        
        rotateLeft() {
            if (this.$refs.cropper && this.cropperReady) {
                this.$refs.cropper.rotate(-90);
            }
        },
        
        rotateRight() {
            if (this.$refs.cropper && this.cropperReady) {
                this.$refs.cropper.rotate(90);
            }
        },
        
        resetCrop() {
            if (this.$refs.cropper && this.cropperReady) {
                this.$refs.cropper.reset();
            }
        },
        
        // Actions
        cancelCrop() {
            this.imageSrc = '';
            this.cropperReady = false;
            this.errorMessage = '';
            this.clearFileInput();
        },
        
        async cropAndUpload() {
            if (!this.$refs.cropper || !this.cropperReady) {
                this.errorMessage = 'Cropper not ready. Please try again.';
                return;
            }
            
            this.isUploading = true;
            this.uploadProgress = {
                show: true,
                percent: 25,
                message: 'Preparing avatar...'
            };
            
            try {
                // Get cropped canvas
                const { canvas } = this.$refs.cropper.getResult();
                
                if (!canvas) {
                    throw new Error('Failed to crop image');
                }
                
                this.uploadProgress = {
                    show: true,
                    percent: 50,
                    message: 'Processing avatar...'
                };
                
                // Convert to blob with optimal settings for avatar
                const blob = await new Promise((resolve) => {
                    canvas.toBlob(resolve, 'image/jpeg', 0.9);
                });
                
                if (!blob) {
                    throw new Error('Failed to process image');
                }
                
                this.uploadProgress = {
                    show: true,
                    percent: 75,
                    message: 'Uploading avatar...'
                };
                
                // Upload directly using avatar endpoint with Axios
                const formData = new FormData();
                formData.append('image', blob, 'avatar.jpg');
                formData.append('photoType', 'avatar');
                
                const response = await axios.post(route('avatar.upload'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    onUploadProgress: (progressEvent) => {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        this.uploadProgress = {
                            show: true,
                            percent: 75 + (percentCompleted * 0.25), // 75-100%
                            message: 'Uploading avatar...'
                        };
                    }
                });
                
                this.uploadProgress = {
                    show: true,
                    percent: 100,
                    message: 'Avatar updated!'
                };
                
                if (response.status === 200 || response.status === 201) {
                    const result = response.data;
                    
                    // Success - update current avatar immediately
                    this.showSuccess = true;
                    this.successMessage = 'Your avatar has been updated successfully!';
                    this.imageSrc = '';
                    this.cropperReady = false;
                    
                    // Update current avatar display with new avatar
                    if (result.avatar) {
                        this.currentProfilePhoto = {
                            id: result.avatar.id,
                            path: result.avatar.path,
                            url: result.avatar.url
                        };
                    }
                    
                    // Emit update event to parent
                    this.$emit('photoUpdated');
                    
                    // Hide progress after delay
                    setTimeout(() => {
                        this.uploadProgress.show = false;
                        this.showSuccess = false;
                        this.successMessage = '';
                    }, 3000);
                } else {
                    throw new Error('Upload failed with status: ' + response.status);
                }
                
            } catch (error) {
                console.error('Avatar upload error:', error);
                
                // Handle Axios errors specifically
                if (error.response) {
                    // Server responded with error status
                    const errorData = error.response.data;
                    this.errorMessage = errorData.error || errorData.message || `Upload failed (${error.response.status})`;
                } else if (error.request) {
                    // Request was made but no response
                    this.errorMessage = 'Network error. Please check your connection.';
                } else {
                    // Something else happened
                    this.errorMessage = error.message || 'Failed to upload avatar. Please try again.';
                }
                
                this.uploadProgress.show = false;
            } finally {
                this.isUploading = false;
            }
        },
        
        // Remove current avatar
        async removeCurrentPhoto() {
            if (!this.currentProfilePhoto) return;
            
            if (!confirm('Are you sure you want to remove your current avatar?')) {
                return;
            }
            
            try {
                const response = await fetch(route('avatar.delete'), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    this.currentProfilePhoto = null;
                    this.$emit('photoUpdated');
                    this.showSuccess = true;
                    this.successMessage = 'Your avatar has been removed successfully!';
                    
                    // Hide success message after delay
                    setTimeout(() => {
                        this.showSuccess = false;
                        this.successMessage = '';
                    }, 3000);
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Failed to remove avatar');
                }
                
            } catch (error) {
                console.error('Error removing avatar:', error);
                this.errorMessage = 'Failed to remove avatar. Please try again.';
            }
        },
        
        // Load current avatar
        async loadCurrentProfilePhoto() {
            try {
                const response = await fetch(route('avatar.current'), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    
                    if (result.success && result.avatar) {
                        this.currentProfilePhoto = {
                            id: result.avatar.id,
                            path: result.avatar.path,
                            url: result.avatar.url
                        };
                    } else {
                        this.currentProfilePhoto = null;
                    }
                } else {
                    this.currentProfilePhoto = null;
                }
            } catch (error) {
                console.log('No avatar found or error loading:', error);
                this.currentProfilePhoto = null;
            }
        },
        
        // Utility
        imagePath(index) {
            const imageName = this.adImages[index];
            return new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;
        }
    },
    mounted() {
        this.loadCurrentProfilePhoto();
        
        if (!this.csrfToken) {
            console.error('CSRF token not found. Please refresh the page.');
        }
    }
}
</script>

<style scoped>
/* Avatar Placeholder */
.avatar-placeholder {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6 !important;
    transition: all 0.3s ease;
}

.avatar-placeholder:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #adb5bd !important;
}

.avatar-placeholder i {
    transition: color 0.3s ease;
}

.avatar-placeholder:hover i {
    color: #6c757d !important;
}

/* Upload Zone */
.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-zone:hover {
    border-color: #007bff;
    background: #f0f7ff;
}

.upload-content {
    max-width: 300px;
    margin: 0 auto;
}

.upload-icon {
    opacity: 0.7;
}

/* Modern Cropper Container */
.cropper-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.cropper-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cropper-header h6 {
    color: white;
    margin-bottom: 0.25rem;
}

.cropper-instructions {
    opacity: 0.9;
}

.modern-cropper-container {
    position: relative;
    height: 400px;
    background: #f8f9fa;
}

/* Vue Advanced Cropper Styling */
:deep(.vue-advanced-cropper) {
    background: #f8f9fa !important;
}

:deep(.vue-advanced-cropper__background) {
    background: #f8f9fa !important;
}

:deep(.vue-advanced-cropper__foreground) {
    background: rgba(0, 0, 0, 0.3) !important;
}

:deep(.vue-advanced-cropper__stencil) {
    border: 2px solid #007bff !important;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5) !important;
}

:deep(.vue-advanced-cropper__stencil-box) {
    border-radius: 50% !important;
}

/* Overlay Controls */
.cropper-overlay-controls {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 10;
}

.zoom-controls,
.rotation-controls,
.reset-controls {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.control-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: #495057;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.control-btn:hover {
    background: white;
    color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.control-btn:active {
    transform: translateY(0);
}

/* Action Buttons */
.cropper-actions {
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* Progress */
.upload-progress {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress {
    flex: 1;
    height: 8px;
    border-radius: 4px;
}

.progress-text {
    min-width: 120px;
    text-align: right;
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .cropper-overlay-controls {
        position: static;
        flex-direction: row;
        justify-content: center;
        padding: 1rem;
        background: rgba(248, 249, 250, 0.95);
        border-top: 1px solid #dee2e6;
    }
    
    .zoom-controls,
    .rotation-controls,
    .reset-controls {
        flex-direction: row;
    }
    
    .cropper-actions {
        flex-direction: column;
    }
    
    .cropper-actions .btn {
        width: 100%;
    }
}

/* Animations */
.cropper-card {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
