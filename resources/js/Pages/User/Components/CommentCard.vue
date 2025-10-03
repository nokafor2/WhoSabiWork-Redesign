<template>
    <div class="col-12 border-bottom pt-2">
        <div class="row justify-content-between align-content-middle">
            <div class="col-auto">
                <div class="d-flex mt-2 mb-1 align-middle">
                    <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(index)>
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
    import MethodsMixin from '../Mixins/MethodsMixin.js';

    export default {
        mixins: [MethodsMixin],
        props: ['index', 'photoComment'],
        emits: [],
        data() {
            return {
                comment: this.photoComment,
                // photoReplies: this.photoComment.photograph_replies,
            }
        },
        methods: {
            
        }
    }
</script>