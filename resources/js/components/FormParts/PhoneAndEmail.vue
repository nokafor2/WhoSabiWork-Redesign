<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2">
            <input type="tel" class="form-control" :class="{'border border-danger text-danger': !phoneNumber.isValid}" id="phone" placeholder="" v-model.trim="phoneNumber.val" @input="sendPhoneNumber" @blur="clearValidity('phoneNumber')">
            <label for="phone" :class="{'text-danger': !phoneNumber.isValid}">Phone number</label>
            <p v-if="!phoneNumber.isValid" :class="{'text-danger': !phoneNumber.isValid}">Please enter a valid phone number</p>
            <p v-if="errors.phone_number" :class="{'text-danger': errors.phone_number}"> {{ errors.phone_number }} </p>
        </div>
        <div class="col-sm-6 form-floating my-2">
            <input type="email" class="form-control" :class="{'border border-danger text-danger': !email.isValid}" id="email" placeholder="" v-model.trim="email.val" @input="sendEmail" @blur="clearValidity('email')">
            <label for="email" :class="{'text-danger': !email.isValid}">Email address</label>
            <p v-if="!email.isValid" :class="{'text-danger': !email.isValid}">Please enter a valid email</p>
            <p v-if="errors.email" :class="{'text-danger': errors.email}"> {{ errors.email }} </p>
        </div>
    </div>
</template>


<script>
    // import segments of code using mixins
    // import DataVariables from '@/Pages/SignUp/Mixins/DataVariables.js';

    export default {
        props: ['errors'],
        emits: ['send-phone-number', 'send-email'],
        // mixins: [DataVariables],
        data() {
            return {
                phoneNumber: {
                    val: null,
                    isValid: true
                },
                email: {
                    val: '',
                    isValid: true
                },
            }
        },
        methods: {
            sendPhoneNumber() {
                this.$emit('send-phone-number', this.phoneNumber);
            },
            sendEmail() {
                this.$emit('send-email', this.email);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>