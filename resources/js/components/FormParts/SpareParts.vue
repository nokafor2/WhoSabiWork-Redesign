<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Spare parts:</p>
        <div class="form-check form-check-inline" v-for="(sparePart, index) in allSpareParts" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedSpareParts.isValid}" name="selectedSpareParts" :id="index" :value="index" v-model="selectedSpareParts.val" @change="getOptions" @blur="clearValidity('selectedSpareParts')">
            <label :for="index" class="form-check-label" :class="{'text-danger': !selectedSpareParts.isValid}">{{ sparePart }}</label>
        </div>
        <p v-if="!selectedSpareParts.isValid" :class="{'text-danger': !selectedSpareParts.isValid}">Please select a technical service</p>
    </div>
</template>


<script>
    export default {
        props: ['allSpareParts', 'errors'],
        emits: ['send-spare-parts'],
        data() {
            return {
                selectedSpareParts: {
                    val: [],
                    isValid: true
                }
            }
        },
        methods: {
            getOptions() {
                this.$emit('send-spare-parts', this.selectedSpareParts);
                console.log(this.selectedSpareParts);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>