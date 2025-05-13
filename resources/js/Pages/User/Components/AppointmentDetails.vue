<template>
    <div class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ appointmentCount() }}</h6>
        </div>
    </div>
    
    <AppointmentCard v-for="(appointmentDetail, index) in appointmentDetails" :key="index" :appointmentDetail="appointmentDetail" :index="index"></AppointmentCard>
</template>


<script>
    import AppointmentCard from './AppointmentCard.vue';

    export default {
        components: {AppointmentCard},
        props: ['appointmentDetails', 'aptNum'],
        emits: [],
        data() {
            return {
                appointmentDetailsObj: this.appointmentDetails,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                visible: true,
            }
        },
        methods: {
            appointmentCount() {
                if (this.aptNum < 1) {
                    return "You have no appointment waiting confirmation.";
                } else if (this.aptNum === 1) {
                    return "You have 1 appointment waiting confirmation.";
                } else if (this.aptNum > 1) {
                    return "You have "+this.aptNum+" appointments waiting confirmation.";
                }
            },
        },
    }
</script>