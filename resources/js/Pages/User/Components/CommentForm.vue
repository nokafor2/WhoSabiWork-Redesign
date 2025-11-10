<template>
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
        
    <div v-if="isVisible" class="mb-4">
        <div class="card bg-light">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Make Comment</h6>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitPhotoComment">
                    <div class="mb-3">
                        <textarea 
                            class="form-control" 
                            placeholder="Enter your comment" 
                            rows="1" 
                            v-model="commentInput.val"
                            :class="{'is-invalid': !commentInput.isValid}"
                        ></textarea>
                        <div v-if="!commentInput.isValid" class="invalid-feedback">
                            Comment is required
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm" @click="cancelCommentEdit">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import FeedbackModal from '@/components/UI/FeedbackModal.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {    
        components: { FeedbackModal },
        props: ['commentData'],
        emits: ['updateComment'],
        data() {
            return {
                pageName: this.commentData.pageName,
                showCommentInput: false,
                commentInput: {
                    val: '',
                    isValid: true
                },
                commentCount: this.commentData.commentCount,
                photographId: this.commentData.photographId,
                photographUserId: this.commentData.photographUserId,
                feedbackModal: {
                    visible: false,
                    title: 'Notification',
                    message: '',
                    showActionButton: false,
                    actionButtonText: 'Login',
                    actionIcon: 'fas fa-sign-in-alt',
                    action: null, // Track what action to perform: 'login', 'delete', etc.
                },
            }
        },
        methods: {
            submitPhotoComment() {
                // check if user is authenticated
                if (!this.isAuthenticated) {
                    this.showFeedbackModal('Authentication Required', 'You must be logged in to comment on photos. Please log in to continue.', true, 'login', 'Login', 'fas fa-sign-in-alt');
                    return;
                }

                if (!this.commentInput.val.trim()) {
                    this.commentInput.isValid = false;
                    return;
                }

                const formData = useForm({
                    photograph_id: this.photographId,
                    photograph_user_id: this.photographUserId,
                    comment: this.commentInput.val
                })
                formData.post(route('photographcomment.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('success', page.props.flash.success);
                        console.log('Updated page data:', page.props);

                        // Check if there's an error in flash (authentication error)
                        if (page.props.flash.error && page.props.flash.error.authError) {
                            this.showFeedbackModal('Authentication Required', page.props.flash.error.message, true, 'login', 'Login', 'fas fa-sign-in-alt');
                            return;
                        }

                        // Update the comment count
                        this.commentCount = page.props.flash.success.commentCount;

                        this.$emit('updateComment', {
                            commentCount: page.props.flash.success.commentCount,
                            commentData: page.props.flash.success.commentData
                        });

                        // Update the respective data in Vuex store
                        if (this.pageName === 'entrepreneur') {
                            this.$store.dispatch('updateGalleryPhotos', { value: page.props.entrepreneur.galleryPhotos });
                        } else if (this.pageName === 'profilePage') {
                            this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });
                        } else if (this.pageName === 'photoFeed') {
                            this.$store.dispatch('updatePhotoFeedData', { value: page.props.photoFeedData });
                        }

                        // Force Vue to detect the change
                        this.$forceUpdate();

                        // Reset the comment input
                        this.commentInput.val = '';
                        this.commentInput.isValid = true;
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        
                        // Handle validation errors
                        if (errors.comment) {
                            this.commentInput.isValid = false;
                        }
                        
                        // Handle authentication errors
                        if (errors.authError && errors.authError.includes('unauthenticated')) {
                            this.showFeedbackModal(
                                'Authentication Required',
                                'You must be logged in to comment on photos. Please log in to continue.',
                                true,
                                'login',
                                'Login',
                                'fas fa-sign-in-alt'
                            );
                        } else if (Object.keys(errors).length > 0) {
                            // Generic error handling
                            const errorMessage = errors[Object.keys(errors)[0]];
                            this.showFeedbackModal('Error', errorMessage, false, null, 'OK', 'fas fa-times');
                        }
                    }
                });
            },
            cancelCommentEdit() {
                this.showCommentInput = false;
                this.commentInput.val = '';
                this.commentInput.isValid = true;
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
        },
        computed: {
            isVisible() {
                if (this.pageName === 'entrepreneur' || this.pageName === 'photoFeed') {
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
    }
</script>