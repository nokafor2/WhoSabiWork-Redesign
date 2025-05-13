<template>
    <div class="card col-12 mb-3">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">{{ appointmentCount() }}</h6>
        </div>
    </div>
    
    <div v-for="(appointmentDetail, index) in appointmentDetails" :key="index" class="card col-12 mb-3">
        <!-- Display the summary appointment -->
        <SummaryAptCard ref="shortApt" :appointmentDetail="appointmentDetail" :index="index" @main-apt-visible="updateMainAptVisible" @sub-apt-visible="updateSubAptVisible"></SummaryAptCard>
        <!-- <SummaryAptCard :appointmentDetail="appointmentDetail" :index="index"></SummaryAptCard> -->
        <!-- Display the main appointment -->
        <MainAptCard ref="mainApt" :appointmentDetail="appointmentDetail" :index="index" @sub-apt-visible="updateSubAptVisible" @main-apt-visible="updateMainAptVisible"></MainAptCard>
    </div>
</template>


<script>
    import SummaryAptCard from './AppointmentCards/SummaryAptCard.vue';
    import MainAptCard from './AppointmentCards/MainAptCard.vue';

    export default {
        components: {SummaryAptCard, MainAptCard},
        props: ['appointmentDetails'],
        emits: [],
        data() {
            return {
                appointmentDetailsObj: this.appointmentDetails,
                size: 0,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                subAptVisible: true,
                mainAptVisible: false,
            }
        },
        methods: {
            // appointmentNum(aptNum) {
            //     this.size = aptNum;
            // },
            getNumAppointments(appointments) {
                this.size = Object.values(appointments).length;

                return appointments;
            },

            appointmentCount() {
                // var count = Object.keys(this.appointmentDetailsObj).length;
                
                if (this.size < 1) {
                    return "You have no appointment waiting confirmation.";
                } else if (this.size === 1) {
                    return "You have 1 appointment waiting confirmation.";
                } else if (this.size > 1) {
                    return "You have "+this.size+" appointments waiting confirmation.";
                }
            },
            updateSubAptVisible(value) {
                this.subAptVisible = value;
                console.log(this.$refs.shortApt);
            },
            updateMainAptVisible(value) {
                this.mainAptVisible = value;
                console.log(this.$refs.mainApt);
            }
        },
        computed: {
            // updateSubAptVisible(value) {
            //     this.subAptVisible = value;
            //     console.log(this.$refs.shortApt);
            // },
            // updateMainAptVisible(value) {
            //     this.mainAptVisible = value;
            //     console.log(this.$refs.mainApt);
            // }
        },
        // provide() {
        //     return {
        //         subAptVisible: this.subAptVisible,
        //         mainAptVisible: this.mainAptVisible,
        //     }
        // }
    }
</script>