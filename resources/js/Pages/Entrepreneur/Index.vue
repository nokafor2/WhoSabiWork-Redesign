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

    <section class="row row-cols-xs-1 row-cols-2 justify-content-evenly">
        <BusinessDetails :user="entrepreneur"></BusinessDetails>
        
        <BusinessDetails2 :user="entrepreneur"></BusinessDetails2>
    </section>

    <!-- Tab Menu for Photo Gallery and Comments -->
    <section class="row my-3 bg-light rounded">
        <div class="col">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab">
                        Gallery
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        Reviews
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="gallery" role="tabpanel">
                    <!-- Photo gallery -->
                    <div class="gallery">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md 10">
                                    <div class="row justify-content-evenly">
                                        <ImageCard v-for="image in galleryPhotos" :key="image.id" :imageData="image" :pageName="pageName"></ImageCard>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="tab-pane fade show" id="reviews" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-xl-6 rounded bg-dark text-light pb-3">
                            <form @submit.prevent="submitComment">
                                <textarea 
                                    class="rounded form-control my-3" 
                                    :class="{ 'is-invalid': !commentInput.isValid }"
                                    name="" 
                                    id="" 
                                    placeholder="Enter your comment" 
                                    rows="2" 
                                    v-model="commentInput.val"
                                    @input="resetValidation">
                                </textarea>
                                <div v-if="!commentInput.isValid" class="invalid-feedback d-block">
                                    {{ commentInput.errorMessage }}
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-sm btn-danger">Comment</button>
                                    <p class="pt-2">{{ commentsAndReplies.commentsCount }} comment(s)</p>
                                </div>
                            </form>

                            <CommentAndReplyCard v-for="comment in commentsAndReplies.commentsAndReplies" :key="comment.id" :commentReplies="comment"></CommentAndReplyCard>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import BusinessDetails from '@/components/UI/BusinessDetails.vue';
    import BusinessDetails2 from '@/components/UI/BusinessDetails2.vue';
    import CommentAndReplyCard from '../User/Components/CommentAndReplyCard.vue';
    import ImageCard from '../User/Components/ImageCard.vue';
    import FeedbackModal from '@/components/UI/FeedbackModal.vue';
    import { usePage, useForm } from '@inertiajs/vue3';

    export default {
        components: {BusinessDetails, BusinessDetails2, CommentAndReplyCard, ImageCard, FeedbackModal},
        props: ['entrepreneur'],
        emits: [],
        
        setup() {
            const page = usePage();
            return { page };
        },
        
        data() {
            return {
                pageName: 'entrepreneur',
                commentInput: {
                    val: '',
                    isValid: true,
                    errorMessage: '',
                },
                feedbackModal: {
                    visible: false,
                    title: 'Notification',
                    message: '',
                    showActionButton: false,
                    actionButtonText: 'Login',
                    actionIcon: 'fas fa-sign-in-alt',
                },
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            submitComment() {
                // Validate comment input
                const trimmedComment = this.commentInput.val ? this.commentInput.val.trim() : '';
                
                if (!trimmedComment) {
                    this.commentInput.isValid = false;
                    this.commentInput.errorMessage = 'Please enter a comment';
                    return;
                }

                // Reset validation state before submission
                this.commentInput.isValid = true;
                this.commentInput.errorMessage = '';

                const formData = useForm({
                    user_id: this.entrepreneurData.userDetails.id,
                    body: trimmedComment
                });
                
                formData.post(route('comment.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('success', page.props.flash.success);
                        console.log('Updated page data:', page.props);

                        // Check if there's an error in flash (authentication error)
                        if (page.props.flash.error) {
                            this.showFeedbackModal('Authentication Required', page.props.flash.error, true);
                            return;
                        }

                        // Update the entrepreneur data in the Vuex store
                        this.$store.dispatch('updateEntrepreneur', { value: page.props.entrepreneur });
                        // Update the comments and replies in the Vuex store
                        this.$store.dispatch('updateCustomerCommentsAndReplies', { value: page.props.entrepreneur.commentsAndReplies });

                        // Force Vue to detect the change
                        this.$forceUpdate();

                        // Reset the comment input
                        this.commentInput.val = '';
                        this.commentInput.isValid = true;
                        this.commentInput.errorMessage = '';
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        
                        // Handle validation errors
                        if (errors.body) {
                            this.commentInput.isValid = false;
                            this.commentInput.errorMessage = errors.body;
                        }
                        
                        // Handle authentication errors
                        if (errors.message && errors.message.includes('unauthenticated')) {
                            this.showFeedbackModal(
                                'Authentication Required',
                                'You must be logged in to post a comment. Please log in to continue.',
                                true
                            );
                        } else if (Object.keys(errors).length > 0) {
                            // Generic error handling
                            const errorMessage = errors[Object.keys(errors)[0]];
                            this.showFeedbackModal('Error', errorMessage, false);
                        }
                    }
                });
            },
            resetValidation() {
                // Reset validation state when user starts typing
                if (!this.commentInput.isValid) {
                    this.commentInput.isValid = true;
                    this.commentInput.errorMessage = '';
                }
            },
            showFeedbackModal(title, message, showLoginButton = false) {
                this.feedbackModal.title = title;
                this.feedbackModal.message = message;
                this.feedbackModal.showActionButton = showLoginButton;
                this.feedbackModal.visible = true;
            },
            closeFeedbackModal() {
                this.feedbackModal.visible = false;
            },
            handleFeedbackAction() {
                // Redirect to login page
                window.location.href = route('login');
            }
        },
        computed: {
            entrepreneurData() {
                return this.$store.getters.getEntrepreneur;
            },
            galleryPhotos() {
                return this.$store.getters.getGalleryPhotos;
            },
            commentsAndReplies() {
                return this.$store.getters.getCustomerCommentsAndReplies;
            }
        },
        mounted() {
            this.$store.dispatch('updateEntrepreneur', { value: this.entrepreneur });
            this.$store.dispatch('updateGalleryPhotos', { value: this.entrepreneur.galleryPhotos });
            this.$store.dispatch('updateCustomerCommentsAndReplies', { value: this.entrepreneur.commentsAndReplies });
        }
    }
</script>