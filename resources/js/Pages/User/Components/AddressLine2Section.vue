<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEleven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Address Line 2:</dt>
                    <dd class="col-sm-9 mb-0">{{ getAddressLine2 }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="addressLine2" value="" placeholder="Address Line 2" class="form-control" v-model="addressLine2Input">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateAddressLine2" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearAddressLine2" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['addressLine2', 'userId'],
        emits: [],
        data() {
            return {
                addressLine2Input: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.addressLine2Input,
                    updateField: 'address_line_2',
                });
                dataToSend.put(route('address.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.addressLine2Input = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getAddressLine2() {
                return this.capitalizeFirstLetter(this.addressLine2);
            }
        },
    }
</script>