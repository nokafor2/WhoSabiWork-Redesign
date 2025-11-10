<template>
    <Navigation></Navigation>
    <!-- Homepage gets full-width treatment, other pages get container -->
    <!-- :style="isHomepage ? '' : 'position: relative; top: 15px;'" -->
    <div 
        :id="isHomepage ? '' : 'mainContainer'" 
        :class="isHomepage ? '' : 'container-lg bg-white mt-5 min-vh-100 d-flex flex-column'"
    >
        <slot></slot>
    </div>
    <FooterLayout></FooterLayout>
</template>

<script setup>
    import { watch, computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import { useStore } from 'vuex';
    import Navigation from '@/components/Layout/Navigation.vue';
    import FooterLayout from '@/components/Layout/FooterLayout.vue';

    const page = usePage();
    const store = useStore();

    // Check if current page is homepage
    const isHomepage = computed(() => {
        return page.component === 'Index/Index';
    });

    // Watch for changes in user prop and update authentication state
    // The 'immediate: true' option ensures this runs on component mount
    watch(() => page.props.user, (newUser) => {
        const isAuthenticated = !!newUser;
        store.dispatch('updateIsAuthenticated', { value: isAuthenticated });
        
        // Update authenticated user details including avatar (null if not authenticated)
        store.dispatch('updateAuthenticatedUser', { value: newUser || null });
    }, { immediate: true, deep: true });
</script>