<template>
    <div class="card m-3 shadow bg-light" style="width: 20rem;">
        <div class="d-flex mt-2 mb-1 align-middle">
            <img class="rounded-circle me-2" style="height: 25px; width: 25px;" :src="imagePath(index)">
            <a :href="route('entrepreneur.show', userId)" class="text-decoration-none me-3 text-dark"><h5 class="card-title">{{ businessName }}</h5></a>
        </div>
        <img :src="imagePath(index)" style="height: 18rem;" class="card-img-top" alt="...">
        <!-- <img :src="imagePath" style="height: 18rem;" class="card-img-top" alt="..."> -->
        <div class="card-body px-0 pt-1">
            <span>
                <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>20</a>
                <a href="#" class="text-decoration-none text-body"><i class="fa-solid fa-comment pe-2"></i>90</a>
            </span>
            <p class="card-text mb-1" style="font-size: 10px;">March 09, 2023</p>
            <p class="card-text mt-3 mb-1 border px-2 rounded-pill">Customer's comments</p>
            <p class="card-text mb-1 border px-2 rounded-pill">Customer's comments</p>
            <p class="card-text mb-1 border px-2 rounded-pill">Customer's comments</p>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps(['userId', 'businessName', 'index', 'imagePath']);

    const adImages = ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'];

    function imagePath(index) {
        const imageName = adImages[index];
        const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

        return imageUrl;
    }
</script>