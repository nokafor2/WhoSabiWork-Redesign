<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2">
            <input type="password" class="form-control" :class="{'border border-danger text-danger': !password.isValid}" id="password" placeholder="Password" v-model.trim="password.val" @input="getPassword" @blur="clearValidity('password')">
            <label for="password" :class="{'text-danger': !password.isValid}">Password</label>
            <p v-if="editPassword" :class="{'text-danger': !password.isValid}">Please enter a valid password</p>
            <p v-if="errors.password" :class="{'text-danger': errors.password}"> {{ errors.password }} </p>
        </div>
        <div class="col-sm-6 form-floating my-2">
            <input type="password" class="form-control" :class="{'border border-danger text-danger': !confirmPassword.isValid}" id="confirmPassword" placeholder="Confirm password" v-model.trim="confirmPassword.val" @input="getConfirmPassword" @blur="clearValidity('confirmPassword')">
            <label for="confirmPassword" :class="{'text-danger': !confirmPassword.isValid}">Confirm password</label>
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
                }
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