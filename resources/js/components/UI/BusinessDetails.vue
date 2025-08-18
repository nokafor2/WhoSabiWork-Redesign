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
                        <i v-for="rating in userRating" :key="rating" class="fa-solid fa-star"></i>
                    </p>
                    <p class="col-lg mb-0 mb-lg-3 bg-light text-body rounded-pill w-auto" >
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
                    <p class="col-lg mb-0 mb-lg-3 bg-danger text-light rounded w-auto btn btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" >
                        Set Appointment
                    </p>
                    <SelectAppointment :entrepreneurId="entrepreneurId" :datesAvailable="availableDates"></SelectAppointment>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <p class="col mb-1 mb-md-3"><i class="fa-solid fa-location-dot me-2"></i>{{ userAddress }}</p>
            <div class="row">
                <p class="col-auto mb-1 mb-md-3"><i class="fa-solid fa-phone me-2"></i>{{ phoneNumber }}</p>
                <p class="col-auto mb-1 mb-md-3"><i class="fa-regular fa-envelope me-2"></i>{{ email }}</p>
            </div>
            <p class="col mb-1 mb-md-3"><i class="fa-solid fa-globe me-2"></i>{{ businessPage }}</p>
        </div>
    </div>
</template>

<script>
    import { useForm } from '@inertiajs/vue3';
    import SelectAppointment from './SelectAppointment.vue';

    export default {
        components: {SelectAppointment},
        props: ['user'],
        data() {
            return {
                address: '',
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                // userRating: '',
                entrepreneurId: this.user.userDetails.id,
                availableDates: this.user.datesAvailable,
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            // getAvailableDates() {
            //     var formData = useForm({
            //         'userId': this.userId
            //     });
            //     formData.post(route('availabilityDates', this.userId), {
            //         preserveState: true,
            //         preserveScroll: true,
            //         onSuccess: (page) => {
            //             if (page.props.flash.success) {
            //                 console.log(page);
            //                 this.availableDates = page.props.flash.success;
            //             }
            //         },
            //         onError: (errors) => {
            //             console.log('Error: ', errors);
            //         }
            //     });
            // }
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

                var address = '';
                if (addressLine1 !== '') {
                    address = address + addressLine1;
                }
                if (addressLine2 !== '') {
                    address = address + ', '+addressLine2;
                }
                if (addressLine3 !== '') {
                    address = address + ', '+addressLine3;
                }
                if (town !== '') {
                    address = address + ', '+town;
                }
                if (state !== '') {
                    address = address + ', '+state;
                }

                return address;
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
            },
            userRating() {
                return this.user.userRating.avgRating;
            }
        }
    }
</script>