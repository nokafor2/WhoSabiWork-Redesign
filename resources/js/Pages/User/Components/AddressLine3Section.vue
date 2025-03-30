<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwelve">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Address Line 3:</dt>
                    <dd class="col-sm-9 mb-0">{{ getAddressLine3 }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwelve" class="accordion-collapse collapse" aria-labelledby="headingTwelve">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="addressLine3" value="" placeholder="Address Line 3" class="form-control" v-model="addressLine3Input">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateAddressLine3" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearAddressLine3" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['addressLine3', 'userId'],
        emits: [],
        data() {
            return {
                addressLine3Input: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.addressLine3Input,
                    updateField: 'address_line_3',
                });
                dataToSend.put(route('address.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.addressLine3Input = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getAddressLine3() {
                return this.capitalizeFirstLetter(this.addressLine3);
            }
        },
    }
</script>