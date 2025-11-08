<template>
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

    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <button class="btn btn-outline-primary btn-sm" @click="toggleLike">
            <i class="fa-solid fa-thumbs-up me-1"></i>
            <span class="badge bg-primary ms-1">{{ likes }}</span>
        </button>
        <button class="btn btn-outline-danger btn-sm" @click="toggleDislike">
            <i class="fa-solid fa-thumbs-down me-1"></i>
            <span class="badge bg-danger ms-1">{{ dislikes }}</span>
        </button>
    </div>
</template>


<script>
    import FeedbackModal from '@/components/UI/FeedbackModal.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        components: { FeedbackModal },
        props: ['likeAndDislikeData'],
        emits: ['updateLikeAndDislike'],
        data() {
            return {
                likeAndDislikeObj: this.likeAndDislikeData,
                pageName: this.likeAndDislikeData.pageName,
                userId: this.likeAndDislikeData.userId,
                photographId: this.likeAndDislikeData.photographId,
                likes: this.likeAndDislikeData.likes,
                dislikes: this.likeAndDislikeData.dislikes,
                userLiked: this.likeAndDislikeData.userLiked,
                userDisliked: this.likeAndDislikeData.userDisliked,
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
            toggleLike() {
                // Check if user is authenticated
                if (!this.isAuthenticated) {
                    this.showFeedbackModal('Authentication Required', 'You must be logged in to like photos. Please log in to continue.', true, 'login', 'Login', 'fas fa-sign-in-alt');
                    return;
                }

                // Check if user has liked the photo before
                const formData = useForm({
                    photograph_id: this.photographId,
                    photograph_user_id: this.userId,
                    like: !this.userLiked
                });
                formData.post(route('photographlike.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('Like success:', page.props.flash.success);
                        console.log('page data:', page.props);
                        
                        // Check if there's an authentication error in flash
                        if (page.props.flash.error && page.props.flash.error.authError) {
                            this.showFeedbackModal(
                                'Authentication Required', 
                                page.props.flash.error.message, 
                                true, 
                                'login', 
                                'Login', 
                                'fas fa-sign-in-alt'
                            );
                            return;
                        }
                        
                        if (page.props.flash.success) {
                            const response = page.props.flash.success;
                            
                            // Update the respective data in Vuex store
                            if (this.pageName === 'entrepreneur') {
                                this.$store.dispatch('updateGalleryPhotos', { value: page.props.entrepreneur.galleryPhotos });
                            } else if (this.pageName === 'profilePage') {
                                this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });
                            } else if (this.pageName === 'photoFeed') {
                                this.$store.dispatch('updatePhotoFeedData', { value: page.props.photoFeedData });
                            }
                            
                            // Update states from server response
                            this.userLiked = response.like;
                            this.userDisliked = response.dislike;
                            
                            // Update like and dislike counts from server response
                            this.likes = response.likesCount;
                            this.dislikes = response.dislikesCount;
                            
                            // Emit updated data to parent component
                            this.$emit('updateLikeAndDislike', {
                                likes: response.likesCount,
                                dislikes: response.dislikesCount,
                                userLiked: response.like,
                                userDisliked: response.dislike
                            });
                        }
                    },
                    onError: (errors) => {
                        console.error('Like error:', errors);
                        
                        // Handle authentication errors
                        if (errors.authError && errors.authError === 'unauthenticated') {
                            this.showFeedbackModal(
                                'Authentication Required',
                                errors.message || 'You must be logged in to like photos. Please log in to continue.',
                                true,
                                'login',
                                'Login',
                                'fas fa-sign-in-alt'
                            );
                        } else if (Object.keys(errors).length > 0) {
                            // Generic error handling
                            const errorMessage = errors.message || errors[Object.keys(errors)[0]];
                            this.showFeedbackModal('Error', errorMessage, false, null, 'OK', 'fas fa-times');
                        }
                    }
                });
            },
            toggleDislike() {
                // Check if user is authenticated
                if (!this.isAuthenticated) {
                    this.showFeedbackModal('Authentication Required', 'You must be logged in to dislike photos. Please log in to continue.', true, 'login', 'Login', 'fas fa-sign-in-alt');
                    return;
                }
                
                // Check if user has disliked the photo before
                const formData = useForm({
                    photograph_id: this.photographId,
                    photograph_user_id: this.userId,
                    dislike: !this.userDisliked
                });

                formData.post(route('photographdislike.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                       // console.log('Dislike success:', page.props.flash.success);
                        
                        // Check if there's an authentication error in flash
                        if (page.props.flash.error && page.props.flash.error.authError) {
                            this.showFeedbackModal(
                                'Authentication Required', 
                                page.props.flash.error.message, 
                                true, 
                                'login', 
                                'Login', 
                                'fas fa-sign-in-alt'
                            );
                            return;
                        }
                        
                        if (page.props.flash.success) {
                            const response = page.props.flash.success;
                            
                            // Update galleryPhoto in Vuex store
                            if (this.pageName === 'entrepreneur') {
                                this.$store.dispatch('updateGalleryPhotos', { value: page.props.entrepreneur.galleryPhotos });
                            } else if (this.pageName === 'profilePage') {
                                this.$store.dispatch('updateGalleryPhotos', { value: page.props.galleryPhotos });
                            }
                            
                            // Update states from server response
                            this.userLiked = response.like;
                            this.userDisliked = response.dislike;
                            
                            // Update like and dislike counts from server response
                            this.likes = response.likesCount;
                            this.dislikes = response.dislikesCount;
                            
                            // Emit updated data to parent component
                            this.$emit('updateLikeAndDislike', {
                                likes: response.likesCount,
                                dislikes: response.dislikesCount,
                                userLiked: response.like,
                                userDisliked: response.dislike
                            });
                        }
                    },
                    onError: (errors) => {
                        console.error('Dislike error:', errors);
                        
                        // Handle authentication errors
                        if (errors.authError && errors.authError === 'unauthenticated') {
                            this.showFeedbackModal(
                                'Authentication Required',
                                errors.message || 'You must be logged in to dislike photos. Please log in to continue.',
                                true,
                                'login',
                                'Login',
                                'fas fa-sign-in-alt'
                            );
                        } else if (Object.keys(errors).length > 0) {
                            // Generic error handling
                            const errorMessage = errors.message || errors[Object.keys(errors)[0]];
                            this.showFeedbackModal('Error', errorMessage, false, null, 'OK', 'fas fa-times');
                        }
                    }
                });
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
            }
        }, 
        computed: {
            isAuthenticated() {
                return this.$store.getters.getIsAuthenticated;
            },
        },
        watch: {
            imageData: {
                handler(newImageData) {
                    this.imageObj = { ...newImageData };
                },
                deep: true
            }
        }
    }
</script>
