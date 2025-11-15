<template>
    <!-- Advertisement Card -->
    <div class="card m-3 shadow bg-light" style="width: 20rem;">
        <div class="d-flex mt-2 mb-1 align-middle">
            <img class="rounded-circle me-2" style="height: 25px; width: 25px;" :src="userAvatar">
            <a :href="route('entrepreneur.show', userId)" class="me-3 text-dark"><h5 class="card-title">{{ businessName }}</h5></a>
        </div>
        <img :src="imagePath" style="height: 18rem;" class="card-img-top" alt="...">
        <p class="card-text mb-1 caption-truncate">{{ photoCaption }}</p>
        <div class="card-body px-0 pt-1">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>
                    <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>{{ photoLikes }}</a>
                    <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-down pe-2"></i>{{ photoDislikes }}</a>
                    <a href="#" class="text-decoration-none text-body"><i class="fa-solid fa-comment pe-2"></i>{{ commentCount }}</a>
                </span>
                <span class="text-muted" style="font-size: 10px;">{{ photoDate }}</span>
            </div>
            <!-- Button Style (Original) -->
            <!-- <button v-if="commentsAvailable" class="btn btm-sm border border-light text-light"  style="background-color: #040D14" @click="toggleCommentsModal">View Comments</button> -->
            
            <!-- Link Style (New) -->
            <a v-if="commentsAvailable" href="#" class="text-decoration-none mt-3" style="color: #040D14; font-weight: 500;" @click.prevent="toggleCommentsModal">
                <i class="fas fa-comments me-1"></i>View Comments
            </a>
        </div>
    </div>

    <!-- Comments Modal -->
    <teleport to="body">
        <div v-if="showCommentsModal" class="modal-backdrop" @click="toggleCommentsModal">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" @click.stop>
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle me-2" style="height: 30px; width: 30px;" :src="userAvatar" alt="User Avatar">
                            <a :href="route('entrepreneur.show', userId)" class="text-dark"><h5 class="modal-title mb-0">{{ businessName }}</h5></a>
                        </div>
                        <button type="button" class="btn-close" @click="toggleCommentsModal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Photo -->
                        <div class="text-center mb-4">
                            <img :src="imagePath" class="img-fluid rounded shadow-sm" alt="Photo">
                        </div>
                        
                        <!-- Caption Display -->
                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <p class="mb-0">{{ photoCaption }}</p>
                                    <p class="text-muted mb-0 mt-1" style="font-size: 12px;">{{ photoDate }}</p>
                                </div>
                            </div>
                        </div>

                        <LikeAndDislikeCard :likeAndDislikeData="likeAndDislikeData" @updateLikeAndDislike="handleLikeAndDislikeUpdate"></LikeAndDislikeCard>
                        
                        <CommentForm :commentData="commentData" @updateComment="handleCommentUpdate"></CommentForm>
                        
                        <!-- Comments Section -->
                        <div class="comments-section">
                            <h6 class="mb-3">
                                <i class="fas fa-comments me-2"></i>Comments
                                <span class="badge bg-primary ms-2">{{ commentCount }}</span>
                            </h6>
                            <div v-if="photoCommentsAndReplies && photoCommentsAndReplies.length > 0" class="comments-list">
                                <PhotoCommentAndReplyCard 
                                    v-for="commentAndReply in photoCommentsAndReplies" 
                                    :key="`comment-${commentAndReply.id}-${commentAndReply.photograph_replies?.length || 0}`" 
                                    :commentReplies="commentAndReply"
                                    :pageName="pageName"
                                    @reply-added="handleReplyAdded"
                                >
                                </PhotoCommentAndReplyCard>
                            </div>
                            <div v-else class="text-center text-muted py-5">
                                <i class="fas fa-comment-slash fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">No comments yet</p>
                                <p class="small">Be the first to comment!</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btm-sm border border-light text-light" style="background-color: #040D14" @click="toggleCommentsModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>


<script>
    import PhotoCommentAndReplyCard from '@/Pages/User/Components/PhotoCommentAndReplyCard.vue';
    import LikeAndDislikeCard from '@/Pages/User/Components/LikeAndDislikeCard.vue';
    import CommentForm from '@/Pages/User/Components/CommentForm.vue';
    
    export default {
        components: { PhotoCommentAndReplyCard, LikeAndDislikeCard, CommentForm },
        props: ['photoData'],
        data() {
            return {
                photoRecord: this.photoData,    
                pageName: 'photoFeed',
                photographId: this.photoData.id,
                userId: this.photoData.user_id,
                businessName: this.photoData.business_name,
                userAvatar: this.photoData.user_avatar,
                imagePath: this.photoData.src,
                photoDate: this.photoData.created_at_human,
                photoCommentsAndReplies: this.photoData.photograph_comments,
                photoLikes: this.photoData.active_likes_count,
                photoDislikes: this.photoData.active_dislikes_count,
                commentCount: this.photoData.photograph_comments_count,
                photoCaption: this.photoData.caption,
                showCommentsModal: false,
                likeAndDislikeData: {
                    pageName: 'photoFeed',
                    userId: this.photoData.user_id,
                    photographId: this.photoData.id,
                    likes: this.photoData.active_likes_count || 0,
                    dislikes: this.photoData.active_dislikes_count || 0,
                    userLiked: this.photoData.userLiked || false,
                    userDisliked: this.photoData.userDisliked || false,
                },
                commentData: {
                    pageName: 'photoFeed',
                    commentCount: this.photoData.photograph_comments?.length || 0,
                    photographId: this.photoData.id,
                    photographUserId: this.photoData.user_id,
                }
            }
        },
        methods: {
            toggleCommentsModal() {
                this.showCommentsModal = !this.showCommentsModal;
                // Prevent body scroll when modal is open
                if (this.showCommentsModal) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            },
            toggleLike() {
                console.log('toggleLike');
            },
            toggleDislike() {
                console.log('toggleDislike');
            },
            // Handle like/dislike updates from LikeAndDislikeCard component
            handleLikeAndDislikeUpdate(data) {
                // Update the local state with the new values from the child component
                this.photoLikes = data.likes;
                this.photoDislikes = data.dislikes;
                
                // Update the likeAndDislikeData object to keep it in sync
                this.likeAndDislikeData.likes = data.likes;
                this.likeAndDislikeData.dislikes = data.dislikes;
                this.likeAndDislikeData.userLiked = data.userLiked;
                this.likeAndDislikeData.userDisliked = data.userDisliked;
                
                // Force Vue to detect the change
                this.$forceUpdate();
            },
            // Handle reply added event from CommentAndReplyCard
            handleReplyAdded({ photographId, commentId, replyData }) {
                // Find the comment in imageObj.photograph_comments and add the reply
                if (this.photoRecord.photograph_comments) {
                    const commentIndex = this.photoRecord.photograph_comments.findIndex(c => c.id === commentId);
                    
                    if (commentIndex !== -1) {
                        const comment = this.photoRecord.photograph_comments[commentIndex];
                        
                        // Initialize photograph_replies if it doesn't exist
                        if (!comment.photograph_replies) {
                            comment.photograph_replies = [];
                        }
                        
                        // Add the new reply at the beginning (latest first)
                        comment.photograph_replies = [replyData, ...comment.photograph_replies];
                        
                        // Force Vue to recognize the change by creating a new array reference
                        this.photoRecord.photograph_comments = [...this.photoRecord.photograph_comments];
                        
                        // Force update to ensure reactivity
                        this.$forceUpdate();
                    } else {
                        console.warn('Comment not found in photoRecord for commentId:', commentId);
                    }
                }
            },
            handleCommentUpdate(data) {
                // Update comment count
                this.commentCount = data.commentCount;
                this.commentData.commentCount = data.commentCount;
                
                // Add the new comment to the comments list for real-time UI update
                if (data.commentData) {
                    // Initialize photograph_replies as empty array for the new comment
                    const newComment = {
                        ...data.commentData,
                        photograph_replies: []
                    };
                    
                    // Add the new comment at the beginning (latest first)
                    if (this.photoCommentsAndReplies) {
                        this.photoCommentsAndReplies = [newComment, ...this.photoCommentsAndReplies];
                    } else {
                        this.photoCommentsAndReplies = [newComment];
                    }
                    
                    // Update the photoRecord as well
                    if (this.photoRecord.photograph_comments) {
                        this.photoRecord.photograph_comments = [newComment, ...this.photoRecord.photograph_comments];
                    } else {
                        this.photoRecord.photograph_comments = [newComment];
                    }
                    
                    // Force Vue to detect the change
                    this.$forceUpdate();
                }
            },
        },
        computed: { 
            commentsAvailable() {
                if (this.commentCount > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        beforeUnmount() {
            // Ensure body scroll is restored when component is destroyed
            document.body.style.overflow = '';
        }
    }
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
    overflow-y: auto;
    padding: 1rem;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-dialog {
    max-width: 700px;
    width: 100%;
    margin: 0 auto;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: scale(0.9) translateY(-50px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

.modal-content {
    background-color: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    font-weight: 600;
    color: #212529;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
    padding: 1.5rem;
    background-color: #f8f9fa;
}

/* Custom scrollbar for modal body */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #6c757d;
}

/* Photo section styling */
.modal-body .img-fluid {
    border-radius: 0.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.modal-body .img-fluid:hover {
    transform: scale(1.02);
}

/* Stats section */
.modal-body .d-flex.gap-3 {
    padding: 1rem;
    background-color: #ffffff;
    border-radius: 0.375rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.comments-section {
    margin-top: 0;
}

.comments-section h6 {
    font-weight: 600;
    color: #495057;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #dee2e6;
}

/* Comments container */
.comments-section > div {
    background-color: #ffffff;
    border-radius: 0.375rem;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Empty state styling */
.text-center.text-muted.py-4 {
    background-color: #ffffff;
    border-radius: 0.375rem;
    border: 2px dashed #dee2e6;
}

.modal-footer {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #e9ecef, #f8f9fa);
    border-top: 1px solid #dee2e6;
}

/* Button hover effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn:active {
    transform: translateY(0);
}

/* Close button styling */
.btn-close {
    transition: transform 0.2s ease;
}

.btn-close:hover {
    transform: rotate(90deg);
}

/* Card styling improvements */
.card.m-3 {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card.m-3:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2) !important;
}

/* View Comments Link Hover Effect */
.card-body > a[href="#"] {
    transition: color 0.3s ease, transform 0.2s ease;
    display: inline-block;
}

.card-body > a[href="#"]:hover {
    color: #0056b3 !important;
    transform: translateX(5px);
}

.card-body > a[href="#"]:hover i {
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-3px);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-backdrop {
        padding: 0.5rem;
    }
    
    .modal-dialog {
        max-width: 95%;
    }
    
    .modal-header {
        padding: 1rem 1.25rem;
    }
    
    .modal-body {
        max-height: 65vh;
        padding: 1.25rem;
    }
    
    .modal-footer {
        padding: 0.875rem 1.25rem;
    }
}

@media (max-width: 576px) {
    .modal-backdrop {
        padding: 0.25rem;
    }
    
    .modal-dialog {
        max-width: 98%;
    }
    
    .modal-header {
        padding: 0.875rem 1rem;
    }
    
    .modal-body {
        max-height: 60vh;
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 0.75rem 1rem;
    }
    
    .modal-title {
        font-size: 1rem;
    }
}

/* Prevent scrolling when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Caption truncation - limit to 2 lines with ellipsis */
.caption-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.5;
    max-height: 3em; /* 2 lines × 1.5 line-height */
}
</style>