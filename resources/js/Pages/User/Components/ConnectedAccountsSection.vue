<template>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-link me-2"></i>Connected Accounts
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                Link your social media accounts to enable multiple sign-in options.
            </p>

            <!-- Google Account -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <div class="provider-icon google-icon me-3">
                        <i class="fab fa-google"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Google</h6>
                        <small v-if="googleAccount" class="text-muted">
                            {{ googleAccount.provider_email }}
                            <span v-if="googleAccount.provider_email_verified" class="badge bg-success ms-2">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        </small>
                        <small v-else class="text-muted">Not connected</small>
                    </div>
                </div>
                <div>
                    <button 
                        v-if="googleAccount" 
                        @click="unlinkProvider('google')"
                        class="btn btn-sm btn-outline-danger"
                        :disabled="isUnlinking"
                    >
                        <i class="fas fa-unlink me-1"></i>
                        {{ isUnlinking && unlinkingProvider === 'google' ? 'Unlinking...' : 'Unlink' }}
                    </button>
                    <a 
                        v-else 
                        :href="route('oauth.redirect', { provider: 'google' })"
                        class="btn btn-sm btn-primary"
                    >
                        <i class="fas fa-link me-1"></i>Link Google
                    </a>
                </div>
            </div>

            <!-- Facebook Account -->
            <div class="d-flex justify-content-between align-items-center pb-3">
                <div class="d-flex align-items-center">
                    <div class="provider-icon facebook-icon me-3">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Facebook</h6>
                        <small v-if="facebookAccount" class="text-muted">
                            {{ facebookAccount.provider_email }}
                            <span v-if="facebookAccount.provider_email_verified" class="badge bg-success ms-2">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        </small>
                        <small v-else class="text-muted">Not connected</small>
                    </div>
                </div>
                <div>
                    <button 
                        v-if="facebookAccount" 
                        @click="unlinkProvider('facebook')"
                        class="btn btn-sm btn-outline-danger"
                        :disabled="isUnlinking"
                    >
                        <i class="fas fa-unlink me-1"></i>
                        {{ isUnlinking && unlinkingProvider === 'facebook' ? 'Unlinking...' : 'Unlink' }}
                    </button>
                    <a 
                        v-else 
                        :href="route('oauth.redirect', { provider: 'facebook' })"
                        class="btn btn-sm btn-primary"
                    >
                        <i class="fas fa-link me-1"></i>Link Facebook
                    </a>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info d-flex align-items-start mt-4" role="alert">
                <i class="fas fa-info-circle me-2 mt-1"></i>
                <div>
                    <strong>Note:</strong> When you link a social account, you can use it to sign in instead of your password. 
                    Your accounts will be automatically merged based on your email address or manually when you're logged in.
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal for Success/Error Messages -->
    <FeedbackModal
        :visible="feedbackModal.show"
        :title="feedbackModal.title"
        :message="feedbackModal.message"
        :showActionButton="false"
        @close="closeFeedbackModal"
    />
</template>

<script>
import { router, usePage } from '@inertiajs/vue3';
import FeedbackModal from '@/components/UI/FeedbackModal.vue';

export default {
    name: 'ConnectedAccountsSection',
    components: {
        FeedbackModal
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        socialAccounts: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            page: usePage(),
            isUnlinking: false,
            unlinkingProvider: null,
            feedbackModal: {
                show: false,
                title: '',
                message: ''
            }
        };
    },
    computed: {
        googleAccount() {
            return this.socialAccounts.find(account => account.provider === 'google');
        },
        facebookAccount() {
            return this.socialAccounts.find(account => account.provider === 'facebook');
        }
    },
    methods: {
        unlinkProvider(provider) {
            if (!confirm(`Are you sure you want to unlink your ${provider.charAt(0).toUpperCase() + provider.slice(1)} account?`)) {
                return;
            }

            this.isUnlinking = true;
            this.unlinkingProvider = provider;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Use router.delete for Inertia request
            router.delete(route('oauth.unlink', { provider }), {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                preserveScroll: true,
                onSuccess: (page) => {
                    this.isUnlinking = false;
                    this.unlinkingProvider = null;
                    
                    // Show success message
                    if (page.props.flash?.success) {
                        this.showFeedbackModal('Success', page.props.flash.success);
                    }
                    
                    // Force page reload to refresh social accounts data
                    router.reload({ only: ['socialAccounts'] });
                },
                onError: (errors) => {
                    this.isUnlinking = false;
                    this.unlinkingProvider = null;
                    
                    // Show error message
                    const errorMessage = errors.error || 'Failed to unlink account. Please try again.';
                    this.showFeedbackModal('Error', errorMessage);
                }
            });
        },
        showFeedbackModal(title, message) {
            this.feedbackModal = {
                show: true,
                title,
                message
            };
        },
        closeFeedbackModal() {
            this.feedbackModal.show = false;
        }
    },
    mounted() {
        // Check for flash messages on component mount
        if (this.page.props.flash?.success) {
            this.showFeedbackModal('Success', this.page.props.flash.success);
        } else if (this.page.props.flash?.error) {
            this.showFeedbackModal('Error', this.page.props.flash.error);
        }
    }
};
</script>

<style scoped>
.provider-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.google-icon {
    background: linear-gradient(135deg, #4285F4, #DB4437);
}

.facebook-icon {
    background: linear-gradient(135deg, #1877F2, #0866FF);
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style>

