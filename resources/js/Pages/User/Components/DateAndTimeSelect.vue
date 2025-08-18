<template>
    <div class="d-flex justify-content-center">
        <p class="fs-3">Set Your Availabilities</p>
    </div>

    <VueDatePicker v-model="date" inline auto-apply :enable-time-picker="false" ignore-time-validation :min-date="new Date()" model-type="yyyy-MM-dd" />
    <!-- <p v-if="date">Selected date: {{ date }}</p> -->

    <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" value="" id="selectAll" @change="toggleSelectAll" v-model="selectAll">
        <label class="form-check-label" for="selectAll">
            Select All
        </label>
    </div>
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
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        components: { TimePicker },
        emits: ['update-schedule'],
        
        data() {
            return {
                date: null,
                selectedTime: [],
                selectAll: false,
                page: usePage(),
            }
        },
        methods: {
            createAvailability() {
                var formData = useForm({
                    date_available: this.date,
                    selectedTime: this.selectedTime,
                    // user_id: this.userId
                    // user_id: usePage().props.user.id
                    user_id: this.page.props.user.id
                });
                formData.post(route('usersavailability.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            this.$emit('update-schedule', page.props.flash.success);
                            this.clearTime();
                            this.selectAll = false;
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
            toggleSelectAll() {
                if (this.selectAll) {
                    this.selectTime();
                } else {
                    this.clearTime();
                }
            },
            selectTime() {
                this.$refs.timePicker.$refs.checkbox800am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox830am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox900am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox930am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1000am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1030am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1100am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1130am.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1200pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox1230pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox100pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox130pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox200pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox230pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox300pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox330pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox400pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox430pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox500pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox530pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox600pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox630pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox700pm.timeSelected = true;
                this.$refs.timePicker.$refs.checkbox730pm.timeSelected = true;
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