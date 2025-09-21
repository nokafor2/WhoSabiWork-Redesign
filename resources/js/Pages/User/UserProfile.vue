<template>
    <div class="mx-auto my-3 text-center display-5">
        {{ fullName }}
    </div>
    
    <div class="d-flex align-items-start justify-content-center">
        <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">Business Details</button>
            <button v-if="businessAccount" class="nav-link text-start" id="photoGallery-tab" data-bs-toggle="pill" data-bs-target="#photoGallery" type="button" role="tab" aria-controls="photoGallery" aria-selected="false">Photo Gallery</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersComments-tab" data-bs-toggle="pill" data-bs-target="#customersComments" type="button" role="tab" aria-controls="customersComments" aria-selected="false">Customers Comments</button>
            <button class="nav-link text-start" id="yourComments-tab" data-bs-toggle="pill" data-bs-target="#yourComments" type="button" role="tab" aria-controls="yourComments" aria-selected="false">My Comments</button>
            <button v-if="businessAccount" class="nav-link text-start" id="availability-tab" data-bs-toggle="pill" data-bs-target="#availability" type="button" role="tab" aria-controls="availability" aria-selected="false">Set Availability</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersAppointments-tab" data-bs-toggle="pill" data-bs-target="#customersAppointments" type="button" role="tab" aria-controls="customersAppointments" aria-selected="false">Customers Appointments</button>
            <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
        </div>
        <div class="tab-content col-9 col-md-8 mb-3" id="v-pills-tabContent">
            <!-- Business details pane -->
            <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
                <div class="accordion" id="accordionExample">
                    <!-- User Details section -->
                    <!-- Profile Photo Section - Modern -->
                    <ProfilePhotoModern :user="user" @photoUpdated="handlePhotoUpdated"></ProfilePhotoModern>
                    
                    <!-- {{-- First name accordion --}} -->
                    <FirstNameSection :firstName="user.first_name" :userId="user.id"></FirstNameSection>
                    <!-- <FieldAccordion :fieldName="'first_name'" :fieldVal="user.first_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Last name accordion --}} -->
                    <LastNameSection :lastName="user.last_name" :userId="user.id"></LastNameSection>
                    <!-- <FieldAccordion :fieldName="'last_name'" :fieldVal="user.last_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Gender accordion --}} -->
                    <GenderSection :gender="user.gender" :userId="user.id"></GenderSection>
                    
                    <!-- {{-- Username accordion --}} -->
                    <UsernameSection :username="user.username" :userId="user.id"></UsernameSection>                    
                    
                    <!-- {{-- Email accordion --}} -->
                    <EmailSection :email="user.email" :userId="user.id"></EmailSection>
                    
                    <!-- {{-- Phone number accordion --}} -->
                    <PhoneNumberSection :phoneNumber="user.phone_number" :userId="user.id"></PhoneNumberSection>
                    
                    <!-- {{-- Password accordion --}} -->
                    <PasswordSection :userId="user.id"></PasswordSection>

                    <!-- {{-- Business name accordion --}} -->
                    <BusinessNameSection v-if="businessAccount" :businessName="user.business_category.business_name" :userId="user.id"></BusinessNameSection>

                    <!-- {{-- Business description accordion --}} -->
                    <BusinessDescriptionSection v-if="businessAccount" :businessDescription="user.business_category.business_description" :userId="user.id"></BusinessDescriptionSection>

                    <!-- {{-- Business page accordion --}} -->
                    <BusinessPageSection v-if="businessAccount" :businessPage="user.business_category.business_page" :userId="user.id"></BusinessPageSection>
                    
                    <!-- {{-- Address Line 1 accordion --}} -->
                    <AddressLine1Section v-if="businessAccount" :addressLine1="user.address.address_line_1" :userId="user.id"></AddressLine1Section>

                    <!-- {{-- Address Line 2 accordion --}} -->
                    <AddressLine2Section v-if="businessAccount" :addressLine2="user.address.address_line_2" :userId="user.id"></AddressLine2Section>
                    
                    <!-- {{-- Address Line 3 accordion --}} -->
                    <AddressLine3Section v-if="businessAccount" :addressLine3="user.address.address_line_3" :userId="user.id"></AddressLine3Section>
                    
                    <!-- {{-- State accordion --}} -->
                    <StateSection v-if="businessAccount" :state="user.address.state" :userId="user.id"></StateSection>
                    
                    <!-- {{-- Town accordion --}} -->
                    <TownSection v-if="businessAccount" :town="user.address.town" :userId="user.id"></TownSection>
                    
                    <!-- {{-- Business category accordion --}} -->
                    <BusinessCategorySection v-if="businessAccount" :businessCategory="user.business_category" :userId="user.id"></BusinessCategorySection>
                    
                    <!-- {{-- Artisans accordion --}} -->
                    <ArtisansSection v-if="isArtisan" :allArtisans="allArtisans" :selectedArtisans="isArtisan" :userId="user.id"></ArtisansSection>
                    
                    <!-- {{-- Mobile market accordion --}} -->
                    <MobileMarketSection v-if="isMobileMarketer" :allProducts="allProducts" :selectedProducts="isMobileMarketer" :userId="user.id"></MobileMarketSection>
                    
                    <!-- {{-- Technicians accordion --}} -->
                    <TechnicalServiceSection v-if="isMechanic" :allTechnicalServices="allTechnicalServices" :selectedTechnicians="isMechanic" :userId="user.id"></TechnicalServiceSection>
                    
                    <!-- Vehicle Category accordion for technical service -->
                    <VehicleCategorySection v-if="hasTechVehCategory" :allVehicleCategories="allVehicleCategories" :selectedVehCategories="hasTechVehCategory" :userId="user.id" :techOrSpare="'technical_service'"></VehicleCategorySection>
                    
                    <!-- {{-- Car brands accordion --}} -->
                    <CarBrandsSection v-if="hasTechCarBrands" :allCarBrands="allCarBrands" :selectedCarBrands="hasTechCarBrands" :userId="user.id" :techOrSpare="'technical_service'"></CarBrandsSection>
                    
                    <!-- {{-- Bus brands accordion --}} -->
                    <BusBrandsSection v-if="hasTechBusBrands" :allBusBrands="allBusBrands" :selectedBusBrands="hasTechBusBrands" :userId="user.id" :techOrSpare="'technical_service'"></BusBrandsSection>
                    
                    <!-- {{-- Truck brnads accordion --}} -->
                    <TruckBrandsSection v-if="hasTechTruckBrands" :allTruckBrands="allTruckBrands" :selectedTruckBrands="hasTechTruckBrands" :userId="user.id" :techOrSpare="'technical_service'"></TruckBrandsSection>
                    
                    <!-- {{-- Spare part sellers accordion --}} -->
                    <SparePartSection v-if="isSparePartSeller" :allSpareParts="allSpareParts" :selectedSpareParts="isSparePartSeller" :userId="user.id"></SparePartSection>
                    
                    <!-- Vehicle Category accordion for spare parts -->
                    <VehicleCategorySection v-if="hasSpartVehCategory" :allVehicleCategories="allVehicleCategories" :selectedVehCategories="hasSpartVehCategory" :userId="user.id" :techOrSpare="'spare_part'"></VehicleCategorySection>
                    
                    <!-- {{-- Car brands accordion --}} -->
                    <CarBrandsSection v-if="hasSpartCarBrands" :allCarBrands="allCarBrands" :selectedCarBrands="hasSpartCarBrands" :userId="user.id" :techOrSpare="'spare_part'"></CarBrandsSection>
                    
                    <!-- {{-- Bus brands accordion --}} -->
                    <BusBrandsSection v-if="hasSpartBusBrands" :allBusBrands="allBusBrands" :selectedBusBrands="hasSpartBusBrands" :userId="user.id" :techOrSpare="'spare_part'"></BusBrandsSection>
                    
                    <!-- {{-- Truck brnads accordion --}} -->
                    <TruckBrandsSection v-if="hasSpartTruckBrands" :allTruckBrands="allTruckBrands" :selectedTruckBrands="hasSpartTruckBrands" :userId="user.id" :techOrSpare="'spare_part'"></TruckBrandsSection>
                </div>
            </div>
            <!-- End of Business details pane -->
    
            <!-- Photo gallery -->
            <div v-if="businessAccount" class="tab-pane fade" id="photoGallery" role="tabpanel" aria-labelledby="photoGallery-tab" tabindex="0">
                <div class="gallery">
                    <div class="container">
                        <PhotoUpload></PhotoUpload>
                    </div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md 10">
                                <div class="row justify-content-evenly">
                                    <ImageCard v-for="index in 6" :key="index" :index="index" :userId="index"></ImageCard>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Photo gallery -->
    
            <!-- Contents for Customers Comments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersComments" role="tabpanel" aria-labelledby="customersComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" style="background-color: #040D14">
                        <p class="pt-2">5 comment(s)</p>
                        <CommentAndReplyCard :index="3" :replies="{}"></CommentAndReplyCard>
                        
                        <CommentAndReplyCard :index="5" :replies="replies"></CommentAndReplyCard>
                        
                        <CommentAndReplyCard :index="10" :replies="{}"></CommentAndReplyCard>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Comments -->
            
            <!-- Contents for My Comments -->
            <div class="tab-pane fade" id="yourComments" role="tabpanel" aria-labelledby="yourComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" >  <!-- style="background-color: #040D14" -->
                        <p class="pt-2 text-body">5 comment(s)</p>
                        <CommentCard v-for="index in 5" :key="index" :index="index"></CommentCard>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Comments -->
    
            <!-- Contents for Set Availability -->
            <div v-if="businessAccount" class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <DateAndTimeSelect @update-schedule="updateSchedule"></DateAndTimeSelect>
                    </div>

                    <div class="col-md-6">
                        <UserAvailability :schedules="schedules"></UserAvailability>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Set Availability -->
    
            <!-- Contents for Customers Appointments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersAppointments" role="tabpanel" aria-labelledby="customersAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <AppointmentDetails :appointmentDetails="neutralAppointments.appointmentDetails" :aptNum="neutralAppointments.aptNum" :user="'entrepreneur'" :appointmentType="'neutral'" @update-apt-details="updateAptDetails"></AppointmentDetails>

                        <AppointmentDetails :appointmentDetails="declinedAppointments.appointmentDetails" :aptNum="declinedAppointments.aptNum" :user="'entrepreneur'" :appointmentType="'declined'" @update-apt-details="updateAptDetails"></AppointmentDetails>
                    </div>

                    <div class="col-md">
                        <AppointmentDetails :appointmentDetails="acceptedAppointments.appointmentDetails" :aptNum="acceptedAppointments.aptNum" :user="'entrepreneur'" :appointmentType="'accepted'" @update-apt-details="updateAptDetails"></AppointmentDetails>

                        <AppointmentDetails :appointmentDetails="cancelledAppointments.appointmentDetails" :aptNum="cancelledAppointments.aptNum" :user="'entrepreneur'" :appointmentType="'cancelled'" @update-apt-details="updateAptDetails"></AppointmentDetails>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Appointments -->
    
            <!-- Contents for My Appointments -->
            <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <AppointmentDetails :appointmentDetails="neutralAppointmentsSchdlr.appointmentDetails" :aptNum="neutralAppointmentsSchdlr.aptNum" :user="'scheduler'" :appointmentType="'neutral'" @update-apt-details="updateAptDetails"></AppointmentDetails>

                        <AppointmentDetails :appointmentDetails="declinedAppointmentsSchdlr.appointmentDetails" :aptNum="declinedAppointmentsSchdlr.aptNum" :user="'scheduler'" :appointmentType="'declined'" @update-apt-details="updateAptDetails"></AppointmentDetails>
                    </div>

                    <div class="col-md">
                        <AppointmentDetails :appointmentDetails="acceptedAppointmentsSchdlr.appointmentDetails" :aptNum="acceptedAppointmentsSchdlr.aptNum" :user="'scheduler'" :appointmentType="'accepted'" @update-apt-details="updateAptDetails"></AppointmentDetails>

                        <AppointmentDetails :appointmentDetails="cancelledAppointmentsSchdlr.appointmentDetails" :aptNum="cancelledAppointmentsSchdlr.aptNum" :user="'scheduler'" :appointmentType="'cancelled'" @update-apt-details="updateAptDetails"></AppointmentDetails>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Appointments -->
        </div>
    </div>
</template>


<script>
    import FieldAccordion from './Components/FieldAccordion.vue';
    import ProfilePhotoModern from './Components/ProfilePhotoModern.vue';
    import FirstNameSection from './Components/FirstNameSection.vue';
    import LastNameSection from './Components/LastNameSection.vue';
    import GenderSection from './Components/GenderSection.vue';
    import UsernameSection from './Components/UsernameSection.vue';
    import EmailSection from './Components/EmailSection.vue';
    import PhoneNumberSection from './Components/PhoneNumberSection.vue';
    import PasswordSection from './Components/PasswordSection.vue';
    import CommentCard from './Components/CommentCard.vue';
    import AppointmentDetails from './Components/AppointmentDetails.vue';
    import AppointmentDetails2 from './Components/AppointmentDetails2.vue';
    import AppointmentDetails1 from './Components/AppointmentDetails1.vue';
    import BusinessNameSection from './Components/BusinessNameSection.vue';
    import AddressLine1Section from './Components/AddressLine1Section.vue';
    import AddressLine2Section from './Components/AddressLine2Section.vue';
    import AddressLine3Section from './Components/AddressLine3Section.vue';
    import StateSection from './Components/StateSection.vue';
    import TownSection from './Components/TownSection.vue';
    import BusinessCategorySection from './Components/BusinessCategorySection.vue';
    import BusinessDescriptionSection from './Components/BusinessDescriptionSection.vue';
    import ArtisansSection from './Components/ArtisansSection.vue';
    import MobileMarketSection from './Components/MobileMarketSection.vue';
    import TechnicalServiceSection from './Components/TechnicalServiceSection.vue';
    import SparePartSection from './Components/SparePartSection.vue';
    import CarBrandsSection from './Components/CarBrandsSection.vue';
    import BusBrandsSection from './Components/BusBrandsSection.vue';
    import TruckBrandsSection from './Components/TruckBrandsSection.vue';
    import VehicleCategorySection from './Components/VehicleCategorySection.vue';
    import BusinessPageSection from './Components/BusinessPageSection.vue';
    import ImageCard from './Components/ImageCard.vue';
    import CommentAndReplyCard from './Components/CommentAndReplyCard.vue';
    import PhotoUpload from './Components/PhotoUpload.vue';
    import DateAndTimeSelect from './Components/DateAndTimeSelect.vue';
    import UserAvailability from './Components/UserAvailability.vue';

    // import Mixins
    import MethodsMixin from './Mixins/MethodsMixin.js';
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        components: {
            FieldAccordion, ProfilePhotoModern, FirstNameSection, 
            LastNameSection, GenderSection, UsernameSection, EmailSection, PhoneNumberSection, 
            PasswordSection, CommentCard, AppointmentDetails, AppointmentDetails1, 
            BusinessNameSection, AddressLine1Section, AddressLine2Section, AddressLine3Section, 
            StateSection, TownSection, BusinessCategorySection, BusinessDescriptionSection, 
            ArtisansSection, MobileMarketSection, TechnicalServiceSection, SparePartSection,
            CarBrandsSection, BusBrandsSection, TruckBrandsSection, VehicleCategorySection,
            BusinessPageSection, ImageCard, CommentAndReplyCard, PhotoUpload, DateAndTimeSelect,
            UserAvailability, AppointmentDetails2
        },
        mixins: [MethodsMixin],
        props: ['user', 'userCategories', 'techVehCategories', 'sPartVehCategories', 'vehicleBrands', 
            'allArtisans', 'allProducts', 'allTechnicalServices', 'allSpareParts', 'allVehicleCategories', 
            'allCarBrands', 'allBusBrands', 'allTruckBrands', 'schedules', 'neutralAppointments', 
            'acceptedAppointments', 'declinedAppointments', 'cancelledAppointments', 'neutralAppointmentsSchdlr', 
            'acceptedAppointmentsSchdlr', 'declinedAppointmentsSchdlr', 'cancelledAppointmentsSchdlr'
        ],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                replies: {
                    0: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?',
                    1: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!',
                    2: 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.'
                },
                page: usePage(),
                schedules2: this.schedules,
                neutralAppointments2: this.neutralAppointments,
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            handlePhotoUpdated() {
                // Handle profile photo update event
                console.log('Profile photo updated');
                // You can add additional logic here if needed
                // For example, refresh user data or show success message
            },
            updateSchedule(schedules) {
                this.schedules2 = schedules;
            },
            updateAptDetails(appointmentDetails) {
                this.neutralAppointments = appointmentDetails;
            }
        },
        computed: {
            user() {
                return this.page.props.user;
            },
            flashSuccess() {
                return this.page.props.flash.success;
            },
            fullName() {
                return this.capitalizeFirstLetter(this.user.first_name) + " " + this.capitalizeFirstLetter(this.user.last_name);
            },
            businessAccount() {
                return (this.user.account_type === 'business') ? true : false;
            },
            isArtisan() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.artisan === "object" && (this.userCategories.artisan !== "undefined" || this.userCategories.artisan !== null)) {
                    return (Object.entries(this.userCategories.artisan).length > 0) ? this.userCategories.artisan : false;
                } else {
                    return false;
                }
            },
            isMobileMarketer() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.mobile_marketer === "object" && (this.userCategories.mobile_marketer !== "undefined" || this.userCategories.mobile_marketer !== null)) {
                    return (Object.entries(this.userCategories.mobile_marketer).length > 0) ? this.userCategories.mobile_marketer : false;
                } else {
                    return false;
                }
            },
            isMechanic() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.mechanic === "object" && (this.userCategories.mechanic !== "undefined" || this.userCategories.mechanic !== null)) {
                    return (Object.entries(this.userCategories.mechanic).length > 0) ? this.userCategories.mechanic : false;
                } else {
                    return false;
                }
            },
            isSparePartSeller() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.spare_part_seller === "object" && (this.userCategories.spare_part_seller !== "undefined" || this.userCategories.spare_part_seller !== null)) {
                    return (Object.entries(this.userCategories.spare_part_seller).length > 0) ? this.userCategories.spare_part_seller : false;
                } else {
                    return false;
                }
            },
            hasTechVehCategory() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                // check if vehicle brands exists
                if (typeof this.techVehCategories === "object" && (this.techVehCategories !== "undefined" || this.techVehCategories !== null)) {
                    return (Object.entries(this.techVehCategories).length > 0) ? this.techVehCategories : false;
                } else {
                    return false;
                }
            },
            hasSpartVehCategory() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.sPartVehCategories === "object" && (this.sPartVehCategories !== "undefined" || this.sPartVehCategories !== null)) {
                    return (Object.entries(this.sPartVehCategories).length > 0) ? this.sPartVehCategories : false;
                } else {
                    return false;
                }
            },
            hasTechCarBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.tech_car === "object" && (this.vehicleBrands.tech_car !== "undefined" || this.vehicleBrands.tech_car !== null)) {
                    return (Object.entries(this.vehicleBrands.tech_car).length > 0) ? this.vehicleBrands.tech_car : false;
                } else {
                    return false;
                } 
            },
            hasTechBusBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.tech_bus === "object" && (this.vehicleBrands.tech_bus !== "undefined" || this.vehicleBrands.tech_bus !== null)) {
                    return (Object.entries(this.vehicleBrands.tech_bus).length > 0) ? this.vehicleBrands.tech_bus : false;
                } else {
                    return false;
                }
            }, 
            hasTechTruckBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.tech_truck === "object" && (this.vehicleBrands.tech_truck !== "undefined" || this.vehicleBrands.tech_truck !== null)) {
                    return (Object.entries(this.vehicleBrands.tech_truck).length > 0) ? this.vehicleBrands.tech_truck : false;
                } else {
                    return false;
                }
            },
            hasSpartCarBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.sPart_car === "object" && (this.vehicleBrands.sPart_car !== "undefined" || this.vehicleBrands.sPart_car !== null)) {
                    return (Object.entries(this.vehicleBrands.sPart_car).length > 0) ? this.vehicleBrands.sPart_car : false;
                } else {
                    return false;
                }
            },
            hasSpartBusBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.sPart_bus === "object" && (this.vehicleBrands.sPart_bus !== "undefined" || this.vehicleBrands.sPart_bus !== null)) {
                    return (Object.entries(this.vehicleBrands.sPart_bus).length > 0) ? this.vehicleBrands.sPart_bus : false;
                } else {
                    return false;
                }
            },
            hasSpartTruckBrands() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.vehicleBrands.sPart_truck === "object" && (this.vehicleBrands.sPart_truck !== "undefined" || this.vehicleBrands.sPart_truck !== null)) {
                    return (Object.entries(this.vehicleBrands.sPart_truck).length > 0) ? this.vehicleBrands.sPart_truck : false;
                } else {
                    return false;
                }
            },
            hasNeutralApts() {
                if (typeof this.neutralAppointments === "object" && (this.neutralAppointments !== "undefined" || this.neutralAppointments !== null)) {
                    return (Object.entries(this.neutralAppointments).length > 0) ? this.neutralAppointments : false;
                } else {
                    return false;
                }
            }
        }
    }
</script>