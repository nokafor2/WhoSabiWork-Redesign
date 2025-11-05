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
        <ReplyCard v-for="(reply, index) in replies" :key="index" :replyData="reply"></ReplyCard>
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
            pageName: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                replies: this.commentReplies?.replies || [],
                commentId: this.commentReplies?.id || '',
                comment: this.commentReplies?.comment || '',
                entrepreneurId: this.commentReplies?.entrepreneurId || '',
                commentUserId: this.commentReplies?.commenterId || '',
                commenterFullName: this.commentReplies?.commenterFullName || '',
                commenterAvatar: this.commentReplies?.commenterAvatar || '',
                commentDate: this.commentReplies?.commentDate || '',
                showReplyForm: false,
                replyMessage: {
                    val: '',
                    isValid: true,
                    errorMessage: ''
                },
                formData: useForm({
                    comment_id: this.commentReplies?.id || '',
                    user_id_comment: this.commentReplies?.user_id_comment || '',
                    body: ''
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
                this.formData.user_id_comment = this.commentUserId;
                this.formData.body = this.replyMessage.val.trim();

                this.formData.post(route('reply.store'), {
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Update the customer comments and replies state in Vuex
                        if (page.props.customerCommentsAndReplies) {
                            this.$store.dispatch('updateCustomerCommentsAndReplies', { value: page.props.customerCommentsAndReplies });
                        }

                        // Hide the reply form
                        this.showReplyForm = false;
                        // Reset the reply message
                        this.replyMessage.val = '';
                        // Reset the reply message validation
                        this.replyMessage.isValid = true;
                        this.replyMessage.errorMessage = '';
                        // Reset the form reply field
                        this.formData.body = '';

                        // Add the new reply to the local replies array for immediate UI update
                        if (page.props.flash?.success) {
                            const replyData = page.props.flash.success;
                            
                            // Add reply to the beginning of the replies array
                            this.replies.unshift(replyData);
                        }
                    },
                    onError: (errors) => {
                        this.replyMessage.isValid = false;
                        this.replyMessage.errorMessage = errors.body ? errors.body[0] : 'Failed to submit reply';
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
        },
        watch: {
            'commentReplies.replies': {
                handler(newReplies) {
                    if (newReplies) {
                        this.replies = newReplies;
                    }
                },
                deep: true
            }
        }
    }
</script>