<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwentySix">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentySix" aria-expanded="false" aria-controls="collapseTwentySix">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Business page:</dt>
                    <dd class="col-sm-9 mb-0">{{ businessPage }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwentySix" class="accordion-collapse collapse" aria-labelledby="headingTwentySix">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="businessPage" value="" placeholder="Business page" class="form-control" v-model="businessPageInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateBusinessPage" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearBusinessPage" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['businessPage', 'userId'],
        emits: [],
        data() {
            return {
                businessPageInput: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.businessPageInput,
                    updateField: 'business_page',
                });
                dataToSend.put(route('businesscategory.update', this.userId), {
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
            
        },
    }
</script>