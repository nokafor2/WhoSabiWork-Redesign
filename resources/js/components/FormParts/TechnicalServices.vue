<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Technical Services:</p>
        <div class="form-check form-check-inline" v-for="(technicalService, index) in allTechnicalServices" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedTechnicalServices.isValid}" name="selectedMobileSeller" :id="index" :value="index" v-model="selectedTechnicalServices.val" @change="getOptions" @blur="clearValidity('selectedTechnicalServices')">
            <label :for="index" class="form-check-label" :class="{'text-danger': !selectedTechnicalServices.isValid}">{{ technicalService }}</label>
        </div>
        <p v-if="!selectedTechnicalServices.isValid" :class="{'text-danger': !selectedTechnicalServices.isValid}">Please select a technical service</p>
    </div>
</template>


<script>
    export default {
        props: ['allTechnicalServices', 'errors'],
        emits: ['send-technical-services'],
        data() {
            return {
                selectedTechnicalServices: {
                    val: [],
                    isValid: true
                },
            }
        },
        methods: {
            getOptions(event) {
                const inputId = event.target.id;
                const isActive = event.target.checked;
                this.$emit('send-technical-services', this.selectedTechnicalServices);
                console.log(this.selectedTechnicalServices);
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>