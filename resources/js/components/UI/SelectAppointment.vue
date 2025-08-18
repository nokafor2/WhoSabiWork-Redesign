<template>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="success !== null" class="row" :class="displayMessage">
                        <p class="text-success">Appointment saved</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <VueDatePicker v-model="date" inline auto-apply :enable-time-picker="false" ignore-time-validation model-type="yyyy-MM-dd" :allowed-dates="allowedDates" />
                        <p v-if="date">Selected date: {{ date }}</p>
                    </div>
                    <p v-if="page.props.errors.appointment_date" class="text-danger">{{ page.props.errors.appointment_date }}</p>

                    <p class="fs-3" v-for="(schedule, date, index) in schedules" :key="index">{{ date }}</p>
                    <div class="row row-cols-4 justify-content-center mt-3" v-for="(schedule, index) in schedules" :key="index">
                        <div v-for="(time, index) in schedule" :key="index" class="my-2">
                            <input type="checkbox" class="btn-check" :id="newId(index)" autocomplete="off" name="timeSelected" :value="index" v-model="timeSelected">
                            <label class="btn btn-outline-primary" :for="newId(index)">
                                <span class="mb-0">{{ time.time }}</span>
                                <span class="my-0 d-flex justify-content-end" style="font-size: 10px;">{{ time.period }}</span>
                            </label>
                        </div>
                    </div>
                    <p v-if="page.props.errors.hours" class="text-danger">{{ page.props.errors.hours }}</p>

                    <textarea class="form-control" id="appointmentMessage" rows="3" v-model="appointmentMessage"></textarea>
                    <p v-if="page.props.errors.appointment_message" class="text-danger">{{ page.props.errors.appointment_message }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="setAppointment" :disabled="!currentUser">
                        {{ currentUser ? 'Set Appointment' : 'Please Log In' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';
</script>

<script>
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        props: ['entrepreneurId', 'datesAvailable'],
        
        data() {
            return {
                date: null,
                userId: this.entrepreneurId,
                schedules: [],
                availableDates: this.datesAvailable || [],
                datesObj: [],
                timeSelected: [],
                appointmentMessage: '',
                errors: [],
                success: null,
                page: usePage(),
            }
        },
        methods: {
            newId(index) {
                return 'dateBtncheck'+index;
            },
            setAppointment() {
                // Validate user is logged in
                if (!this.currentUser) {
                    alert('Please log in to set an appointment');
                    return;
                }
                
                // Validate required fields
                if (!this.userId) {
                    alert('Entrepreneur ID is required');
                    return;
                }

                var formData = useForm({
                    user_id: this.userId,
                    scheduler_id: this.schedulerId,
                    appointment_date: this.date,
                    hours: this.timeSelected,
                    appointment_message: this.appointmentMessage,
                    user_decision: 'neutral'
                });
                formData.post(route('usersappointment.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            console.log(page);
                            // clear the variables
                            this.timeSelected = [];
                            this.appointmentMessage = '';
                            this.success = page.props.flash.success;
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                        this.errors = errors;
                    }
                });
            },
            closeModal() {
                const myModal = new bootstrap.Modal.getInstance(document.getElementById("staticBackdrop"));
                myModal.hide();
            },
            displayMessage() {
                return this.success !== null ? 'active' : 'inactive';
            }
        },
        computed: {
            // Safe access to current user
            currentUser() {
                return this.page?.props?.user || null;
            },
            
            // Safe access to scheduler ID
            schedulerId() {
                return this.currentUser?.id || null;
            },
            
            allowedDates() {
                // Reset datesObj to prevent duplicates
                this.datesObj = [];
                
                Object.values(this.availableDates).forEach(value => {
                    this.datesObj.push(new Date(value.date_available.toString()));
                });

                return this.datesObj;
            }
        },
        watch: {
            date() {
                if (!this.userId || !this.date) {
                    return;
                }
                
                var formData = useForm({
                    user_id: this.userId,
                    date_available: this.date,
                    from: 'selectAppointment',
                });
                formData.get(route('usersavailability.show', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            // console.log(page);
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