import './bootstrap'; 

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import MainLayout from '@/components/Layout/MainLayout.vue'
// used to inform of named route of laravel to vue and inertia 
import { ZiggyVue } from 'ziggy' 
import store from '@/Store/index.js'

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


// import { createApp } from 'vue';

// import App from './App.vue';
// import MainBody from './components/layout/MainBody.vue';
// import Navigation from './components/layout/Navigation.vue';
// import Container from './components/layout/Container.vue';
// import Footer from './components/layout/FooterLayout.vue';

// const app = createApp(App);

// app.component('main-body', MainBody);
// app.component('navigation', Navigation);
// app.component('container', Container);
// app.component('footer', Footer);

// app.mount('#app');