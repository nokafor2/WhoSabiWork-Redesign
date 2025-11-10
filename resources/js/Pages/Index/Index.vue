<template>
    <div>
        <!-- Hero Section with Carousel -->
        <hero-section></hero-section>

        <!-- About Section -->
        <about-section></about-section>

        <!-- Packages Section -->
        <packages-section></packages-section>

        <!-- CTA Section -->
        <CTASection></CTASection>
    </div>
</template>

<script setup>
    import HeroSection from "./Components/HeroSection.vue";
    import AboutSection from "./Components/AboutSection.vue";
    import PackagesSection from "./Components/PackagesSection.vue";
    import CTASection from "./Components/CTASection.vue";
</script>

<script>
export default {
    components: { 
        HeroSection, 
        AboutSection, 
        PackagesSection, 
        CTASection 
    }
}
</script>
