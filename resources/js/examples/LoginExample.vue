<template>
    <div class="login-example">
        <h3>Auth Service Example</h3>
        
        <div v-if="!authService.isAuthenticated()">
            <form @submit.prevent="login">
                <input v-model="email" type="email" placeholder="Email" required>
                <input v-model="password" type="password" placeholder="Password" required>
                <button type="submit" :disabled="loading">Login</button>
            </form>
        </div>
        
        <div v-else>
            <p>Welcome, {{ authService.getUser()?.first_name }}!</p>
            <button @click="logout">Logout</button>
        </div>
        
        <div v-if="error" class="error">{{ error }}</div>
    </div>
</template>

<script>
import AuthService from '@/services/AuthService'

export default {
    data() {
        return {
            email: '',
            password: '',
            loading: false,
            error: null,
            authService: AuthService
        }
    },
    
    methods: {
        async login() {
            this.loading = true
            this.error = null
            
            try {
                await AuthService.login({
                    email: this.email,
                    password: this.password
                })
                
                // Optionally redirect or update UI
                console.log('Login successful!')
                
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed'
            } finally {
                this.loading = false
            }
        },
        
        async logout() {
            try {
                await AuthService.logout()
                console.log('Logout successful!')
            } catch (error) {
                console.error('Logout failed:', error)
            }
        }
    }
}
</script>
