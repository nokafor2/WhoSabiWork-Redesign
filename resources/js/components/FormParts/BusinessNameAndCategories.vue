<template>
    <div class="row gx-2">
        <div class="col-sm-6 form-floating my-2">
            <input type="text" class="form-control" :class="{'border border-danger text-danger': !businessName.isValid}" id="businessName" placeholder="" v-model.trim="businessName.val" @input="sendBusinessName" @blur="clearValidity('businessName')">
            <label for="businessName" :class="{'text-danger': !businessName.isValid}">Business name</label>
            <p v-if="!businessName.isValid" :class="{'text-danger': !businessName.isValid}">Please enter a valid name</p>
            <p v-if="errors.business_name" :class="{'text-danger': errors.business_name}"> {{ errors.business_name }} </p>
    </div>
        <div class="col-sm-6 form-floating my-2 just">
            <p class="mb-2">Business categories:</p>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" :class="{'border border-danger': !businessCategories.isValid}" name="businessCategories" id="artisan" value="artisan" v-model="businessCategories.val" @change="getOption" @blur="clearValidity('businessCategories')" >
                <label for="artisan" class="form-check-label" :class="{'text-danger': !businessCategories.isValid}">Artisan</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" :class="{'border border-danger': !businessCategories.isValid}" name="businessCategories" id="mobileMarket" value="mobileMarket" v-model="businessCategories.val" @change="getOption" @blur="clearValidity('businessCategories')">
                <label for="mobileMarket" class="form-check-label" :class="{'text-danger': !businessCategories.isValid}">Mobile marketer</label>
            </div>
            <p v-if="!businessCategories.isValid" :class="{'text-danger': !businessName.isValid}">Please enter a valid name</p>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['errors'],
        emits: ['send-business-name', 'send-artisan-checked', 'send-mobile-market-checked'],
        data() {
            return {
                businessName: {
                    val: '',
                    isValid: true
                },
                businessCategories: {
                    val: [],
                    isValid: true
                },
                artisanCheckboxVal: false,
                mobileMarketCheckboxVal: false,
            }
        },
        methods: {
            sendBusinessName() {
                this.$emit('send-business-name', this.businessName);
            },
            getOption(event) {
                const inputId = event.target.id;
                const isActive = this.artisanCheckboxVal = event.target.checked;
                // save to state
                if (inputId === 'artisan') {
                    this.artisanCheckboxVal = isActive;         
                    this.$store.dispatch('createAccount/updateArtisanCheckboxId', { value: inputId });
                    this.$store.dispatch('createAccount/updateArtisanCheckboxActive', { value: isActive });
                    this.$emit('send-artisan-checked', this.businessCategories, this.artisanCheckboxVal);
                }
                
                if (inputId === 'mobileMarket') {
                    this.mobileMarketCheckboxVal = isActive;   
                    this.$store.dispatch('createAccount/updateMobileMarketChkBoxId', { value: inputId });
                    this.$store.dispatch('createAccount/updateMobileMarketChkBoxActive', { value: isActive });
                    this.$emit('send-mobile-market-checked', this.businessCategories, this.mobileMarketCheckboxVal);
                }
            },
            clearValidity(input) {
                this[input].isValid = true;
            }
        },
        computed: {
            
        },
        watch: {
            
        }
    }
</script>