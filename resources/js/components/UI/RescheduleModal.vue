<template>
    <!-- Teleport the modal to the body, with fallback -->
    <Teleport to="body" :disabled="!canUseTeleport">
        <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
            <div class="modal-container" @click.stop>
                <div class="modal-header">
                    <h2 class="modal-title">Reschedule Appointment</h2>
                    <button type="button" class="close-button" @click="closeModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                
                <div class="modal-body">
                    <!-- Success message -->
                    <div v-if="successMessage" class="success-message">
                        <p>{{ successMessage }}</p>
                    </div>
                    
                    <!-- Date picker -->
                    <div class="date-picker-container">
                        <VueDatePicker 
                            v-model="selectedDate" 
                            inline 
                            auto-apply 
                            :enable-time-picker="false" 
                            ignore-time-validation 
                            model-type="yyyy-MM-dd" 
                            :allowed-dates="allowedDates" 
                        />
                        <p v-if="selectedDate" class="selected-date">Selected date: {{ selectedDate }}</p>
                        <small v-if="!availableDates || availableDates.length === 0" class="text-muted">
                            No specific dates loaded. You can select any date and check availability.
                        </small>
                    </div>
                    
                    <!-- Error messages -->
                    <div v-if="errors.appointment_date" class="error-message">
                        {{ errors.appointment_date }}
                    </div>
                    
                    <!-- Available time slots -->
                    <div v-if="schedules && Object.keys(schedules).length > 0" class="time-slots-section">
                        <h3 v-for="(schedule, date, index) in schedules" :key="index" class="schedule-date">
                            {{ date }}
                        </h3>
                        <div class="time-slots-grid" v-for="(schedule, index) in schedules" :key="index">
                            <div v-for="(time, timeIndex) in schedule" :key="timeIndex" class="time-slot">
                                <input 
                                    type="checkbox" 
                                    :id="`time-${timeIndex}`" 
                                    :value="timeIndex" 
                                    v-model="selectedTimes"
                                    class="time-checkbox"
                                >
                                <label :for="`time-${timeIndex}`" class="time-label">
                                    <span class="time">{{ time.time }}</span>
                                    <span class="period">{{ time.period }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Error for time selection -->
                    <div v-if="errors.hours" class="error-message">
                        {{ errors.hours }}
                    </div>
                    
                    <!-- Appointment message -->
                    <div class="message-section">
                        <label for="appointmentMessage" class="message-label">Appointment Message:</label>
                        <textarea 
                            id="appointmentMessage" 
                            v-model="appointmentMessage" 
                            rows="3" 
                            class="message-textarea"
                            placeholder="Enter your appointment message..."
                        ></textarea>
                    </div>
                    
                    <!-- Error for appointment message -->
                    <div v-if="errors.appointment_message" class="error-message">
                        {{ errors.appointment_message }}
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-primary" 
                        @click="rescheduleAppointment"
                        :disabled="!canReschedule"
                    >
                        {{ isLoading ? 'Rescheduling...' : 'Reschedule Appointment' }}
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script>
import { useForm, usePage } from '@inertiajs/vue3';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    name: 'RescheduleModal',
    components: {
        VueDatePicker
    },
    props: {
        isOpen: {
            type: Boolean,
            default: false
        },
        existingAppointment: {
            type: Object,
            required: true
        },
        availableDates: {
            type: Array,
            default: () => []
        }
    },
    emits: ['close', 'success'],
    
    data() {
        return {
            selectedDate: null,
            selectedTimes: [],
            appointmentMessage: '',
            schedules: {},
            successMessage: null,
            errors: {},
            isLoading: false,
            page: usePage(),
            lastClickTime: 0
        }
    },
    
    computed: {
        canReschedule() {
            return this.selectedDate && this.selectedTimes.length > 0 && !this.isLoading;
        },
        
        currentUser() {
            return this.page?.props?.user || null;
        },
        
        allowedDates() {
            if (!this.availableDates || !Array.isArray(this.availableDates)) {
                console.log('No available dates provided, allowing all dates');
                return []; // Allow all dates if none provided
            }
            return this.availableDates.map(dateObj => new Date(dateObj.date_available));
        },
        
        canUseTeleport() {
            // Check if we can use Teleport (body element exists)
            return typeof document !== 'undefined' && document.body;
        }
    },
    
    watch: {
        isOpen(newVal) {
            try {
                if (newVal) {
                    this.populateExistingData();
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                } else {
                    document.body.style.overflow = ''; // Restore scrolling
                    this.resetForm();
                }
            } catch (error) {
                console.error('Error in isOpen watcher:', error);
            }
        },
        
        selectedDate() {
            try {
                if (this.selectedDate && this.existingAppointment) {
                    this.fetchSchedules();
                }
            } catch (error) {
                console.error('Error in selectedDate watcher:', error);
            }
        }
    },
    
    mounted() {
        // Add keyboard event listener
        this.handleKeydown = (e) => {
            try {
                if (e.key === 'Escape' && this.isOpen) {
                    this.closeModal();
                }
            } catch (error) {
                console.error('Error in keyboard handler:', error);
            }
        };
        
        document.addEventListener('keydown', this.handleKeydown);
    },
    
    methods: {
        closeModal() {
            try {
                // Prevent multiple close calls and debounce
                const now = Date.now();
                if (!this.isOpen || (now - this.lastClickTime < 300)) return;
                this.lastClickTime = now;
                
                this.$emit('close');
            } catch (error) {
                console.error('Error closing modal:', error);
                // Force close by emitting the event anyway
                this.$emit('close');
            }
        },
        
        populateExistingData() {
            if (this.existingAppointment) {
                // Pre-populate the appointment message
                this.appointmentMessage = this.existingAppointment.appointmentMessage || '';
                
                // Pre-select the current appointment date if available
                if (this.existingAppointment.date && this.existingAppointment.date.rawDate) {
                    this.selectedDate = this.existingAppointment.date.rawDate;
                }
                
                console.log('Pre-populated reschedule form:', {
                    message: this.appointmentMessage,
                    date: this.selectedDate
                });
            }
        },
        
        fetchSchedules() {
            try {
                if (!this.selectedDate || !this.existingAppointment) return;
                
                const formData = useForm({
                    user_id: this.existingAppointment.userId || this.existingAppointment.user_id,
                    date_available: this.selectedDate,
                    from: 'rescheduleModal',
                });
                
                formData.get(route('usersavailability.show', this.existingAppointment.userId || this.existingAppointment.user_id), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        try {
                            if (page.props.flash.success) {
                                this.schedules = page.props.flash.success;
                                console.log('Schedules loaded for reschedule:', this.schedules);
                            }
                        } catch (error) {
                            console.error('Error processing schedule success:', error);
                        }
                    },
                    onError: (errors) => {
                        try {
                            console.error('Error fetching schedules:', errors);
                            this.errors = errors;
                        } catch (error) {
                            console.error('Error processing schedule error:', error);
                        }
                    }
                });
            } catch (error) {
                console.error('Error in fetchSchedules:', error);
            }
        },
        
        rescheduleAppointment() {
            try {
                // Debounce button clicks
                const now = Date.now();
                if (!this.canReschedule || (now - this.lastClickTime < 1000)) return;
                this.lastClickTime = now;
                
                // Additional validation
                if (!this.selectedDate) {
                    alert('Please select a date');
                    return;
                }
                if (!this.selectedTimes || this.selectedTimes.length === 0) {
                    alert('Please select at least one time slot');
                    return;
                }
                if (!this.existingAppointment || !this.existingAppointment.id) {
                    alert('Invalid appointment data');
                    return;
                }
                
                this.isLoading = true;
                this.errors = {};
                
                const formData = useForm({
                    id: this.existingAppointment.id,
                    user_id: this.existingAppointment.userId || this.existingAppointment.user_id,
                    scheduler_id: this.currentUser?.id,
                    appointment_date: this.selectedDate,
                    hours: this.selectedTimes,
                    appointment_message: this.appointmentMessage,
                    user_decision: 'neutral'
                });
                
                console.log('Rescheduling appointment with data:', formData.data());
                console.log('Selected times:', this.selectedTimes);
                console.log('Selected date:', this.selectedDate);
                console.log('Existing appointment:', this.existingAppointment);
                
                formData.put(route('usersappointment.update', this.existingAppointment.id), {
                    preserveState: true,
                    preserveScroll: true,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    onSuccess: (page) => {
                        try {
                            console.log('Appointment rescheduled successfully:', page);
                            this.successMessage = 'Appointment rescheduled successfully!';
                            this.isLoading = false;
                            
                            // Close modal after showing success message
                            setTimeout(() => {
                                try {
                                    this.$emit('success', page.props.flash.success);
                                    this.closeModal();
                                } catch (error) {
                                    console.error('Error in success timeout:', error);
                                    this.closeModal();
                                }
                            }, 1500);
                        } catch (error) {
                            console.error('Error processing reschedule success:', error);
                            this.isLoading = false;
                        }
                    },
                    onError: (errors) => {
                        try {
                            console.error('Error rescheduling appointment:', errors);
                            this.errors = errors;
                            this.isLoading = false;
                        } catch (error) {
                            console.error('Error processing reschedule error:', error);
                            this.isLoading = false;
                        }
                    }
                });
            } catch (error) {
                console.error('Error in rescheduleAppointment:', error);
                this.isLoading = false;
            }
        },
        
        resetForm() {
            this.selectedDate = null;
            this.selectedTimes = [];
            this.appointmentMessage = '';
            this.schedules = {};
            this.successMessage = null;
            this.errors = {};
            this.isLoading = false;
        }
    },
    
    beforeUnmount() {
        try {
            // Remove keyboard event listener
            if (this.handleKeydown) {
                document.removeEventListener('keydown', this.handleKeydown);
            }
            
            // Ensure body scroll is restored when component is destroyed
            document.body.style.overflow = '';
        } catch (error) {
            console.error('Error in beforeUnmount:', error);
        }
    }
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
    padding: 20px;
    box-sizing: border-box;
}

.modal-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e5e5;
}

.modal-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.close-button {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    border-radius: 4px;
    color: #666;
    transition: all 0.2s;
}

.close-button:hover {
    background-color: #f5f5f5;
    color: #333;
}

.modal-body {
    padding: 24px;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
}

.date-picker-container {
    margin-bottom: 24px;
    text-align: center;
}

.selected-date {
    margin-top: 12px;
    font-weight: 500;
    color: #333;
}

.time-slots-section {
    margin: 24px 0;
}

.schedule-date {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
    text-align: center;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.time-slot {
    position: relative;
}

.time-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.time-label {
    display: block;
    padding: 12px;
    border: 2px solid #e5e5e5;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    transition: all 0.2s;
    background: white;
}

.time-checkbox:checked + .time-label {
    border-color: #dc3545;
    background-color: #dc3545;
    color: white;
}

.time-label:hover {
    border-color: #dc3545;
    background-color: #f8f9fa;
}

.time-checkbox:checked + .time-label:hover {
    background-color: #c82333;
}

.time {
    display: block;
    font-weight: 500;
    margin-bottom: 2px;
}

.period {
    display: block;
    font-size: 0.75rem;
    opacity: 0.8;
}

.message-section {
    margin: 24px 0;
}

.message-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.message-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    resize: vertical;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
}

.message-textarea:focus {
    outline: none;
    border-color: #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 8px 12px;
    border-radius: 4px;
    margin: 8px 0;
    border: 1px solid #f5c6cb;
    font-size: 14px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e5e5e5;
    background-color: #f8f9fa;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-primary {
    background-color: #dc3545;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background-color: #c82333;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Responsive design */
@media (max-width: 768px) {
    .modal-container {
        margin: 10px;
        max-height: 95vh;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 16px;
    }
    
    .time-slots-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 8px;
    }
    
    .time-label {
        padding: 8px;
        font-size: 14px;
    }
}
</style>
