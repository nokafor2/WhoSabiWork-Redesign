<template>
    <div class="card col-12 mb-3">
        <div v-if="subAptVisible" :id="dynamicId('subApt', index)" class="card-body mb-2" >
            <div class="d-flex">
                <div v-if="isSchdlr()" class="row">
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
                    <div v-if="isSchdlr()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-shop"></i>
                            {{ appointmentDetailObj.businessName }}
                        </p>
                    </div>
                    <div v-if="isSchdlr()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-phone"></i>
                            {{ appointmentDetailObj.phoneNumber }}
                        </p>
                    </div>
                    <!-- user === 'entrepreneur' -->
                    <div v-if="isEntre()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-regular fa-user"></i>
                            {{ appointmentDetailObj.schedulerFullName }}
                        </p>
                    </div>
                    <div v-if="isEntre()" class="d-block d-sm-flex">
                        <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                            <i class="fa-solid fa-phone"></i>
                            {{ appointmentDetailObj.schedulerPhoneNumber }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row mt-3 ">
                    <button :id="dynamicId('showBtn-subApt', index)" class="btn btn-white btn-sm" @click="showMainApt">Show</button>
                </div>
            </div>
        </div>
        
        <div v-if="mainAptVisible" class="card-body" :id="dynamicId('main-subApt', index)" > 
            <h5 class="card-title text-center">Appointment Details</h5>
            <div v-if="isSchdlr()" class="row mb-2">
                <img :src="imagePath(index)" style="height: 10rem; width: 100%;" class="card-img-top" alt="...">
            </div>
            
            <div v-if="isSchdlr()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-shop"></i>
                    {{ appointmentDetailObj.businessName }}
                </p>
            </div>

            <div v-if="isSchdlr()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-regular fa-user"></i>
                    {{ appointmentDetailObj.fullName }}
                </p>
            </div>                                    
            <div v-if="isSchdlr()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-phone"></i>
                    {{ appointmentDetailObj.phoneNumber }}
                </p>
            </div>
            <div v-if="isEntre()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-regular fa-user"></i>
                    {{ appointmentDetailObj.schedulerFullName }}
                </p>
            </div>
            <div v-if="isEntre()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-phone"></i>
                    {{ appointmentDetailObj.schedulerPhoneNumber }}
                </p>
            </div>

            <div v-if="isSchdlr()" class="d-block d-sm-flex">
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

            <div v-if="isDeclined() && isSchdlr()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetailObj.userDeclineMessage }}
                </p>
            </div>
            <div v-else-if="isCancelled() && isSchdlr()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetailObj.userCancelMessage }}
                </p>
            </div>
            <div v-else-if="isCancelled() && isEntre()" class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetailObj.schedulerCancelMessage }}
                </p>
            </div>
            <div v-else class="d-block d-sm-flex">
                <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                    <i class="fa-solid fa-message"></i>
                    {{ appointmentDetailObj.appointmentMessage }}
                </p>
            </div>

            <!-- <div class="row">
                <button :id="dynamicId('cancelApt', index)" class="btn btn-white btn-sm" @click="cancelApt">Cancel Appointment</button>
            </div> -->
            <div v-if="isNeutral() && isEntre()">
                <div class="row mt-3 mb-2">
                    <button :id="dynamicId('acceptApt', index)" class="btn btn-success btn-sm" @click="acceptApt">Accept Appointment</button>
                </div>
                <div class="row">
                    <button :id="dynamicId('declineAptShBox', index)" class="btn btn-outline-danger btn-sm" @click="showDeclineBox">Decline Appointment</button>
                </div>
                <div v-if="declineBox" class="my-2">
                    <textarea class="form-control" id="appointmentMessage" rows="3" v-model="declineMessage"></textarea>
                    <p v-if="page.props.errors.user_decline_message" class="text-danger">{{ declineMessageError }}</p>
                    <div class="row mt-2">
                        <button :id="dynamicId('declineApt', index)" class="btn btn-outline-danger btn-sm" @click="declineApt">Decline</button>
                    </div>
                </div>
            </div>

            <div v-if="isAccepted() && isEntre()">
                <div class="row">
                    <button :id="dynamicId('cancelAptShBox', index)" class="btn btn-outline-danger btn-sm" @click="showCanceleBox">Cancel Appointment</button>
                </div>
                <div v-if="cancelBox" class="my-2">
                    <textarea class="form-control" id="appointmentMessage" rows="3" v-model="cancelMessage"></textarea>
                    <p v-if="page.props.errors.user_cancel_message" class="text-danger">{{ cancelMessageError }}</p>
                    <div class="row mt-2">
                        <button :id="dynamicId('cancelApt', index)" class="btn btn-outline-danger btn-sm" @click="cancelAcceptedApt">Cancel</button>
                    </div>
                </div>
            </div>

            <div v-if="isAccepted() && isSchdlr()">
                <div class="row mt-3 mb-2">
                    <button :id="dynamicId('reschApt', index)" class="btn btn-outline-danger btn-sm" @click="reschApt">Reschedule Appointment</button>
                </div>
                <div class="row">
                    <button :id="dynamicId('schdlrCancelAptShBox', index)" class="btn btn-outline-danger btn-sm" @click="schdlrShowCanceleBox">Cancel Appointment</button>
                </div>
                <div v-if="schdlrCancelBox" class="my-2">
                    <textarea class="form-control" id="appointmentMessage" rows="3" v-model="schdlrCancelMessage"></textarea>
                    <p v-if="page.props.errors.scheduler_cancel_message" class="text-danger">{{ schdlrCancelMessageError }}</p>
                    <div class="row mt-2">
                        <button :id="dynamicId('schdlrCanelApt', index)" class="btn btn-outline-danger btn-sm" @click="schdlrCancelApt">Cancel</button>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3 ">
                <button :id="dynamicId('hideBtn-subApt', index)" class="btn btn-white btn-sm" @click="hideMainApt">Hide</button>
            </div>
        </div>
    </div>
    <RescheduleModal 
        :isOpen="showRescheduleModal"
        :existingAppointment="appointmentDetailObj"
        :availableDates="availableDates"
        @close="closeRescheduleModal"
        @success="onRescheduleSuccess"
    />
</template>

<script>
    import { useForm, usePage } from '@inertiajs/vue3';
    import SelectAppointment from '@/components/UI/SelectAppointment.vue';
    import RescheduleModal from '@/components/UI/RescheduleModal.vue';

    export default {
        components: {SelectAppointment, RescheduleModal},
        props: ['appointmentDetail', 'index', 'user', 'appointmentType'],
        emits: ['update-apt-details'],
        data() {
            return {
                appointmentDetailObj: this.appointmentDetail,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                subAptVisible: true,
                mainAptVisible: false,
                page: usePage(),
                userType: this.user,
                declineBox: false,
                declineMessage: '',
                declineMessageError: '',
                cancelBox: false,
                cancelMessage: '',
                cancelMessageError: '',
                schdlrCancelBox: false,
                schdlrCancelMessage: '',
                schdlrCancelMessageError: '',
                availableDates: [],
                showRescheduleModal: false,
            }
        },
        methods: {
            imagePath(index) {
                index = index + 1;
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            dynamicId(idName, index) {
                return idName+index;
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
                console.log('Reschedule button clicked!');
                console.log('Current appointment data:', this.appointmentDetailObj);
                console.log('User ID:', this.userId);
                
                // Fetch available dates for rescheduling
                var formData = useForm({
                    userId: this.userId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                });
                
                console.log('Fetching available dates for rescheduling...', formData.data());
                
                formData.post(route('availabilitydates'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log('API Success response:', page);
                        if (page.props.flash.success) {
                            console.log('Available dates loaded:', page.props.flash.success);
                            this.availableDates = page.props.flash.success;
                            this.showRescheduleModal = true;
                            console.log('Modal opened successfully with available dates');
                        } else {
                            console.warn('No success data in response, opening modal anyway');
                            this.availableDates = []; // Empty array as fallback
                            this.showRescheduleModal = true;
                        }
                    },
                    onError: (errors) => {
                        console.error('API Error:', errors);
                        this.errors = errors;
                        // Still open modal even on error, user can try to reschedule manually
                        console.log('Opening modal despite API error');
                        this.availableDates = [];
                        this.showRescheduleModal = true;
                    }
                });
            },
            closeRescheduleModal() {
                console.log('Closing reschedule modal');
                this.showRescheduleModal = false;
            },
            onRescheduleSuccess(successData) {
                console.log('Reschedule successful:', successData);
                // Emit update to parent component if needed
                this.$emit('update-apt-details', successData);
                // You can add more success handling here, like refreshing appointment data
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
                            // this.$emit('update-apt-details', page.props.flash.success);
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
                            // this.$emit('update-apt-details', page.props.flash.success);
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
                            // this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.declineMessageErrorFxn;
                    }
                });
            },
            cancelAcceptedApt() {
                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                    id: this.appointmentDetailObj.id,
                    user_decision: 'cancelled',
                    user_cancel_message: this.cancelMessage,
                });
                formData.put(route('usersappointment.update', this.appointmentDetailObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // Get the new appointment details
                            // this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.cancelMessageErrorFxn;
                    }
                });
            },
            schdlrCancelApt() {
                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.appointmentDetailObj.date.rawDate,
                    id: this.appointmentDetailObj.id,
                    user_decision: 'cancelled',
                    scheduler_cancel_message: this.schdlrCancelMessage,
                });
                
                console.log('FormData being sent:', formData.data());
                
                formData.put(route('usersappointment.update', this.appointmentDetailObj.id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // Get the new appointment details
                            // this.$emit('update-apt-details', page.props.flash.success);
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.schdlrCancelMessageErrorFxn;
                    }
                });
            },
            showDeclineBox() {
                this.declineBox = !this.declineBox;
            },
            showCanceleBox() {
                this.cancelBox = !this.cancelBox;
            },
            schdlrShowCanceleBox() {
                this.schdlrCancelBox = !this.schdlrCancelBox;
            },
            isEntre() {
                return (this.user === 'entrepreneur') ? true : false;
            },
            isSchdlr() {
                return (this.user === 'scheduler') ? true : false;
            },
            isNeutral() {
                return (this.appointmentType === 'neutral') ? true : false;
            },
            isAccepted() {
                return (this.appointmentType === 'accepted') ? true : false;
            },
            isDeclined() {
                return (this.appointmentType === 'declined') ? true : false;
            },
            isCancelled() {
                return (this.appointmentType === 'cancelled') ? true : false;
            }
        },
        computed: {
            userId() {
                // For scheduler operations, we need the entrepreneur's ID (from appointment)
                // For entrepreneur operations, we need the current user's ID
                if (this.isSchdlr()) {
                    return this.appointmentDetailObj.userId; // Entrepreneur who made the appointment
                } else {
                    return this.page.props.user.id; // Current user (entrepreneur)
                }
            },
            schedulerId() {
                // For scheduler operations, we need the current user's ID (scheduler)
                // For entrepreneur operations, we need the scheduler from appointment
                if (this.isSchdlr()) {
                    return this.page.props.user.id; // Current user (scheduler)
                } else {
                    return this.appointmentDetailObj.schedulerId; // Scheduler from appointment
                }
            },
            declineMessageErrorFxn() {
                this.declineMessageError = this.page.props.errors.user_decline_message;
            },
            cancelMessageErrorFxn() {
                this.cancelMessageError = this.page.props.errors.user_cancel_message;
            },
            schdlrCancelMessageErrorFxn() {
                this.schdlrCancelMessageError = this.page.props.errors.scheduler_cancel_message;
            }
        }
    }
</script>