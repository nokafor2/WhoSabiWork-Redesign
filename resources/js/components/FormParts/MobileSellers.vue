<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Mobile sellers:</p>
        <div class="form-check form-check-inline" v-for="(product, index) in allProducts" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedMobileSellers.isValid}" name="selectedMobileSellers" :id="index" :value="index" v-model="selectedMobileSellers.val" @change="getOptions" @blur="clearValidity('selectedMobileSellers')">
            <label :for="index" class="form-check-label" :class="{'text-danger': !selectedMobileSellers.isValid}">{{ product }}</label>
        </div>
        <p v-if="!selectedMobileSellers.isValid" :class="{'text-danger': !selectedMobileSellers.isValid}">Please select a technical service</p>
    </div>
</template>


<script>
    export default {
        props: ['allProducts', 'errors'],
        emits: ['send-mobile-sellers'],
        data() {
            return {
                selectedMobileSellers: {
                    val: [],
                    isValid: true
                },
                sparePartSellerChecked: false
            }
        },
        methods: {
            getOptions() {
                if (this.selectedMobileSellers.val.includes('spare_parts')) {
                    this.sparePartSellerChecked = true;
                } else {
                    this.sparePartSellerChecked = false;
                }
                // update the state of selected mobile marketers
                this.$store.dispatch('createAccount/updateSelectedMobileMarketers', { value: this.selectedMobileSellers.val });
                // emit the mobile marketers checkboxes selectd
                this.$emit('send-mobile-sellers', this.selectedMobileSellers, this.sparePartSellerChecked);
                // console.log(this.selectedMobileSellers);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>