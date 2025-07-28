<template>
    <div v-if="isNeutral() || isAccepted()">
        <p v-if="isNeutral() && isEntre()" class="bg-primary text-light text-center">Appointments requested from customers</p>
        <p v-if="isNeutral() && isSchdlr()" class="bg-primary text-light text-center">Appointments requested with technicians</p>
        <div v-if="isNeutral()" class="card col-12 mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptNeutralCount() }}</h6>
            </div>
        </div>
        <p v-if="isAccepted() && isEntre()" class="bg-success text-light text-center">Appointments confirmed with customers</p>
        <p v-if="isAccepted() && isSchdlr()" class="bg-success text-light text-center">Appointments confirmed by technicians</p>
        <div v-if="isAccepted()" class="card col-12 mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptAcceptedCount() }}</h6>
            </div>
        </div>
        
        <AppointmentCard v-for="(appointmentDetail, index) in appointmentDetails" :key="index" :appointmentDetail="appointmentDetail" :index="index" :user="user" :appointmentType="appointmentType"></AppointmentCard>
    </div>

    <div v-if="isDeclined() || isCancelled()">
        <p v-if="isDeclined() && isEntre()" class="bg-warning text-light text-center">Appointments declined with customers</p>
        <p v-if="isDeclined() && isSchdlr()" class="bg-warning text-light text-center">Appointments declined by technicians</p>
        <div v-if="isDeclined()" class="card col-12 mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptDeclinedCount() }}</h6>
            </div>
        </div>
        <p v-if="isCancelled() && isEntre()" class="bg-danger text-light text-center">Appointments cancelled with customers</p>
        <p v-if="isCancelled() && isSchdlr()" class="bg-danger text-light text-center">Appointments cancelled by technicians</p>
        <div v-if="isCancelled()" class="card col-12 mb-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptCancelledCount() }}</h6>
            </div>
        </div>

        <AppointmentCard v-for="(appointmentDetail, index) in appointmentDetails" :key="index" :appointmentDetail="appointmentDetail" :index="index" :user="user" :appointmentType="appointmentType"></AppointmentCard>
    </div>
</template>


<script>
    import AppointmentCard from './AppointmentCard.vue';

    export default {
        components: {AppointmentCard},
        props: ['appointmentDetails', 'aptNum', 'user', 'appointmentType'],
        emits: [],
        data() {
            return {
                appointmentDetailsObj: this.appointmentDetails,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                visible: true,
            }
        },
        methods: {
            aptNeutralCount() {
                if (this.aptNum < 1) {
                    return "You have no appointment request from a customer.";
                } else if (this.aptNum === 1) {
                    return "You have 1 appointment request from a customer.";
                } else if (this.aptNum > 1) {
                    return "You have "+this.aptNum+" appointment requests from a customer.";
                }

                // if (this.aptNum < 1) {
                //     return "You have no appointment waiting confirmation.";
                // } else if (this.aptNum === 1) {
                //     return "You have 1 appointment waiting confirmation.";
                // } else if (this.aptNum > 1) {
                //     return "You have "+this.aptNum+" appointments waiting confirmation.";
                // }
            },
            aptAcceptedCount() {
                if (this.aptNum < 1) {
                    return "You have no appointment accepted.";
                } else if (this.aptNum === 1) {
                    return "You have 1 appointment accepted.";
                } else if (this.aptNum > 1) {
                    return "You have "+this.aptNum+" appointments accepted.";
                }
            },
            aptDeclinedCount() {
                if (this.aptNum < 1) {
                    return "You have no appointment declined.";
                } else if (this.aptNum === 1) {
                    return "You have 1 appointment declined.";
                } else if (this.aptNum > 1) {
                    return "You have "+this.aptNum+" appointments declined.";
                }
            },
            aptCancelledCount() {
                if (this.aptNum < 1) {
                    return "You have no appointment cancelled.";
                } else if (this.aptNum === 1) {
                    return "You have 1 appointment cancelled.";
                } else if (this.aptNum > 1) {
                    return "You have "+this.aptNum+" appointments cancelled.";
                }
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
    }
</script>