<template>
    <div class="form-floating my-2 ">
        <p class="mb-2">Vehicle categories:</p>
        <div class="form-check form-check-inline" v-for="(vehicleCategory, index) in allVehicleCategories" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger text-danger': !selectedVehicleCategories.isValid}" name="selectedVehicleCategories" :id="newId(index)" :value="index" v-model="selectedVehicleCategories.val" @change="getOptions" @blur="clearValidity('selectedVehicleCategories')">
            <label :for="newId(index)" class="form-check-label" :class="{'text-danger': !selectedVehicleCategories.isValid}">{{ vehicleCategory }}</label>
        </div>
        <p v-if="!selectedVehicleCategories.isValid" :class="{'text-danger': !selectedVehicleCategories.isValid}">Please select a vehicle category</p>
    </div>
</template>

<script>
    export default {
        props: ['allVehicleCategories', 'techOrSpare', 'errors'],
        emits: ['send-vehicle-categories'],
        data() {
            return {
                selectedVehicleCategories: {
                    val: [],
                    isValid: true
                },
                carSelected: false,
                busSelected: false,
                truckSelected: false
            }
        },
        methods: {
            getOptions(event) {
                const inputId = event.target.id;
                const isActive = event.target.checked;

                var newCarId = this.techOrSpare + "_car";
                if (inputId === newCarId) {
                    this.carSelected = isActive;                   
                }

                var newBusId = this.techOrSpare + "_bus";
                if (inputId === newBusId) {
                    this.busSelected = isActive;
                }

                var newTruckId = this.techOrSpare + "_truck";
                if (inputId === newTruckId) {
                    this.truckSelected = isActive;
                }
                
                this.$emit('send-vehicle-categories', this.selectedVehicleCategories, this.carSelected, this.busSelected, this.truckSelected, this.techOrSpare);
            },
            newId(index) {
                return this.techOrSpare + "_" + index.toString();
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        }
    }
</script>