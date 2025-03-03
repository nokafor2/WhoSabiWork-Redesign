<template>
    <div class="row gx-2">
        <div class="col-sm-6 my-2">
            <select class="form-select" :class="{'border border-danger text-danger': !state.isValid}" aria-label="Default select example" v-model="state.val" @change="sendState" @blur="clearValidity('state')">
                <option selected>Select state</option>
                <option value="abuja">Abuja</option>
                <option value="lagos">Lagos</option>
                <option value="enugu">Enugu</option>
            </select>
            <p v-if="!state.isValid" :class="{'text-danger': !state.isValid}">Please enter a valid name</p>
            <p v-if="errors.state" :class="{'text-danger': errors.state}"> {{ errors.state }} </p>
        </div>
        <div class="col-sm-6 my-2">
            <select class="form-select" :class="{'border border-danger text-danger': !town.isValid}" aria-label="Default select example" v-model="town.val" @change="sendTown" @blur="clearValidity('town')">
                <option selected>Select town</option>
                <option value="abuja">Abuja</option>
                <option value="lagos">Lagos</option>
                <option value="enugu">Enugu</option>
            </select>
            <p v-if="!town.isValid" :class="{'text-danger': !town.isValid}">Please enter a valid name</p>
            <p v-if="errors.town" :class="{'text-danger': errors.town}"> {{ errors.town }} </p>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['errors'],
        emits: ['send-state', 'send-town'],
        data() {
            return {
                state: {
                    val: 'Select state',
                    isValid: true
                },
                town: {
                    val: 'Select town',
                    isValid: true
                }
            }
        },
        methods: {
            sendState() {
                this.$emit('send-state', this.state);
            },
            sendTown() {
                this.$emit('send-town', this.town)
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>