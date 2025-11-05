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
        <div v-if="showReplyBtn" class="d-flex justify-content-end">
            <button class="btn btm-sm border border-light text-light" style="background-color: #040D14" @click="toggleReplyForm">Reply</button>
        </div>

        <!-- reply form -->
        <div v-if="showReplyForm" class="col-12 mt-3 p-3 border rounded bg-light">
            <textarea 
                v-model="replyMessage.val"
                @input="validateReply"
                @blur="validateReply"
                :class="['form-control mb-1', { 'is-invalid': !replyMessage.isValid }]"
                rows="1" 
                placeholder="Write your reply..."
                maxlength="255">
            </textarea>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <small v-if="!replyMessage.isValid" class="text-danger">
                    {{ replyMessage.errorMessage }}
                </small>
                <small v-else class="text-muted invisible">No errors</small>
                <small class="text-muted">
                    {{ replyMessage.val.length }}/255
                </small>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-sm btn-secondary" @click="cancelReply">Cancel</button>
                <button 
                    class="btn btn-sm text-light" 
                    style="background-color: #040D14" 
                    @click="submitReply"
                    :disabled="!replyMessage.isValid || replyMessage.val.trim().length === 0">
                    Reply
                </button>
            </div>
        </div>

        <!-- reply -->
        <PhotoReplyCard v-for="(reply, index) in replies" :key="index" :replyData="reply"></PhotoReplyCard>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import PhotoReplyCard from './PhotoReplyCard.vue';
    import { useForm } from '@inertiajs/vue3';
    
    export default {
        components: {PhotoReplyCard},
        mixins: [MethodsMixin],
        props: {
            commentReplies: {
                type: Object,
                default: () => ({})
            },
            pageName: {
                type: String,
                default: ''
            }
        },
        emits: ['reply-added'],
        data() {
            return {
                replies: this.commentReplies?.photograph_replies || [],
                commentId: this.commentReplies?.id || '',
                comment: this.commentReplies?.comment || '',
                photographId: this.commentReplies?.photograph_id || '',
                commentUserId: this.commentReplies?.user_id_comment || '',
                commentUser: this.commentReplies?.comment_user || [],
                commenterFullName: this.commentReplies?.commenter_full_name || '',
                commenterAvatar: this.commentReplies?.commenter_avatar || '',
                commentDate: this.commentReplies?.created_at_human || '',
                showReplyForm: false,
                replyMessage: {
                    val: '',
                    isValid: true,
                    errorMessage: ''
                },
                formData: useForm({
                    comment_id: this.commentReplies?.id || '',
                    photograph_id: this.commentReplies?.photograph_id || '',
                    user_id_comment: this.commentReplies?.user_id_comment || '',
                    reply: ''
                })
            }
        },
        methods: {
            validateReply() {
                const reply = this.replyMessage.val.trim();
                
                // Reset validation
                this.replyMessage.isValid = true;
                this.replyMessage.errorMessage = '';
                
                // Check if empty
                if (reply.length === 0) {
                    this.replyMessage.isValid = false;
                    this.replyMessage.errorMessage = 'Reply cannot be empty';
                    return false;
                }
                
                // Check minimum length
                if (reply.length < 2) {
                    this.replyMessage.isValid = false;
                    this.replyMessage.errorMessage = 'Reply must be at least 2 characters';
                    return false;
                }
                
                // Check maximum length
                if (reply.length > 255) {
                    this.replyMessage.isValid = false;
                    this.replyMessage.errorMessage = 'Reply cannot exceed 255 characters';
                    return false;
                }
                
                // Check for only whitespace
                if (reply.replace(/\s/g, '').length === 0) {
                    this.replyMessage.isValid = false;
                    this.replyMessage.errorMessage = 'Reply cannot contain only spaces';
                    return false;
                }
                
                return true;
            },
            toggleReplyForm() {
                this.showReplyForm = !this.showReplyForm;
            },
            cancelReply() {
                this.showReplyForm = false;
                this.replyMessage.val = '';
                this.replyMessage.isValid = true;
                this.replyMessage.errorMessage = '';
            },
            submitReply() {
                // Validate before submitting
                if (!this.validateReply()) {
                    return;
                }
                
                // Update form data with current values
                this.formData.comment_id = this.commentId;
                this.formData.photograph_id = this.photographId;
                this.formData.user_id_comment = this.commentUserId;
                this.formData.reply = this.replyMessage.val.trim();

                this.formData.post(route('photographreply.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Update the image state
                        if (page.props.images) {
                            this.$store.dispatch('updateImages', { value: page.props.images });
                        }

                        // Hide the reply form
                        this.showReplyForm = false;
                        // Reset the reply message
                        this.replyMessage.val = '';
                        // Reset the reply message validation
                        this.replyMessage.isValid = true;
                        this.replyMessage.errorMessage = '';
                        // Reset the form reply field
                        this.formData.reply = '';
                        
                        // Add the reply to the replies array via parent component
                        if (page.props.flash?.success) {
                            const replyData = page.props.flash.success;
                            
                            // Emit event to parent component (ImageCard) to update imageObj
                            // The watcher will automatically update this.replies when the parent updates
                            this.$emit('reply-added', {
                                photographId: replyData.photograph_id,
                                commentId: replyData.comment_id,
                                replyData: replyData
                            });
                        }
                    },
                    onError: (errors) => {
                        // Show validation error from server
                        if (errors.reply) {
                            this.replyMessage.isValid = false;
                            this.replyMessage.errorMessage = errors.reply;
                        }
                    }
                });
            }
        },
        computed: {
            isAuthenticated() {
                return this.$store.getters.getIsAuthenticated;
            },
            authenticatedUser() {
                return this.$store.getters.getAuthenticatedUser;
            },
            showReplyBtn() {
                if (this.pageName === 'entrepreneur') {
                    if (this.isAuthenticated && this.authenticatedUser.id === this.entrepreneurId) {
                        return true;
                    } else {
                        return false;
                    }
                } else if (this.pageName === 'profilePage') {
                    return true;
                } 
            }
        }
    }
</script>

