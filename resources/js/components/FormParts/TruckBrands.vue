<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Truck Brands:</p>
        <div class="form-check form-check-inline" v-for="(truckBrand, index) in allTruckBrands" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedTruckBrands.isValid}" name="selectedTruckBrands" :id="newId(index)" :value="index" v-model="selectedTruckBrands.val" @change="getOptions" @blur="clearValidity('selectedTruckBrands')">
            <label :for="newId(index)" class="form-check-label" :class="{'text-danger': !selectedTruckBrands.isValid}">{{ truckBrand }}</label>
        </div>
        <p v-if="!selectedTruckBrands.isValid" :class="{'text-danger': !selectedTruckBrands.isValid}">Please select a technical service</p>
    </div>
</template>

<script>
    export default {
        props: ['allTruckBrands', 'techOrSpare', 'errors'],
        emits: ['send-truck-brands'],
        data() {
            return {
                selectedTruckBrands: {
                    val: [],
                    isValid: true
                }
            }
        },
        methods: {
            getOptions() {
                this.$emit('send-truck-brands', this.selectedTruckBrands, this.techOrSpare);
            },
            newId(index) {
                return this.techOrSpare + "_truck_" + index.toString();
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>