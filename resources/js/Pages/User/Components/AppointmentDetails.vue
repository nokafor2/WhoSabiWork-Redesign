<template>
    <div class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ appointmentCount() }}</h6>
        </div>
    </div>
    <!-- @click="showMainApt2"  -->
    <div v-for="(appointmentDetail, index) in appointmentDetailsObj" :key="index" class="card col-12 mb-3">
        <div :id="subAptId(index)" class="card-body mb-2" style="display: block;">
            <div class="d-flex">
                <div class="row">
                    <img :src="imagePath(index)" style="height: 7rem; width: 100%;" class="card-img-top" alt="...">
                </div>
                <div class="">
                    <div class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <span v-for="(aptTime, index) in appointmentDetail.time" :key="index">
                                <i class="fa-regular fa-clock me-1"></i>
                                <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                            </span>
                        </p>
                    </div>
                    <div class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-shop"></i>
                            {{ appointmentDetail.businessName }}
                        </p>
                    </div>
                    <div class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-phone"></i>
                            {{ appointmentDetail.phoneNumber }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row mt-3 ">
                    <button :id="showAptId(index)" class="btn btn-white btn-sm" @click="showMainApt">Show</button>
                </div>
            </div>
        </div>
        <div class="card-body" :id="mainSubAptId(index)" style="display: none;">
            <h5 class="card-title text-center">Appointment Details</h5>
            <div class="row mb-2">
                <img :src="imagePath(index)" style="height: 10rem; width: 100%;" class="card-img-top" alt="...">
            </div>
            <!-- Use this to get the number of appointments -->
            <p class="d-none">{{ appointmentNum(appointmentDetail.size) }}</p>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-shop"></i>
                    {{ appointmentDetail.businessName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-regular fa-user"></i>
                    {{ appointmentDetail.fullName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-phone"></i>
                    {{ appointmentDetail.phoneNumber }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ appointmentDetail.address }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{ appointmentDetail.date.prettyDate }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <span v-for="(aptTime, index) in appointmentDetail.time" :key="index">
                        <i class="fa-regular fa-clock me-1"></i>
                        <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                    </span>
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <!-- <span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">
                        Appointment Reason:
                    </span> -->
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetail.appointmentMessage }}
                </p>
            </div>
            <div class="row mt-3 mb-2">
                <button :id="reschAptId(index)" class="btn btn-outline-danger btn-sm" @click="reschApt">Reschedule Appointment</button>
            </div>
            <div class="row">
                <button :id="cancelAptId(index)" class="btn btn-white btn-sm" @click="cancelApt">Cancel Appointment</button>
            </div>
            <div class="row mt-3 ">
                <button :id="hideAptId(index)" class="btn btn-white btn-sm" @click="hideMainApt">Hide</button>
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
                size: 0,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                visible: true,
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

            appointmentNum(aptNum) {
                this.size = aptNum;
            },

            appointmentCount() {
                // var count = Object.values(this.appointmentDetails).length;
                
                if (this.size < 1) {
                    return "You have no appointment waiting confirmation.";
                } else if (this.size === 1) {
                    return "You have 1 appointment waiting confirmation.";
                } else if (this.size > 1) {
                    return "You have "+this.size+" appointments waiting confirmation.";
                }
            },

            imagePath(index) {
                index = index + 1;
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            subAptId(index) {
                return "subApt"+index;
            },
            mainSubAptId(index) {
                return "main-subApt"+index;
            },
            showAptId(index) {
                return "showBtn-subApt"+index;
            },
            hideAptId(index) {
                return "hideBtn-subApt"+index;
            },
            cancelAptId(index) {
                return "cancelApt"+index;
            },
            reschAptId(index) {
                return "reschApt"+index;
            },
            showMainApt2(event) {
                var divId = event.currentTarget.id;
                var mainDivId = "main-"+divId;
                this.toggleDiv(mainDivId);
            },
            showMainApt(event) {
                var btnId = event.currentTarget.id;
                // remove 'showBtn' from the id
                var subDivId = btnId.replace("showBtn-", "");
                var mainDivId = "main-"+subDivId;
                this.toggleDiv(subDivId);
                this.toggleDiv(mainDivId);
            },
            hideMainApt(event) {
                var btnId = event.currentTarget.id;
                // remove 'hideBtn' from the id
                var subDivId = btnId.replace("hideBtn-", "");
                var mainDivId = "main-"+subDivId;
                this.toggleDiv(subDivId);
                this.toggleDiv(mainDivId);
            },
            toggleDiv(divId) {
                const div = document.getElementById(divId);
                if (div.style.display === "none" || div.style.display === "") {
                    div.style.display = "block"; // Show the div
                } else {
                    div.style.display = "none"; // Hide the div
                }
            },
            reschApt() {
                return "";
            },
            cancelApt() {
                return "";
            }
        },
        computed: {
            // appointmentCount() {
            //     // var count = Object.values(this.appointmentDetails).length;
                
            //     if (this.size < 1) {
            //         return "You have no appointment waiting confirmation.";
            //     } else if (this.size === 1) {
            //         return "You have 1 appointment waiting confirmation.";
            //     } else if (this.size > 1) {
            //         return "You have "+count+" appointments waiting confirmation.";
            //     }
            // }
        }
    }
</script>