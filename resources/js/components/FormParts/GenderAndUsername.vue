<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2 just">
            <div class="form-check form-check-inline">
                <input type="radio" @change="radioSelected" class="form-check-input" :class="{'border border-danger': !gender.isValid}" name="gender" id="maleRadioBtn" value="male" v-model="gender.val" @blur="clearValidity('gender')">
                <label for="maleRadioBtn" class="form-check-label" :class="{'text-danger': !gender.isValid}">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" @change="radioSelected" class="form-check-input" :class="{'border border-danger': !gender.isValid}" name="gender" id="femaleRadioBtn" value="female" v-model="gender.val" @blur="clearValidity('gender')">
                <label for="femaleRadioBtn" class="form-check-label" :class="{'text-danger': !gender.isValid}">Female</label>
            </div>
            <p v-if="!gender.isValid">Please select a gender</p>
            <p v-if="errors.gender" :class="{'text-danger': errors.gender}"> {{ errors.gender }} </p>
        </div>
        <div class="col-sm-6 form-floating my-2">
            <input type="text" @input="sendUsername" class="form-control" :class="{'border border-danger text-danger': !username.isValid}" id="username" placeholder="" v-model.trim="username.val" @blur="clearValidity('username')">
            <label for="username" :class="{'text-danger': !username.isValid}">Username</label>
            <p v-if="!username.isValid" :class="{'text-danger': !username.isValid}">Please enter a valid username</p>
            <p v-if="errors.username" :class="{'text-danger': errors.username}"> {{ errors.username }} </p>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['errors'],
        emits: ['radio-selected', 'send-username'],
        data() {
            return {
                gender: {
                    val: '',
                    isValid: true
                },
                username: {
                    val: '',
                    isValid: true
                }
            }
        },
        methods: {
            radioSelected() {
                this.$emit('radio-selected', this.gender);
            },
            sendUsername() {
                this.$emit('send-username', this.username);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>