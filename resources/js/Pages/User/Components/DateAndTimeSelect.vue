<template>
    <!-- <VueDatePicker v-model="date" inline auto-apply :min-date="new Date()" model-type="format" /> -->
    <VueDatePicker v-model="date" inline auto-apply :enable-time-picker="false" ignore-time-validation :min-date="new Date()" model-type="yyyy-MM-dd" />
    <p v-if="date">Selected date: {{ date }}</p>

    <TimePicker ref="timePicker" @selected-times="updateSelectedTimes"></TimePicker>

    <div class="d-flex justify-content-center">
        <button class="btn btn-danger" type="button" @click="createAvailability">Create Availability</button>
    </div>
</template>
  
<script setup>
    // import { ref } from 'vue';
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';

    // const date = ref(new Date());
</script>

<script>
    import TimePicker from './TimePicker.vue';
    import { useForm } from '@inertiajs/vue3';

    export default {
        components: { TimePicker },
        data() {
            return {
                date: null,
                selectedTime: [],
                userId: 1,
            }
        },
        methods: {
            createAvailability() {
                var formData = useForm({
                    date_available: this.date,
                    selectedTime: this.selectedTime,
                    user_id: this.userId
                });
                formData.post(route('usersavailability.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            this.clearTime();
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            updateSelectedTimes(selectedTime) {
                this.selectedTime = selectedTime;
            },
            clearTime() {
                this.selectedTime = [];
                this.$refs.timePicker.selectedTime = [];
                this.$refs.timePicker.$refs.checkbox800am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox830am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox900am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox930am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1000am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1030am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1100am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1130am.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1200pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox1230pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox100pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox130pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox200pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox230pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox300pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox330pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox400pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox430pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox500pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox530pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox600pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox630pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox700pm.timeSelected = false;
                this.$refs.timePicker.$refs.checkbox730pm.timeSelected = false;
            }
        }
    }
</script>