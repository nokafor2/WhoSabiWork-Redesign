<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Car Brands:</p>
        <div class="form-check form-check-inline" v-for="(carBrand, index) in allCarBrands" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedCarBrands.isValid}" name="selectedCarBrands" :id="newId(index)" :value="index" v-model="selectedCarBrands.val" @change="getOptions" @blur="clearValidity('selectedCarBrands')">
            <label :for="newId(index)" class="form-check-label" :class="{'text-danger': !selectedCarBrands.isValid}">{{ carBrand }}</label>
        </div>
        <p v-if="!selectedCarBrands.isValid" :class="{'text-danger': !selectedCarBrands.isValid}">Please enter a valid name</p>
    </div>
</template>

<script>
    export default {
        props: ['allCarBrands', 'techOrSpare', 'errors'],
        emits: ['send-car-brands'],
        data() {
            return {
                selectedCarBrands: {
                    val: [],
                    isValid: true
                }
            }
        },
        methods: {
            getOptions() {
                this.$emit('send-car-brands', this.selectedCarBrands, this.techOrSpare);
            },
            newId(index) {
                var stringIndex = index.toString();
                return `${this.techOrSpare}_car_${stringIndex}`;
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>