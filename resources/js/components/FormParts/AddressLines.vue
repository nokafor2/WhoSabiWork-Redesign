<template>
    <div class="form-floating my-2">
        <input type="text" class="form-control" :class="{'border border-danger text-danger': !addressLine1.isValid}" id="addressLine1" placeholder="Address line 1" v-model.trim="addressLine1.val" @input="sendAddressLine1" @blur="clearValidity('addressLine1')">
        <label for="addressLine1" :class="{'text-danger': !addressLine1.isValid}">Address line 1</label>
        <p v-if="!addressLine1.isValid" :class="{'text-danger': !addressLine1.isValid}">Please enter a valid address</p>
        <p v-if="errors.address_line_1" :class="{'text-danger': errors.address_line_1}"> {{ errors.address_line_1 }} </p>
    </div>
    <div class="form-floating my-2">
        <input type="text" class="form-control" :class="{'border border-danger text-danger': !addressLine2.isValid}" id="addressLine2" placeholder="Address line 2" v-model.trim="addressLine2.val" @input="sendAddressLine2" @blur="clearValidity('addressLine2')">
        <label for="addressLine2" :class="{'text-danger': !addressLine2.isValid}">Address line 2</label>
        <p v-if="!addressLine2.isValid" :class="{'text-danger': !addressLine2.isValid}">Please enter a valid address</p>
        <p v-if="errors.address_line_2" :class="{'text-danger': errors.address_line_2}"> {{ errors.address_line_2 }} </p>
    </div>
    <div class="form-floating my-2">
        <input type="text" class="form-control" :class="{'border border-danger text-danger': !addressLine3.isValid}" id="addressLine3" placeholder="Address line 3" v-model.trim="addressLine3.val" @input="sendAddressLine3" @blur="clearValidity('addressLine3')">
        <label for="addressLine3" :class="{'text-danger': !addressLine3.isValid}">Address line 3</label>
        <p v-if="!addressLine3.isValid" :class="{'text-danger': !addressLine3.isValid}">Please enter a valid address</p>
        <p v-if="errors.address_line_3" :class="{'text-danger': errors.address_line_3}"> {{ errors.address_line_3 }} </p>
    </div>
</template>


<script>
    export default {
        props: ['errors'],
        emits: ['send-address-line-1', 'send-address-line-2', 'send-address-line-3'],
        data() {
            return {
                addressLine1: {
                    val: '',
                    isValid: true
                },
                addressLine2: {
                    val: '',
                    isValid: true
                },
                addressLine3: {
                    val: '',
                    isValid: true
                }
            }
        },
        methods: {
            sendAddressLine1() {
                this.$emit('send-address-line-1', this.addressLine1);
            },
            sendAddressLine2() {
                this.$emit('send-address-line-2', this.addressLine2);
            },
            sendAddressLine3() {
                this.$emit('send-address-line-3', this.addressLine3);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>