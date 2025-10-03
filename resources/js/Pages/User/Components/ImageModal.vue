<template>
    <teleport to="body">
        <div class="modal fade" :id="`imageModal-${uniqueId}`" tabindex="-1" :aria-labelledby="`imageModalLabel-${uniqueId}`" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="closeButton"></button>
                    </div>
                    <div class="modal-body">
                        <img :src="imgSrc" class="img-fluid" alt="">
                        <p class="card-text my-2 px-2 bg-dark text-light rounded">{{ caption }}</p>
                        <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>20</a>
                        <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-down pe-2"></i>5</a>
                        <div class="d-flex my-3">
                            <button @click="toggleCaptionInput" class="btn btm-sm col-auto bg-danger text-light me-3">
                                {{ showCaptionInput ? 'Cancel Edit' : 'Edit Caption' }}
                            </button>
                            <button class="btn btm-sm col-auto bg-danger text-light me-3" @click="setAsCoverPhoto">{{ coverPhotoText }}</button>
                            <button class="btn btm-sm col-auto bg-danger text-light" @click="deletePhoto">Delete Photo</button>
                        </div>

                        <div v-if="showCaptionInput" id="captionInput" class="col-12 rounded bg-dark text-light p-2 mb-3">
                            <form action="" @submit.prevent="submitCaption">
                                <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                                <textarea class="rounded form-control my-3" name="" id="" placeholder="Edit Caption" rows="3" v-model="captionInput.val"></textarea>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-danger">Submit</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-12 rounded bg-dark text-light p-2">
                            <p class="pt-2">5 comment(s)</p>
                            <!-- <CommentAndReplyCard v-for="index in 5" :key="index" :index="index" :replies="{}"></CommentAndReplyCard> -->
                            <CommentAndReplyCard v-for="index in 5" :key="index" :index="index"></CommentAndReplyCard>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import CommentCard from './CommentCard.vue';
    import CommentAndReplyCard from './CommentAndReplyCard.vue';
    import ErrorAlert from '@/components/UI/ErrorAlert.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {CommentCard, ErrorAlert, CommentAndReplyCard},
        props: ['userId', 'imageId', 'imgSrc', 'uniqueId', 'caption', 'photoType', 'isCoverPhoto'],
        emits: ['photoDeleted', 'photoUpdated'],
        data() {
            return {
                categoryInput: [],
                showCaptionInput: false,
                captionInput: {
                    val: '',
                    isValid: true
                },
            }
        },
        methods: {
            toggleCaptionInput() {
                this.showCaptionInput = !this.showCaptionInput;
            },
            submitCaption() {
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
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.captionInput.val = '';
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
                        
                        // Revert the local state if there was an error
                        this.isCoverPhotoStatus = !this.isCoverPhotoStatus;
                        if (this.isCoverPhotoStatus) {
                            this.coverPhotoText = 'Remove Cover Photo';
                        } else {
                            this.coverPhotoText = 'Set Image as Cover Photo';
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
                        // Small delay to ensure DOM operations complete
                        this.$nextTick(() => {
                            setTimeout(() => {
                                // Close the modal after successful deletion
                                // this.closeModal();
                                // Emit event to parent component to handle any cleanup
                                this.$emit('photoDeleted', this.imageId);
                            }, 100);
                        });
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            closeModal() {
                console.log('closeModal called for uniqueId:', this.uniqueId);
                
                // Strategy 1: Use Vue ref for the close button (most reliable)
                if (this.$refs.closeButton) {
                    console.log('Using Vue ref close button...');
                    try {
                        this.$refs.closeButton.click();
                        console.log('Vue ref close button click succeeded');
                        
                        // Force cleanup of backdrop and body classes after a short delay
                        setTimeout(() => {
                            this.forceCleanupModal();
                        }, 300); // Wait for Bootstrap animation to complete
                        
                        // Reset modal-specific data
                        this.showCaptionInput = false;
                        this.captionInput.val = '';
                        return; // Exit early if successful
                    } catch (error) {
                        console.log('Vue ref close button failed:', error);
                    }
                }
                
                // Strategy 2: Use Vue's ref approach - get current component's modal
                const currentModal = this.$el.closest('.modal');
                console.log('Current modal via $el:', !!currentModal);
                
                let modalElement = currentModal;
                
                // Strategy 2: Fallback to document search with multiple selectors
                if (!modalElement) {
                    // Try different ID patterns
                    const selectors = [
                        `#imageModal-${this.uniqueId}`,
                        `[id*="imageModal-${this.uniqueId}"]`,
                        `[id*="${this.uniqueId}"]`
                    ];
                    
                    for (const selector of selectors) {
                        modalElement = document.querySelector(selector);
                        console.log(`Trying selector "${selector}":`, !!modalElement);
                        if (modalElement) break;
                    }
                }
                
                // Strategy 3: Find any open modal with this component's image
                if (!modalElement) {
                    const openModals = document.querySelectorAll('.modal.show');
                    console.log('Open modals found:', openModals.length);
                    
                    for (const modal of openModals) {
                        const img = modal.querySelector('img');
                        if (img && img.src === this.imgSrc) {
                            modalElement = modal;
                            console.log('Found modal by matching image src');
                            break;
                        }
                    }
                }
                
                if (modalElement) {
                    console.log('Modal element found, attempting to close...');
                    let modalClosed = false;
                    
                    // Method 1: Click the close button (most reliable with Bootstrap)
                    console.log('Trying close button click...');
                    const closeButton = modalElement.querySelector('.btn-close, [data-bs-dismiss="modal"], [data-dismiss="modal"]');
                    console.log('Close button found:', !!closeButton);
                    
                    if (closeButton) {
                        try {
                            closeButton.click();
                            modalClosed = true;
                            console.log('Close button click succeeded');
                        } catch (error) {
                            console.log('Close button click failed:', error);
                        }
                    }
                    
                    // Method 2: Try Bootstrap Modal API if close button didn't work
                    if (!modalClosed && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        try {
                            console.log('Trying Bootstrap Modal API...');
                            const bootstrapModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                            bootstrapModal.hide();
                            modalClosed = true;
                            console.log('Bootstrap Modal API succeeded');
                        } catch (error) {
                            console.log('Bootstrap Modal API failed:', error);
                        }
                    }
                    
                    // Method 3: Direct DOM manipulation as last resort
                    if (!modalClosed) {
                        console.log('Trying direct DOM manipulation...');
                        modalElement.style.display = 'none';
                        modalElement.classList.remove('show');
                        modalElement.setAttribute('aria-hidden', 'true');
                        
                        // Force comprehensive cleanup
                        this.forceCleanupModal();
                        
                        modalClosed = true;
                        console.log('Direct DOM manipulation completed');
                    }
                    
                    // Reset modal-specific data
                    this.showCaptionInput = false;
                    this.captionInput.val = '';
                    
                    console.log('Modal close process completed');
                } else {
                    console.log('Modal element could not be found using any strategy');
                    console.log('Available modal elements:', document.querySelectorAll('.modal').length);
                    console.log('Available open modals:', document.querySelectorAll('.modal.show').length);
                }
            },
            forceCleanupModal() {
                console.log('Force cleanup modal remnants...');
                
                // Remove all modal backdrops
                const backdrops = document.querySelectorAll('.modal-backdrop');
                console.log('Backdrops found:', backdrops.length);
                backdrops.forEach(backdrop => {
                    backdrop.remove();
                    console.log('Backdrop removed');
                });
                
                // Clean up body classes and styles
                const body = document.body;
                body.classList.remove('modal-open');
                body.style.overflow = '';
                body.style.paddingRight = '';
                
                // Additional cleanup for stubborn body styles
                body.style.removeProperty('overflow');
                body.style.removeProperty('padding-right');
                
                // Ensure all modals are properly hidden
                const openModals = document.querySelectorAll('.modal.show');
                console.log('Open modals to close:', openModals.length);
                openModals.forEach(modal => {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                    console.log('Modal forced closed');
                });
                
                console.log('Force cleanup completed');
            }
        },
        computed: {
            isCoverPhotoStatus() {
                return this.isCoverPhoto || this.photoType === 'cover photo';
            },
            coverPhotoText() {
                return this.isCoverPhotoStatus ? 'Remove Cover Photo' : 'Set Image as Cover Photo';
            }
        },
    }
</script>

<style scoped>
/* Smooth animation for caption input toggle */
#captionInput {
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

/* Button hover effects */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}
</style>