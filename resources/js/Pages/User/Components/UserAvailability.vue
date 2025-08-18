<template>
    <div class="d-flex justify-content-center">
        <p class="fs-3">Your Available Schedules</p>
    </div>
    <!-- <button class="btn btn-danger my-3" type="button" @click="getUserAvailabilities">Show Schedule</button> -->

    <div class="accordion" id="accordionExample">
        <UserSchedule v-for="(schedule, index, index2) in schedules" :key="index2" :count="index2" :date="index" :times="schedule" @updated-schedule="updateSchedule"></UserSchedule>
    </div>
</template>


<script>
    import { useForm, usePage } from '@inertiajs/vue3';
    import UserSchedule from './UserSchedule.vue';

    export default {
        components: { UserSchedule },
        props: ['schedules'],
        data() {
            return {
                page: usePage(),
                schedules2: this.schedules,
            }
        },
        methods: {
            getUserAvailabilities() {
                var formData = useForm({
                    user_id: this.page.props.user.id,
                });
                formData.get(route('usersavailability.show', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            this.schedules = page.props.flash.success;
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            updateSchedule(schedules) {
                this.schedules2 = schedules;
            }
        }
    }
</script>