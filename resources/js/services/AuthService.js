import axios from 'axios'

class AuthService {
    constructor() {
        this.token = localStorage.getItem('auth_token')
        this.user = null
        this.baseURL = '/api'
        
        // Set up axios interceptors
        this.setupInterceptors()
    }

    setupInterceptors() {
        // Request interceptor to add auth token
        axios.interceptors.request.use(
            (config) => {
                if (this.token) {
                    config.headers.Authorization = `Bearer ${this.token}`
                }
                config.headers['X-Requested-With'] = 'XMLHttpRequest'
                return config
            },
            (error) => {
                return Promise.reject(error)
            }
        )

        // Response interceptor to handle auth errors
        axios.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response?.status === 401) {
                    this.logout()
                }
                return Promise.reject(error)
            }
        )
    }

    async login(credentials) {
        try {
            const response = await axios.post(`${this.baseURL}/login`, credentials)
            
            if (response.data.success) {
                this.token = response.data.token
                this.user = response.data.user
                
                // Store in localStorage
                localStorage.setItem('auth_token', this.token)
                localStorage.setItem('auth_user', JSON.stringify(this.user))
                
                return response.data
            }
            
            throw new Error(response.data.message || 'Login failed')
        } catch (error) {
            console.error('Login error:', error)
            throw error
        }
    }

    async register(userData) {
        try {
            const response = await axios.post(`${this.baseURL}/register`, userData)
            return response.data
        } catch (error) {
            console.error('Registration error:', error)
            throw error
        }
    }

    async logout() {
        try {
            if (this.token) {
                await axios.post(`${this.baseURL}/logout`)
            }
        } catch (error) {
            console.error('Logout error:', error)
        } finally {
            // Clear everything regardless
            this.token = null
            this.user = null
            localStorage.removeItem('auth_token')
            localStorage.removeItem('auth_user')
        }
    }

    async getCurrentUser() {
        try {
            if (!this.token) return null
            
            const response = await axios.get(`${this.baseURL}/user`)
            this.user = response.data
            localStorage.setItem('auth_user', JSON.stringify(this.user))
            return this.user
        } catch (error) {
            console.error('Get user error:', error)
            throw error
        }
    }

    isAuthenticated() {
        return !!this.token
    }

    getToken() {
        return this.token
    }

    getUser() {
        if (!this.user && localStorage.getItem('auth_user')) {
            this.user = JSON.parse(localStorage.getItem('auth_user'))
        }
        return this.user
    }

    // Helper method for making authenticated requests
    async authenticatedRequest(method, url, data = null) {
        const config = {
            method,
            url: `${this.baseURL}${url}`,
        }

        if (data) {
            config.data = data
        }

        try {
            const response = await axios(config)
            return response.data
        } catch (error) {
            console.error(`${method.toUpperCase()} ${url} error:`, error)
            throw error
        }
    }
}

// Create and export a singleton instance
export default new AuthService()
