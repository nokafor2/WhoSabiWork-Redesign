<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Address Line 1:</dt>
                    <dd class="col-sm-9 mb-0">{{ getAddressLine1 }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="addressLine1" value="" placeholder="Address Line 1" class="form-control" v-model="addressLine1Input">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateAddressLine1" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearAddressLine1" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['addressLine1', 'userId'],
        emits: [],
        data() {
            return {
                addressLine1Input: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.addressLine1Input,
                    updateField: 'address_line_1',
                });
                dataToSend.put(route('address.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getAddressLine1() {
                return this.capitalizeFirstLetter(this.addressLine1);
            }
        },
    }
</script>