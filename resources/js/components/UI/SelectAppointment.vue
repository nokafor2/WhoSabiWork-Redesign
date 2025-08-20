<template>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        {{ isRescheduling ? 'Reschedule Appointment' : 'Set New Appointment' }}
                    </h1>
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
                        {{ currentUser ? (isRescheduling ? 'Reschedule Appointment' : 'Set Appointment') : 'Please Log In' }}
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
        props: ['entrepreneurId', 'datesAvailable', 'isRescheduling', 'existingAppointment'],
        
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

                // Determine if this is a reschedule or new appointment
                if (this.isRescheduling && this.existingAppointment) {
                    // For rescheduling, add the appointment ID and use PUT method
                    formData.data().id = this.existingAppointment.id;
                    formData.put(route('usersappointment.update', this.existingAppointment.id), {
                        preserveState: true,
                        preserveScroll: true,
                        onSuccess: (page) => {
                            if (page.props.flash.success) {
                                console.log(page);
                                // clear the variables
                                this.timeSelected = [];
                                this.appointmentMessage = '';
                                this.success = page.props.flash.success;
                                
                                // Close the modal after successful appointment update
                                setTimeout(() => {
                                    this.closeModal();
                                    this.resetModalState();
                                }, 1500); // Wait 1.5 seconds to show success message
                            }
                        },
                        onError: (errors) => {
                            console.log('Error: ', errors);
                            this.errors = errors;
                        }
                    });
                } else {
                    // For new appointments, use POST method
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
                                
                                // Close the modal after successful appointment creation
                                setTimeout(() => {
                                    this.closeModal();
                                    this.resetModalState();
                                }, 1500); // Wait 1.5 seconds to show success message
                            }
                        },
                        onError: (errors) => {
                            console.log('Error: ', errors);
                            this.errors = errors;
                        }
                    });
                }
            },
            closeModal() {
                try {
                    // Method 1: Try using Bootstrap JS API
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modalElement = document.getElementById("staticBackdrop");
                        if (modalElement) {
                            const myModal = bootstrap.Modal.getInstance(modalElement);
                            if (myModal) {
                                myModal.hide();
                                console.log('Modal closed using Bootstrap API');
                                return;
                            }
                            // If no instance exists, try creating one and hiding it
                            const newModal = new bootstrap.Modal(modalElement);
                            newModal.hide();
                            console.log('Modal closed using new Bootstrap instance');
                            return;
                        }
                    }
                    
                    // Method 2: Try using jQuery if available
                    if (typeof $ !== 'undefined') {
                        $('#staticBackdrop').modal('hide');
                        console.log('Modal closed using jQuery');
                        return;
                    }
                    
                    // Method 3: Manual modal closing
                    const modal = document.getElementById('staticBackdrop');
                    if (modal) {
                        modal.style.display = 'none';
                        modal.classList.remove('show');
                        modal.setAttribute('aria-hidden', 'true');
                        document.body.classList.remove('modal-open');
                        
                        // Remove all backdrops (in case there are multiple)
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        
                        console.log('Modal closed manually');
                    }
                } catch (error) {
                    console.error('Error closing modal:', error);
                }
            },
            resetModalState() {
                // Reset all modal data to initial state
                this.date = null;
                this.schedules = [];
                this.timeSelected = [];
                this.appointmentMessage = '';
                this.success = null;
                this.errors = [];
            },
            displayMessage() {
                return this.success !== null ? 'active' : 'inactive';
            },
            populateExistingData() {
                if (this.isRescheduling && this.existingAppointment) {
                    // Pre-populate the appointment message
                    this.appointmentMessage = this.existingAppointment.appointmentMessage || '';
                    
                    // Pre-select the current appointment date if available
                    if (this.existingAppointment.date && this.existingAppointment.date.rawDate) {
                        this.date = this.existingAppointment.date.rawDate;
                    }
                    
                    console.log('Pre-populated form with existing appointment data:', {
                        message: this.appointmentMessage,
                        date: this.date
                    });
                }
            }
        },
        mounted() {
            // Populate form with existing data if rescheduling
            this.populateExistingData();
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