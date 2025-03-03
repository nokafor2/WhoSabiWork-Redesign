// import "../js/bootstrap.js";
import { createApp } from 'vue';

import ArtisanIndex from './components/Artisans/ArtisanIndex.vue';
import UserIndex from './components/Users/UserIndex.vue';
// import App from './App.vue';

const app = createApp(UserIndex);

// app.component('artisan-index', ArtisanIndex);

app.mount('#app');
