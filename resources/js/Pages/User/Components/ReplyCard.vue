<template>
    <div v-if="replyObj && Object.keys(replyObj).length > 0" class="row justify-content-end">
        <div class="col-10">
            <div class="row justify-content-between align-content-middle">
                <div class="col-auto">
                    <div class="d-flex mt-2 mb-1 align-middle">
                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src="replierAvatar">
                        <!-- <p class="col-auto mb-1">You replied</p> -->
                        <p class="col-auto mb-1">{{ replierFullName }}</p>
                    </div>
                </div>
                <p class="col-auto pt-2">{{ replyDate }}</p>
            </div>
            <p class="col-12 bg-white text-body rounded">
                {{ reply }}
            </p>
        </div>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';

    export default {
        mixins: [MethodsMixin],
        props: {
            index: {
                type: Number,
                default: 0
            },
            replyData: {
                type: Object,
                default: () => ({})
            }
        },
        emits: [],  
        data() {
            return {
                replyObj: this.replyData,
                reply: this.replyData?.reply || '',
                commentOwner: this.replyData?.comment_owner || [],
                replyUser: this.replyData?.reply_user || [],
                replierFullName: this.replyData?.replier_full_name || '',
                replierAvatar: this.replyData?.replier_avatar || '',
                replyDate: this.replyData?.created_at_human || '',
            }
        },
        methods: {
            
        }
    }
</script>