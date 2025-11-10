<template>
    <!-- Hero Section with Carousel -->
    <section id="hero">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div 
                    v-for="(image, index) in slideShowImages" 
                    :key="index" 
                    :class="['carousel-item', { active: index === 0 }]"
                >
                    <img 
                        :src="imagePath(image)" 
                        class="d-block w-100" 
                        alt="Artisan showcase" 
                        style="height: 600px; object-fit: cover;"
                    >
                    <div class="carousel-caption hero-overlay d-flex flex-column align-items-center justify-content-center h-100 top-0 bottom-0">
                        <div class="container">
                            <h1 class="display-3 fw-bold text-white mb-4" style="line-height: 1.2;">
                                Empowering Artisans with Digital Marketing
                            </h1>
                            <p class="lead text-white mb-4 fs-4" style="opacity: 0.9;">
                                Bringing skilled artisans to your doorstep. Connect with verified professionals across Nigeria.
                            </p>
                            <a :href="route('users.create')" class="btn btn-secondary-orange btn-lg px-5 py-3 hover-scale" style="transition: transform 0.3s ease;">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button 
                class="carousel-control-prev" 
                type="button" 
                data-bs-target="#carouselExampleAutoplaying" 
                data-bs-slide="prev"
            >
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button 
                class="carousel-control-next" 
                type="button" 
                data-bs-target="#carouselExampleAutoplaying" 
                data-bs-slide="next"
            >
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
</template>

<script>
// Import images explicitly for Vite (relative path from this component to resources/homePageImages)
import slideShowImage11 from '../../../../homePageImages/slideShowImage11.jpg';
import slideShowImage12 from '../../../../homePageImages/slideShowImage12.jpg';
import slideShowImage13 from '../../../../homePageImages/slideShowImage13.jpg';

export default {
    data() {
        return {
            slideShowImages: [
                slideShowImage11,
                slideShowImage12,
                slideShowImage13,
            ]
        };
    },
    methods: {
        imagePath(imageUrl) {
            // Images are already imported, just return the URL
            return imageUrl;
        }
    }
}
</script>

<style scoped>
/* Make carousel captions cover full slide */
.carousel-caption {
    left: 0;
    right: 0;
}
</style>

