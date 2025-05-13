<template>
    <div v-if="mainAptVisible" class="card-body" :id="mainSubAptId(index)" > 
        <h5 class="card-title text-center">Appointment Details</h5>
        <div class="row mb-2">
            <img :src="imagePath(index)" style="height: 10rem; width: 100%;" class="card-img-top" alt="...">
        </div>
        
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
</template>

<script>
    export default {
        props: ['appointmentDetail', 'index'],
        // inject: ['mainAptVisible'],
        emits: ['sub-apt-visible', 'main-apt-visible'],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                // mainAptVisible2: this.mainAptVisible,
                // mainAptVisible: this.$store.getters["appointment/getMainAptVisible"],
                mainAptVisible: false,
            }
        },
        methods: {
            imagePath(index) {
                index = index + 1;
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            hideAptId(index) {
                return "hideBtn-subApt"+index;
            },
            mainSubAptId(index) {
                return "main-subApt"+index;
            },
            cancelAptId(index) {
                return "cancelApt"+index;
            },
            reschAptId(index) {
                return "reschApt"+index;
            },
            hideMainApt(event) {
                // var btnId = event.currentTarget.id;
                // // remove 'hideBtn' from the id
                // var subDivId = btnId.replace("hideBtn-", "");
                // var mainDivId = "main-"+subDivId;
                // this.toggleDiv(subDivId);
                // this.toggleDiv(mainDivId);
                
                // this.mainAptVisible2 = false;
                // Emit this value
                // this.$emit('main-apt-visible', false);
                // this.$emit('sub-apt-visible', true);
                // this.subAptVisible = true;

                this.$store.dispatch("appointment/updateMainAptVisible", false);
                this.$store.dispatch("appointment/updateSubAptVisible", true);
            },
            reschApt() {
                return "";
            },
            cancelApt() {
                return "";
            }
        }
    }
</script>
