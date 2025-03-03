<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Bus Brands:</p>
        <div class="form-check form-check-inline" v-for="(busBrand, index) in allBusBrands" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedBusBrands.isValid}" name="selectedCarBrands" :id="newId(index)" :value="index" v-model="selectedBusBrands.val" @change="getOptions" @blur="clearValidity('selectedBusBrands')">
            <label :for="newId(index)" class="form-check-label" :class="{'text-danger': !selectedBusBrands.isValid}">{{ busBrand }}</label>
        </div>
        <p v-if="!selectedBusBrands.isValid" :class="{'text-danger': !selectedBusBrands.isValid}">Please select a bus brand</p>
    </div>
</template>

<script>
    export default {
        props: ['allBusBrands', 'techOrSpare', 'errors'],
        emits: ['send-bus-brands'],
        data() {
            return {
                selectedBusBrands: {
                    val: [],
                    isValid: true
                }
            }
        },
        methods: {
            getOptions() {
                console.log(this.selectedBusBrands.val);
                this.$emit('send-bus-brands', this.selectedBusBrands, this.techOrSpare);
            },
            newId(index) {
                return this.techOrSpare + "_bus_" + index.toString();
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>