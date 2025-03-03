<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2" >
            <input type="text" @input="sendFirstName" class="form-control" :class="{'border border-danger text-danger': !firstName.isValid}" id="firstName" placeholder="First name" v-model.trim="firstName.val" @blur="clearValidity('firstName')">
            <label for="firstName" :class="{'text-danger': !firstName.isValid}">First name</label>
            <p v-if="!firstName.isValid" :class="{'text-danger': !firstName.isValid}">Please enter a valid name</p>
            <p v-if="errors.first_name" :class="{'text-danger': errors.first_name}"> {{ errors.first_name }} </p>
        </div>
        <div class="col-sm-6 form-floating my-2">
            <input type="text" @input="sendLastName" class="form-control" :class="{'border border-danger text-danger': !lastName.isValid}" id="lastName" placeholder="Last name" v-model.trim="lastName.val" @blur="clearValidity('lastName')">
            <label for="lastName" :class="{'text-danger': !lastName.isValid}">Last name</label>
            <p v-if="!lastName.isValid" :class="{'text-danger': !lastName.isValid}">Please enter a valid name</p>
            <p v-if="errors.last_name" :class="{'text-danger': errors.last_name}"> {{ errors.last_name }} </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['errors'],
        emits: ['send-first-name', 'send-last-name'],
        data() {
            return {
                firstName: {
                    val: '',
                    isValid: true
                },
                lastName: {
                    val: '',
                    isValid: true
                },
            }
        },
        methods: {
            sendFirstName() {
                this.$emit('send-first-name', this.firstName);
            },
            sendLastName() {
                this.$emit('send-last-name', this.lastName);
            },
            validateInput() {
                if (this.firstName === '') {
                    this.firstNameValidity = 'invalid';
                } else {
                    this.firstNameValidity = 'valid';
                }
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>

<style scoped>
.invalid label {
    color: red;
}
.invalid {
    border: 1px solid red;
}
</style>