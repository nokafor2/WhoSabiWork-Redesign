<template>
    <div class="col-12 border-bottom pt-2">
        <div class="row justify-content-between align-content-middle">
            <div class="col-auto">
                <div class="d-flex mt-2 mb-1 align-middle">
                    <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                    <p class="col-auto mb-1">Nna-ayua Okafor</p>
                </div>
            </div>
            <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
        </div>
        <p class="col-12 bg-white text-body rounded">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
        </p>
    </div>
</template>


<script>
    export default {
        props: [],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        }
    }
</script>