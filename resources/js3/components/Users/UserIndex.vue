<script setup>
import axios from "axios"
import { ref, onMounted } from 'vue'

const users = ref([])
const getUsers = () => {
    axios.get('/api/users')
    .then(res => users.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getUsers())
</script>

<template>
    <h1>All Users</h1>
    <div>
        <ul>
            <li v-for="user in users" :key="user.id">
                {{ user.id }} | {{ user.first_name }} | {{ user.last_name }}
            </li>
        </ul>
    </div>
</template>