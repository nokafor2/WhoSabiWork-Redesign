import './bootstrap'; 

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import axios from 'axios'
import MainLayout from '@/components/Layout/MainLayout.vue'
// used to inform of named route of laravel to vue and inertia 
import { ZiggyVue } from 'ziggy' 
import store from '@/Store/index.js'

// Ensure CSRF token is available globally for Inertia
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        // Set up Axios defaults (already done in bootstrap.js but ensuring it's set)
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        
        // Make token available globally
        window.csrfToken = token.getAttribute('content');
        
        // console.log('CSRF token initialized for Inertia:', token.getAttribute('content').substring(0, 10) + '...');
    } else {
        console.error('CSRF token meta tag not found');
    }
});

createInertiaApp({
//   resolve: name => {
//     const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
//     return pages[`./Pages/${name}.vue`]
//   },

    // Make the function asynchronous
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        
        const page = await pages[`./Pages/${name}.vue`]
        // page.default.layout: has a layout property which can be set
        // Check if the property has been set, before changing it
        page.default.layout = page.default.layout || MainLayout
    
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
        .use(plugin)
        .use(store)
        .use(ZiggyVue)
        .mount(el)
    },
});