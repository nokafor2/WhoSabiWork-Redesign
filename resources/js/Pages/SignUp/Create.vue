<template>
    <div class="mx-auto my-3 text-center display-5">
        Create Account
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-8 py-3">
            <div v-if="page.props.flash.success" class="text-success">
                {{ page.props.flash.success }} 
            </div>
            <form @submit.prevent="submitForm">
                <div class="form-check form-switch">
                    <input @change="setAccountType" class="form-check-input" type="checkbox" role="switch" id="businessAccountSelect" value="businessAccount" v-model="accountType">
                    <label class="form-check-label" for="businessAccountSelect">Business Account</label>
                </div>
                <first-and-last-name ref="firstAndLastNames" @send-first-name="updateFirstName" @send-last-name="updateLastName" :errors="formData.errors" ></first-and-last-name>
                <gender-and-username ref="genderAndUsername" @radio-selected="updateGender" @send-username="updateUsername" :errors="formData.errors"></gender-and-username>                
                <password-and-confirm ref="passwordAndConfirm" @send-password="updatePassword" @send-confirm-password="updateConfirmPassword" :errors="formData.errors"></password-and-confirm>
                <phone-and-email ref="phoneAndEmail" @send-phone-number="updatePhoneNumber" @send-email="updateEmail" :errors="formData.errors"></phone-and-email>
                <div :class="setBusinessAccount">
                    <address-lines ref="addressLines" @send-address-line-1="updateAddressLine1" @send-address-line-2="updateAddressLine2" @send-address-line-3="updateAddressLine3" :errors="formData.errors"></address-lines>
                    <state-and-town ref="stateAndTown" @send-state="updateState" @send-town="updateTown" :errors="formData.errors"></state-and-town>
                    <business-name-and-categories ref="businessNameAndCat" @send-business-name="updateBusinessName" @send-artisan-checked="updateArtisanChecked" @send-mobile-market-checked="updateMobileMarketChecked" :errors="formData.errors"></business-name-and-categories>
                    <artisan-check-boxes ref="artisansChkbxs" :class="setArtisanChoicesVisible" :allArtisans="allArtisans" @send-selected-artisans="updateSelectedArtisans" :errors="formData.errors"></artisan-check-boxes>
                    <technical-services ref="techServsChkbxs" :class="setTechServChoicesVisible" :allTechnicalServices="allTechnicalServices" @send-technical-services="updateTechnicalServices" :errors="formData.errors" ></technical-services>
                    <vehicle-categories ref="vehicleCatsChkbxs" :class="setVehicleCategoriesVisible" :allVehicleCategories="allVehicleCategories" :techOrSpare="techServCat" @send-vehicle-categories="updateVehicleCategories" :errors="formData.errors"></vehicle-categories>
                    <bus-brands ref="busBrandsChkbxs" :class="setBusBrandsVisible" :allBusBrands="allBusBrands" :techOrSpare="techServCat" @send-bus-brands="updateBusBrands" :errors="formData.errors" ></bus-brands>
                    <car-brands ref="carBrandsChkbxs" :class="setCarBrandsVisible" :allCarBrands="allCarBrands" :techOrSpare="techServCat" @send-car-brands="updateCarBrands" :errors="formData.errors"></car-brands>
                    <truck-brands ref="truckBrandsChkbxs" :class="setTruckBrandsVisible" :allTruckBrands="allTruckBrands" :techOrSpare="techServCat" @send-truck-brands="updateTruckBrands" :errors="formData.errors" ></truck-brands>
                    <mobile-sellers ref="mobileMarketsChkbxs" :class="setMobileSellerChoicesVisible" :allProducts="allProducts" @send-mobile-sellers="updateSelectedMobileSellers" :errors="formData.errors"></mobile-sellers>
                    <spare-parts ref="sparePartsChkbxs" :class="setSparePartChoicesVisible" :allSpareParts="allSpareParts" @send-spare-parts="updateSpareParts" :errors="formData.errors" ></spare-parts>
                    <vehicle-categories ref="vehicleCatsChkbxsSS" :class="setVehicleCategoriesVisibleSS" :allVehicleCategories="allVehicleCategories" :techOrSpare="sparePartCat" @send-vehicle-categories="updateVehicleCategories" :errors="formData.errors"></vehicle-categories>
                    <bus-brands ref="busBrandsChkbxsSS" :class="setBusBrandsVisibleSS" :allBusBrands="allBusBrands" :techOrSpare="sparePartCat" @send-bus-brands="updateBusBrands" :errors="formData.errors" ></bus-brands>
                    <car-brands ref="carBrandsChkbxsSS" :class="setCarBrandsVisibleSS" :allCarBrands="allCarBrands" :techOrSpare="sparePartCat" @send-car-brands="updateCarBrands" :errors="formData.errors"></car-brands>
                    <truck-brands ref="truckBrandsChkbxsSS" :class="setTruckBrandsVisibleSS" :allTruckBrands="allTruckBrands" :techOrSpare="sparePartCat" @send-truck-brands="updateTruckBrands" :errors="formData.errors" ></truck-brands>
                </div>

                <p v-if="formData.errors.selectedArtisans" :class="{'text-danger': formData.errors.selectedArtisans}"> {{ formData.errors.selectedArtisans }} </p>

                <p class="small mt-3">
                    By Registering, you agree that you've read and accepted our User <a href="#" class="text-decoration-none">Terms of Use</a>, you're at least 18 years old, you consent to our <a href="#" class="link-underline-light">Privacy Policy</a> and you have accepted to receive marketing communications from us.
                </p>

                <P v-if="!formIsValid" :class="{'text-danger': !formIsValid}">Please fix the above errors and submit again.</P>
                <div class="row justify-content-center mt-5">
                    <button type="submit" class="btn btn-danger w-50">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import FirstAndLastName from '@/components/FormParts/FirstAndLastName.vue';
    import GenderAndUsername from '@/components/FormParts/GenderAndUsername.vue';
    import PasswordAndConfirm from '@/components/FormParts/PasswordAndConfirm.vue';
    import PhoneAndEmail from '@/components/FormParts/PhoneAndEmail.vue';
    import AddressLines from '@/components/FormParts/AddressLines.vue';
    import StateAndTown from '@/components/FormParts/StateAndTown.vue';
    import BusinessNameAndCategories from '@/components/FormParts/BusinessNameAndCategories.vue';
    import ArtisanCheckBoxes from '@/components/FormParts/ArtisanCheckBoxes.vue';
    import MobileSellers from '@/components/FormParts/MobileSellers.vue';
    import TechnicalServices from '@/components/FormParts/TechnicalServices.vue';
    import SpareParts from '@/components/FormParts/SpareParts.vue';
    import VehicleCategories from '@/components/FormParts/VehicleCategories.vue';
    import CarBrands from '@/components/FormParts/CarBrands.vue';
    import BusBrands from '@/components/FormParts/BusBrands.vue';
    import TruckBrands from '@/components/FormParts/TruckBrands.vue';
    
    // import segments of code using mixins
    import DataVariables from './Mixins/DataVariables.js';
    import MethodsMixins from './Mixins/MoreMethods.js'
    
    // import functions for use
    import { reactive } from 'vue';
    import { router, useForm, usePage } from '@inertiajs/vue3';

    
    export default {
        components: { 
            FirstAndLastName, 
            GenderAndUsername, 
            PasswordAndConfirm, 
            PhoneAndEmail, 
            AddressLines, 
            StateAndTown, 
            BusinessNameAndCategories, 
            ArtisanCheckBoxes,
            MobileSellers,
            TechnicalServices,
            SpareParts,
            VehicleCategories,
            CarBrands,
            BusBrands,
            TruckBrands
        },
        mixins: [DataVariables, MethodsMixins],
        props: [
            'allArtisans', 
            'allProducts', 
            'allTechnicalServices', 
            'allSpareParts', 
            'allVehicleCategories', 
            'allCarBrands',
            'allBusBrands',
            'allTruckBrands' 
        ],
        methods: {
            submitForm() {
                this.validateForm();

                if (!this.formIsValid) {
                    return;
                }    
                
                this.dataToSend = {
                    first_name: this.firstName.val,
                    last_name: this.lastName.val,
                    gender: this.gender.val,
                    username: this.username.val,
                    password: this.password.val,
                    password_confirmation: this.confirmPassword.val,
                    phone_number: this.phoneNumber.val,
                    email: this.email.val,
                };


                if (this.businessAccount) {
                    // At this point validation has been passed, so you can check what needs to be sent or not
                    this.dataToSend.address_line_1 = this.addressLine1.val;
                    if (this.addressLine2.val !== '') {
                        this.dataToSend.address_line_2 = this.addressLine2.val;
                    }
                    if (this.addressLine3.val !== '') {
                        this.dataToSend.address_line_3 = this.addressLine3.val;
                    }
                    this.dataToSend.state = this.state.val;
                    this.dataToSend.town = this.town.val;
                    this.dataToSend.business_name = this.businessName.val;
                    this.dataToSend.businessCategories = this.businessCategories.val;
                    if (this.selectedArtisans.val?.length) {
                        this.dataToSend.selectedArtisans = this.selectedArtisans.val;
                    }

                    if (this.selectedMobileSellers.val?.length) {
                        this.dataToSend.selectedMobileSellers = this.selectedMobileSellers.val;
                    }

                    if (this.selectedTechnicalServices.val?.length) {
                        this.dataToSend.selectedTechnicalServices = this.selectedTechnicalServices.val;
                    }
                    
                    if (this.selectedSpareParts.val?.length) {
                        this.dataToSend.selectedSpareParts = this.selectedSpareParts.val;
                    }
                    
                    if (this.selectedVehicleCategories.val?.length) {
                        this.dataToSend.selectedVehicleCategories = this.selectedVehicleCategories.val;
                    }
                    
                    if (this.selectedCarBrands.val?.length) {
                        this.dataToSend.selectedCarBrands = this.selectedCarBrands.val;
                    }
                    
                    if (this.selectedBusBrands.val?.length) {
                        this.dataToSend.selectedBusBrands = this.selectedBusBrands.val;
                    }
                    
                    if (this.selectedTruckBrands.val?.length) {
                        this.dataToSend.selectedTruckBrands = this.selectedTruckBrands.val;
                    }
                    
                    if (this.selectedVehicleCategoriesSS.val?.length) {
                        this.dataToSend.selectedVehicleCategoriesSS = this.selectedVehicleCategoriesSS.val;
                    }
                    
                    if (this.selectedCarBrandsSS.val?.length) {
                        this.dataToSend.selectedCarBrandsSS = this.selectedCarBrandsSS.val;
                    }
                    
                    if (this.selectedBusBrandsSS.val?.length) {
                        this.dataToSend.selectedBusBrandsSS = this.selectedBusBrandsSS.val;
                    }
                    
                    if (this.selectedTruckBrandsSS.val?.length) {
                        this.dataToSend.selectedTruckBrandsSS = this.selectedTruckBrandsSS.val;
                    }
                }

                this.formData = useForm(this.dataToSend);

                // const create = () => router.post('/users', formData);
                // const create = () => formData.post('/users');
                this.formData.post('/users');
            },

            setAccountType() {
                if (this.accountType == true) {
                    this.businessAccount = !this.businessAccount;
                } else {
                    this.businessAccount = !this.businessAccount;
                    // reset selected artisan checkbox
                    this.resetArtisanChkBx();
                    // reset selected artisan checkboxes
                    this.resetArtisanChoices();
                    // reset selected mobile market checkbox
                    this.resetMobileMarketChkBx();
                    // reset selected mobile market checkboxes
                    this.resetMobileMarketChoices();
                    // reset the technical services checkboxes
                    this.resetTechServChoices();
                    // reset the spare part checkboxes
                    this.resetSparePartChoices();
                    // reset the vehicle categories
                    this.resetVehicleCatgoryChoices('techServ');
                    this.resetVehicleCatgoryChoices('sparePart');
                    // reset the car brand checkboxes
                    this.resetCarBrandChoices('techServ');
                    this.resetCarBrandChoices('sparePart');
                    // reset the bus brand checkboxes
                    this.resetBusBrandChoices('techServ');
                    this.resetBusBrandChoices('sparePart');
                    // reset the truck brand checkboxes
                    this.resetTruckBrandChoices('techServ');
                    this.resetTruckBrandChoices('sparePart');
                    // Reset validation
                    this.resetBusinessAccountValidation();
                }
            },
            updateFirstName(firstName) {
                this.firstName = firstName;
                console.log(this.firstName);
            },
            updateLastName(lastName) {
                this.lastName = lastName;
            },
            updateGender(gender) {
                this.gender = gender;
            },
            updateUsername(username) {
                this.username = username;
            },
            updatePassword(password) {
                this.password = password;
            },
            updateConfirmPassword(password) {
                this.confirmPassword = password;
            },
            updatePhoneNumber(phoneNumber) {
                this.phoneNumber = phoneNumber;
            },
            updateEmail(email) {
                this.email = email;
            },
            updateAddressLine1(address1) {
                this.addressLine1 = address1;
            },
            updateAddressLine2(address2) {
                this.addressLine2 = address2;
            },
            updateAddressLine3(address3) {
                this.addressLine3 = address3;
            },
            updateState(state) {
                this.state = state;
            },
            updateTown(town) {
                this.town = town;
            },
            updateBusinessName(businessName) {
                this.businessName = businessName;
            },
            updateArtisanChecked(businessCategories, artisanCheckboxVal) {
                if (!artisanCheckboxVal && this.artisanChoicesVisible) {
                    // reset selected artisan checkboxes
                    this.resetArtisanChoices();
                    // reset the technical services checkboxes
                    this.resetTechServChoices();
                    // reset the vehicle categories
                    this.resetVehicleCatgoryChoices('techServ');
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes('techServ');
                } else {
                    // this.artisanChoicesVisible = !this.artisanChoicesVisible;
                    this.artisanChoicesVisible = artisanCheckboxVal;
                    this.businessCategories = businessCategories;
                    console.log(this.businessCategories);
                }
            },
            updateMobileMarketChecked(businessCategories, mobileMarketCheckboxVal) {
                if (!mobileMarketCheckboxVal && this.mobileSellerChoicesVisible) {
                    // reset selected mobile market checkboxes
                    this.resetMobileMarketChoices();
                    // reset the spare parts checkboxes
                    this.resetSparePartChoices();
                    // reset the vehicle categories
                    this.resetVehicleCatgoryChoices('sparePart');
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes('sparePart');
                } else {
                    // this.mobileSellerChoicesVisible = !this.mobileSellerChoicesVisible;
                    this.mobileSellerChoicesVisible = mobileMarketCheckboxVal;
                    this.businessCategories = businessCategories;
                    console.log(this.businessCategories);
                }
            },
            updateSelectedArtisans(artisans, mechanicChecked) {
                console.log(artisans);
                this.selectedArtisans = artisans;
                console.log(mechanicChecked);
                if (mechanicChecked === true) {
                    this.techServChoicesVisible = mechanicChecked;
                }
                
                // this.vehicleCategoriesVisible = mechanicChecked;
                if (!mechanicChecked) {
                    // reset the technical services checkboxes
                    this.resetTechServChoices();
                    // reset the vehicle categories
                    this.resetVehicleCatgoryChoices('techServ');
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes('techServ');
                }
                mechanicChecked === false;
            },
            updateSelectedMobileSellers(mobileSellers, sparePartSellerChecked) {
                this.selectedMobileSellers = mobileSellers;
                this.sparePartChoicesVisible = sparePartSellerChecked;
                // this.vehicleCategoriesVisible = sparePartSellerChecked;
                console.log(this.selectedMobileSellers);
                if (!sparePartSellerChecked) {
                    // reset the spare parts checkboxes
                    this.resetSparePartChoices();
                    // reset the vehicle categories
                    this.resetVehicleCatgoryChoices('sparePart');
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes('sparePart');
                }
            },
            updateTechnicalServices(technicalServices) {
                this.selectedTechnicalServices = technicalServices;
                console.log(this.selectedTechnicalServices);
                if (this.techServChoicesVisible && (this.selectedTechnicalServices.val.length > 0)) {
                    this.vehicleCategoriesVisible = true;
                } else {
                    // reset the vehicle categories
                    var techOrSpare = 'techServ'
                    this.resetVehicleCatgoryChoices(techOrSpare);
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes(techOrSpare);
                }
            },
            updateSpareParts(spareParts) {
                this.selectedSpareParts = spareParts;
                console.log(this.selectedSpareParts);
                if (this.sparePartChoicesVisible && (this.selectedSpareParts.val.length > 0)) {
                    this.vehicleCategoriesVisibleSS = true;
                } else {
                    // reset the vehicle categories
                    var techOrSpare = 'sparePart'
                    this.resetVehicleCatgoryChoices(techOrSpare);
                    // reset all the vehicle types choices
                    this.resetAllVehicleTypes(techOrSpare);
                }
            },
            updateVehicleCategories(vehicleCategories, carSelected, busSelected, truckSelected, techOrSpare) {
                if (techOrSpare === 'techServ') {
                    this.selectedVehicleCategories = vehicleCategories;
                    this.carBrandsVisible = carSelected;
                    this.busBrandsVisible = busSelected;
                    this.truckBrandsVisible = truckSelected;
                    console.log(this.selectedVehicleCategories);
                    
                    // Reset vehicle types
                    this.resetVehicleTypes(vehicleCategories.val, 'techServ');
                } else if (techOrSpare === 'sparePart') {
                    this.selectedVehicleCategoriesSS = vehicleCategories;
                    this.carBrandsVisibleSS = carSelected;
                    this.busBrandsVisibleSS = busSelected;
                    this.truckBrandsVisibleSS = truckSelected;
                    console.log(this.selectedVehicleCategoriesSS);

                    // Reset vehicle types
                    this.resetVehicleTypes(vehicleCategories.val, 'sparePart');
                }
            },
            updateCarBrands(carBrands, techOrSpare) {
                if (techOrSpare === 'techServ') {
                    this.selectedCarBrands = carBrands;
                    console.log(this.selectedCarBrands);
                } else if (techOrSpare === 'sparePart') {
                    this.selectedCarBrandsSS = carBrands;
                    console.log(this.selectedCarBrandsSS);
                }
            },
            updateBusBrands(busBrands, techOrSpare) {
                if (techOrSpare === 'techServ') {
                    this.selectedBusBrands = busBrands;
                    console.log(this.selectedBusBrands);
                } else if (techOrSpare === 'sparePart') {
                    this.selectedBusBrandsSS = busBrands;
                    console.log(this.selectedBusBrandsSS);
                }
            },
            updateTruckBrands(truckBrands, techOrSpare) {
                if (techOrSpare === 'techServ') {
                    this.selectedTruckBrands = truckBrands;
                    console.log(this.selectedTruckBrands);
                } else if (techOrSpare === 'sparePart') {
                    this.selectedTruckBrandsSS = truckBrands;
                    console.log(this.selectedTruckBrandsSS);
                }
            },
            resetArtisanChkBx() {
                // Uncheck artisan selected
                var chkBoxActive = this.$store.getters['createAccount/getArtisanCheckboxActive'];
                if (chkBoxActive) {
                    var inputId = this.$store.getters['createAccount/getArtisanCheckboxId'];
                    document.getElementById(inputId).checked = false;
                }
                this.$store.dispatch('createAccount/updateArtisanCheckboxId', { value: '' });
                this.$store.dispatch('createAccount/updateArtisanCheckboxActive', { value: false });
            },
            resetArtisanChoices() {
                // Uncheck all the selected artisan checkboxes
                this.$refs.artisansChkbxs.selectedArtisans.val = [];
                // empty the array
                this.$store.dispatch('createAccount/updateSelectedArtisans', { value: [] });
                this.selectedArtisans.val = [];
                // close the artisan checkboxes
                this.artisanChoicesVisible = false;
            },
            resetMobileMarketChkBx() {
                // Uncheck mobile market if selected
                var chkBoxActiveMM = this.$store.getters['createAccount/getMobileMarketChkBoxActive'];
                if (chkBoxActiveMM) {
                    var inputId = this.$store.getters['createAccount/getMobileMarketChkBoxId'];
                    document.getElementById(inputId).checked = false;
                }
                this.$store.dispatch('createAccount/updateMobileMarketChkBoxActive', { value: false });
                this.$store.dispatch('createAccount/updateMobileMarketChkBoxId', { value: '' });
            },
            resetMobileMarketChoices() {
                // Uncheck all the selected mobile market checkboxes
                this.$refs.mobileMarketsChkbxs.selectedMobileSellers.val = [];
                // empty the array
                this.$store.dispatch('createAccount/updateSelectedMobileMarketers', { value: [] });
                this.selectedMobileSellers.val = [];
                // close the mobile marketers checkboxes
                this.mobileSellerChoicesVisible = false;
            },
            resetTechServChoices() {
                // Uncheck all the selected technical services checkboxes
                this.$refs.techServsChkbxs.selectedTechnicalServices.val = [];
                this.selectedTechnicalServices.val = [];
                // close the technical services checkboxes
                this.techServChoicesVisible = false;
            },
            resetSparePartChoices() { 
                // Uncheck all the selected spare parts checkboxes
                this.$refs.sparePartsChkbxs.selectedSpareParts.val = [];
                this.selectedSpareParts.val = [];
                // close the spare parts checkboxes
                this.sparePartChoicesVisible = false;
            },
            resetVehicleCatgoryChoices(techOrSpare) {
                if (techOrSpare === 'techServ') {
                    // Uncheck all the selected vehicle categories checkboxes
                    this.$refs.vehicleCatsChkbxs.selectedVehicleCategories.val = [];
                    this.selectedVehicleCategories.val = [];
                    // close the vehicle categories checkboxes
                    this.vehicleCategoriesVisible = false;
                } else if (techOrSpare === 'sparePart') {
                    // Uncheck all the selected vehicle categories checkboxes
                    this.$refs.vehicleCatsChkbxsSS.selectedVehicleCategories.val = [];
                    this.selectedVehicleCategoriesSS.val = [];
                    // close the vehicle categories checkboxes
                    this.vehicleCategoriesVisibleSS = false;
                }
            },
            resetCarBrandChoices(techOrSpare) {
                if (techOrSpare === 'techServ') {
                    // Uncheck all the selected car brands checkboxes
                    this.$refs.carBrandsChkbxs.selectedCarBrands.val = [];
                    this.selectedCarBrands.val = [];
                    // close the car brands checkboxes
                    this.carBrandsVisible = false;
                } else if (techOrSpare === 'sparePart') {
                    // Uncheck all the selected car brands checkboxes
                    this.$refs.carBrandsChkbxsSS.selectedCarBrands.val = [];
                    this.selectedCarBrandsSS.val = [];
                    // close the car brands checkboxes
                    this.carBrandsVisibleSS = false;
                }
            },
            resetBusBrandChoices(techOrSpare) {
                if (techOrSpare === 'techServ') {
                    // Uncheck all the selected bus brands checkboxes
                    this.$refs.busBrandsChkbxs.selectedBusBrands.val = [];
                    this.selectedBusBrands.val = [];
                    // close the bus brands checkboxes
                    this.busBrandsVisible = false;
                } else if (techOrSpare === 'sparePart') {
                    // Uncheck all the selected bus brands checkboxes
                    this.$refs.busBrandsChkbxsSS.selectedBusBrands.val = [];
                    this.selectedBusBrandsSS.val = [];
                    // close the bus brands checkboxes
                    this.busBrandsVisibleSS = false;
                }
            },
            resetTruckBrandChoices(techOrSpare) {
                if (techOrSpare === 'techServ') {
                    // Uncheck all the selected truck brands checkboxes
                    this.$refs.truckBrandsChkbxs.selectedTruckBrands.val = [];
                    this.selectedTruckBrands.val = [];
                    // close the truck brands checkboxes
                    this.truckBrandsVisible = false;
                } else if (techOrSpare === 'sparePart') {
                    // Uncheck all the selected truck brands checkboxes
                    this.$refs.truckBrandsChkbxsSS.selectedTruckBrands.val = [];
                    this.selectedTruckBrandsSS.val = [];
                    // close the truck brands checkboxes
                    this.truckBrandsVisibleSS = false;
                }
            },
            resetVehicleChoices(value, techOrSpare) {
                if (value === 'car') {
                    return this.resetCarBrandChoices(techOrSpare);
                } else if (value === 'bus') {
                    return this.resetBusBrandChoices(techOrSpare);
                } else if (value === 'truck') {
                    return this.resetTruckBrandChoices(techOrSpare);
                } 
            },
            resetVehicleTypes(vehicleCategories, techOrSpare) {
                var vehicleTypes = Array.from(Object.values(this.allVehicleCategories));
                vehicleTypes = vehicleTypes.map(value => value.toLowerCase());
                const difference = vehicleTypes.filter(value => !vehicleCategories.includes(value));
                difference.map(value => this.resetVehicleChoices(value, techOrSpare));
            },
            resetAllVehicleTypes(techOrSpare) {
                var vehicleTypes = Array.from(Object.values(this.allVehicleCategories));
                vehicleTypes = vehicleTypes.map(value => value.toLowerCase());
                vehicleTypes.map(value => this.resetVehicleChoices(value, techOrSpare));
            }
        },
        computed: {
            setBusinessAccount() {
                return this.businessAccount ? 'active' : 'inactive';
            },
            setArtisanChoicesVisible() {
                return this.artisanChoicesVisible ? 'active' : 'inactive';
            },
            setMobileSellerChoicesVisible() {
                return this.mobileSellerChoicesVisible ? 'active' : 'inactive';
            },
            setTechServChoicesVisible() {
                return this.techServChoicesVisible ? 'active' : 'inactive';
            },
            setSparePartChoicesVisible() {
                return this.sparePartChoicesVisible ? 'active' : 'inactive';
            },
            setVehicleCategoriesVisible() {
                return this.vehicleCategoriesVisible ? 'active' : 'inactive';
            },
            setCarBrandsVisible() {
                return this.carBrandsVisible ? 'active' : 'inactive';
            },
            setBusBrandsVisible() {
                return this.busBrandsVisible ? 'active' : 'inactive';
            },
            setTruckBrandsVisible() {
                return this.truckBrandsVisible ? 'active' : 'inactive';
            },
            setVehicleCategoriesVisibleSS() {
                return this.vehicleCategoriesVisibleSS ? 'active' : 'inactive';
            },
            setCarBrandsVisibleSS() {
                return this.carBrandsVisibleSS ? 'active' : 'inactive';
            },
            setBusBrandsVisibleSS() {
                return this.busBrandsVisibleSS ? 'active' : 'inactive';
            },
            setTruckBrandsVisibleSS() {
                return this.truckBrandsVisibleSS ? 'active' : 'inactive';
            }
        },
    }
</script>

<style scoped>
    .active {
        display: block;
    }
    .inactive {
        display: none;
    }
</style>