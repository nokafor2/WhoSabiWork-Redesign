<template>
    <div class="card m-3 shadow" style="width: 200px;">
        <div class="image-container" type="button" data-bs-toggle="modal" :data-bs-target="`#imageModal-${uniqueId}`">
            <img :src="imgSrc" class="card-image" alt="">
        </div>
        <p class="card-text mb-1 px-2">{{ caption }}</p>
        
        <!-- Modal -->
        <ImageModal :imgSrc="imgSrc" :userId="userId" :imageId="imageId" :uniqueId="uniqueId" :caption="caption" :photoType="photoType" :isCoverPhoto="isCoverPhoto" @photoDeleted="handlePhotoDeleted" @photoUpdated="handlePhotoUpdated"></ImageModal>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import ImageModal from './ImageModal.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {ImageModal},
        props: ['userId', 'imageId', 'imgSrc', 'caption', 'photoType', 'isCoverPhoto'],
        emits: ['photoDeleted', 'photoUpdated'],
        data() {
            return {
                categoryInput: [],
                uniqueId: null
            }
        },
        created() {
            // Generate a unique ID for this component instance
            this.uniqueId = `${this.userId}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        },
        methods: {
            handlePhotoDeleted(imageId) {
                // Emit the event to the parent component (UserProfile.vue)
                this.$emit('photoDeleted', imageId);
            },
            handlePhotoUpdated(updatedImage) {
                // Emit the event to the parent component (UserProfile.vue)
                this.$emit('photoUpdated', updatedImage);
            }
        },
        computed: {

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

/* Responsive adjustments */
@media (max-width: 768px) {
    .image-container {
        height: 150px; /* Slightly smaller on mobile */
    }
}
</style>