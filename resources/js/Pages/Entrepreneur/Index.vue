<template>
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
                                        <!-- <PhotoCard v-for="(id, index) in adImages" :key="id" :photoId="index" ></PhotoCard> -->
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
                            <form action="">
                                <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                                <textarea class="rounded form-control my-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-danger">Submnit</button>
                                    <p class="pt-2">{{ commentsAndReplies.commentsCount }} comment(s)</p>
                                </div>
                            </form>

                            <!-- <MessageCard v-for="index in 10" :key="index" :photoId="index"></MessageCard> -->
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
    import PhotoCard from '@/components/UI/PhotoCard.vue';
    import MessageCard from '@/components/UI/MessageCard.vue';
    import CommentAndReplyCard from '../User/Components/CommentAndReplyCard.vue';
    import ImageCard from '../User/Components/ImageCard.vue';
    import { usePage } from '@inertiajs/vue3';

    export default {
        components: {BusinessDetails, BusinessDetails2, PhotoCard, MessageCard, CommentAndReplyCard, ImageCard},
        props: ['entrepreneur'],
        emits: [],
        
        setup() {
            const page = usePage();
            return { page };
        },
        
        data() {
            return {
                pageName: 'entrepreneur',
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        },
        computed: {
            galleryPhotos() {
                return this.$store.getters.getGalleryPhotos;
            },
            commentsAndReplies() {
                return this.$store.getters.getCustomerCommentsAndReplies;
            }
        },
        mounted() {
            this.$store.dispatch('updateGalleryPhotos', { value: this.entrepreneur.galleryPhotos });
            this.$store.dispatch('updateCustomerCommentsAndReplies', { value: this.entrepreneur.commentsAndReplies });
        }
    }
</script>