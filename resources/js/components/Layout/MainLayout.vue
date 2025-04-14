<template>
    <Navigation></Navigation>
    <!-- <div id="mainContainer" class="container-lg bg-white mt-5 flex-grow-1" style="position: relative; top: 15px;"> -->
    <div id="mainContainer" class="container-lg bg-white mt-5 min-vh-100 d-flex flex-column" style="position: relative; top: 15px;">
        <slot></slot>
    </div>
    <FooterLayout></FooterLayout>
</template>

<script setup>
    import Navigation from '@/components/Layout/Navigation.vue';
    import FooterLayout from '@/components/Layout/FooterLayout.vue';
</script>