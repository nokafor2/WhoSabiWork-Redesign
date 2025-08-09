import { ref, reactive } from 'vue'
import axios from 'axios'

const user = ref(null)
const token = ref(localStorage.getItem('auth_token'))
const isAuthenticated = ref(!!token.value)

export const useAuth = () => {
    const login = async (credentials) => {
        try {
            // Ensure the credential field is named 'email' for backend compatibility
            const loginData = {
                email: credentials.email || credentials.username || credentials.phone,
                password: credentials.password
            }
            
            const response = await axios.post('/api/login', loginData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })

            if (response.data.success) {
                token.value = response.data.token
                user.value = response.data.user
                isAuthenticated.value = true
                
                // Store token in localStorage
                localStorage.setItem('auth_token', response.data.token)
                
                // Set default Authorization header for future requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                
                return response.data
            }
        } catch (error) {
            console.error('Login error:', error.response?.data || error.message)
            throw error
        }
    }

    const logout = async () => {
        try {
            if (token.value) {
                await axios.post('/api/logout', {}, {
                    headers: {
                        'Authorization': `Bearer ${token.value}`,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
            }
        } catch (error) {
            console.error('Logout error:', error)
        } finally {
            // Clear local state regardless of API call success
            token.value = null
            user.value = null
            isAuthenticated.value = false
            localStorage.removeItem('auth_token')
            delete axios.defaults.headers.common['Authorization']
        }
    }

    const fetchUser = async () => {
        try {
            if (!token.value) return null
            
            const response = await axios.get('/api/user', {
                headers: {
                    'Authorization': `Bearer ${token.value}`,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            
            user.value = response.data
            return response.data
        } catch (error) {
            console.error('Fetch user error:', error)
            // If unauthorized, clear the token
            if (error.response?.status === 401) {
                await logout()
            }
            throw error
        }
    }

    const register = async (userData) => {
        try {
            const response = await axios.post('/api/register', userData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            
            return response.data
        } catch (error) {
            console.error('Registration error:', error.response?.data || error.message)
            throw error
        }
    }

    // Initialize axios with token if it exists
    if (token.value) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
    }

    return {
        user,
        token,
        isAuthenticated,
        login,
        logout,
        fetchUser,
        register
    }
}
