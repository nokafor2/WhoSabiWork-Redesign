<template>
    <div class="card m-3 shadow" style="width: 200px;">
        <div class="image-container" @click="openModal">
            <img :src="imgSrc" class="card-image" alt="">
        </div>
        <p class="card-text mb-1 px-2">{{ caption }}</p>
        
        <!-- Custom Modal -->
        <teleport to="body">
            <div v-if="isModalOpen" class="custom-modal-overlay" @click="closeModal">
                <div class="custom-modal-container" @click.stop>
                    <!-- Modal Header -->
                    <div class="modal-header bg-dark text-light border-bottom border-secondary">
                        <h5 class="modal-title">
                            <i class="fas fa-image me-2"></i>Photo Gallery
                        </h5>
                        <button type="button" class="btn btn-outline-light btn-sm" @click="closeModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body bg-dark text-light">
                        <!-- Image Display -->
                        <div class="text-center mb-4">
                            <img :src="imgSrc" class="img-fluid custom-modal-image" alt="">
                        </div>
                        
                        <!-- Caption Display -->
                        <div class="mb-3">
                            <div class="card bg-secondary">
                                <div class="card-body py-2">
                                    <p class="mb-0 text-light">{{ displayCaption }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Like/Dislike Actions -->
                        <div class="d-flex align-items-center mb-3">
                            <button class="btn btn-outline-light btn-sm me-3" @click="toggleLike">
                                <i class="fa-solid fa-thumbs-up me-1"></i>
                                <span class="badge bg-primary ms-1">{{ likes }}</span>
                            </button>
                            <button class="btn btn-outline-light btn-sm me-3" @click="toggleDislike">
                                <i class="fa-solid fa-thumbs-down me-1"></i>
                                <span class="badge bg-danger ms-1">{{ dislikes }}</span>
                            </button>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button @click="toggleCaptionInput" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                {{ showCaptionInput ? 'Cancel Edit' : 'Edit Caption' }}
                            </button>
                            <button class="btn btn-warning btn-sm" @click="setAsCoverPhoto">
                                <i class="fas fa-star me-1"></i>
                                {{ coverPhotoText }}
                            </button>
                            <button class="btn btn-danger btn-sm" @click="deletePhoto">
                                <i class="fas fa-trash me-1"></i>
                                Delete Photo
                            </button>
                        </div>

                        <!-- Caption Edit Form -->
                        <div v-if="showCaptionInput" class="mb-4">
                            <div class="card bg-secondary">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Caption</h6>
                                </div>
                                <div class="card-body">
                                    <form @submit.prevent="submitCaption">
                                        <div class="mb-3">
                                            <textarea 
                                                class="form-control bg-dark text-light border-secondary" 
                                                placeholder="Edit Caption" 
                                                rows="3" 
                                                v-model="captionInput.val"
                                                :class="{'is-invalid': !captionInput.isValid}"
                                            ></textarea>
                                            <div v-if="!captionInput.isValid" class="invalid-feedback">
                                                Caption is required
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-secondary btn-sm" @click="cancelCaptionEdit">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-save me-1"></i>Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="card bg-secondary">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-comments me-2"></i>Comments
                                    <span class="badge bg-primary ms-2">{{ commentCount }}</span>
                                </h6>
                            </div>
                            <div class="card-body custom-comments-section">
                                <!-- Comment Cards -->
                                <CommentCard v-for="index in commentCount" :key="index" :index="index"></CommentCard>
                                
                                <!-- No Comments Message -->
                                <div v-if="commentCount === 0" class="text-center text-muted py-4">
                                    <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                    <p class="mb-0">No comments yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer bg-dark text-light border-top border-secondary">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            <i class="fas fa-times me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-primary" @click="sharePhoto">
                            <i class="fas fa-share me-1"></i>Share Photo
                        </button>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import CommentCard from './CommentCard.vue';
    import ErrorAlert from '@/components/UI/ErrorAlert.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {CommentCard, ErrorAlert},
        props: ['userId', 'imageId', 'imgSrc', 'caption', 'photoType', 'isCoverPhoto'],
        emits: ['photoDeleted', 'photoUpdated'],
        data() {
            return {
                categoryInput: [],
                uniqueId: null,
                isModalOpen: false,
                showCaptionInput: false,
                captionInput: {
                    val: '',
                    isValid: true
                },
                likes: 20, // Default values - could be props later
                dislikes: 5,
                commentCount: 5,
                userLiked: false,
                userDisliked: false
            }
        },
        created() {
            // Generate a unique ID for this component instance
            this.uniqueId = `${this.userId}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            // Initialize caption input with current caption
            this.captionInput.val = this.caption;
        },
        methods: {
            openModal() {
                this.isModalOpen = true;
                // Prevent body scrolling when modal is open
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.isModalOpen = false;
                // Restore body scrolling
                document.body.style.overflow = '';
                // Reset form states
                this.showCaptionInput = false;
                this.captionInput.val = this.caption;
                this.captionInput.isValid = true;
            },
            toggleCaptionInput() {
                this.showCaptionInput = !this.showCaptionInput;
                if (this.showCaptionInput) {
                    this.captionInput.val = this.caption;
                }
            },
            cancelCaptionEdit() {
                this.showCaptionInput = false;
                this.captionInput.val = this.caption;
                this.captionInput.isValid = true;
            },
            submitCaption() {
                if (!this.captionInput.val.trim()) {
                    this.captionInput.isValid = false;
                    return;
                }

                const formData = useForm({
                    caption: this.captionInput.val
                })
                
                formData.put(route('photograph.update', this.imageId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('success', page.props.flash.success);
                        console.log('Updated page data:', page.props);
                        
                        // Update the image state
                        this.$store.dispatch('updateImages', { value: page.props.images });
                        
                        // Hide the caption input and emit update event
                        this.showCaptionInput = false;
                        this.$emit('photoUpdated', page.props.images.find(img => img.id === this.imageId));
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.captionInput.isValid = false;
                    }
                });
            },
            setAsCoverPhoto() {
                // Get CSRF token for explicit handling
                const csrfToken = this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const formData = useForm({
                    setAsCoverPhoto: !this.isCoverPhotoStatus,
                    user_id: this.userId  // Backend expects this for cover photo logic
                })
                
                console.log('Submitting cover photo update:', {
                    imageId: this.imageId,
                    setAsCoverPhoto: this.isCoverPhotoStatus,
                    route: route('photograph.update', this.imageId),
                    csrf: csrfToken ? 'present' : 'missing'
                });
                
                formData.put(route('photograph.update', this.imageId), {
                    preserveState: true,
                    preserveScroll: true,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    onSuccess: (page) => {
                        console.log('success', page.props.flash.success);
                        console.log('Cover photo update page data:', page.props);
                        
                        // Update the image state
                        this.$store.dispatch('updateImages', { value: page.props.images });
                        this.$emit('photoUpdated', page.props.images.find(img => img.id === this.imageId));
                    },
                    onError: (errors) => {
                        console.error('Cover photo update error:', errors);
                        console.error('Error details:', {
                            imageId: this.imageId,
                            attempted_status: this.isCoverPhotoStatus,
                            csrf_token: csrfToken ? 'present' : 'missing',
                            errors: errors
                        });
                        
                        // Check for CSRF error specifically
                        if (errors && (errors.message?.includes('419') || errors.status === 419)) {
                            console.error('CSRF Token Error detected. Token may be expired or invalid.');
                            alert('Session expired. Please refresh the page and try again.');
                        }
                    }
                });
            },
            deletePhoto() {
                if (!confirm('Are you sure you want to delete this photo?')) {
                    return;
                }
                const formData = useForm({
                    user_id: this.userId
                });
                formData.delete(route('photograph.destroy', this.imageId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('success', page.props.flash.success);
                        // Update the image state
                        this.$store.dispatch('updateImages', { value: page.props.images });
                        
                        // Close the modal immediately after successful deletion
                        this.closeModal();
                        
                        // Emit event to parent component to handle any cleanup
                        this.$emit('photoDeleted', this.imageId);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        alert('Failed to delete photo. Please try again.');
                    }
                });
            },
            toggleLike() {
                this.userLiked = !this.userLiked;
                if (this.userLiked) {
                    this.likes++;
                    if (this.userDisliked) {
                        this.userDisliked = false;
                        this.dislikes--;
                    }
                } else {
                    this.likes--;
                }
                // Here you would typically send an API request to update the backend
            },
            toggleDislike() {
                this.userDisliked = !this.userDisliked;
                if (this.userDisliked) {
                    this.dislikes++;
                    if (this.userLiked) {
                        this.userLiked = false;
                        this.likes--;
                    }
                } else {
                    this.dislikes--;
                }
                // Here you would typically send an API request to update the backend
            },
            sharePhoto() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Check out this photo!',
                        text: this.caption,
                        url: this.imgSrc
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(this.imgSrc).then(() => {
                        alert('Photo URL copied to clipboard!');
                    });
                }
            },
            // Legacy methods for parent component compatibility
            handlePhotoDeleted(imageId) {
                this.$emit('photoDeleted', imageId);
            },
            handlePhotoUpdated(updatedImage) {
                this.$emit('photoUpdated', updatedImage);
            }
        },
        computed: {
            isCoverPhotoStatus() {
                return this.isCoverPhoto || this.photoType === 'cover photo';
            },
            coverPhotoText() {
                return this.isCoverPhotoStatus ? 'Remove Cover Photo' : 'Set Image as Cover Photo';
            },
            displayCaption() {
                return this.caption || 'No caption provided';
            }
        }
    }
</script>

<style scoped>
/* Image container for centering within card */
.image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 174px; /* Fixed height matching original card design */
    width: 100%;
    overflow: hidden;
    background-color: #f8f9fa; /* Light background to show image bounds */
    border-radius: 0.375rem 0.375rem 0 0; /* Rounded top corners to match card */
    cursor: pointer;
    transition: all 0.2s ease;
}

.image-container:hover {
    background-color: #e9ecef;
    transform: scale(1.02);
}

/* Card image styling - maintains original dimensions */
.card-image {
    max-width: 100%;
    max-height: 100%;
    width: auto !important; /* Preserve original width ratio */
    height: auto !important; /* Preserve original height ratio */
    object-fit: contain; /* Maintain aspect ratio without cropping */
    border-radius: 4px;
    transition: all 0.2s ease;
}

/* Hover effect for the image */
.image-container:hover .card-image {
    transform: scale(1.05);
}

/* Custom Modal Overlay */
.custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
    padding: 1rem;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Custom Modal Container */
.custom-modal-container {
    background-color: #343a40;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    max-width: 90vw;
    max-height: 95vh;
    width: 800px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: scale(0.9) translateY(-50px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* Modal Header */
.modal-header {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
    border-bottom: 1px solid #495057;
    background: linear-gradient(45deg, #343a40, #495057);
}

.modal-title {
    margin: 0;
    flex-grow: 1;
    font-size: 1.25rem;
    font-weight: 500;
}

/* Modal Body */
.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    flex-grow: 1;
    background-color: #343a40;
}

/* Custom Modal Image */
.custom-modal-image {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 0.375rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.custom-modal-image:hover {
    transform: scale(1.02);
}

/* Modal Footer */
.modal-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    border-top: 1px solid #495057;
    background: linear-gradient(45deg, #495057, #343a40);
}

/* Button Animations */
.btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

/* Like/Dislike button effects */
.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #fff;
}

/* Caption Input Animation */
.card.bg-secondary {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Comments section styling */
.custom-comments-section {
    max-height: 250px;
    overflow-y: auto;
}

/* Custom scrollbar for comments */
.custom-comments-section::-webkit-scrollbar {
    width: 8px;
}

.custom-comments-section::-webkit-scrollbar-track {
    background: #495057;
    border-radius: 4px;
}

.custom-comments-section::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 4px;
}

.custom-comments-section::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

/* Badge animations */
.badge {
    transition: all 0.2s ease;
}

.btn:hover .badge {
    transform: scale(1.1);
}

/* Form validation styling */
.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .image-container {
        height: 150px; /* Slightly smaller on mobile */
    }
    
    .custom-modal-overlay {
        padding: 0.5rem;
    }
    
    .custom-modal-container {
        width: 95vw;
        max-height: 90vh;
    }
    
    .modal-header {
        padding: 0.75rem 1rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 0.75rem 1rem;
    }
    
    .custom-modal-image {
        max-height: 250px;
    }
    
    .d-flex.flex-wrap.gap-2 {
        gap: 0.5rem !important;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .custom-modal-overlay {
        padding: 0.25rem;
    }
    
    .custom-modal-container {
        width: 98vw;
        max-height: 95vh;
    }
    
    .custom-modal-image {
        max-height: 200px;
    }
    
    .modal-title {
        font-size: 1rem;
    }
    
    .btn-sm {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
}

/* Prevent scrolling when modal is open */
body.modal-open {
    overflow: hidden;
}
</style>