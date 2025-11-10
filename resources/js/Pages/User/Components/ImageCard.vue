<template>
    <div class="card m-3 shadow image-card-container">
        <div class="image-container" @click="openModal">
            <img :src="imageObj.src" class="card-image" alt="">
        </div>
        <p class="card-text mb-1 px-2">{{ imageObj.caption }}</p>
        
        <!-- Feedback Modal -->
        <FeedbackModal
            :visible="feedbackModal.visible"
            :title="feedbackModal.title"
            :message="feedbackModal.message"
            :show-action-button="feedbackModal.showActionButton"
            :action-button-text="feedbackModal.actionButtonText"
            :action-icon="feedbackModal.actionIcon"
            @close="closeFeedbackModal"
            @action="handleFeedbackAction">
        </FeedbackModal>
        
        <!-- Custom Modal -->
        <teleport to="body">
            <div v-if="isModalOpen" class="custom-modal-overlay" @click="closeModal">
                <div class="custom-modal-container" @click.stop>
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-image me-2"></i>Photo Gallery
                        </h5>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Image Display -->
                        <div class="text-center mb-4">
                            <img :src="imageObj.src" class="img-fluid custom-modal-image" alt="">
                        </div>
                        
                        <!-- Caption Display -->
                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="mb-0">{{ displayCaption }}</p>
                                </div>
                            </div>
                        </div>

                        <LikeAndDislikeCard 
                            :likeAndDislikeData="likeAndDislikeData"
                            @updateLikeAndDislike="handleLikeAndDislikeUpdate">
                        </LikeAndDislikeCard>
                        
                        <!-- Action Buttons -->
                        <div v-if="isProfilePage" class="d-flex flex-wrap gap-2 mb-3">
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
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Caption</h6>
                                </div>
                                <div class="card-body">
                                    <form @submit.prevent="submitCaption">
                                        <div class="mb-3">
                                            <textarea 
                                                class="form-control" 
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

                        <!-- Comment Form -->
                        <CommentForm :commentData="commentData" @updateComment="handleCommentUpdate"></CommentForm>

                        <!-- Comments Section -->
                        <div class="card bg-light">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-comments me-2"></i>Comments
                                    <span class="badge bg-primary ms-2">{{ commentCount }}</span>
                                </h6>
                            </div>
                            <div class="card-body custom-comments-section">
                                <!-- Comment Cards -->
                                <PhotoCommentAndReplyCard 
                                    v-for="commentReply in imageObj.photograph_comments" 
                                    :key="`comment-${commentReply.id}-${commentReply.photograph_replies?.length || 0}`"
                                    :commentReplies="commentReply"
                                    :pageName="pageName"
                                    @reply-added="handleReplyAdded"
                                >
                                </PhotoCommentAndReplyCard>
                                <!-- No Comments Message -->
                                <div v-if="commentCount === 0" class="text-center text-muted py-4">
                                    <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                    <p class="mb-0">No comments yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
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
    import PhotoCommentAndReplyCard from './PhotoCommentAndReplyCard.vue';
    import LikeAndDislikeCard from './LikeAndDislikeCard.vue';
    import FeedbackModal from '@/components/UI/FeedbackModal.vue';
    import CommentForm from './CommentForm.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {CommentCard, PhotoCommentAndReplyCard, LikeAndDislikeCard, FeedbackModal, CommentForm},
        props: ['imageData', 'pageName'],
        emits: ['photoDeleted', 'photoUpdated'],
        data() {
            return {
                imageObj: this.imageData,
                pageName: this.pageName,
                userId: this.imageData.user_id,
                categoryInput: [],
                uniqueId: null,
                isModalOpen: false,
                showCaptionInput: false,
                showCommentInput: false,
                captionInput: {
                    val: '',
                    isValid: true
                },
                commentInput: {
                    val: '',
                    isValid: true
                },
                userLiked: this.imageData?.userLiked || false,
                userDisliked: this.imageData?.userDisliked || false,
                ownerLiked: this.imageData?.ownerLiked || false,
                ownerDisliked: this.imageData?.ownerDisliked || false,
                likes: this.imageData?.active_likes_count || 0,
                dislikes: this.imageData?.active_dislikes_count || 0,
                commentCount: this.imageData?.photograph_comments?.length || 0,
                feedbackModal: {
                    visible: false,
                    title: 'Notification',
                    message: '',
                    showActionButton: false,
                    actionButtonText: 'Login',
                    actionIcon: 'fas fa-sign-in-alt',
                    action: null, // Track what action to perform: 'login', 'delete', etc.
                },
                likeAndDislikeData: {
                    pageName: this.pageName,
                    userId: this.imageData.user_id,
                    photographId: this.imageData.id,
                    likes: this.imageData.active_likes_count || 0,
                    dislikes: this.imageData.active_dislikes_count || 0,
                    userLiked: this.imageData.userLiked || false,
                    userDisliked: this.imageData.userDisliked || false,
                },
                commentData: {
                    pageName: this.pageName,
                    commentCount: this.imageData.photograph_comments?.length || 0,
                    photographId: this.imageData.id,
                    photographUserId: this.imageData.user_id,
                }
            }
        },
        created() {
            // Generate a unique ID for this component instance
            this.uniqueId = `${this.userId}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            // Initialize caption input with current caption
            this.captionInput.val = this.imageObj?.caption || '';
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
                this.showCommentInput = false;
                this.captionInput.val = this.imageObj?.caption || '';
                this.commentInput.val = this.imageObj?.comment || '';
                this.captionInput.isValid = true;
            },
            toggleCaptionInput() {
                this.showCaptionInput = !this.showCaptionInput;
                if (this.showCaptionInput) {
                    this.captionInput.val = this.imageObj?.caption || '';
                }
            },
            cancelCaptionEdit() {
                this.showCaptionInput = false;
                this.captionInput.val = this.imageObj?.caption || '';
                this.captionInput.isValid = true;
            },
            cancelCommentEdit() {
                this.showCommentInput = false;
                this.commentInput.val = this.imageObj?.comment || '';
                this.commentInput.isValid = true;
            },
            handleCommentUpdate(data) {
                this.commentCount = data.commentCount;
                this.commentData.commentCount = data.commentCount;
            },
            submitCaption() {
                if (!this.captionInput.val.trim()) {
                    this.captionInput.isValid = false;
                    return;
                }

                const formData = useForm({
                    caption: this.captionInput.val
                });
                
                formData.put(route('photograph.update', this.imageObj.id), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Find the updated image from the response
                        const updatedImage = page.props.images?.find(img => img.id === this.imageObj.id);
                        
                        if (updatedImage) {
                           // Update local imageObj caption directly for immediate reactivity
                            this.imageObj.caption = updatedImage.caption;
                            
                            // Force Vue to detect the change
                            this.$forceUpdate();
                            
                            // Update the image state in Vuex store
                            this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });                            
                        } else {
                            console.error('Updated image not found in response');
                        }
                        
                        // Hide the caption input
                        this.showCaptionInput = false;
                    },
                    onError: (errors) => {
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
                
                formData.put(route('photograph.update', this.imageObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    onSuccess: (page) => {
                        // Update the image state
                        this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });
                        this.$emit('photoUpdated', page.props.images.find(img => img.id === this.imageObj.id));
                    },
                    onError: (errors) => {
                        // Check for CSRF error specifically
                        if (errors && (errors.message?.includes('419') || errors.status === 419)) {
                            console.error('CSRF Token Error detected. Token may be expired or invalid.');
                            alert('Session expired. Please refresh the page and try again.');
                        }
                    }
                });
            },
            deletePhoto() {
                // check if user is authenticated
                if (!this.isAuthenticated) {
                    this.showFeedbackModal(
                        'Authentication Required', 
                        'You must be logged in to delete photos. Please log in to continue.', 
                        true,
                        'login',
                        'Login',
                        'fas fa-sign-in-alt'
                    );
                    return;
                }
                // Show this confirmation with the showFeedbackModal
                this.showFeedbackModal(
                    'Confirm Delete', 
                    'Are you sure you want to delete this photo? This action cannot be undone.', 
                    true,
                    'delete',
                    'Yes, Delete',
                    'fas fa-trash'
                );
            },
            executeDeletePhoto() {
                const formData = useForm({
                    user_id: this.userId
                });
                formData.delete(route('photograph.destroy', this.imageObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Update the image state
                        this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });
                        
                        // Close the modal immediately after successful deletion
                        this.closeModal();
                        
                        // Emit event to parent component to handle any cleanup
                        this.$emit('photoDeleted', this.imageObj.id);
                    },
                    onError: (errors) => {
                        // console.log('Error: ', errors);
                        this.showFeedbackModal('Error', 'Failed to delete photo. Please try again.', false, null, 'OK', 'fas fa-times');
                    }
                });
            },
            sharePhoto() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Check out this photo!',
                        text: this.imageObj?.caption || '',
                        url: this.imageObj?.src
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(this.imageObj?.src).then(() => {
                        alert('Photo URL copied to clipboard!');
                    });
                }
            },
            // Handle reply added event from CommentAndReplyCard
            handleReplyAdded({ photographId, commentId, replyData }) {
                // Find the comment in imageObj.photograph_comments and add the reply
                if (this.imageObj.photograph_comments) {
                    const commentIndex = this.imageObj.photograph_comments.findIndex(c => c.id === commentId);
                    
                    if (commentIndex !== -1) {
                        const comment = this.imageObj.photograph_comments[commentIndex];
                        
                        // Initialize photograph_replies if it doesn't exist
                        if (!comment.photograph_replies) {
                            comment.photograph_replies = [];
                        }
                        
                        // Add the new reply at the beginning (latest first)
                        comment.photograph_replies = [replyData, ...comment.photograph_replies];
                        
                        // Force Vue to recognize the change by creating a new array reference
                        this.imageObj.photograph_comments = [...this.imageObj.photograph_comments];
                        
                        // Force update to ensure reactivity
                        this.$forceUpdate();
                    } else {
                        console.warn('Comment not found in imageObj for commentId:', commentId);
                    }
                }
            },
            // Legacy methods for parent component compatibility
            handlePhotoDeleted(imageId) {
                this.$emit('photoDeleted', imageId);
            },
            handlePhotoUpdated(updatedImage) {
                this.$emit('photoUpdated', updatedImage);
            },
            // Feedback Modal Methods
            showFeedbackModal(title, message, showActionButton = false, action = null, actionButtonText = 'OK', actionIcon = 'fas fa-check') {
                this.feedbackModal.title = title;
                this.feedbackModal.message = message;
                this.feedbackModal.showActionButton = showActionButton;
                this.feedbackModal.action = action;
                this.feedbackModal.actionButtonText = actionButtonText;
                this.feedbackModal.actionIcon = actionIcon;
                this.feedbackModal.visible = true;
            },
            closeFeedbackModal() {
                this.feedbackModal.visible = false;
                this.feedbackModal.action = null;
            },
            handleFeedbackAction() {
                // Handle different actions based on the action type
                if (this.feedbackModal.action === 'login') {
                    // Redirect to login page
                    window.location.href = route('login');
                } else if (this.feedbackModal.action === 'delete') {
                    // Execute the delete photo action
                    this.closeFeedbackModal();
                    this.executeDeletePhoto();
                }
            },
            // Handle like/dislike updates from LikeAndDislikeCard component
            handleLikeAndDislikeUpdate(data) {
                // Update the local state with the new values from the child component
                this.likes = data.likes;
                this.dislikes = data.dislikes;
                this.userLiked = data.userLiked;
                this.userDisliked = data.userDisliked;
                
                // Update the likeAndDislikeData object to keep it in sync
                this.likeAndDislikeData.likes = data.likes;
                this.likeAndDislikeData.dislikes = data.dislikes;
                this.likeAndDislikeData.userLiked = data.userLiked;
                this.likeAndDislikeData.userDisliked = data.userDisliked;
                
                // Force Vue to detect the change
                this.$forceUpdate();
            }
        },
        computed: {
            isCoverPhotoStatus() {
                return this.imageObj?.photo_type === 'cover photo';
            },
            coverPhotoText() {
                return this.isCoverPhotoStatus ? 'Remove Cover Photo' : 'Set Image as Cover Photo';
            },
            displayCaption() {
                return this.imageObj?.caption || 'No caption provided';
            },
            isProfilePage() {
                if (this.pageName === 'profilePage') {
                    return true;
                } else {
                    return false;
                }
            },
            isEntrepreneurPage() {
                if (this.pageName === 'entrepreneur') {
                    return true;
                } else {
                    return false;
                }
            },
            isAuthenticated() {
                return this.$store.getters.getIsAuthenticated;
            },
            authenticatedUser() {
                return this.$store.getters.getAuthenticatedUser;
            },
        },
        watch: {
            // Watch for changes in imageData prop and sync with local imageObj
            imageData: {
                handler(newImageData) {
                    if (newImageData) {
                        // Update imageObj when prop changes
                        this.imageObj = { ...newImageData };
                    }
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
/* Image card container - responsive width */
.image-card-container {
    width: 200px;
}

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
    background-color: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
    border-bottom: 1px solid #dee2e6;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.modal-title {
    margin: 0;
    flex-grow: 1;
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
}

/* Modal Body */
.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    flex-grow: 1;
    background-color: #f8f9fa;
}

/* Custom scrollbar for modal body */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #6c757d;
}

/* Custom Modal Image */
.custom-modal-image {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 0.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.custom-modal-image:hover {
    transform: scale(1.02);
}

/* Card styling for inner sections */
.modal-body .card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Modal Footer */
.modal-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    border-top: 1px solid #dee2e6;
    background: linear-gradient(135deg, #e9ecef, #f8f9fa);
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

/* Close button styling */
.btn-close {
    transition: transform 0.2s ease;
}

.btn-close:hover {
    transform: rotate(90deg);
}

/* Caption Input Animation */
.card.bg-light {
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