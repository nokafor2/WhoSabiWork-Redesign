export default {
    mounted() {
        this.useRef();
    },
    methods: {
        useRef() {
            // Just point to the function, do not execute.
            // When the submit button is clicked, it would be executed.
            this.validateForm;
            this.resetBusinessAccountValidation;
        },
        validateForm() {
            this.formIsValid = true;

            if (this.firstName.val === '') {
                this.firstName.isValid = false;
                this.formIsValid = false;
                this.$refs.firstAndLastNames.firstName.isValid = false;
            }

            if (this.lastName.val === '') {
                this.lastName.isValid = false;
                this.formIsValid = false;
                this.$refs.firstAndLastNames.lastName.isValid = false;
            }

            if (this.password.val === '') {
                this.password.isValid = false;
                this.formIsValid = false;
                this.$refs.passwordAndConfirm.password.isValid = false;
            }

            if (this.confirmPassword.val === '') {
                this.confirmPassword.isValid = false;
                this.formIsValid = false;
                this.$refs.passwordAndConfirm.confirmPassword.isValid = false;
            }

            if (this.gender.val === '') {
                this.gender.isValid = false;
                this.formIsValid = false;
                this.$refs.genderAndUsername.gender.isValid = false;
            }

            if (this.username.val === '') {
                this.username.isValid = false;
                this.formIsValid = false;
                this.$refs.genderAndUsername.username.isValid = false;
            }

            if (!this.phoneNumber.val) {
                this.phoneNumber.isValid = false;
                this.formIsValid = false;
                this.$refs.phoneAndEmail.phoneNumber.isValid = false;
            }

            if (this.email.val === '') {
                this.email.isValid = false;
                this.formIsValid = false;
                this.$refs.phoneAndEmail.email.isValid = false;
            }

            // check if business account is checked before validating
            if (this.businessAccount) {
                if (this.addressLine1.val === '') {
                    this.addressLine1.isValid = false;
                    this.formIsValid = false;
                    this.$refs.addressLines.addressLine1.isValid = false;
                }

                // if (this.addressLine2.val === '') {
                //     this.addressLine2.isValid = false;
                //     this.formIsValid = false;
                //     this.$refs.addressLines.addressLine2.isValid = false;
                // }

                // if (this.addressLine3.val === '') {
                //     this.addressLine3.isValid = false;
                //     this.formIsValid = false;
                //     this.$refs.addressLines.addressLine3.isValid = false;
                // }

                if (this.town.val === '') {
                    this.town.isValid = false;
                    this.formIsValid = false;
                    this.$refs.stateAndTown.town.isValid = false;
                }

                if (this.state.val === '') {
                    this.state.isValid = false;
                    this.formIsValid = false;
                    this.$refs.stateAndTown.state.isValid = false;
                }

                if (this.businessName.val === '') {
                    this.businessName.isValid = false;
                    this.formIsValid = false;
                    this.$refs.businessNameAndCat.businessName.isValid = false;
                }

                if (this.businessCategories.val.length === 0) {
                    this.businessCategories.isValid = false;
                    this.formIsValid = false;
                    this.$refs.businessNameAndCat.businessCategories.isValid = false;
                } else {
                    if (this.businessCategories.val.includes('artisan')) {
                        // validate selected artisans
                        if (this.selectedArtisans.val.length === 0) {
                            this.selectedArtisans.isValid = false;
                            this.formIsValid = false;
                            this.$refs.artisansChkbxs.selectedArtisans.isValid = false;
                        } else {
                            // check if mechanic is selected
                            if (this.selectedArtisans.val.includes('mechanic')) {
                                // validate technicians selected
                                if (this.selectedTechnicalServices.val.length === 0) {
                                    this.selectedTechnicalServices.isValid = false;
                                    this.formIsValid = false;
                                    this.$refs.techServsChkbxs.selectedTechnicalServices.isValid = false;
                                } else {
                                    // Validate vehicle categories
                                    if (this.selectedVehicleCategories.val.length === 0) {
                                        this.selectedVehicleCategories.isValid = false;
                                        this.formIsValid = false;
                                        this.$refs.vehicleCatsChkbxs.selectedVehicleCategories.isValid = false;
                                    } else {
                                        // Validate the vehicle types selected
                                        if (this.selectedVehicleCategories.val.includes('car')) {
                                            if (this.selectedCarBrands.val.length === 0) {
                                                this.selectedCarBrands.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.carBrandsChkbxs.selectedCarBrands.isValid = false;
                                            }
                                        }
                                        if (this.selectedVehicleCategories.val.includes('bus')) {
                                            if (this.selectedBusBrands.val.length === 0) {
                                                this.selectedBusBrands.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.busBrandsChkbxs.selectedBusBrands.isValid = false;
                                            }
                                        }
                                        if (this.selectedVehicleCategories.val.includes('truck')) {
                                            if (this.selectedTruckBrands.val.length === 0) {
                                                this.selectedTruckBrands.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.truckBrandsChkbxs.selectedTruckBrands.isValid = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if (this.businessCategories.val.includes('mobileMarket')) {
                        if (this.selectedMobileSellers.val.length === 0) {
                            this.selectedMobileSellers.isValid = false;
                            this.formIsValid = false;
                            this.$refs.mobileMarketsChkbxs.selectedMobileSellers.isValid = false;
                        } else {
                            // check if spare part is seleted
                            if (this.selectedMobileSellers.val.includes('spare_parts')) {
                                // validate spare parts selected
                                if (this.selectedSpareParts.val.length === 0) {
                                    this.selectedSpareParts.isValid = false;
                                    this.formIsValid = false;
                                    this.$refs.sparePartsChkbxs.selectedSpareParts.isValid = false;
                                } else {
                                    // Validate vehicle categories
                                    if (this.selectedVehicleCategoriesSS.val.length === 0) {
                                        this.selectedVehicleCategoriesSS.isValid = false;
                                        this.formIsValid = false;
                                        this.$refs.vehicleCatsChkbxsSS.selectedVehicleCategories.isValid = false;
                                    } else {
                                        // Validate the vehicle types selected
                                        if (this.selectedVehicleCategoriesSS.val.includes('car')) {
                                            if (this.selectedCarBrandsSS.val.length === 0) {
                                                this.selectedCarBrandsSS.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.carBrandsChkbxsSS.selectedCarBrands.isValid = false;
                                            }
                                        }
                                        if (this.selectedVehicleCategoriesSS.val.includes('bus')) {
                                            if (this.selectedBusBrandsSS.val.length === 0) {
                                                this.selectedBusBrandsSS.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.busBrandsChkbxsSS.selectedBusBrands.isValid = false;
                                            }
                                        }
                                        if (this.selectedVehicleCategoriesSS.val.includes('truck')) {
                                            if (this.selectedTruckBrandsSS.val.length === 0) {
                                                this.selectedTruckBrandsSS.isValid = false;
                                                this.formIsValid = false;
                                                this.$refs.truckBrandsChkbxsSS.selectedTruckBrands.isValid = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },
        resetBusinessAccountValidation() {
            this.$refs.addressLines.addressLine1.isValid = true;
            this.$refs.addressLines.addressLine2.isValid = true;
            this.$refs.addressLines.addressLine3.isValid = true;
            this.$refs.stateAndTown.state.isValid = true;
            this.$refs.stateAndTown.town.isValid = true;
            this.$refs.businessNameAndCat.businessName.isValid = true;
            this.$refs.businessNameAndCat.businessCategories.isValid = true;
        }
    }
}