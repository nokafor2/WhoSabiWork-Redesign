<template>
    <div class="form-floating my-2">
        <p class="mb-2">Artisans:</p>
        <div class="form-check form-check-inline" v-for="(artisan, index) in allArtisans" :key="index">
            <input type="checkbox" class="form-check-input" :class="{'border border-danger': !selectedArtisans.isValid}" name="selectedArtisans" :id="index" v-model="selectedArtisans.val" :value="index" @change="getOption" @blur="clearValidity('selectedArtisans')">
            <label :for="index" class="form-check-label" :class="{'text-danger': !selectedArtisans.isValid}">{{ artisan }}</label>
        </div>
        <p v-if="!selectedArtisans.isValid" :class="{'text-danger': !selectedArtisans.isValid}">Please select an artisan</p>
    </div>
</template>


<script>
    export default {
        props: ['allArtisans', 'errors'],
        emits: ['send-selected-artisans'],
        data() {
            return {
                selectedArtisans: {
                    val: [],
                    isValid: true
                },
                mechanicChecked: false,
            }
        },
        methods: {
            getOption() {
                if (this.selectedArtisans.val.includes('mechanic')) {
                    this.mechanicChecked = true;
                } else {
                    this.mechanicChecked = false;
                }

                // update the state of selected artisans
                this.$store.dispatch('createAccount/updateSelectedArtisans', { value: this.selectedArtisans.val });
                // emit the artisan checkboxes selectd                
                this.$emit('send-selected-artisans', this.selectedArtisans, this.mechanicChecked);
                
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        },
    }
</script>