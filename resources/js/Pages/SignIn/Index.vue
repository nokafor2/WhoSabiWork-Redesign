<template>
    <div class="mx-auto my-3 text-center display-5">
        Sign In
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-5 py-3">
            <!-- Login Method Toggle -->
            <!-- <div class="mb-3">
                <div class="btn-group w-100" role="group">
                    <button 
                        type="button" 
                        class="btn btn-sm" 
                        :class="loginMethod === 'web' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="loginMethod = 'web'">
                        <i class="fas fa-globe me-1"></i> Web Login
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-sm" 
                        :class="loginMethod === 'api' ? 'btn-primary' : 'btn-outline-primary'"
                        @click="loginMethod = 'api'">
                        <i class="fas fa-key me-1"></i> API Login
                    </button>
                </div>
                <small class="text-muted d-block mt-1">
                    {{ loginMethod === 'web' ? 'Traditional session-based login' : 'Token-based API authentication' }}
                </small>
            </div> -->

            <!-- Success/Error Messages -->
            <div v-if="page.props.flash.success" class="alert alert-success">
                    {{ page.props.flash.success }} 
            </div>
            <div v-if="apiSuccess" class="alert alert-success">
                {{ apiSuccess }}
            </div>
            <div v-if="apiError" class="alert alert-danger">
                {{ apiError }}
            </div>

            <form @submit.prevent="submitForm">
                <!-- Login Method Info -->
                <div class="mb-3 p-2 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        You can login using any of the following:
                    </small>
                    <div class="mt-1">
                        <span class="badge bg-secondary me-1"><i class="fas fa-envelope me-1"></i>Email</span>
                        <span class="badge bg-secondary me-1"><i class="fas fa-user me-1"></i>Username</span>
                        <span class="badge bg-secondary"><i class="fas fa-phone me-1"></i>Phone Number</span>
                    </div>
                </div>

                <div class="form-floating my-2">
                    <input 
                        type="text" 
                        class="form-control" 
                        :class="{ 'is-invalid': formData.errors.email || apiError }"
                        id="username" 
                        placeholder="" 
                        v-model="username.val"
                        @input="detectInputType">
                    <label for="username">
                        <i :class="inputIcon" class="me-1"></i>
                        {{ inputPlaceholder }}
                    </label>
                    <p v-if="formData.errors.email" class="text-danger small mt-1">{{ formData.errors.email }}</p>
                    <small v-if="inputTypeDetected" class="text-muted">
                        Detected: {{ inputTypeDetected }}
                    </small>
                </div>
                <div class="form-floating my-2">
                    <input type="password" class="form-control" id="password" placeholder="" v-model="password.val">
                    <label for="password">Password</label>
                    <p v-if="formData.errors.password" :class="{'text-danger': formData.errors.password}"> {{ formData.errors.password }} </p>
                </div>
                <button class="btn btn-sm bg-white" type="submit">
                    Forgot password?
                </button>
                
                <div class="my-3">
                    <button 
                        class="btn btn-danger w-100" 
                        type="submit"
                        :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                        {{ loading ? 'Signing In...' : 'Submit' }}
                    </button>
                </div>

                <div class="d-flex justify-content-center mb-3">
                    <p class="col-auto" style="width: 100%;
                    text-align: center;
                    border-bottom: 1px solid gray;
                    line-height: 0.1em;
                    margin: 20px 0 20px;">
                        <span class="bg-white px-3">Or Sign In Using</span>
                    </p>

                    <!-- <p class="col-auto w-100 text-center border-bottom border-dark lh-sm my-2">
                        <span class="bg-white px-3">Or Sign In Using</span>
                    </p> -->
                </div>

                <div class="row gx-2">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <a :href="route('oauth.redirect', { provider: 'facebook' })" class="col btn btn-primary text-light w-100">
                            <i class="fa-brands fa-facebook pe-2"></i>Facebook
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a :href="route('oauth.redirect', { provider: 'google' })" class="col btn btn-light text-primary border-primary w-100 align-middle">
                            <img class="pe-2" style="width:25px;" src="https://developers.google.com/identity/images/g-logo.png">Google
                        </a>
                    </div>
                </div>

                <p class="small text-center pt-2">Don't have an account? <a :href="route('users.create')" class="text-decoration-none text-danger">Sign up here</a></p>
            </form>

            <!-- API Login Success Info -->
            <div v-if="loginMethod === 'api' && isAuthenticated && user" class="mt-4 p-3 bg-light rounded">
                <h6><i class="fas fa-user-check text-success me-2"></i>API Authentication Successful</h6>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-1"><strong>Name:</strong> {{ user.first_name }} {{ user.last_name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ user.email }}</p>
                        <p class="mb-1"><strong>Username:</strong> {{ user.username }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ user.phone_number }}</p>
                        <p class="mb-1"><strong>Account Type:</strong> {{ user.account_type }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1"><strong>Token:</strong></p>
                        <small class="text-muted font-monospace">{{ token ? token.substring(0, 30) + '...' : 'None' }}</small>
                    </div>
                </div>
                <div class="mt-2">
                    <button @click="handleApiLogout" class="btn btn-sm btn-outline-danger me-2">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                    <button @click="testApiCall" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-test-tube me-1"></i>Test API Call
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import { useForm, usePage } from '@inertiajs/vue3';
    import { useAuth } from '@/composables/useAuth';

    export default {
        setup() {
            const { login: apiLogin, logout: apiLogout, user, token, isAuthenticated } = useAuth();
            return { 
                apiLogin, 
                apiLogout, 
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
                loginMethod: 'web', // Default to web login to maintain existing behavior
                loading: false,
                apiSuccess: '',
                apiError: '',
                inputType: null, // 'email', 'phone', 'username'
                inputTypeDetected: '',
                formData: useForm({
                    username: '',
                    password: '',
                }),
                page: usePage(),
            }
        },
        mounted() {
            // Initialize authentication state based on page props
            if (this.pageUser) {
                this.$store.dispatch('updateIsAuthenticated', { value: true });
            } else {
                this.$store.dispatch('updateIsAuthenticated', { value: false });
            }
        },
        methods: {
            async submitForm() {
                this.loading = true;
                this.clearMessages();

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
                    // Validate input before attempting login
                    const validation = this.validateInput();
                    if (!validation.isValid) {
                        this.apiError = validation.message;
                        return;
                    }

                    const result = await this.apiLogin({
                        email: this.username.val,
                        password: this.password.val,
                    });

                    console.log('API Login successful:', result);
                    this.apiSuccess = `Welcome back, ${result.user.first_name}! You are now authenticated via API using your ${this.inputTypeDetected.toLowerCase()}.`;
                    
                    // Update authentication state in Vuex store
                    this.$store.dispatch('updateIsAuthenticated', { value: true });
                    
                    console.log('User authenticated in Vuex store');
                    
                    // Clear form
                    this.clearForm();
                    
                } catch (error) {
                    this.apiError = error.response?.data?.message || 'API login failed. Please try again.';
                    console.error('API Login error:', error);
                }
            },

            async handleWebLogin() {
                // Existing Inertia web login logic
                this.formData = useForm({
                    email: this.username.val,
                    password: this.password.val,
                });
                
                this.formData.post(route('login.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('Web login successful:', page);
                        
                        // Update authentication state in Vuex store
                        this.$store.dispatch('updateIsAuthenticated', { value: true });
                        
                        console.log('User authenticated in Vuex store');
                    },
                    onError: (errors) => {
                        console.log('Web login error:', errors);
                        this.loading = false;
                    },
                    onFinish: () => {
                        this.loading = false;
                    }
                });
            },

            async handleApiLogout() {
                try {
                    await this.apiLogout();
                    
                    // Update authentication state in Vuex store
                    this.$store.dispatch('updateIsAuthenticated', { value: false });
                    
                    console.log('User logged out, authentication state updated');
                    
                    this.apiSuccess = 'Successfully logged out from API.';
                    this.clearForm();
                } catch (error) {
                    this.apiError = 'Logout failed. Please try again.';
                    console.error('API Logout error:', error);
                }
            },

            async testApiCall() {
                try {
                    // Test an authenticated API call
                    const response = await fetch('/api/user', {
                        headers: {
                            'Authorization': `Bearer ${this.token}`,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        const userData = await response.json();
                        this.apiSuccess = `API Test successful! Retrieved user: ${userData.first_name} ${userData.last_name}`;
                    } else {
                        this.apiError = 'API test failed. Check your authentication.';
                    }
                } catch (error) {
                    this.apiError = 'API test failed. Please check your connection.';
                    console.error('API test error:', error);
                }
            },

            clearMessages() {
                this.apiSuccess = '';
                this.apiError = '';
            },

            clearForm() {
                this.username.val = '';
                this.password.val = '';
                this.inputType = null;
                this.inputTypeDetected = '';
            },

            detectInputType() {
                const value = this.username.val.trim();
                
                if (!value) {
                    this.inputType = null;
                    this.inputTypeDetected = '';
                    return;
                }

                // Check if it's an email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(value)) {
                    this.inputType = 'email';
                    this.inputTypeDetected = 'Email Address';
                    return;
                }

                // Check if it's a phone number (supports various formats)
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[(]?[\d\s\-\(\)]{10,}$/;
                if (phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                    this.inputType = 'phone';
                    this.inputTypeDetected = 'Phone Number';
                    return;
                }

                // If it contains only letters, numbers, and allowed username characters
                const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
                if (usernameRegex.test(value)) {
                    this.inputType = 'username';
                    this.inputTypeDetected = 'Username';
                    return;
                }

                // Default to username if it doesn't match other patterns
                this.inputType = 'username';
                this.inputTypeDetected = 'Username';
            },

            validateInput() {
                const value = this.username.val.trim();
                
                if (!value) {
                    return { isValid: false, message: 'Please enter your email, username, or phone number' };
                }

                switch (this.inputType) {
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            return { isValid: false, message: 'Please enter a valid email address' };
                        }
                        break;
                    
                    case 'phone':
                        const cleanPhone = value.replace(/[\s\-\(\)]/g, '');
                        if (cleanPhone.length < 10) {
                            return { isValid: false, message: 'Please enter a valid phone number' };
                        }
                        break;
                    
                    case 'username':
                        if (value.length < 3) {
                            return { isValid: false, message: 'Username must be at least 3 characters long' };
                        }
                        break;
                }

                return { isValid: true };
            }
        },
        computed: {
            pageUser() {
                return this.page.props.user;
            },
            flashSuccess() {
                return this.page.props.flash.success;
            },
            inputIcon() {
                switch (this.inputType) {
                    case 'email':
                        return 'fas fa-envelope text-primary';
                    case 'phone':
                        return 'fas fa-phone text-success';
                    case 'username':
                        return 'fas fa-user text-info';
                    default:
                        return 'fas fa-sign-in-alt text-muted';
                }
            },
            inputPlaceholder() {
                switch (this.inputType) {
                    case 'email':
                        return 'Email Address';
                    case 'phone':
                        return 'Phone Number';
                    case 'username':
                        return 'Username';
                    default:
                        return 'Email, Username, or Phone Number';
                }
            }
        },
        watch: {
            loginMethod() {
                // Clear messages when switching login methods
                this.clearMessages();
            }
        }
    }
</script>