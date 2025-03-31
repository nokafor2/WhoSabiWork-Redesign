<template>
    <div class="card m-3 shadow" style="width: 200px;">
        <img :src=imagePath(index) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal">
        <p class="card-text mb-1 px-2">Image caption</p>
        
        <!-- Modal -->
        <ImageModal v-for="index in 5" :key="index" :index="index" :userId="index"></ImageModal>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import ImageModal from './ImageModal.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {ImageModal},
        props: ['index', 'userId'],
        emits: [],
        data() {
            return {
                categoryInput: []
            }
        },
        methods: {

        },
        computed: {

        }
    }
</script>