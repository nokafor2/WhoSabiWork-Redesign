<template>
    <div class="mx-auto my-3 text-center display-5">
        Contact Us Here
    </div>

    <div class="row justify-content-center">
            <div class="col-md-8 py-3">
                <div v-if="page.props.flash.success" class="text-success">
                    {{ page.props.flash.success }} 
                </div>
                <p>
                    Thank you for using this website. If you have any suggestion on how this website can be improved upon, please don't hesitate to notify us on our contact page. Thank you for your patronage.
                </p>
                <p>
                    You can also make your complaints to us here. We will get back to you as soon as possible.
                </p>
                <form @submit.prevent="submitForm">
                    <first-and-last-name ref="firstAndLastNames" @send-first-name="updateFirstName" @send-last-name="updateLastName" :errors="formData.errors" ></first-and-last-name>
                    <phone-and-email ref="phoneAndEmail" @send-phone-number="updatePhoneNumber" @send-email="updateEmail" :errors="formData.errors"></phone-and-email>
                    <div class="col-sm-6 my-2">
                        <select class="form-select" aria-label="Default select example" v-model="messageSubject.val">
                            <option value="default">How can we assist you?</option>
                            <option value="suggestion">Suggestion</option>
                            <option value="complain">Complain</option>
                            <option value="request">Request</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <p v-if="formData.errors.message_subject" :class="{'text-danger': formData.errors.message_subject}">{{ formData.errors.message_subject }}</p>
                    <textarea class="rounded form-control my-3" name="messageContent" id="messageContent" placeholder="Enter your message here" rows="4" v-model="messageContent.val"></textarea>
                    <p v-if="formData.errors.message_content" :class="{'text-danger': formData.errors.message_content}">{{ formData.errors.message_content }}</p>
                    <div class="d-flex justify-content-end align-items-center">
                        <p class="d-inline mb-0 pe-2">Character count:</p>
                        <input class="form-control form-check-inline me-0 text-end" :class="{'text-danger': textLimit}" style="width: 90px;" type="text" :value="countTextInput" aria-label="Disabled input" disabled readonly>
                    </div>
                    
                    <div class="row justify-content-center mt-5">
                        <button class="btn btn-danger w-50" type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div>
                    <p class="display-6">Phone Support</p>
                    <p class="fw-bold fs-4"><i class="fa-solid fa-phone pe-2"></i>0805-736-8560</p>
                    <p class="fw-bold fs-4"><i class="fa-solid fa-phone pe-2"></i><i class="fa-brands fa-whatsapp pe-2"></i>0907-004-6964</p>
                    <p class="" style="font-size: 12px;">8am - 5pm (Monday - Friday)</p>
                    <p class="" style="font-size: 12px;">8am - 12noon Weekends</p>
                </div>
                <div>
                    <p class="display-6">Email Support</p>
                    <p class="fw-bold fs-5"><i class="fa-regular fa-envelope me-2"></i>support@whosabiwork.com</p>
                    <p class="" style="font-size: 12px;">We would ensure to get back to you.</p>
                </div>
            </div>
        </div>
</template>

<script>
    import FirstAndLastName from '@/components/FormParts/FirstAndLastName.vue';
    import PhoneAndEmail from '@/components/FormParts/PhoneAndEmail.vue';

    // import functions for use
    // import { reactive } from 'vue';
    import { useForm, usePage } from '@inertiajs/vue3';

    export default {
        components: { 
            FirstAndLastName, 
            PhoneAndEmail, 
        },
        data() {
            return {
                firstName: {
                    val: '',
                    isValid: true
                },
                lastName: {
                    val: '',
                    isValid: true
                },
                phoneNumber: {
                    val: null,
                    isValid: true
                },
                email: {
                    val: '',
                    isValid: true
                },
                messageSubject: {
                    val: 'default',
                    isValid: true
                },
                messageContent: {
                    val: '',
                    isValid: true
                },
                textCount: 0,
                textLimit: false,
                formData: useForm({
                    first_name: '',
                    last_name: '',
                    phone_number: '',
                    email: '',
                }),
                page: usePage(),
                dataToSend: {},
            }
        },
        methods: {
            submitForm() {
                this.formData = useForm({
                    first_name: this.firstName.val,
                    last_name: this.lastName.val,
                    phone_number: this.phoneNumber.val,
                    email: this.email.val,
                    message_subject: this.messageSubject.val,
                    message_content: this.messageContent.val,
                });
                this.formData.post(route('contactus.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // console.log(page);
                        this.firstName.val = '';
                        this.lastName.val = '';
                        this.phoneNumber.val = '';
                        this.email.val = '';
                        this.messageSubject.val = '';
                        this.messageContent.val = '';
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            updateFirstName(firstName) {
                this.firstName = firstName;
            },
            updateLastName(lastName) {
                this.lastName = lastName;
            },
            updatePhoneNumber(phoneNumber) {
                this.phoneNumber = phoneNumber;
            },
            updateEmail(email) {
                this.email = email;
            }
        },
        computed: {
            countTextInput() {
                if (this.messageContent.val) {
                    this.textCount = this.messageContent.val.length;
                }
                return this.textCount+'/250';
            }
        },
        watch: {
            'messageContent.val'() {
                console.log(this.messageContent.val);
                this.textCount = this.messageContent.val.length;
                console.log(this.textCount);
                if (this.textCount > 250) {
                    this.textLimit = true;
                } else if (this.textCount < 251) {
                    this.textLimit = false;
                }
            }
        }
    }
</script>