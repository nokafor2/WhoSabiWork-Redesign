<template>
    <div class="col-sm-6 col-11 shadow text-light mt-3 rounded pb-3 pb-lg-0" style="background-color: #040D14">
        <p class="display-6 text-center mb-0 mb-lg-3 "> {{ businessName }}</p>
        <div class="row justify-content-evenly">
            <div class="col-4 align-middle text-center my-auto pe-2">
                <img class="rounded-circle" style="height: 120px; width: 120px;" :src=imagePath(0)>
            </div>
            <div class="col-8">
                <p class="text-center fs-3 mb-0 mb-lg-3 rounded-pill">{{ fullName }}</p>
                <div class="row col-12 text-center text-sm-end text-lg-start gap-1 mb-1 mb-lg-0 justify-content-center">
                    <p class="col-lg mb-0 mb-lg-3 bg-light text-body rounded-pill w-auto d-block-md">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star-half-stroke"></i>
                        <i class="fa-regular fa-star"></i>
                    </p>
                    <p class="col-lg mb-0 mb-lg-3 bg-light text-body rounded-pill w-auto">
                        {{ advocators }} advocators
                    </p>
                </div>
                <div class="row col-12 text-center text-lg-start gap-1 justify-content-center">
                    <p class="col-lg mb-0 mb-lg-3 bg-light text-body rounded-pill w-auto">
                        <i class="fa-brands fa-facebook px-1"></i>
                        <i class="fa-brands fa-x-twitter px-1"></i>
                        <i class="fa-brands fa-whatsapp px-1"></i>
                        <i class="fa-brands fa-linkedin px-1"></i>
                    </p>
                    <p class="col-lg mb-0 mb-lg-3 bg-danger text-light rounded w-auto btn btn-sm">
                        Set Appointment
                    </p>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <!-- <span class="badge">contact:</span> -->
            <p class="col mb-1 mb-md-3"><i class="fa-solid fa-location-dot me-2"></i>{{ userAddress }}</p>
            <div class="row">
                <p class="col-auto mb-1 mb-md-3"><i class="fa-solid fa-phone me-2"></i>{{ phoneNumber }}</p>
                <p class="col-auto mb-1 mb-md-3"><i class="fa-regular fa-envelope me-2"></i>{{ email }}</p>
            </div>
            <p class="col mb-1 mb-md-3"><i class="fa-solid fa-globe me-2"></i>{{ email }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                address: '',
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
        },
        computed: {
            fullName() {
                return this.user.userDetails.first_name+" "+this.user.userDetails.last_name;
            },
            userAddress() {
                const addressLine1 = this.user.userDetails.address.address_line_1;
                const addressLine2 = this.user.userDetails.address.address_line_2;
                const addressLine3 = this.user.userDetails.address.address_line_3;
                const town = this.user.userDetails.address.town;
                const state = this.user.userDetails.address.state;

                if (addressLine1 !== '') {
                    this.address = this.address + addressLine1;
                }
                if (addressLine2 !== '') {
                    this.address = this.address + ', '+addressLine2;
                }
                if (addressLine3 !== '') {
                    this.address = this.address + ', '+addressLine3;
                }
                if (town !== '') {
                    this.address = this.address + ', '+town;
                }
                if (state !== '') {
                    this.address = this.address + ', '+state;
                }

                return this.address;
            },
            phoneNumber() {
                return this.user.userDetails.phone_number;
            },
            email() {
                return this.user.userDetails.email;
            },
            businessName() {
                return this.user.userDetails.business_category.business_name;
            },
            businessPage() {
                return this.user.userDetails.business_category.business_page;
            },
            advocators() {
                return this.user.userDetails.business_category.views;
            }
        }
    }
</script>