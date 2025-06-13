<template>
    <div v-if="isNeutral()" class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptNeutralCount() }}</h6>
        </div>
    </div>
    <div v-if="isAccepted()" class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ aptAcceptedCount() }}</h6>
        </div>
    </div>
    
    <AppointmentCard v-for="(appointmentDetail, index) in appointmentDetails" :key="index" :appointmentDetail="appointmentDetail" :index="index" :user="user" :appointmentType="appointmentType"></AppointmentCard>
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