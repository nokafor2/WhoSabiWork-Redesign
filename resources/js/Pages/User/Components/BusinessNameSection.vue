<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingNine">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Business name:</dt>
                    <dd class="col-sm-9 mb-0">{{ getBusinessName }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="businessName" value="" placeholder="Business name" class="form-control" v-model="businessNameInput">
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
        props: ['businessName', 'userId'],
        emits: [],
        data() {
            return {
                businessNameInput: ""
            }
        },
        methods: {
            submitAction() {
                console.log(this.businessNameInput);
                const dataToSend = useForm({
                    updateVal: this.businessNameInput,
                    updateField: 'business_name',
                });
                // dataToSend.put('/users/'+this.userId);
                dataToSend.put(route('businesscategory.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.businessNameInput = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getBusinessName() {
                return this.capitalizeFirstLetter(this.businessName);
            }
        },
    }
</script>