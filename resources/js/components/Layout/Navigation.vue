<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-0">
        <div class="container-fluid">
            <!-- Initial position of logo -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bolder" :href="route('home.index')">
                <img src="../../../Images/WhoSabiWorkL1.jpg" alt="Website Logo" class="img-fluid" style="height: 45px;">
                <!-- WhoSabiWork -->
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-pills me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!-- {{-- <a class="nav-link active" aria-current="page" href="#">Home</a> --}} -->
                        <a class="nav-link text-danger" :href="route('home.photoFeeds')">
                            <i class="fas fa-users fa-1x"></i> Photo Feed
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" :href="route('mobilemarketers.index')">
                            <i class="fas fa-store fa-1x"></i> Mobile Market                            
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" :href="route('artisans.index')">
                            <i class="fas fa-users-cog fa-1x"></i> Artisans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" :href="route('contactus.index')">
                            <i class="fas fa-headset fa-1x"></i> Contact Us
                        </a>
                    </li>
                </ul>

                <ul v-if="!user" class="navbar-nav nav-pills ms-auto me-2 mb-2 mb-xl-0 gap-2 pt-2">
                    <li class="nav-item col-sm-6 justify-content-center">
                        <a :href="route('users.create')" class="btn btn-danger btn-sm text-light w-100">Sign Up</a>
                    </li>
                                            
                    <li class="nav-item col-sm-6">
                        <a :href="route('users.index')" class="btn btn-danger btn-sm text-light w-100">Sign In</a>
                    </li>
                </ul>

                <ul v-else class="navbar-nav nav-pills mb-2 mb-xl-0 ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.830 1.418-.832 1.664z"/>
                              </svg> {{ (user.firstName && user.lastName) ? (user.firstName + ' ' + user.lastName) : (user.firstName || user.username || 'User') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="right: 0; left: auto; z-index: 1050;">
                            <li><a class="dropdown-item" href="#">Welcome {{ user.firstName || user.username || 'User' }}</a></li>
                            <li v-if="user && user.id">
                                <a class="dropdown-item" :href="route('users.show', user.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.830 1.418-.832 1.664z"/>
                                      </svg> Your Profile
                                </a>
                            </li>
                            <li v-else>
                                <span class="dropdown-item text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.830 1.418-.832 1.664z"/>
                                      </svg> Profile Unavailable
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="nav-item">
                                <Link :href="route('logout')" method="delete" as="button" class="btn btn-danger btn-sm text-light mx-2 w-75">Sign Out</Link>
                                <!-- <form @submit.prevent="logout" method="delete">
                                    <button class="btn btn-danger btn-sm" type="button">Sign Out</button>
                                </form> -->
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- <form class="d-flex mb-2 mb-xl-0" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
                </form>              -->
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
                return this.page.props.user;
            },
            flashSuccess() {
                return this.page.props.flash.success;
            }
        }
    }
</script>

<style scoped>
/* Ensure dropdown menu is right-aligned and not obscured */
.dropdown-menu-end {
    --bs-position: end;
    right: 0 !important;
    left: auto !important;
}

/* Mobile responsive fixes */
@media (max-width: 991.98px) {
    .navbar-collapse .dropdown-menu {
        position: static !important;
        float: none;
        width: auto;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        box-shadow: none;
    }
    
    .navbar-collapse .dropdown-menu .dropdown-item {
        color: #dc3545;
        padding-left: 1.5rem;
    }
    
    .navbar-collapse .dropdown-menu .dropdown-item:hover,
    .navbar-collapse .dropdown-menu .dropdown-item:focus {
        background-color: rgba(220, 53, 69, 0.1);
    }
}

/* Desktop dropdown positioning */
@media (min-width: 992px) {
    .dropdown-menu {
        margin-top: 0.5rem;
        min-width: 200px;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
}

/* Ensure dropdown is always on top */
.navbar .dropdown-menu {
    z-index: 1050 !important;
}
</style>
