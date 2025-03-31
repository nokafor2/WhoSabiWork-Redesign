<template>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img :src=imagePath(index) class="img-fluid" alt="">
                    <p class="card-text my-2 px-2 bg-dark text-light rounded">A beautiful cruise boat parked in the harbor waiting a beautiful environment.</p>
                    <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>20</a>
                    <div class="d-flex my-3">
                        <button class="btn btm-sm col-auto bg-danger text-light me-3">Edit Caption</button>
                        <button class="btn btm-sm col-auto bg-danger text-light me-3">Set Image as Cover Photo</button>
                        <button class="btn btm-sm col-auto bg-danger text-light">Delete Photo</button>
                    </div>

                    <div class="col-12 rounded bg-dark text-light p-2 mb-3">
                        <form action="">
                            <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                            <textarea class="rounded form-control my-3" name="" id="" placeholder="Edit Caption" rows="3"></textarea>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 rounded bg-dark text-light p-2">
                        <p class="pt-2">5 comment(s)</p>
                        <CommentCard v-for="index in 5" :key="index" :index="index"></CommentCard>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import CommentCard from './CommentCard.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        components: {CommentCard},
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

        },
    }
</script>