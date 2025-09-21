<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Profile Photo:</dt>
                    <dd class="col-sm-9 mb-0">
                        <span v-if="currentProfilePhoto" class="text-success">
                            <i class="fas fa-check-circle"></i> Photo uploaded
                        </span>
                        <span v-else class="text-muted">No photo uploaded</span>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">
                <div class="d-sm-flex align-items-start">
                    <!-- Current Profile Photo Display -->
                    <div class="me-3 mb-3">
                        <div class="position-relative">
                            <img 
                                class="rounded-circle border" 
                                style="height: 120px; width: 120px; object-fit: cover;" 
                                :src="currentProfilePhotoUrl"
                                :alt="currentProfilePhoto ? 'Current profile photo' : 'Default placeholder'"
                            >
                            <div v-if="currentProfilePhoto" class="position-absolute top-0 end-0">
                                <button 
                                    @click="removeCurrentPhoto" 
                                    class="btn btn-sm btn-danger rounded-circle p-1"
                                    style="width: 25px; height: 25px; line-height: 1;"
                                    title="Remove current photo"
                                >
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">Current Photo</small>
                    </div>
                    
                    <!-- FilePond Upload Section -->
                    <div class="flex-grow-1">
                        <label class="form-label fw-bold">Upload New Profile Photo</label>
                        
                        <!-- FilePond Component -->
                        <FilePond
                            ref="pond"
                            name="profileImage"
                            label-idle='Drag & Drop your profile photo or <span class="filepond--label-action">Browse</span>'
                            allow-multiple="false"
                            max-files="1"
                            accepted-file-types="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                            :server="{
                                process: handleFilePondProcess,
                                revert: handleFilePondRevert,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }"
                            v-on:init="handleFilePondInit"
                            v-on:addfile="handleFileAdd"
                            v-on:processfile="handleFileProcess"
                            v-on:error="handleFilePondError"
                            
                            :image-preview-height="200"
                            :image-crop-aspect-ratio="1"
                            :image-resize-target-width="400"
                            :image-resize-target-height="400"
                            :image-resize-mode="'cover'"
                            :image-resize-upscale="true"
                            :image-transform-output-quality="85"
                            :image-transform-output-format="'jpeg'"
                            :image-transform-output-mime-type="'image/jpeg'"
                            :image-transform-output-strip-image-head="false"
                            
                            :image-validate-size-min-width="100"
                            :image-validate-size-min-height="100"
                            :image-validate-size-max-width="2000"
                            :image-validate-size-max-height="2000"
                            :max-file-size="'5MB'"
                            
                            :instant-upload="true"
                            :label-file-processing="'Uploading...'"
                            :label-file-processing-complete="'Upload complete'"
                            :label-file-processing-aborted="'Upload cancelled'"
                            :label-file-processing-revert-error="'Error during revert'"
                            :label-file-processing-error="'Error during upload'"
                        />
                        
                        <!-- Upload Progress -->
                        <div v-if="uploadProgress.show" class="mt-2">
                            <div class="progress" style="height: 6px;">
                                <div 
                                    class="progress-bar bg-primary" 
                                    role="progressbar" 
                                    :style="`width: ${uploadProgress.percent}%`"
                                    :aria-valuenow="uploadProgress.percent" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100"
                                ></div>
                            </div>
                            <small class="text-muted">{{ uploadProgress.message }}</small>
                        </div>
                        
                        <!-- Upload Button -->
                        <div class="mt-3">
                            <button 
                                @click="uploadProfilePhoto"
                                :disabled="!tempImageId || isUploading"
                                class="btn btn-danger"
                                :class="{ 'disabled': !tempImageId || isUploading }"
                            >
                                <span v-if="isUploading">
                                    <i class="fas fa-spinner fa-spin"></i> Updating Profile...
                                </span>
                                <span v-else>
                                    <i class="fas fa-upload"></i> Update Profile Photo
                                </span>
                            </button>
                            
                            <button 
                                v-if="tempImageId" 
                                @click="cancelUpload"
                                :disabled="isUploading"
                                class="btn btn-outline-secondary ms-2"
                            >
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                        
                        <!-- File Requirements -->
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>Requirements:</strong><br>
                                • Formats: JPEG, PNG, GIF, WebP<br>
                                • Size: 100x100 to 2000x2000 pixels<br>
                                • Max file size: 5MB<br>
                                • Image will be cropped to square and resized to 400x400px
                            </small>
                        </div>
                        
                        <!-- Compression Stats -->
                        <div v-if="compressionStats.show" class="mt-3">
                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Optimization:</strong> 
                                    {{ compressionStats.originalSize }} → {{ compressionStats.compressedSize }}
                                    ({{ compressionStats.compressionRatio }}% reduction)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';

// FilePond imports
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

// FilePond plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';

// Create FilePond component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateSize,
    FilePondPluginImageValidateSize,
    FilePondPluginImageEdit
);

export default {
    components: {
        FilePond,
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
            // Form for final upload
            form: useForm({
                tempImageId: null,
                photoType: 'profile'
            }),
            
            // Current state
            tempImageId: null,
            isUploading: false,
            currentProfilePhoto: null,
            
            // UI state
            uploadProgress: {
                show: false,
                percent: 0,
                message: ''
            },
            compressionStats: {
                show: false,
                originalSize: '',
                compressedSize: '',
                compressionRatio: 0
            },
            
            // Page reference
            page: usePage(),
            
            // Default placeholder images
            adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
        }
    },
    computed: {
        csrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        },
        currentProfilePhotoUrl() {
            if (this.currentProfilePhoto && this.currentProfilePhoto.path) {
                return `/storage/${this.currentProfilePhoto.path}`;
            }
            // Fallback to default placeholder
            return this.imagePath(0);
        }
    },
    methods: {
        // FilePond event handlers
        handleFilePondInit() {
            console.log('FilePond initialized for profile photo upload');
        },
        
        handleFileAdd(error, file) {
            if (error) {
                console.error('Error adding file:', error);
                return;
            }
            
            console.log('File added for profile upload:', file.filename);
            
            // Show compression stats if available
            if (file.file && file.file.size) {
                const originalSize = this.formatFileSize(file.file.size);
                this.compressionStats = {
                    show: false, // Will show after processing
                    originalSize: originalSize,
                    compressedSize: '',
                    compressionRatio: 0
                };
            }
        },
        
        handleFileProcess(error, file) {
            if (error) {
                console.error('Error processing file:', error);
                return;
            }
            
            console.log('File processed for profile upload:', file.filename, 'Server ID:', file.serverId);
            
            // Store the temporary image ID
            this.tempImageId = file.serverId;
            this.form.tempImageId = file.serverId;
            
            // Show compression stats
            if (file.file && this.compressionStats.originalSize) {
                const compressedSize = file.file.size ? this.formatFileSize(file.file.size) : 'Unknown';
                const originalBytes = this.parseFileSize(this.compressionStats.originalSize);
                const compressedBytes = file.file.size || 0;
                const compressionRatio = originalBytes > 0 ? Math.round((1 - compressedBytes / originalBytes) * 100) : 0;
                
                this.compressionStats = {
                    show: true,
                    originalSize: this.compressionStats.originalSize,
                    compressedSize: compressedSize,
                    compressionRatio: compressionRatio
                };
            }
        },
        
        handleFilePondProcess(fieldName, file, metadata, load, error, progress, abort) {
            // Upload to temporary storage
            const formData = new FormData();
            formData.append('image', file, file.name);
            
            const request = new XMLHttpRequest();
            
            request.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    progress(e.loaded, e.total);
                }
            });
            
            request.addEventListener('load', () => {
                if (request.status >= 200 && request.status < 300) {
                    load(request.responseText);
                } else {
                    error('Upload failed');
                }
            });
            
            request.addEventListener('error', () => {
                error('Upload failed');
            });
            
            request.open('POST', route('upload.temporary'));
            request.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
            request.send(formData);
            
            return {
                abort: () => {
                    request.abort();
                    abort();
                }
            };
        },
        
        async handleFilePondRevert(uniqueId, load, error) {
            try {
                console.log('Reverting temporary file:', uniqueId);
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    error('CSRF token missing');
                    return;
                }
                
                await axios.post(route('delete.temporary'), {
                    uniqueId: uniqueId
                }, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });
                
                // Clear temporary image ID
                this.tempImageId = null;
                this.form.tempImageId = null;
                this.compressionStats.show = false;
                
                console.log('Temporary file deleted successfully');
                load();
            } catch (error) {
                console.error('Error deleting temporary file:', error);
                error('Failed to delete temporary file');
            }
        },
        
        handleFilePondError(error, file, status) {
            console.error('FilePond error:', error, file, status);
            alert(`Upload error: ${error.main || error}`);
        },
        
        // Upload profile photo to permanent storage
        async uploadProfilePhoto() {
            if (!this.tempImageId) {
                alert('Please select an image first');
                return;
            }
            
            this.isUploading = true;
            this.uploadProgress = {
                show: true,
                percent: 25,
                message: 'Preparing profile photo...'
            };
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }
                
                this.uploadProgress = {
                    show: true,
                    percent: 50,
                    message: 'Uploading profile photo...'
                };
                
                this.form
                .transform((data) => ({
                    tempImages: [data.tempImageId], // Array format expected by controller
                    captions: { [data.tempImageId]: '' }, // Empty caption for profile photo
                    photoType: 'profile' // Set photo type
                }))
                .post(route('photograph.store'), {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    onProgress: (progress) => {
                        this.uploadProgress.percent = 50 + (progress.percentage * 0.4);
                    },
                    onSuccess: (page) => {
                        this.uploadProgress = {
                            show: true,
                            percent: 100,
                            message: 'Profile photo updated successfully!'
                        };
                        
                        // Clear FilePond
                        this.$refs.pond.removeFiles();
                        this.tempImageId = null;
                        this.form.tempImageId = null;
                        this.compressionStats.show = false;
                        
                        // Update current profile photo
                        this.loadCurrentProfilePhoto();
                        
                        // Emit event to parent
                        this.$emit('photoUpdated');
                        
                        // Hide progress after delay
                        setTimeout(() => {
                            this.uploadProgress.show = false;
                            this.isUploading = false;
                        }, 2000);
                    },
                    onError: (errors) => {
                        console.error('Upload errors:', errors);
                        this.uploadProgress.show = false;
                        this.isUploading = false;
                        
                        const errorMessage = errors.tempImages || errors.message || 'Upload failed. Please try again.';
                        alert(`Error: ${errorMessage}`);
                    }
                });
                
            } catch (error) {
                console.error('Error uploading profile photo:', error);
                this.uploadProgress.show = false;
                this.isUploading = false;
                alert(`Upload failed: ${error.message}`);
            }
        },
        
        // Cancel upload
        cancelUpload() {
            if (this.tempImageId) {
                this.$refs.pond.removeFiles();
                this.tempImageId = null;
                this.form.tempImageId = null;
                this.compressionStats.show = false;
            }
        },
        
        // Remove current profile photo
        async removeCurrentPhoto() {
            if (!this.currentProfilePhoto) return;
            
            if (!confirm('Are you sure you want to remove your current profile photo?')) {
                return;
            }
            
            try {
                const response = await axios.delete(route('photograph.destroy', this.currentProfilePhoto.id), {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                this.currentProfilePhoto = null;
                this.$emit('photoUpdated');
                alert('Profile photo removed successfully');
                
            } catch (error) {
                console.error('Error removing profile photo:', error);
                alert('Failed to remove profile photo. Please try again.');
            }
        },
        
        // Load current profile photo
        async loadCurrentProfilePhoto() {
            try {
                // Check if user prop has profile photo info
                if (this.user && this.user.id) {
                    const response = await axios.get(route('photograph.user', this.user.id), {
                        params: { photo_type: 'profile' },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.data && response.data.length > 0) {
                        this.currentProfilePhoto = response.data[0];
                    }
                }
            } catch (error) {
                console.log('No profile photo found or error loading:', error);
                this.currentProfilePhoto = null;
            }
        },
        
        // Utility methods
        imagePath(index) {
            const imageName = this.adImages[index];
            const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;
            return imageUrl;
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        parseFileSize(sizeStr) {
            const match = sizeStr.match(/^([\d.]+)\s*(Bytes|KB|MB|GB)$/i);
            if (!match) return 0;
            
            const value = parseFloat(match[1]);
            const unit = match[2].toUpperCase();
            const multipliers = { 'BYTES': 1, 'KB': 1024, 'MB': 1024 * 1024, 'GB': 1024 * 1024 * 1024 };
            
            return value * (multipliers[unit] || 1);
        }
    },
    mounted() {
        // Load current profile photo on component mount
        this.loadCurrentProfilePhoto();
        
        // Initialize CSRF token validation
        if (!this.csrfToken) {
            console.error('CSRF token not found. Please refresh the page.');
        }
    }
}
</script>

