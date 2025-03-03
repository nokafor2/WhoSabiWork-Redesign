<template>
    <Navigation></Navigation>
    <div id="mainContainer" class="container-lg bg-white mt-5 flex-grow-1" style="position: relative; top: 15px;">
        <slot></slot>
    </div>
    <FooterLayout></FooterLayout>
</template>

<script setup>
    import Navigation from './Navigation.vue';
    import FooterLayout from './FooterLayout.vue';
</script>