<template>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-key me-2"></i>Enhanced Authentication Demo</h4>
                        <small class="text-muted">Login with Email, Username, or Phone Number</small>
                    </div>
                    <div class="card-body">
                        
                        <!-- Example Credentials -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Test with these examples:</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Email:</strong><br>
                                    <code>john.doe@example.com</code>
                                </div>
                                <div class="col-md-4">
                                    <strong>Username:</strong><br>
                                    <code>johndoe123</code>
                                </div>
                                <div class="col-md-4">
                                    <strong>Phone:</strong><br>
                                    <code>+1234567890</code><br>
                                    <code>(123) 456-7890</code>
                                </div>
                            </div>
                        </div>

                        <!-- Detection Examples -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Input Detection Examples:</h6>
                                <div class="mb-2">
                                    <input 
                                        type="text" 
                                        class="form-control form-control-sm" 
                                        v-model="testInput"
                                        @input="detectInputType"
                                        placeholder="Type something to see detection...">
                                    <small class="text-muted">
                                        Detected: <span class="badge" :class="badgeClass">{{ detectedType }}</span>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Authentication Status:</h6>
                                <div v-if="isAuthenticated" class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Authenticated as {{ user?.first_name }} {{ user?.last_name }}
                                </div>
                                <div v-else class="text-muted">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Not authenticated
                                </div>
                            </div>
                        </div>

                        <!-- Pattern Reference -->
                        <div class="mt-3">
                            <h6>Supported Patterns:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-envelope text-primary me-2"></i><strong>Email:</strong> Contains @ and domain (e.g., user@domain.com)</li>
                                <li><i class="fas fa-phone text-success me-2"></i><strong>Phone:</strong> Numbers with optional formatting (e.g., +1234567890, (123) 456-7890)</li>
                                <li><i class="fas fa-user text-info me-2"></i><strong>Username:</strong> Letters, numbers, dots, dashes, underscores (e.g., john_doe123)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/composables/useAuth';

export default {
    setup() {
        const { user, isAuthenticated } = useAuth();
        return { user, isAuthenticated };
    },
    data() {
        return {
            testInput: '',
            detectedType: 'None',
        }
    },
    computed: {
        badgeClass() {
            switch (this.detectedType) {
                case 'Email Address':
                    return 'bg-primary';
                case 'Phone Number':
                    return 'bg-success';
                case 'Username':
                    return 'bg-info';
                default:
                    return 'bg-secondary';
            }
        }
    },
    methods: {
        detectInputType() {
            const value = this.testInput.trim();
            
            if (!value) {
                this.detectedType = 'None';
                return;
            }

            // Check if it's an email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(value)) {
                this.detectedType = 'Email Address';
                return;
            }

            // Check if it's a phone number
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[(]?[\d\s\-\(\)]{10,}$/;
            if (phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                this.detectedType = 'Phone Number';
                return;
            }

            // Username pattern
            const usernameRegex = /^[a-zA-Z0-9_.-]+$/;
            if (usernameRegex.test(value)) {
                this.detectedType = 'Username';
                return;
            }

            this.detectedType = 'Invalid Format';
        }
    }
}
</script>
