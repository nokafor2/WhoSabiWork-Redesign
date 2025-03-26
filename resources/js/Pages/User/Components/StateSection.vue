<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThirteen">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">State:</dt>
                    <dd class="col-sm-9 mb-0">{{ getState }}</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="state" value="" placeholder="State" class="form-control" v-model="stateInput">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateState" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearState" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['state', 'userId'],
        emits: [],
        data() {
            return {
                stateInput: ""
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.stateInput,
                    updateField: 'state',
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
            getState() {
                return this.capitalizeFirstLetter(this.state);
            }
        },
    }
</script>