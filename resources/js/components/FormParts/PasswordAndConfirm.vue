<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2 position-relative">
            <input 
                :type="showPassword ? 'text' : 'password'" 
                class="form-control pe-5" 
                :class="{'border border-danger text-danger': !password.isValid}" 
                id="password" 
                placeholder="Password" 
                v-model.trim="password.val" 
                @input="getPassword" 
                @blur="clearValidity('password')">
            <label for="password" :class="{'text-danger': !password.isValid}">Password</label>
            <button 
                type="button" 
                class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2"
                @click="showPassword = !showPassword"
                style="z-index: 10; background: transparent; border: none;"
                :title="showPassword ? 'Hide password' : 'Show password'">
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-muted"></i>
            </button>
            <p v-if="editPassword" :class="{'text-danger': !password.isValid}">Please enter a valid password</p>
            <p v-if="errors.password" :class="{'text-danger': errors.password}"> {{ errors.password }} </p>
        </div>
        <div class="col-sm-6 form-floating my-2 position-relative">
            <input 
                :type="showConfirmPassword ? 'text' : 'password'" 
                class="form-control pe-5" 
                :class="{'border border-danger text-danger': !confirmPassword.isValid}" 
                id="confirmPassword" 
                placeholder="Confirm password" 
                v-model.trim="confirmPassword.val" 
                @input="getConfirmPassword" 
                @blur="clearValidity('confirmPassword')">
            <label for="confirmPassword" :class="{'text-danger': !confirmPassword.isValid}">Confirm password</label>
            <button 
                type="button" 
                class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2"
                @click="showConfirmPassword = !showConfirmPassword"
                style="z-index: 10; background: transparent; border: none;"
                :title="showConfirmPassword ? 'Hide password' : 'Show password'">
                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-muted"></i>
            </button>
            <p v-if="editConfPassword" :class="{'text-danger': !confirmPassword.isValid}">Please enter a valid matching password</p>
            <p v-if="errors.password_confirmation" :class="{'text-danger': errors.password_confirmation}"> {{ errors.password_confirmation }} </p>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['errors'],
        emits: ['send-password', 'send-confirm-password'],
        data() {
            return {
                password: {
                    val: '',
                    isValid: true
                },
                confirmPassword: {
                    val: '',
                    isValid: true
                },
                showPassword: false, // Toggle for password visibility
                showConfirmPassword: false // Toggle for confirm password visibility
            }
        },
        methods: {
            getPassword() {
                this.$emit('send-password', this.password);
            },
            getConfirmPassword() {
                this.$emit('send-confirm-password', this.confirmPassword);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        },
        computed: {
            editPassword() {
                return !this.password.isValid;
            },
            editConfPassword() {
                return !this.confirmPassword.isValid;
            }
        }
    }
</script>