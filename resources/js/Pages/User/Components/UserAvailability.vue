<template>
    <button class="btn btn-danger my-3" type="button" @click="getUserAvailabilities">Show Schedule</button>

    <div class="accordion" id="accordionExample">
        <!-- <UserSchedule v-for="(date, index) in schedules" :key="index" :count="index" :date="index" :times="date.timeName" ></UserSchedule> -->
        <UserSchedule v-for="(date, index) in schedules" :key="index" :count="index" :date="index" :times="date" ></UserSchedule>
    </div>
</template>


<script>
    import { useForm } from '@inertiajs/vue3';
    import UserSchedule from './UserSchedule.vue';

    export default {
        components: { UserSchedule },
        data() {
            return {
                userId: 1,
                schedules: [],
            }
        },
        methods: {
            getUserAvailabilities() {
                var formData = useForm({
                    user_id: this.userId,
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
            }
        }
    }
</script>