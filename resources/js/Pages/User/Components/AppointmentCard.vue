<template>
    <div class="card col-12 mb-3">
        <div v-if="subAptVisible" :id="subAptId(index)" class="card-body mb-2" >
            <div class="d-flex">
                <div class="row">
                    <img :src="imagePath(index)" style="height: 7rem; width: 100%;" class="card-img-top" alt="...">
                </div>
                <div class="">
                    <div class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-calendar-day"></i>
                            {{ appointmentDetailObj.date.prettyDate }}
                        </p>
                    </div>
                    <div class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <span v-for="(aptTime, index) in appointmentDetailObj.time" :key="index">
                                <i class="fa-regular fa-clock me-1"></i>
                                <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                            </span>
                        </p>
                    </div>
                    <div v-if="checkSchdler()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-shop"></i>
                            {{ appointmentDetailObj.businessName }}
                        </p>
                    </div>
                    <div v-if="checkSchdler()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-phone"></i>
                            {{ appointmentDetailObj.phoneNumber }}
                        </p>
                    </div>
                    <!-- user === 'entrepreneur' -->
                    <div v-if="checkEntre()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-regular fa-user"></i>
                            {{ appointmentDetailObj.schedulerFullName }}
                        </p>
                    </div>
                    <div v-if="checkEntre()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-phone"></i>
                            {{ appointmentDetailObj.schedulerPhoneNumber }}
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
        
        <div v-if="mainAptVisible" class="card-body" :id="mainSubAptId(index)" > 
            <h5 class="card-title text-center">Appointment Details</h5>
            <div class="row mb-2">
                <img :src="imagePath(index)" style="height: 10rem; width: 100%;" class="card-img-top" alt="...">
            </div>
            
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-shop"></i>
                    {{ appointmentDetailObj.businessName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-regular fa-user"></i>
                    {{ appointmentDetailObj.fullName }}
                </p>
            </div>                                    
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-phone"></i>
                    {{ appointmentDetailObj.phoneNumber }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ appointmentDetailObj.address }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{ appointmentDetailObj.date.prettyDate }}
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <span v-for="(aptTime, index) in appointmentDetailObj.time" :key="index">
                        <i class="fa-regular fa-clock me-1"></i>
                        <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                    </span>
                </p>
            </div>
            <div class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetailObj.appointmentMessage }}
                </p>
            </div>
            <!-- <div class="row mt-3 mb-2">
                <button :id="reschAptId(index)" class="btn btn-outline-danger btn-sm" @click="reschApt">Reschedule Appointment</button>
            </div>
            <div class="row">
                <button :id="cancelAptId(index)" class="btn btn-white btn-sm" @click="cancelApt">Cancel Appointment</button>
            </div> -->
            <div class="row mt-3 mb-2">
                <button :id="acceptAptId(index)" class="btn btn-success btn-sm" @click="acceptApt">Accept Appointment</button>
            </div>
            <div class="row">
                <button :id="declineAptId(index)" class="btn btn-outline-danger btn-sm" @click="showDeclineBox">Decline Appointment</button>
            </div>
            <div v-if="declineBox" class="my-2">
                <textarea class="form-control" id="appointmentMessage" rows="3" v-model="declineMessage"></textarea>
                <p v-if="page.props.errors.user_decline_message" class="text-danger">{{ declineMessageError }}</p>
                <div class="row mt-2">
                    <button :id="declineAptId(index)" class="btn btn-outline-danger btn-sm" @click="declineApt">Submit</button>
                </div>
            </div>
            <div class="row mt-3 ">
                <button :id="hideAptId(index)" class="btn btn-white btn-sm" @click="hideMainApt">Hide</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        props: ['appointmentDetail', 'index', 'user'],
        emits: ['update-apt-details'],
        data() {
            return {
                appointmentDetailObj: this.appointmentDetail,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                subAptVisible: true,
                mainAptVisible: false,
                userId: 1,
                schedulerId: 1,
                page: usePage(),
                declineBox: false,
                declineMessage: '',
                declineMessageError: '',
                userType: this.user,
            }
        },
        methods: {
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
            acceptAptId(index) {
                return "acceptApt"+index;
            },
            declineAptId(index) {
                return "declineApt"+index;
            },
            showMainApt2(event) {
                var divId = event.currentTarget.id;
                var mainDivId = "main-"+divId;
                this.toggleDiv(mainDivId);
            },
            showMainApt(event) {
                // var btnId = event.currentTarget.id;
                // // remove 'showBtn' from the id
                // var subDivId = btnId.replace("showBtn-", "");
                // var mainDivId = "main-"+subDivId;
                // this.toggleDiv(subDivId);
                // this.toggleDiv(mainDivId);

                this.subAptVisible = false;
                this.mainAptVisible = true;
            },
            hideMainApt(event) {
                // var btnId = event.currentTarget.id;
                // // remove 'hideBtn' from the id
                // var subDivId = btnId.replace("hideBtn-", "");
                // var mainDivId = "main-"+subDivId;
                // this.toggleDiv(subDivId);
                // this.toggleDiv(mainDivId);

                this.subAptVisible = true;
                this.mainAptVisible = false;
                this.declineBox = false;
                this.declineMessageError = '';
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
                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                    id: this.appointmentDetailObj.id,
                });
                formData.delete(route('usersappointment.destroy', this.appointmentDetailObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // Get the new appointment details
                            // this.appointmentDetailObj = page.props.flash.success;
                            this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.errors = errors;
                    }
                });
            },
            acceptApt() {
                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                    id: this.appointmentDetailObj.id,
                    user_decision: 'accepted',
                });
                formData.put(route('usersappointment.update', this.appointmentDetailObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // Get the new appointment details
                            this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.errors = errors;
                    }
                });
            },
            declineApt() {
                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                    id: this.appointmentDetailObj.id,
                    user_decision: 'declined',
                    user_decline_message: this.declineMessage,
                });
                formData.put(route('usersappointment.update', this.appointmentDetailObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // Get the new appointment details
                            this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.declineMessageErrorFxn;
                    }
                });
            },
            showDeclineBox() {
                this.declineBox = !this.declineBox;
            },
            checkEntre() {
                if (this.user === 'entrepreneur') {
                    return true;
                } else {
                    return false;
                }
                 
            },
            checkSchdler() {
                if (this.user === 'scheduler') {
                    return true;
                } else {
                    return false;
                }
            }
        },
        computed: {
            declineMessageErrorFxn() {
                this.declineMessageError = this.page.props.errors.user_decline_message;
            }
        }
    }
</script>