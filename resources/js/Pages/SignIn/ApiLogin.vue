<template>
    <div class="mx-auto my-3 text-center display-5">
        API Sign In
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-5 py-3">
            <!-- Toggle between Web and API login -->
            <div class="mb-3">
                <div class="btn-group w-100" role="group">
                    <button 
                        type="button" 
                        class="btn" 
                        :class="loginMethod === 'web' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="loginMethod = 'web'">
                        Web Login
                    </button>
                    <button 
                        type="button" 
                        class="btn" 
                        :class="loginMethod === 'api' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="loginMethod = 'api'">
                        API Login
                    </button>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
            </div>

            <!-- Error Messages -->
            <div v-if="errors.email" class="alert alert-danger">
                {{ errors.email }}
            </div>

            <form @submit.prevent="submitForm">
                <div class="form-floating my-2">
                    <input 
                        type="text" 
                        class="form-control" 
                        :class="{ 'is-invalid': errors.email }"
                        id="username" 
                        placeholder="" 
                        v-model="username.val">
                    <label for="username">Username / E-mail / Phone number</label>
                </div>
                <div class="form-floating my-2">
                    <input 
                        type="password" 
                        class="form-control" 
                        :class="{ 'is-invalid': errors.password }"
                        id="password" 
                        placeholder="" 
                        v-model="password.val">
                    <label for="password">Password</label>
                </div>
                
                <div class="my-3">
                    <button 
                        class="btn btn-danger w-100" 
                        type="submit"
                        :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        {{ loading ? 'Signing In...' : 'Submit' }}
                    </button>
                </div>

                <!-- User Info Display (for API login) -->
                <div v-if="isAuthenticated && user" class="mt-4 p-3 bg-light rounded">
                    <h5>Logged in as:</h5>
                    <p><strong>Name:</strong> {{ user.first_name }} {{ user.last_name }}</p>
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <p><strong>Token:</strong> {{ token ? token.substring(0, 20) + '...' : 'None' }}</p>
                    <button @click="handleLogout" class="btn btn-sm btn-outline-danger">
                        Logout
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/composables/useAuth';
import { useForm, usePage } from '@inertiajs/vue3';

export default {
    setup() {
        const { login, logout, user, token, isAuthenticated } = useAuth();
        return { 
            apiLogin: login, 
            apiLogout: logout, 
            user, 
            token, 
            isAuthenticated 
        };
    },
    data() {
        return {
            username: {
                val: '',
                isValid: true,
            },
            password: {
                val: '',
                isValid: true,
            },
            loginMethod: 'api', // Default to API login for this component
            loading: false,
            errors: {},
            successMessage: '',
            page: usePage(),
        }
    },
    methods: {
        async submitForm() {
            this.loading = true;
            this.errors = {};
            this.successMessage = '';

            try {
                if (this.loginMethod === 'api') {
                    await this.handleApiLogin();
                } else {
                    await this.handleWebLogin();
                }
            } catch (error) {
                console.error('Login failed:', error);
            } finally {
                this.loading = false;
            }
        },

        async handleApiLogin() {
            try {
                const result = await this.apiLogin({
                    email: this.username.val,
                    password: this.password.val,
                });

                console.log('API Login successful:', result);
                this.successMessage = 'Successfully logged in via API!';
                
                // Clear form
                this.username.val = '';
                this.password.val = '';
                
            } catch (error) {
                this.errors = error.response?.data?.errors || { 
                    email: error.response?.data?.message || 'Login failed' 
                };
            }
        },

        async handleWebLogin() {
            // Use Inertia for web login
            const formData = useForm({
                email: this.username.val,
                password: this.password.val,
            });
            
            formData.post(route('login.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log('Web login successful:', page);
                    this.successMessage = 'Successfully logged in via Web!';
                },
                onError: (errors) => {
                    console.log('Web login error:', errors);
                    this.errors = errors;
                }
            });
        },

        async handleLogout() {
            try {
                await this.apiLogout();
                this.successMessage = 'Successfully logged out!';
            } catch (error) {
                console.error('Logout failed:', error);
            }
        }
    }
}
</script>
