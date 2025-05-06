<template>
    <div class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ appointmentCount }}</h6>
        </div>
    </div>

    <div v-for="(appointmentDetail, index) in appointmentDetailsObj" :key="index" class="card col-12 mb-3">
        <div class="card-body">
            <h5 class="card-title text-center">Appointment Details</h5>
            <!-- <img src="..." class="card-img-top" alt="..."> -->
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <i class="fa-solid fa-shop"></i>
                    {{ appointmentDetail.businessName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <i class="fa-regular fa-user"></i>
                    {{ appointmentDetail.fullName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <i class="fa-solid fa-phone"></i>
                    {{ appointmentDetail.phoneNumber }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ appointmentDetail.address }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{ appointmentDetail.date.prettyDate }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <span v-for="(aptTime, index) in appointmentDetail.time" :key="index">
                        <i class="fa-regular fa-clock me-1"></i>
                        <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                    </span>
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3">
                    <!-- <span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">
                        Appointment Reason:
                    </span> -->
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetail.appointmentMessage }}
                </p>
            </div>
            <div class="row mt-3 mb-2">
                <button class="btn btn-outline-danger btn-sm">Reschedule Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-white btn-sm">Cancel Appointment</button>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['appointmentDetails'],
        emits: [],
        data() {
            return {
                appointmentDetailsObj: this.appointmentDetails,
            }
        },
        methods: {
            convertTimeFormat(aptTimes) {
                var timeString = "testing";
                aptTimes.forEach(aptTime => {
                    timeString = aptTime.time+" "+aptTime.period+","
                });

                return timeString;
            },

            // appointmentCount() {
            //     var count = Object.values(this.appointmentDetails).length;
                
            //     if (count < 1) {
            //         return "You have no appointment waiting confirmation.";
            //     } else if (count === 1) {
            //         return "You have 1 appointment waiting confirmation.";
            //     } else if (count > 1) {
            //         return "You have "+count+" appointments waiting confirmation.";
            //     }
            // }
        },
        computed: {
            appointmentCount() {
                var count = Object.values(this.appointmentDetails).length;
                
                if (count < 1) {
                    return "You have no appointment waiting confirmation.";
                } else if (count === 1) {
                    return "You have 1 appointment waiting confirmation.";
                } else if (count > 1) {
                    return "You have "+count+" appointments waiting confirmation.";
                }
            }
        }
    }
</script>