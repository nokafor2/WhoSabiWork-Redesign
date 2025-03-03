<template>
    <div class="row p-3 justify-content-evenly">
        <ad-card v-for="(entrepreneur, index) in entrepreneurs" :key="index"
            :businessName=entrepreneur.business_category.business_name
            :index=index
            :imagePath=imagePath(index)
        ></ad-card>
    </div>
</template>

<script setup>
    import AdCard from "../../components/UI/AdCard.vue";

    defineProps(['entrepreneurs']);

    const adImages = ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'];

    function imagePath(index) {
        const imageName = adImages[index];
        const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

        return imageUrl;
    }
</script>