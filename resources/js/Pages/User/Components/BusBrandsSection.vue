<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwentyOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyOne" aria-expanded="false" aria-controls="collapseTwentyOne">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Bus brands:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(busBrand, index) in selectedBusBrands" :key="index" class="list-inline-item">{{ busBrand }}</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwentyOne" class="accordion-collapse collapse" aria-labelledby="headingTwentyOne">
            <div class="accordion-body">
                <form class="" @submit.prevent="submitAction">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(busBrand, index) in allBusBrands" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="bus_Brands[]" type="checkbox" :id="newId(index, 'bus')" :value="index" v-model="busBrandsInput">
                            <label class="form-check-label" :for="newId(index, 'bus')">{{ busBrand }}</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateBusBrands" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearBusBrands" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allBusBrands', 'selectedBusBrands', 'userId'],
        emits: [],
        data() {
            return {
                busBrandsInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.busBrandsInput,
                });
                dataToSend.put(route('busbrand.update', this.userId), {
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