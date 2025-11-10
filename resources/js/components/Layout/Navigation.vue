<template>
    <!-- Modern Navbar with Bootstrap -->
    <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top border-bottom shadow-sm py-0">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" :href="route('home.index')">
                <div class="d-flex align-items-center justify-content-center rounded bg-danger text-white" style="height: 40px; width: 40px;">
                    <span class="fw-bold fs-6">
                        <img src="../../../Images/WhoSabiWorkLogo.png" alt="Website Logo" class="img-fluid" style="height: 45px;">
                    </span>
                </div>
                <span class="d-none d-sm-inline-block fw-semibold">WhoSabiWork</span>
                <!-- <span class="d-none d-sm-inline-block fw-semibold">
                    <img src="../../../Images/WhoSabiWorkL1.jpg" alt="Website Logo" class="img-fluid" style="height: 45px;">
                </span> -->
            </a>

            <!-- Desktop Navigation Links -->
            <div class="d-none d-md-flex align-items-center gap-1 mx-auto">
                <a :href="route('home.index')" class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link">
                    Home
                </a>
                <a :href="route('home.photoFeeds')" class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link">
                    Photo Feed
                </a>
                <a :href="route('mobilemarketers.index')" class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link">
                    Mobile Market
                </a>
                <a :href="route('artisans.index')" class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link">
                    Artisans
                </a>
                <a :href="route('contactus.index')" class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link">
                    Contact Us
                </a>
            </div>

            <!-- Desktop Auth Section -->
            <div class="d-none d-md-flex align-items-center gap-2">
                <!-- Authenticated User Dropdown -->
                <div v-if="user" class="dropdown">
                    <button 
                        class="btn btn-light d-flex align-items-center gap-2 rounded-pill px-3 py-2 border-0 hover-bg-light" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="height: 40px;"
                    >
                        <img 
                            :src="userAvatar" 
                            :alt="user.firstName || user.username" 
                            class="rounded-circle" 
                            style="height: 28px; width: 28px; object-fit: cover;"
                        >
                        <span class="fw-medium small">{{ getUserDisplayName }}</span>
                        <i class="fas fa-chevron-down text-muted" style="font-size: 0.75rem;"></i>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="min-width: 240px; border-radius: 0.5rem;">
                        <li class="px-3 py-2">
                            <div class="d-flex flex-column">
                                <p class="mb-0 fw-medium small">{{ getUserDisplayName }}</p>
                                <p class="mb-0 text-muted" style="font-size: 0.75rem;">{{ user.email }}</p>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a 
                                v-if="user && user.id"
                                :href="route('users.show', user.id)" 
                                class="dropdown-item py-2 px-3 d-flex align-items-center gap-2"
                            >
                                <i class="fas fa-user text-muted" style="width: 16px;"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a 
                                v-if="user && user.id"
                                :href="route('entrepreneur.show', user.id)" 
                                class="dropdown-item py-2 px-3 d-flex align-items-center gap-2"
                            >
                                <i class="fas fa-briefcase text-muted" style="width: 16px;"></i>
                                <span>Entrepreneur Profile</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <Link 
                                :href="route('logout')" 
                                method="delete" 
                                as="button" 
                                class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-danger"
                                style="border: none; background: transparent; width: 100%; text-align: left;"
                            >
                                <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                                <span>Log out</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Guest Buttons -->
                <template v-else>
                    <a :href="route('users.index')" class="btn btn-outline-danger btn-sm">
                        Log in
                    </a>
                    <a :href="route('users.create')" class="btn btn-danger btn-sm">
                        Get Started
                    </a>
                </template>
            </div>

            <!-- Mobile menu button -->
            <button 
                class="btn btn-light d-md-none border-0" 
                type="button" 
                @click="toggleMobileMenu"
                aria-label="Toggle menu"
            >
                <i v-if="isMobileMenuOpen" class="fas fa-times" style="font-size: 1.25rem;"></i>
                <i v-else class="fas fa-bars" style="font-size: 1.25rem;"></i>
            </button>
        </div>

        <!-- Mobile menu -->
        <div 
            class="mobile-menu d-md-none overflow-hidden"
            :class="{ 'mobile-menu-open': isMobileMenuOpen }"
        >
            <div class="border-top bg-white px-3 pb-3 pt-2">
                <!-- Mobile Navigation Links -->
                <div class="d-flex flex-column gap-1 mb-3">
                    <a 
                        :href="route('home.index')" 
                        @click="closeMobileMenu"
                        class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link"
                    >
                        Home
                    </a>
                    <a 
                        :href="route('home.photoFeeds')" 
                        @click="closeMobileMenu"
                        class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link"
                    >
                        Photo Feed
                    </a>
                    <a 
                        :href="route('mobilemarketers.index')" 
                        @click="closeMobileMenu"
                        class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link"
                    >
                        Mobile Market
                    </a>
                    <a 
                        :href="route('artisans.index')" 
                        @click="closeMobileMenu"
                        class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link"
                    >
                        Artisans
                    </a>
                    <a 
                        :href="route('contactus.index')" 
                        @click="closeMobileMenu"
                        class="nav-link rounded px-3 py-2 text-muted fw-medium hover-link"
                    >
                        Contact Us
                    </a>
                </div>

                <!-- Mobile Auth Section -->
                <div class="border-top pt-3">
                    <!-- Authenticated User (Mobile) -->
                    <div v-if="user" class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-3 px-3 py-2">
                            <img 
                                :src="userAvatar" 
                                :alt="user.firstName || user.username" 
                                class="rounded-circle" 
                                style="height: 40px; width: 40px; object-fit: cover;"
                            >
                            <div class="d-flex flex-column">
                                <span class="fw-medium small">{{ getUserDisplayName }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ user.email }}</span>
                            </div>
                        </div>
                        <a 
                            v-if="user && user.id"
                            :href="route('users.show', user.id)"
                            @click="closeMobileMenu"
                            class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none text-dark fw-medium hover-link"
                        >
                            <i class="fas fa-user" style="width: 16px;"></i>
                            Profile
                        </a>
                        <a 
                            v-if="user && user.id"
                            :href="route('entrepreneur.show', user.id)"
                            @click="closeMobileMenu"
                            class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none text-dark fw-medium hover-link"
                        >
                            <i class="fas fa-briefcase" style="width: 16px;"></i>
                            Entrepreneur Profile
                        </a>
                        <Link 
                            :href="route('logout')" 
                            method="delete" 
                            as="button"
                            @click="closeMobileMenu"
                            class="d-flex align-items-center gap-2 rounded px-3 py-2 text-danger fw-medium hover-link"
                            style="border: none; background: transparent; text-align: left;"
                        >
                            <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                            Log out
                        </Link>
                    </div>

                    <!-- Guest Buttons (Mobile) -->
                    <div v-else class="d-flex flex-column gap-2 px-2">
                        <a 
                            :href="route('users.index')" 
                            @click="closeMobileMenu"
                            class="btn btn-outline-danger w-100"
                        >
                            Log in
                        </a>
                        <a 
                            :href="route('users.create')" 
                            @click="closeMobileMenu"
                            class="btn btn-danger w-100"
                        >
                            Get Started
                        </a>
                    </div>
                </div>
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
                page: usePage(),
                isMobileMenuOpen: false
            }
        },
        methods: {
            logout() {
                useForm().delete(route('logout'));
            },
            toggleMobileMenu() {
                this.isMobileMenuOpen = !this.isMobileMenuOpen;
            },
            closeMobileMenu() {
                this.isMobileMenuOpen = false;
            },
            getUserInitials(name) {
                if (!name) return 'U';
                return name
                    .split(' ')
                    .map(n => n[0])
                    .join('')
                    .toUpperCase()
                    .slice(0, 2);
            }
        },
        computed: {
            user() {
                // Use Vuex store as primary source (persistent across pages)
                // Fall back to page.props.user if Vuex store hasn't been populated yet
                const vuexUser = this.$store.getters.getAuthenticatedUser;
                return vuexUser || this.page.props.user;
            },
            flashSuccess() {
                return this.page.props.flash.success;
            },
            userAvatar() {
                // Get user from Vuex store (persistent) or page props (initial load)
                const currentUser = this.user;
                
                // Return user avatar if available, otherwise return a default avatar
                if (currentUser && currentUser.avatar) {
                    return currentUser.avatar;
                }
                
                // Return a default avatar SVG data URL
                return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9IiNkYzM1NDUiLz48Y2lyY2xlIGN4PSI1MCIgY3k9IjM1IiByPSIxNSIgZmlsbD0iI2ZmZiIvPjxwYXRoIGQ9Ik0yNSA3NWMwLTEzLjggMTEuMi0yNSAyNS0yNXMyNSAxMS4yIDI1IDI1IiBmaWxsPSIjZmZmIi8+PC9zdmc+';
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
/* Navbar styling */
.navbar {
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95) !important;
}

/* Hover effects for navigation links */
.hover-link {
    transition: all 0.2s ease-in-out;
}

.hover-link:hover {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545 !important;
}

/* Hover effect for user dropdown button */
.hover-bg-light:hover {
    background-color: rgba(0, 0, 0, 0.05) !important;
}

/* Dropdown menu styling */
.dropdown-menu {
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-item {
    transition: all 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: rgba(220, 53, 69, 0.1);
}

.dropdown-item.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.1);
}

/* Mobile menu animation */
.mobile-menu {
    max-height: 0;
    opacity: 0;
    transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.mobile-menu-open {
    max-height: 600px;
    opacity: 1;
}

/* Ensure dropdown is always on top */
.dropdown-menu {
    z-index: 1050 !important;
}

/* Button hover effects */
.btn-outline-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

/* Avatar image styling */
img.rounded-circle {
    border: 2px solid rgba(220, 53, 69, 0.1);
}

/* Mobile responsive adjustments */
@media (max-width: 767.98px) {
    .navbar-brand span {
        font-size: 1rem;
    }
    
    .mobile-menu {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
}

/* Desktop dropdown positioning */
@media (min-width: 768px) {
    .dropdown-menu {
        margin-top: 0.5rem;
        min-width: 240px;
    }
}

/* Smooth transitions for all interactive elements */
* {
    transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
}
</style>
