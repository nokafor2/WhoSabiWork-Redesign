<template>
    <div class="col-12 border-bottom py-2">
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
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.
        </p>
        <div class="d-flex justify-content-end">
            <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
        </div>

        <!-- reply -->
        <ReplyCard v-for="(reply, index) in replies" :key="index" :index="index" :reply="reply"></ReplyCard>
        <!-- <div class="row justify-content-end">
            <div class="col-10">
                <div class="row justify-content-between align-content-middle">
                    <div class="col-auto">
                        <div class="d-flex mt-2 mb-1 align-middle">
                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                            <p class="col-auto mb-1">You replied</p>
                        </div>
                    </div>
                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                </div>
                <p class="col-12 bg-white text-body rounded">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                </p>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-10">
                <div class="row justify-content-between align-content-middle">
                    <div class="col-auto">
                        <div class="d-flex mt-2 mb-1 align-middle">
                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                            <p class="col-auto mb-1">You replied</p>
                        </div>
                    </div>
                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                </div>
                <p class="col-12 bg-white text-body rounded">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                </p>
            </div>
        </div> -->
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import ReplyCard from './ReplyCard.vue';
    
    export default {
        components: {ReplyCard},
        mixins: [MethodsMixin],
        props: ['index', 'replies'],
        emits: [],
        data() {
            return {
                
            }
        },
        methods: {
            
        }
    }
</script>