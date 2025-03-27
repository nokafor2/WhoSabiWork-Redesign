<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwentyFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwentyFour" aria-expanded="false" aria-controls="collapseTwentyFour">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Vehicle Categories:</dt>
                    <dd class="col-sm-9 mb-0">
                        <ul class="list-inline mb-0">
                            <li v-for="(vehCategory, index) in selectedVehicleCategories" :key="index" class="list-inline-item">{{ vehCategory }}</li>
                        </ul>
                    </dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwentyFour" class="accordion-collapse collapse" aria-labelledby="headingTwentyFour">
            <div class="accordion-body">
                <form class="">
                    <div class="form-group col-12 mb-3">
                        <div v-for="(vehCategory, index) in allVehicleCategories" :key="index" class="form-check form-check-inline">
                            <input class="form-check-input" name="truck_brands[]" type="checkbox" :id="newId(index, techOrSpare)" :value="index" v-model="vehicleCategoriesInput">
                            <label class="form-check-label" :for="newId(index, techOrSpare)">Sinotruck</label>
                        </div>
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateTruckBrands" class="btn btn-danger col-auto">Update</button>
                        <!-- <button type="submit" name="clearTruckBrands" class="btn btn-danger col-auto">Clear</button> -->
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
        props: ['allVehicleCategories', 'selectedVehicleCategories', 'userId', 'techOrSpare'],
        emits: [],
        data() {
            return {
                vehicleCategoriesInput: []
            }
        },
        methods: {
            submitAction() {
                const dataToSend = useForm({
                    updateVal: this.vehicleCategoriesInput,
                });
                dataToSend.put(route('truckbrand.update', this.userId), {
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