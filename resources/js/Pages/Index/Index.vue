<template>
    <section>
        <image-carousel></image-carousel>
        <about-layout></about-layout>
    </section>
</template>

<script setup>
    import AboutLayout from "./Components/AboutLayout.vue";
    import ImageCarousel from "./Components/ImageCarousel.vue";

    // defineProps(['mobileMarketers']);

    

    

</script>

<script>
export default {
    components: { AboutLayout, ImageCarousel }
}
</script>
  