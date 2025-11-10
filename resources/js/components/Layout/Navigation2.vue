<template>
    <!-- Navigation (Alternative Design from V0) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" :href="route('home.index')">
                <div class="d-flex align-items-center justify-content-center bg-warning rounded-3 me-2" style="width: 40px; height: 40px;">
                    <span class="fw-bold fs-5 text-white">W</span>
                </div>
                <span class="fw-bold fs-5">WhoSabiWork</span>
            </a>
            <button 
                class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav"
                aria-controls="navbarNav" 
                aria-expanded="false" 
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" :href="route('artisans.index')">Artisans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :href="route('mobilemarketers.index')">Mobile Market</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :href="route('artisans.index')">Mechanics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :href="route('mobilemarketers.index')">Spare Parts</a>
                    </li>
                    
                    <!-- Auth Section -->
                    <li v-if="!user" class="nav-item">
                        <a class="btn btn-warning text-white ms-lg-3" :href="route('users.create')">Get Started</a>
                    </li>
                    
                    <!-- Authenticated User Dropdown -->
                    <li v-else class="nav-item dropdown ms-lg-2">
                        <a 
                            class="nav-link dropdown-toggle d-flex align-items-center gap-2" 
                            href="#" 
                            role="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                        >
                            <img 
                                :src="userAvatar" 
                                :alt="user.firstName || user.username" 
                                class="rounded-circle" 
                                style="height: 30px; width: 30px; object-fit: cover;"
                            >
                            <span>{{ getUserDisplayName }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a 
                                    v-if="user && user.id"
                                    class="dropdown-item" 
                                    :href="route('users.show', user.id)"
                                >
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a 
                                    v-if="user && user.id"
                                    class="dropdown-item" 
                                    :href="route('entrepreneur.show', user.id)"
                                >
                                    <i class="fas fa-briefcase me-2"></i>Entrepreneur Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <Link 
                                    :href="route('logout')" 
                                    method="delete" 
                                    as="button" 
                                    class="dropdown-item text-danger"
                                    style="border: none; background: transparent; width: 100%; text-align: left;"
                                >
                                    <i class="fas fa-sign-out-alt me-2"></i>Log out
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
</script>

<script>
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        data () {
            return {
                page: usePage()
            }
        },
        methods: {
            logout() {
                useForm().delete(route('logout'));
            }
        },
        computed: {
            user() {
                const vuexUser = this.$store.getters.getAuthenticatedUser;
                return vuexUser || this.page.props.user;
            },
            userAvatar() {
                const currentUser = this.user;
                if (currentUser && currentUser.avatar) {
                    return currentUser.avatar;
                }
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9IiNmZmMxMDciLz48Y2lyY2xlIGN4PSI1MCIgY3k9IjM1IiByPSIxNSIgZmlsbD0iI2ZmZiIvPjxwYXRoIGQ9Ik0yNSA3NWMwLTEzLjggMTEuMi0yNSAyNS0yNXMyNSAxMS4yIDI1IDI1IiBmaWxsPSIjZmZmIi8+PC9zdmc+';
            },
            getUserDisplayName() {
                if (!this.user) return 'User';
                if (this.user.firstName && this.user.lastName) {
                    return `${this.user.firstName} ${this.user.lastName}`;
                }
                return this.user.firstName || this.user.username || 'User';
            }
        }
    }
</script>

<style scoped>
.navbar {
    backdrop-filter: blur(10px);
}

.nav-link:hover {
    color: #ffc107 !important;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}
</style>

