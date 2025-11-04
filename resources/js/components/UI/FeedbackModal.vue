<template>
    <!-- Teleported Modal -->
    <teleport to="body">
        <transition name="modal-fade">
            <div v-if="isVisible" class="feedback-modal-overlay" @click="handleOverlayClick">
                <div class="feedback-modal-container" @click.stop>
                    <!-- Modal Header -->
                    <div class="feedback-modal-header">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-danger me-2 fa-lg"></i>
                            <h5 class="mb-0">{{ title }}</h5>
                        </div>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="feedback-modal-body">
                        <p class="mb-0">{{ message }}</p>
                        <slot></slot>
                    </div>

                    <!-- Modal Footer -->
                    <div class="feedback-modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            <i class="fas fa-times me-1"></i>Close
                        </button>
                        <button v-if="showActionButton" type="button" class="btn btn-danger" @click="handleAction">
                            <i :class="actionIcon" class="me-1"></i>{{ actionButtonText }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </teleport>
</template>

<script>
export default {
    name: 'FeedbackModal',
    props: {
        title: {
            type: String,
            default: 'Notification'
        },
        message: {
            type: String,
            required: true
        },
        visible: {
            type: Boolean,
            default: false
        },
        showActionButton: {
            type: Boolean,
            default: false
        },
        actionButtonText: {
            type: String,
            default: 'OK'
        },
        actionIcon: {
            type: String,
            default: 'fas fa-check'
        },
        closeOnOverlayClick: {
            type: Boolean,
            default: true
        }
    },
    emits: ['close', 'action'],
    data() {
        return {
            isVisible: this.visible
        };
    },
    watch: {
        visible(newVal) {
            this.isVisible = newVal;
            if (newVal) {
                // Prevent body scrolling when modal is open
                document.body.style.overflow = 'hidden';
            } else {
                // Restore body scrolling
                document.body.style.overflow = '';
            }
        }
    },
    methods: {
        closeModal() {
            this.isVisible = false;
            this.$emit('close');
        },
        handleAction() {
            this.$emit('action');
            this.closeModal();
        },
        handleOverlayClick() {
            if (this.closeOnOverlayClick) {
                this.closeModal();
            }
        }
    },
    beforeUnmount() {
        // Ensure body scrolling is restored when component is destroyed
        document.body.style.overflow = '';
    }
};
</script>

<style scoped>
/* Modal Overlay */
.feedback-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    z-index: 9999;
    padding: 1rem;
    padding-top: 5rem;
}

/* Modal Container */
.feedback-modal-container {
    background-color: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Modal Header */
.feedback-modal-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid #bd2130;
}

.feedback-modal-header h5 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.feedback-modal-header .btn-close {
    background-color: rgba(255, 255, 255, 0.8);
    opacity: 1;
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.feedback-modal-header .btn-close:hover {
    background-color: rgba(255, 255, 255, 1);
    transform: scale(1.1);
}

/* Modal Body */
.feedback-modal-body {
    padding: 1.5rem;
    color: #333;
    font-size: 1rem;
    line-height: 1.6;
    background-color: #fff;
}

.feedback-modal-body p {
    color: #555;
}

/* Modal Footer */
.feedback-modal-footer {
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    border-top: 1px solid #dee2e6;
}

.feedback-modal-footer .btn {
    transition: all 0.3s ease;
}

.feedback-modal-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Modal Fade Transition */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-active .feedback-modal-container {
    animation: slideDown 0.3s ease-out;
}

.modal-fade-leave-active .feedback-modal-container {
    animation: slideUp 0.3s ease-in;
}

@keyframes slideUp {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(-50px);
        opacity: 0;
    }
}

/* Responsive Design */
@media (max-width: 576px) {
    .feedback-modal-overlay {
        padding-top: 2rem;
    }

    .feedback-modal-container {
        max-width: 95%;
        margin: 1rem;
    }

    .feedback-modal-header {
        padding: 1rem;
    }

    .feedback-modal-body {
        padding: 1rem;
    }

    .feedback-modal-footer {
        padding: 0.75rem 1rem;
        flex-direction: column;
    }

    .feedback-modal-footer .btn {
        width: 100%;
    }
}
</style>

