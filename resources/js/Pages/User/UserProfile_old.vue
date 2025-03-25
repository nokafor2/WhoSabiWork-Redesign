<template>
    <div class="mx-auto my-3 text-center display-5">
        {{ fullName }}
    </div>
    
    <div class="d-flex align-items-start justify-content-center">
        <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">User Details</button>
            <button class="nav-link text-start" id="comments-tab" data-bs-toggle="pill" data-bs-target="#comments" type="button" role="tab" aria-controls="comments" aria-selected="false">Comments</button>
            <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
        </div>
        <div class="tab-content col-9 col-md-8 mb-3" id="v-pills-tabContent">
            <!-- User details pane -->
            <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
                <div class="accordion" id="accordionExample">
                    <!-- User Details section -->
                    <!-- Profile Photo Section -->
                    <ProfilePhotoSection></ProfilePhotoSection>

                    <!-- {{-- First name accordion --}} -->
                    <FirstNameSection :firstName="user.first_name" :userId="user.id"></FirstNameSection>
                    
                    <!-- {{-- Last name accordion --}} -->
                    <LastNameSection :lastName="user.last_name" :userId="user.id"></LastNameSection>
                    
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
                </div>
            </div>
    
            <!-- Comments pane -->
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded bg-dark text-light pb-3">
                        <p class="pt-2">5 comment(s)</p>
                        <CommentCard v-for="index in 5" :key="index"></CommentCard>
                    </div>
                </div>
            </div>
    
            <!-- Appointments pane -->
            <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments requested with technicians</p>
                        <AppointmentDetails1></AppointmentDetails1>

                        <AppointmentDetails></AppointmentDetails>
                        
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have 1 appointment waiting confirmation</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request declined.</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request canceled.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments confirmed with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment confirmed yet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ProfilePhotoSection from './Components/ProfilePhotoSection.vue';
    import FirstNameSection from './Components/FirstNameSection.vue';
    import LastNameSection from './Components/LastNameSection.vue';
    import GenderSection from './Components/GenderSection.vue';
    import UsernameSection from './Components/UsernameSection.vue';
    import EmailSection from './Components/EmailSection.vue';
    import PhoneNumberSection from './Components/PhoneNumberSection.vue';
    import PasswordSection from './Components/PasswordSection.vue';
    import CommentCard from './Components/CommentCard.vue';
    import AppointmentDetails from './Components/AppointmentDetails.vue';
    import AppointmentDetails1 from './Components/AppointmentDetails1.vue';

    export default {
        components: {ProfilePhotoSection, FirstNameSection, LastNameSection, GenderSection, UsernameSection, EmailSection, PhoneNumberSection, PasswordSection, CommentCard, AppointmentDetails, AppointmentDetails1},
        props: ['user'],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            capitalizeFirstLetter(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            },  
        },
        computed: {
            fullName() {
                return this.user.first_name + " " + this.user.last_name
            }
        }
    }
</script>