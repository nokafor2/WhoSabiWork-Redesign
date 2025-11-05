<template>
    <Navigation></Navigation>
    <!-- <div id="mainContainer" class="container-lg bg-white mt-5 flex-grow-1" style="position: relative; top: 15px;"> -->
    <div id="mainContainer" class="container-lg bg-white mt-5 min-vh-100 d-flex flex-column" style="position: relative; top: 15px;">
        <slot></slot>
    </div>
    <FooterLayout></FooterLayout>
</template>

<script setup>
    import { watch } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import { useStore } from 'vuex';
    import Navigation from '@/components/Layout/Navigation.vue';
    import FooterLayout from '@/components/Layout/FooterLayout.vue';

    const page = usePage();
    const store = useStore();

    // Watch for changes in user prop and update authentication state
    // The 'immediate: true' option ensures this runs on component mount
    watch(() => page.props.user, (newUser) => {
        const isAuthenticated = !!newUser;
        store.dispatch('updateIsAuthenticated', { value: isAuthenticated });
        
        // Update authenticated user details (null if not authenticated)
        store.dispatch('updateAuthenticatedUser', { value: newUser || null });
    }, { immediate: true });
</script>