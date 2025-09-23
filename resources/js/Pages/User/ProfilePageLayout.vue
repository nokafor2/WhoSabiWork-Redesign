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
        <slot></slot>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                
            }
        }
    }
</script>