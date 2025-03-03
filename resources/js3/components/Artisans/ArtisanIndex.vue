<script setup>
import axios from "axios"
import { ref, onMounted } from 'vue'

const artisans = ref([])
const getArtisans = () => {
    axios.get('/api/artisans')
    .then(res => artisans.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getArtisans())
</script>

<template>
    <h1>All Artisans</h1>
    <div>
        <ul>
            <li v-for="artisan in artisans" :key="artisan.id">
                {{ artisan.id }} | {{ artisan.caterer }} | {{ artisan.tailor }}
            </li>
        </ul>
    </div>
</template>