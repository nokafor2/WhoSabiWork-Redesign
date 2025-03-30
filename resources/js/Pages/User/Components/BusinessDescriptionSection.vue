<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwentyThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree" aria-expanded="false" aria-controls="collapseTwentyThree">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Business description:</dt>
                    <dd class="col-sm-9 mb-0">{{ getBusinessDesc }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwentyThree" class="accordion-collapse collapse" aria-labelledby="headingTwentyThree">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="businessName" value="" placeholder="Business name" class="form-control" v-model="businessDescInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateBusinessName" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearBusinessName" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['businessDescription', 'userId'],
        emits: [],
        data() {
            return {
                businessDescInput: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.businessDescInput,
                    updateField: 'business_description',
                });
                dataToSend.put(route('businesscategory.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.businessDescInput = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getBusinessDesc() {
                return this.capitalizeFirstLetter(this.businessDescription);
            }
        },
    }
</script>