<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFourteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Town:</dt>
                    <dd class="col-sm-9 mb-0">{{ getTown }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="town" value="" placeholder="Town" class="form-control" v-model="townInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateTown" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearTown" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['town', 'userId'],
        emits: [],
        data() {
            return {
                townInput: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.townInput,
                    updateField: 'town',
                });
                dataToSend.put(route('address.update', this.userId), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        if (page.props.flash.success) {
                            this.townInput = '';
                        }
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            }
        },
        computed: {
            getTown() {
                return this.capitalizeFirstLetter(this.town);
            }
        },
    }
</script>