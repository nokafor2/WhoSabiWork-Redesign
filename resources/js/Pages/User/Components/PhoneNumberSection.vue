<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSeven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Phone number:</dt>
                    <dd class="col-sm-9 mb-0">{{ phoneNumber }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="tel" name="phoneNumber" value="" placeholder="Phone number" class="form-control" v-model="phoneNumberInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updatePhoneNumber" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearPhoneNumber" class="btn btn-danger col-auto">Clear</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
</template>


<script>
    import MethodsMixin from '../Mixins/MethodsMixin.js';
    import { useForm } from '@inertiajs/vue3';

    export default {
        mixins: [MethodsMixin],
        props: ['phoneNumber', 'userId'],
        emits: [],
        data() {
            return {
                phoneNumberInput: ''
            }
        },
        methods: {
            submitAction() {
                console.log(this.phoneNumberInput);
                const dataToSend = useForm({
                    updateVal: this.phoneNumberInput,
                    updateField: 'phone_number',
                });
                dataToSend.put(route('users.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page.props.updateStatus);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        }
    }
</script>