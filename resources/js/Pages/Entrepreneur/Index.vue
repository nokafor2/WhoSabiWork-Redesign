<template>
    <section class="row row-cols-xs-1 row-cols-2 justify-content-evenly">
        <BusinessDetails :user="user"></BusinessDetails>
        
        <BusinessDetails2 :user="user"></BusinessDetails2>
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
                                        <PhotoCard v-for="(id, index) in adImages" :key="id" :photoId="index" ></PhotoCard>
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
                                    <p class="pt-2">5 comment(s)</p>
                                </div>
                            </form>

                            <MessageCard v-for="index in 10" :key="index" :photoId="index"></MessageCard>
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

    export default {
        components: {BusinessDetails, BusinessDetails2, PhotoCard, MessageCard},
        props: ['user'],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        }
    }
</script>