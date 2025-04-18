<template>
    <!-- <VueDatePicker v-model="date" inline auto-apply :min-date="new Date()" model-type="format" /> -->
    <VueDatePicker v-model="date" inline auto-apply :enable-time-picker="false" ignore-time-validation :min-date="new Date()" model-type="yyyy-MM-dd" />
    <p v-if="date">Selected date: {{ date }}</p>

    <TimePicker @selected-times="updateSelectedTimes"></TimePicker>

    <button class="btn btn-danger w-100" type="button" @click="createAvailability">Create Availability</button>
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
                            this.selectedTime = [];
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            updateSelectedTimes(selectedTime) {
                this.selectedTime = selectedTime;
                console.log(selectedTime);
            },
            format(date) {
                // const day = date.getDate();
                // const month = date.getMonth() + 1;
                // const year = date.getFullYear();

                // // return `Selected date is ${day}/${month}/${year}`;
                // return `Selected date is ${year}-${month}-${day}`;

                var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

                if (month.length < 2) 
                    month = '0' + month;
                if (day.length < 2) 
                    day = '0' + day;

                return [year, month, day].join('-');
            }
        }
    }
</script>