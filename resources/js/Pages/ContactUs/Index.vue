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
                        <select class="form-select" aria-label="Default select example">
                            <option selected>How can we assist you?</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <textarea class="rounded form-control my-3" name="" id="" placeholder="Enter your message here" rows="4"></textarea>
                    <div class="d-flex justify-content-end align-items-center">
                        <p class="d-inline mb-0 pe-2">Character count:</p>
                        <input class="form-control form-check-inline me-0 text-end" style="width: 90px;" type="text" value="0/250" aria-label="Disabled input" disabled readonly>
                    </div>
                    
                    <div class="row justify-content-center mt-5">
                        <button class="btn btn-danger w-50">Submit</button>
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
    import { router, useForm, usePage } from '@inertiajs/vue3';

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

            },
            updateFirstName(firstName) {
                this.firstName = firstName;
                console.log(this.firstName);
            },
            updateLastName(lastName) {
                this.lastName = lastName;
                console.log(this.lastName);
            },
            updatePhoneNumber(phoneNumber) {
                this.phoneNumber = phoneNumber;
                console.log(this.phoneNumber);
            },
            updateEmail(email) {
                this.email = email;
                console.log(this.email);
            },
        }
    }
</script>