<template>
    <div v-if="commentReplies && Object.keys(commentReplies).length > 0" class="col-12 border-bottom py-2">
        <div class="row justify-content-between align-content-middle">
            <div class="col-auto">
                <div class="d-flex mt-2 mb-1 align-middle">
                    <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src="commenterAvatar">
                    <p class="col-auto mb-1">{{ commenterFullName }}</p>
                </div>
            </div>
            <p class="col-auto pt-2">{{ commentDate }}</p>
        </div>
        <p class="col-12 bg-white text-body rounded">
            {{ comment }}
            <!-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo? -->
        </p>
        <div class="d-flex justify-content-end">
            <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
        </div>

        <!-- reply -->
        <ReplyCard v-for="(reply, index) in replies" :key="index" :index="index" :replyData="reply"></ReplyCard>
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
    import { useForm } from '@inertiajs/vue3';
    
    export default {
        components: {ReplyCard},
        mixins: [MethodsMixin],
        props: {
            commentReplies: {
                type: Object,
                default: () => ({})
            },
            index: {
                type: Number,
                default: 0
            }
        },
        emits: [],
        data() {
            return {
                replies: this.commentReplies?.photograph_replies || [],
                comment: this.commentReplies?.comment || '',
                commentUser: this.commentReplies?.comment_user || [],
                commenterFullName: this.commentReplies?.commenter_full_name || '',
                commenterAvatar: this.commentReplies?.commenter_avatar || '',
                commentDate: this.commentReplies?.created_at_human || '',
            }
        },
        methods: {
            
        },
    }
</script>